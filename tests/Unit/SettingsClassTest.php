<?php

use Tests\Support\TestSettings;
use VanOns\FilamentSettings\Facades\FilamentSettings;

it('falls back to defaults() when the DB has no record', function () {
    $settings = new TestSettings();

    expect($settings->get())->toBe(['theme' => 'light', 'locale' => 'en']);
});

it('constructor settingsName parameter overrides the property and loads from that key', function () {
    FilamentSettings::setValue('custom_name', ['theme' => 'blue']);

    $settings = new TestSettings('custom_name');

    expect($settings->get('theme'))->toBe('blue');
});

it('get() with no argument returns the full settings array', function () {
    FilamentSettings::setValue('test_settings', ['theme' => 'dark']);

    $settings = new TestSettings();

    expect($settings->get())->toBe(['theme' => 'dark']);
});

it('get() resolves a dot-notation key via Arr::get', function () {
    FilamentSettings::setValue('test_settings', ['mail' => ['from' => 'hello@example.com']]);

    $settings = new TestSettings();

    expect($settings->get('mail.from'))->toBe('hello@example.com');
});

it('set() persists a key to the DB and is readable by a fresh instance', function () {
    $settings = new TestSettings();
    $settings->set('theme', 'dark');

    $fresh = new TestSettings();

    expect($fresh->get('theme'))->toBe('dark');
});

it('save() persists an arbitrary array and a fresh instance reads it back', function () {
    $settings = new TestSettings();
    $data = ['a' => 1, 'b' => ['c' => true]];
    $settings->save($data);

    $fresh = new TestSettings();

    expect($fresh->get())->toBe($data);
});

it('filterSettings() strips empty strings, empty arrays, and nulls — keeps 0, false, and non-empty values', function () {
    $settings = new TestSettings();

    $result = $settings->publicFilterSettings([
        'empty_string' => '',
        'empty_array'  => [],
        'null_value'   => null,
        'zero'         => 0,
        'false_value'  => false,
        'valid_string' => 'hello',
        'valid_array'  => ['a'],
    ]);

    expect($result)->toBe([
        'zero'         => 0,
        'false_value'  => false,
        'valid_string' => 'hello',
        'valid_array'  => ['a'],
    ]);
});
