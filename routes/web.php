<?php

use App\Http\Controllers\Admin\BuildUploadController;
use App\Http\Middleware\EnsureActiveCatalogStaff;
use Illuminate\Support\Facades\Route;

Route::view('/', 'storefront.home')->name('home');

Route::prefix('admin/build-uploads')
    ->name('admin.build-uploads.')
    ->middleware(['auth', EnsureActiveCatalogStaff::class])
    ->controller(BuildUploadController::class)
    ->group(function (): void {
        Route::get('/{uploadId}', 'status')->whereUuid('uploadId')->name('status');
        Route::post('/chunks', 'chunk')->name('chunks');
        Route::post('/complete', 'complete')->name('complete');
        Route::delete('/{uploadId}', 'abort')->whereUuid('uploadId')->name('abort');
    });

require __DIR__.'/settings.php';
