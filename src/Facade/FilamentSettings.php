<?php

namespace VanOns\FilamentSettings\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key)
 * @method static void set(string $key, mixed $value)
 */
class FilamentSettings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'filament-settings';
    }
}
