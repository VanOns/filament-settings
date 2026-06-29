<?php

namespace Tests\Support;

use VanOns\FilamentSettings\Classes\Settings;

class TestSettings extends Settings
{
    public string $settingsName = 'test_settings';

    public function defaults(): array
    {
        return ['theme' => 'light', 'locale' => 'en'];
    }

    public function publicFilterSettings(array $settings): array
    {
        return $this->filterSettings($settings);
    }
}
