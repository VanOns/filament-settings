<?php

namespace VanOns\FilamentSettings\Filament\Traits;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use ReflectionClass;
use VanOns\FilamentSettings\Models\Settings;

/**
 * @mixin Page
 */
trait HasSettings
{
    public ?string $example_test;

    public function mount(): void
    {
        foreach (self::getTraitProperties() as $property) {
            $this->{$property} = self::getSetting($property);
        }
    }

    public static function getSetting(string $key): mixed
    {
        return Settings::getValue($key);
    }

    public static function getTraitProperties(): array
    {
        $properties = (new ReflectionClass(
            new class () {
                use HasSettings;
            }
        ))->getProperties();

        $traitProperties = [];
        foreach ($properties as $property) {
            $traitProperties[] = $property->getName();
        }

        return $traitProperties;
    }

    public function submit(): void
    {
        try {
            $validated = $this->validate();
            foreach ($validated as $key => $value) {
                Settings::set($key, $value);
            }
        } catch (\Exception) {
            Notification::make()
                ->danger()
                ->title(__('moolang-filament::panel.error_message'))
                ->send();
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-actions::edit.single.notifications.saved.title'))
            ->send();
    }
}
