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
    protected $guarded = ['id'];

    protected $casts = [
        'value' => 'json',
    ];

    public static function set(string $key, mixed $value): void
    {
        self::query()->updateOrCreate(
            ['name' => $key],
            ['value' => $value]
        );
    }

    public static function getValue(string $key): mixed
    {
        $record = self::query()->where('name', $key)->first();

        return $record?->value;
    }
}
