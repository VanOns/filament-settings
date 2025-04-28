<?php

namespace VanOns\FilamentSettings\Filament\Traits;

use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use VanOns\FilamentSettings\Classes\Settings;
use VanOns\FilamentSettings\Filament\Pages\SettingsPage;

/**
 * @mixin SettingsPage
 */
trait HasSettings
{
    use CanMutateData;

    protected string $settingsClass;
    protected Settings $settingsInstance;
    public array $settings = [];

    public function mount(): void
    {
        $this->settingsInstance = $this->getSettingsInstance();
        $this->fillForm();
    }

    public function fillForm(): void
    {
        $data = $this->mutateFormDataBeforeFill(
            $this->settingsInstance->get()
        );

        $this->form->fill($data);
    }

    public function saveForm(): void
    {
        $data = $this->mutateFormDataBeforeSave(
            $this->form->getState()
        );

        $this->settingsInstance->save($data);
    }

    public function getSettingsInstance(): Settings
    {
        return new $this->settingsClass();
    }

    public function submit(): void
    {
        $this->settingsInstance = $this->getSettingsInstance();
        try {
            $this->saveForm();
        } catch (ValidationException $e) {
            $this->onValidationError($e);

            $this->dispatch('form-validation-error', livewireId: $this->getId());

            throw $e;
        } catch (\Exception) {
            Notification::make()
                ->danger()
                ->title(__('filament-settings-lang::panel.error_message'))
                ->send();
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-actions::edit.single.notifications.saved.title'))
            ->send();
    }
}
