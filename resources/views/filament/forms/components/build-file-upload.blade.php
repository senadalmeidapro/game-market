<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="gameBuildUploader({
            statusUrl: @js(url('/admin/build-uploads/UPLOAD_ID')),
            chunkUrl: @js(route('admin.build-uploads.chunks')),
            completeUrl: @js(route('admin.build-uploads.complete')),
            abortUrl: @js(url('/admin/build-uploads/UPLOAD_ID')),
            csrf: @js(csrf_token()),
            livewireId: @js($getLivewire()->getId()),
            userId: @js(auth()->id()),
            keyPath: @js($getStatePath()),
            filenamePath: @js($getFilenameStatePath()),
            sizePath: @js($getSizeStatePath()),
            shaPath: @js($getSha256StatePath()),
            maxBytes: @js((int) config('gamehub.max_build_bytes')),
        })"
        class="space-y-2"
    >
        <input type="file" x-ref="input" x-on:change="start($event.target.files[0])" class="fi-input block w-full" />
        <div x-show="busy || message" class="space-y-1" aria-live="polite">
            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
                <span x-text="message"></span>
                <span x-show="busy" x-text="`${progress}%`"></span>
            </div>
            <progress x-show="busy" max="100" x-bind:value="progress" class="w-full"></progress>
        </div>
        <button type="button" x-show="uploadId && busy" x-on:click="cancel()" class="text-sm text-danger-600">Annuler le transfert</button>
    </div>
</x-dynamic-component>

@once
    <script>
        window.gameBuildUploader = (config) => ({
            busy: false,
            progress: 0,
            message: '',
            uploadId: null,
            localKey: null,
            cancelled: false,
            chunkBytes: 1024 * 1024,
            fingerprint(file) { return `${file.name}:${file.size}:${file.lastModified}`; },
            storageKey(file) { return `game-build-upload:${config.userId}:${this.fingerprint(file)}`; },
            async start(file) {
                if (!file) return;
                if (file.size > config.maxBytes) { this.message = 'Fichier supérieur à la limite configurée.'; return; }
                this.busy = true;
                this.cancelled = false;
                this.message = 'Préparation du transfert…';
                const localKey = this.storageKey(file);
                this.localKey = localKey;
                this.uploadId = localStorage.getItem(localKey) || crypto.randomUUID();
                localStorage.setItem(localKey, this.uploadId);
                const total = Math.ceil(file.size / this.chunkBytes);
                let uploaded = new Set();
                try {
                    const status = await fetch(config.statusUrl.replace('UPLOAD_ID', this.uploadId), { headers: { 'Accept': 'application/json' } });
                    if (status.ok) {
                        const body = await status.json();
                        if (body.file_size_bytes !== file.size || body.total_chunks !== total || body.filename !== file.name) {
                            throw new Error('Le fichier sélectionné ne correspond pas au transfert à reprendre.');
                        }
                        uploaded = new Set(body.uploaded_chunks);
                    } else if (status.status !== 404) {
                        throw new Error('Impossible de reprendre le transfert.');
                    }
                    for (let index = 0; index < total; index++) {
                        if (this.cancelled) return;
                        if (uploaded.has(index)) { this.progress = Math.round(((index + 1) / total) * 100); continue; }
                        const form = new FormData();
                        form.append('upload_id', this.uploadId);
                        form.append('chunk_index', index);
                        form.append('total_chunks', total);
                        form.append('file_size_bytes', file.size);
                        form.append('filename', file.name);
                        form.append('chunk', file.slice(index * this.chunkBytes, Math.min(file.size, (index + 1) * this.chunkBytes)), file.name);
                        const response = await fetch(config.chunkUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': config.csrf, 'Accept': 'application/json' }, body: form });
                        if (!response.ok) throw new Error('Un bloc n’a pas pu être envoyé. Réessayez en sélectionnant le même fichier.');
                        this.progress = Math.round(((index + 1) / total) * 100);
                    }
                    this.message = 'Assemblage et vérification du fichier…';
                    const response = await fetch(config.completeUrl, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': config.csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ upload_id: this.uploadId, total_chunks: total, file_size_bytes: file.size }),
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'Le fichier n’a pas pu être assemblé.');
                    const wire = window.Livewire.find(config.livewireId)?.$wire;
                    await Promise.all([
                        wire.$set(config.keyPath, result.storage_key),
                        wire.$set(config.filenamePath, result.filename),
                        wire.$set(config.sizePath, result.size_bytes),
                        wire.$set(config.shaPath, result.sha256),
                    ]);
                    localStorage.removeItem(localKey);
                    this.message = `${result.filename} — ${(result.size_bytes / 1024 / 1024).toFixed(1)} Mo, SHA-256 calculé`;
                    this.progress = 100;
                    this.uploadId = null;
                } catch (error) {
                    this.message = error.message || 'Erreur de téléversement. Réessayez.';
                } finally {
                    this.busy = false;
                }
            },
            async cancel() {
                if (!this.uploadId) return;
                this.cancelled = true;
                await fetch(config.abortUrl.replace('UPLOAD_ID', this.uploadId), { method: 'DELETE', headers: { 'X-CSRF-TOKEN': config.csrf, 'Accept': 'application/json' } });
                if (this.localKey) localStorage.removeItem(this.localKey);
                this.uploadId = null;
                this.progress = 0;
                this.message = 'Transfert annulé.';
                this.busy = false;
            },
        });
    </script>
@endonce
