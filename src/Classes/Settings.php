<?php

namespace VanOns\FilamentSettings\Classes;

use Illuminate\Support\Str;
use VanOns\FilamentSettings\Models\Settings as SettingsModel;

abstract class Settings
{
    public array $settings = [];
    public string $settingsName = 'general';

    public function __construct()
    {
        $this->settings = array_merge(
            $this->defaults(),
            $this->getSettings()
        );
    }

    public function defaults(): array
    {
        return [];
    }

    protected function getSettings(): array
    {
        return SettingsModel::getValue($this->settingsName) ?? [];
    }

    public function get(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->settings;
        }
        return $this->settings[$key] ?? null;
    }

    public function save(mixed $value): void
    {
        SettingsModel::set($this->settingsName, $value);
    }
}