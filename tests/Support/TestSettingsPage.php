<?php

namespace Tests\Support;

use VanOns\FilamentSettings\Filament\Traits\HasSettings;

class TestSettingsPage
{
    use HasSettings;

    public function __construct(protected ?string $pageVariant = null)
    {
        $this->settingsClass = TestSettings::class;
    }

    public function getSettingsVariant(): ?string
    {
        return $this->pageVariant;
    }
}
