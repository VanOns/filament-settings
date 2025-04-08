<?php

namespace VanOns\FilamentSettings\Filament\Traits;

use Filament\Notifications\Notification;
use VanOns\FilamentSettings\Classes\Settings;
use VanOns\FilamentSettings\Filament\Pages\SettingsPage;

/**
 * @mixin SettingsPage
 */
trait HasSettings
{
    protected string $settingsClass;
    protected Settings $settingsInstance;
    public array $settings = [];

    public function mount(): void
    {
        $this->settingsInstance = new $this->settingsClass();
        $this->settings = $this->settingsInstance->get();
    }

    public function submit(): void
    {
        $this->settingsInstance = new $this->settingsClass();
        try {
            $validated = $this->validate();
            if (array_key_exists('settings', $validated)) {
                $this->settingsInstance->set($validated['settings']);
            } else {
                throw new \Exception('Settings not found');
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
