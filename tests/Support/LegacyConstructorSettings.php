<?php

namespace Tests\Support;

use VanOns\FilamentSettings\Classes\Settings;

class LegacyConstructorSettings extends Settings
{
    public string $settingsName = 'legacy_settings';
    public bool $constructorRan = false;

    public function __construct(?string $settingsName = null)
    {
        parent::__construct($settingsName);

        $this->constructorRan = true;
    }

    public function defaults(): array
    {
        return ['theme' => 'light'];
    }
}
