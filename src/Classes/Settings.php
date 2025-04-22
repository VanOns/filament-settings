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

    public function getParsedSettings(): array
    {
        return $this->parseArray($this->settings);
    }

    public function set(mixed $value): void
    {
        SettingsModel::set($this->settingsName, $value);
        $this->settings = array_merge(
            $value,
            $this->settings
        );
    }

    protected function parseArray(array $array): array
    {
        $newArray = [];

        foreach ($array as $key => $value) {
            $newKey = is_int($key) ? Str::uuid()->toString() : $key;

            if (is_array($value)) {
                $value = $this->parseArray($value);
            }
            $newArray[$newKey] = $value;
        }

        return $newArray;
    }
}