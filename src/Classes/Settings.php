<?php

namespace VanOns\FilamentSettings\Classes;

use Illuminate\Support\Arr;
use VanOns\FilamentSettings\Facades\FilamentSettings;

abstract class Settings
{
    public array $settings = [];
    public string $settingsName = 'general';

    public function __construct(
        ?string $settingsName = null
    ) {
        if (!is_null($settingsName)) {
            $this->settingsName = $settingsName;
        }

        $this->settings = empty($this->getSettings())
            ? $this->defaults()
            : $this->getSettings();
    }

    public function defaults(): array
    {
        return [];
    }

    public function getSettingsName(): string
    {
        return $this->settingsName;
    }

    protected function getSettings(): array
    {
        return FilamentSettings::getValue($this->getSettingsName()) ?? [];
    }

    protected function filterSettings(array $settings): array
    {
        return array_filter(
            $settings,
            [$this, 'isValidSetting']
        );
    }

    protected function isValidSetting(mixed $value): bool
    {
        if (is_string($value) || is_array($value)) {
            return !empty($value);
        }

        return !is_null($value);
    }

    public function get(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->settings;
        }

        return Arr::get($this->settings, $key);
    }

    public function set(string $key, mixed $value): void
    {
        $this->save(
            Arr::set($this->settings, $key, $value)
        );
    }

    public function save(mixed $value): void
    {
        FilamentSettings::setValue($this->getSettingsName(), $value);
    }
}
