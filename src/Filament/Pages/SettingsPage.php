<?php

namespace VanOns\FilamentSettings\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use VanOns\FilamentSettings\Filament\Actions\SaveAction;
use VanOns\FilamentSettings\Filament\Traits\HasSettings;

abstract class SettingsPage extends Page implements HasForms
{
    use InteractsWithForms;
    use HasSettings;

    protected string $view = 'filament-settings::filament.pages.settings';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected function getActions(): array
    {
        return [
            SaveAction::make(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('settings')
            ->columns(3)
            ->components($this->getFormSchema());
    }

    public function getFormSchema(): array
    {
        return [
            //
        ];
    }
}
