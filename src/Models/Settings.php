<?php

namespace VanOns\FilamentSettings\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $value
 */
class Settings extends Model
{
    protected $fillable = ['name', 'value'];

    protected $casts = [
        'value' => 'json',
    ];
}
