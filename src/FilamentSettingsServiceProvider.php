<?php

namespace VanOns\FilamentSettings;

use Illuminate\Support\ServiceProvider;

class FilamentSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(
            path: __DIR__ . '/../config/filament-settings.php',
            key: 'filament-settings'
        );

        $this->publishes(
            paths: [
                __DIR__ . '/../config/filament-settings.php' => config_path('filament-settings.php'),
            ],
            groups: 'filament-settings-config'
        );

        $this->loadViewsFrom(
            path: __DIR__ . '/../resources/views',
            namespace: 'filament-settings'
        );

        $this->loadTranslationsFrom(
            path: __DIR__.'/../lang',
            namespace: 'filament-settings-lang'
        );

        $this->publishes(
            paths: [
                __DIR__.'/../lang' => $this->app->langPath('vendor/filament-settings'),
            ],
            groups: 'filament-settings-lang'
        );
    }

    public function register(): void
    {
        //
    }
}
