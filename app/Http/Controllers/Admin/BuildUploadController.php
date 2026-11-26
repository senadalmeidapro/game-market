<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BuildUploadController extends Controller
{
    private const DISK = 'local';

    private const MAX_CHUNK_KB = 2048;

    public function status(Request $request, string $uploadId): JsonResponse
    {
        abort_unless(Str::isUuid($uploadId), 404);

        $disk = Storage::disk(self::DISK);
        $directory = $this->directory($request, $uploadId);
        $manifestPath = "{$directory}/manifest.json";
        abort_unless($disk->exists($manifestPath), 404);

        $manifest = json_decode($disk->get($manifestPath), true);
        $uploaded = collect($disk->files($directory))
            ->filter(fn (string $path): bool => preg_match('/\/\d{6}\.part$/', $path) === 1)
            ->map(fn (string $path): int => (int) basename($path, '.part'))
            ->values();

        return response()->json([
            'upload_id' => $uploadId,
            'total_chunks' => $manifest['total_chunks'],
            'uploaded_chunks' => $uploaded,
            'filename' => $manifest['filename'],
            'file_size_bytes' => $manifest['file_size_bytes'],
        ]);
    }

    public function chunk(Request $request): JsonResponse
    {
        $data = $request->validate([
            'upload_id' => ['required', 'uuid'],
            'chunk_index' => ['required', 'integer', 'min:0'],
            'total_chunks' => ['required', 'integer', 'min:1', 'max:20480'],
            'file_size_bytes' => ['required', 'integer', 'min:1', 'max:'.(int) config('gamehub.max_build_bytes', 21474836480)],
            'filename' => ['required', 'string', 'max:255'],
            'chunk' => ['required', 'file', 'max:'.self::MAX_CHUNK_KB],
        ]);

        abort_if($data['chunk_index'] >= $data['total_chunks'], 422);
        $disk = Storage::disk(self::DISK);
        $directory = $this->directory($request, $data['upload_id']);
        $manifestPath = "{$directory}/manifest.json";
        $manifest = [
            'total_chunks' => (int) $data['total_chunks'],
            'file_size_bytes' => (int) $data['file_size_bytes'],
            'filename' => basename($data['filename']),
            'created_at' => now()->toIso8601String(),
        ];

        if ($disk->exists($manifestPath)) {
            $existing = json_decode($disk->get($manifestPath), true);
            abort_unless(
                $existing['total_chunks'] === $manifest['total_chunks']
                    && $existing['file_size_bytes'] === $manifest['file_size_bytes']
                    && $existing['filename'] === $manifest['filename'],
                409,
            );
        } else {
            $disk->makeDirectory($directory);
            $disk->put($manifestPath, json_encode($manifest, JSON_THROW_ON_ERROR));
        }

        $chunkPath = sprintf('%s/%06d.part', $directory, $data['chunk_index']);
        $disk->putFileAs($directory, $data['chunk'], basename($chunkPath));

        return response()->json(['chunk_index' => (int) $data['chunk_index'], 'stored' => true]);
    }

    public function complete(Request $request): JsonResponse
    {
        $data = $request->validate([
            'upload_id' => ['required', 'uuid'],
            'total_chunks' => ['required', 'integer', 'min:1', 'max:20480'],
            'file_size_bytes' => ['required', 'integer', 'min:1', 'max:'.(int) config('gamehub.max_build_bytes', 21474836480)],
        ]);

        $disk = Storage::disk(self::DISK);
        $directory = $this->directory($request, $data['upload_id']);
        $manifestPath = "{$directory}/manifest.json";
        abort_unless($disk->exists($manifestPath), 404);
        $manifest = json_decode($disk->get($manifestPath), true);
        abort_unless($manifest['total_chunks'] === (int) $data['total_chunks'] && $manifest['file_size_bytes'] === (int) $data['file_size_bytes'], 409);

        $missing = [];
        for ($index = 0; $index < $manifest['total_chunks']; $index++) {
            if (! $disk->exists(sprintf('%s/%06d.part', $directory, $index))) {
                $missing[] = $index;
            }
        }
        if ($missing !== []) {
            return response()->json(['message' => 'Certains blocs ne sont pas encore reçus.', 'missing_chunks' => $missing], 422);
        }

        $disk->makeDirectory('game-builds');
        $safeFilename = $this->safeFilename($manifest['filename']);
        $storageKey = 'game-builds/'.Str::uuid().'-'.$safeFilename;
        $temporaryKey = $storageKey.'.assembling';
        $destination = fopen($disk->path($temporaryKey), 'wb');
        if (! is_resource($destination)) {
            abort(500, 'Impossible de créer le fichier assemblé.');
        }

        try {
            for ($index = 0; $index < $manifest['total_chunks']; $index++) {
                $source = fopen($disk->path(sprintf('%s/%06d.part', $directory, $index)), 'rb');
                if (! is_resource($source)) {
                    abort(500, 'Impossible de lire un bloc du téléversement.');
                }
                try {
                    stream_copy_to_stream($source, $destination);
                } finally {
                    fclose($source);
                }
            }
        } catch (\Throwable $exception) {
            fclose($destination);
            $disk->delete($temporaryKey);
            throw $exception;
        }
        fclose($destination);

        $actualSize = $disk->size($temporaryKey);
        abort_unless($actualSize === $manifest['file_size_bytes'], 422, 'La taille assemblée ne correspond pas au fichier source.');
        $sha256 = hash_file('sha256', $disk->path($temporaryKey));
        abort_unless(is_string($sha256), 500, 'Impossible de calculer le SHA-256.');
        $disk->move($temporaryKey, $storageKey);
        $disk->deleteDirectory($directory);

        return response()->json([
            'storage_key' => $storageKey,
            'filename' => $safeFilename,
            'size_bytes' => $actualSize,
            'sha256' => $sha256,
        ], 201);
    }

    public function abort(Request $request, string $uploadId): JsonResponse
    {
        abort_unless(Str::isUuid($uploadId), 404);
        Storage::disk(self::DISK)->deleteDirectory($this->directory($request, $uploadId));

        return response()->json(['aborted' => true]);
    }

    private function directory(Request $request, string $uploadId): string
    {
        return 'game-build-chunks/'.$request->user()->getKey().'/'.$uploadId;
    }

    private function safeFilename(string $filename): string
    {
        $filename = basename(str_replace('\\', '/', $filename));
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $safeName = Str::slug($name) ?: 'build';

        return $safeName.($extension !== '' ? '.'.strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $extension)) : '');
    }
}
