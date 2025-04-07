<?php

namespace VanOns\FilamentSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Settings extends Model
{
    protected $guarded = [
        'id',
    ];

    public static function new(string $key, mixed $value = null): ?self
    {
        return !self::whereName($key)->exists()
            ? self::create(array_filter([
                'name' => $key,
                'value' => is_null($value) ? null : json_encode($value),
            ]))
            : null;
    }

    public static function set(string $key, mixed $value): void
    {
        $handleMethod = Str::camel('after-' . $key . 'SettingUpdate');
        if (!method_exists(self::class, $handleMethod) || self::getValue($key) === $value) {
            $handleMethod = null;
        }

        $item = self::updateOrCreate(
            ['name' => $key],
            [
                'value' => is_null($value) ? null : json_encode($value),
            ]
        );

        if ($handleMethod) {
            self::$handleMethod($value);
        }
    }

    public static function getValue(string $key, bool $overwrite = false): mixed
    {
        return json_decode(
            self::whereName($key)->first()?->value,
            true
        );
//        return app(AITranslationsSettings::class)->cache(
//            key: $key,
//            callback: function () use ($key): mixed {
//                return json_decode(
//                    self::whereName($key)->first()?->value,
//                    true
//                );
//            },
//            overwrite: $overwrite
//        );
    }

    public static function getDefaultLocale(): ?string
    {
        return self::getValue('default_locale');
    }

    public static function getSlugTranslations(): ?bool
    {
        return self::getValue('slug_translations') ?? false;
    }

    public static function getLocaleUrlPrefix(): ?bool
    {
        return self::getValue('locale_url_prefix') ?? false;
    }
}
