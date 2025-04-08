<?php

namespace VanOns\FilamentSettings;

use Illuminate\Support\ServiceProvider;
use VanOns\FilamentSettings\Console\Commands\MakeSettingsPageCommand;

class FilamentSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishesMigrations(
            paths: [
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ],
            groups: 'filament-settings-migrations'
        );

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

        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'filament-settings'
        );

        $this->publishes(
            paths: [
                __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-settings'),
            ],
            groups: 'filament-settings-views'
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeSettingsPageCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        //
    }
}
