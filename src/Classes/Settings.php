<?php

namespace VanOns\FilamentSettings\Classes;

use Illuminate\Support\Arr;
use VanOns\FilamentSettings\Facades\FilamentSettings;

/**
 * @phpstan-consistent-constructor
 */
abstract class Settings
{
    public array $settings = [];
    public string $settingsName = 'general';
    protected ?string $variant = null;

    public function __construct(
        ?string $settingsName = null,
        ?string $variant = null
    ) {
        if (!is_null($settingsName)) {
            $this->settingsName = $settingsName;
        }

        if (!is_null($variant)) {
            $this->variant = $variant;
        }

        $this->settings = empty($this->getSettings())
            ? $this->defaults()
            : $this->getSettings();
    }

    public static function forVariant(?string $variant): static
    {
        return new static(variant: $variant);
    }

    public function defaults(): array
    {
        return [];
    }

    public function getVariant(): ?string
    {
        return $this->variant;
    }

    public function getSettingsName(): string
    {
        if (is_null($this->variant) || $this->variant === '') {
            return $this->settingsName;
        }

        return $this->settingsName . '.' . $this->variant;
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
