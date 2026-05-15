<?php

namespace VanOns\FilamentSettings;

use VanOns\FilamentSettings\Models\Settings;

class FilamentSettings
{
    public function getValue(string $key, mixed $default = null): mixed
    {
        return Settings::query()
            ->where('name', $key)
            ->first()
            ?->value ?? $default;
    }

    public function setValue(string $key, mixed $value): void
    {
        Settings::query()->updateOrCreate(
            ['name' => $key],
            ['value' => $value]
        );
    }
}
