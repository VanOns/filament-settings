<?php

namespace VanOns\FilamentSettings\Facade;

use Illuminate\Support\Facades\Facade;

class FilamentSettings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filament-settings';
    }
}
