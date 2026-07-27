<?php

use VanOns\FilamentSettings\Facades\FilamentSettings;
use VanOns\FilamentSettings\Models\Settings;

it('creates a new record and reads it back', function () {
    FilamentSettings::setValue('foo', 'bar');

    expect(FilamentSettings::getValue('foo'))->toBe('bar');
});

it('updates an existing key without creating a duplicate', function () {
    FilamentSettings::setValue('foo', 'first');
    FilamentSettings::setValue('foo', 'second');

    expect(FilamentSettings::getValue('foo'))->toBe('second');
    expect(Settings::query()->where('name', 'foo')->count())->toBe(1);
});

it('returns null for a key that does not exist', function () {
    expect(FilamentSettings::getValue('missing'))->toBeNull();
});

it('stores an explicit null value and a record still exists', function () {
    FilamentSettings::setValue('key', null);

    expect(Settings::query()->where('name', 'key')->count())->toBe(1);
    expect(FilamentSettings::getValue('key'))->toBeNull();
});

it('stores and retrieves a nested array through JSON cast', function () {
    $data = ['mail' => ['host' => 'smtp.example.com', 'port' => 587]];

    FilamentSettings::setValue('config', $data);

    expect(FilamentSettings::getValue('config'))->toBe($data);
});

it('creates a record directly through the model and reads name/value back', function () {
    $settings = Settings::create(['name' => 'direct', 'value' => ['a' => 1]]);

    expect($settings->fresh())
        ->name->toBe('direct')
        ->value->toBe(['a' => 1]);
});

it('ignores an id passed in the input array and lets the database assign it', function () {
    $settings = Settings::create(['id' => 999, 'name' => 'ignored-id', 'value' => 'bar']);

    expect($settings->id)->not->toBe(999);
    expect(Settings::query()->where('name', 'ignored-id')->value('value'))->toBe('bar');
});
