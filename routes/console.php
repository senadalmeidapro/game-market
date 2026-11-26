<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function (): void {
    $disk = Storage::disk('local');
    foreach ($disk->allFiles('game-build-chunks') as $path) {
        if (basename($path) !== 'manifest.json') {
            continue;
        }
        $manifest = json_decode($disk->get($path), true);
        if (! is_array($manifest) || empty($manifest['created_at'])) {
            continue;
        }
        if (Carbon::parse($manifest['created_at'])->isBefore(now()->subDays(7))) {
            $disk->deleteDirectory(dirname($path));
        }
    }
})->dailyAt('03:00')->name('prune-incomplete-build-uploads')->withoutOverlapping();
