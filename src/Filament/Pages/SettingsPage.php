<?php

namespace VanOns\FilamentSettings\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use VanOns\FilamentSettings\Filament\Actions\SaveAction;
use VanOns\FilamentSettings\Filament\Traits\HasSettings;

class SettingsPage extends Page implements HasForms
{
    use InteractsWithForms;
    use HasSettings;

    protected static string $view = 'filament-settings::filament.pages.settings';

    public static function getType(): string
    {
        return 'settings';
    }

    protected function getActions(): array
    {
        return [
            SaveAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema($this->getFormSchema());
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('example_test')
                ->integer()
        ];
    }
}
