<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'storefront.home')->name('home');

require __DIR__.'/settings.php';
