<?php

namespace VanOns\FilamentSettings\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $guarded = [
        'id',
    ];

    public static function set(string $key, mixed $value): void
    {
        self::query()->updateOrCreate(
            ['name' => $key],
            [
                'value' => is_null($value) ? null : json_encode($value),
            ]
        );
    }

    public static function getValue(string $key): mixed
    {
        $record = self::query()->where('name', $key)->first();

        return json_decode(
            $record->value,
            true
        );
    }
}
