<?php

namespace VanOns\FilamentSettings\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed getValue(string $key, mixed $default = null)
 * @method static void setValue(string $key, mixed $value)
 */
class FilamentSettings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'filament-settings';
    }
}
