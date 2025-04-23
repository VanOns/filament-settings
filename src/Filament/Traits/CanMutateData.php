<?php

namespace VanOns\FilamentSettings\Filament\Traits;

trait CanMutateData
{
    public function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}