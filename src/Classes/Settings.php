<?php

namespace VanOns\FilamentSettings\Classes;

use Illuminate\Support\Arr;
use VanOns\FilamentSettings\Models\Settings as SettingsModel;

abstract class Settings
{
    public array $settings = [];
    public string $settingsName = 'general';

    public function __construct()
    {
        $this->settings = array_merge(
            $this->defaults(),
            $this->filterSettings(
                $this->getSettings()
            )
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

    protected function filterSettings(array $settings): array
    {
        return array_filter(
            $settings,
            fn ($value) => !((is_string($value) || is_array($value)) && empty($value)),
        );
    }

    public function get(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->settings;
        }

        return Arr::get($this->settings, $key);
    }

    public function save(mixed $value): void
    {
        SettingsModel::set($this->settingsName, $value);
    }
}