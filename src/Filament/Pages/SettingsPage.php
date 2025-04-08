<?php

namespace VanOns\FilamentSettings\Filament\Pages;

use Filament\Forms\Components\Group;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use VanOns\FilamentSettings\Filament\Actions\SaveAction;
use VanOns\FilamentSettings\Filament\Traits\HasSettings;

abstract class SettingsPage extends Page implements HasForms
{
    use InteractsWithForms;
    use HasSettings;

    protected static string $view = 'filament-settings::filament.pages.settings';
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

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
            ->schema([
                Group::make($this->getFormSchema())
                    ->columns()
                    ->columnSpanFull()
                    ->statePath('settings')
            ]);
    }

    protected function getFormSchema(): array
    {
        return [
            //
        ];
    }
}
