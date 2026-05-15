<?php

namespace VanOns\FilamentSettings;

use VanOns\FilamentSettings\Models\Settings;

class FilamentSettings
{
    public function get(string $key): mixed
    {
        return Settings::query()
            ->where('name', $key)
            ->first()
            ?->value;
    }

    public function set(string $key, mixed $value): void
    {
        Settings::query()->updateOrCreate(
            ['name' => $key],
            ['value' => $value]
        );
    }
}
