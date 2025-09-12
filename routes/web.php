<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Gallery Routes
|--------------------------------------------------------------------------
|
| Hier definiëren we de routes voor de Gallery package.
|
*/

Route::middleware(['web', 'auth:staff'])->prefix(config('manta-gallery.route_prefix'))
    ->name('gallery.')
    ->group(function () {
        Route::get("/", \Darvis\MantaGallery\Livewire\GalleryList::class)->name('list');
        Route::get("/toevoegen", \Darvis\MantaGallery\Livewire\GalleryCreate::class)->name('create');
        Route::get("/aanpassen/{gallery}", \Darvis\MantaGallery\Livewire\GalleryUpdate::class)->name('update');
        Route::get("/lezen/{gallery}", \Darvis\MantaGallery\Livewire\GalleryRead::class)->name('read');
        Route::get("/bestanden/{gallery}", \Darvis\MantaGallery\Livewire\GalleryUpload::class)->name('upload');
        // Route::get("/instellingen", \Darvis\MantaGallery\Livewire\GallerySettings::class)->name('settings');
    });
