<?php

namespace VanOns\FilamentSettings;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VanOns\FilamentSettings\Console\Commands\MakeSettingsPageCommand;

class FilamentSettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        // Recognises migrations an app already published, whatever their date, so publishing again only adds new ones.
        $package
            ->name('filament-settings')
            ->discoversMigrations();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('filament-settings', fn () => new FilamentSettings());
    }

    public function packageBooted(): void
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

        // Named like the folder it is published to, so the app's copy overrides the package's.
        $this->loadTranslationsFrom(
            path: __DIR__.'/../lang',
            namespace: 'filament-settings'
        );

        // The namespace before v3.3, for apps that use it themselves; remove in v4.
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
}
