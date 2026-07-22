<?php

namespace VanOns\FilamentSettings;

use VanOns\FilamentSettings\Models\Settings;

class FilamentSettings
{
    public function getValue(string $key, mixed $default = null): mixed
    {
        return Settings::getValue($key) ?? $default;
    }

    public function setValue(string $key, mixed $value): void
    {
        Settings::set($key, $value);
    }
}
