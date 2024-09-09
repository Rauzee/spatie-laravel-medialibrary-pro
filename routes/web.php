<?php

use Illuminate\Support\Facades\Route;
use Spatie\MediaLibraryPro\Http\Controllers\MediaLibraryStylesController;

Route::get('media-library-pro/styles.css', '\\'.MediaLibraryStylesController::class)
    ->name('media-library-styles');
