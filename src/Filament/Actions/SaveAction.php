<?php

namespace VanOns\FilamentSettings\Filament\Actions;

use Filament\Actions\Action;

class SaveAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'save';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-actions::edit.single.modal.actions.save.label'))
            ->action('submit');
    }
}
