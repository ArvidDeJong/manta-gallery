<?php

namespace Darvis\MantaGallery;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class GalleryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register package services
        $this->mergeConfigFrom(
            __DIR__ . '/../config/manta-gallery.php',
            'manta-gallery'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publiceer configuratie
        $this->publishes([
            __DIR__ . '/../config/manta-gallery.php' => config_path('manta-gallery.php'),
        ], 'manta-gallery-config');

        // Publiceer migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'manta-gallery-migrations');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'manta-gallery');

        // Register Livewire components
        Livewire::component('gallery-list', \Darvis\MantaGallery\Livewire\GalleryList::class);
        Livewire::component('gallery-create', \Darvis\MantaGallery\Livewire\GalleryCreate::class);
        Livewire::component('gallery-read', \Darvis\MantaGallery\Livewire\GalleryRead::class);
        Livewire::component('gallery-update', \Darvis\MantaGallery\Livewire\GalleryUpdate::class);
        Livewire::component('gallery-upload', \Darvis\MantaGallery\Livewire\GalleryUpload::class);


        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Darvis\MantaGallery\Console\Commands\InstallCommand::class,
                \Darvis\MantaGallery\Console\Commands\SeedGalleryCommand::class,
            ]);
        }

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
