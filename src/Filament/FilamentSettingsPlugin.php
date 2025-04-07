<?php

namespace VanOns\FilamentSettings\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentSettingsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-settings';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([
                Pages\SettingsPage::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
    }
}
