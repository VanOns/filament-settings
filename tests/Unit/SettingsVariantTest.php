<?php

use Tests\Support\TestSettings;
use VanOns\FilamentSettings\Facades\FilamentSettings;

it('uses the plain settings name when no variant is set', function () {
    $settings = new TestSettings();

    expect($settings->getVariant())->toBeNull()
        ->and($settings->getSettingsName())->toBe('test_settings');
});

it('appends the variant to the settings name', function () {
    $settings = (new TestSettings())->withVariant('nl');

    expect($settings->getVariant())->toBe('nl')
        ->and($settings->getSettingsName())->toBe('test_settings.nl');
});

it('appends the variant to an overridden settings name', function () {
    $settings = (new TestSettings('custom_name'))->withVariant('de');

    expect($settings->getSettingsName())->toBe('custom_name.de');
});

it('ignores an empty variant', function () {
    $settings = (new TestSettings())->withVariant('');

    expect($settings->getSettingsName())->toBe('test_settings');
});

it('withVariant() returns the same instance for chaining', function () {
    $settings = new TestSettings();

    expect($settings->withVariant('nl'))->toBe($settings);
});

it('withVariant(null) keeps the plain settings name', function () {
    expect((new TestSettings())->withVariant(null)->getSettingsName())->toBe('test_settings');
});

it('reads the row belonging to its variant', function () {
    FilamentSettings::setValue('test_settings.nl', ['theme' => 'orange']);

    expect((new TestSettings())->withVariant('nl')->get('theme'))->toBe('orange');
});

it('reloads settings when the variant changes', function () {
    FilamentSettings::setValue('test_settings', ['theme' => 'dark']);
    FilamentSettings::setValue('test_settings.nl', ['theme' => 'orange']);

    $settings = new TestSettings();

    expect($settings->get('theme'))->toBe('dark')
        ->and($settings->withVariant('nl')->get('theme'))->toBe('orange');
});

it('falls back to defaults() when the variant has no row yet', function () {
    FilamentSettings::setValue('test_settings', ['theme' => 'dark']);

    expect((new TestSettings())->withVariant('nl')->get())->toBe(['theme' => 'light', 'locale' => 'en']);
});

it('keeps variants isolated from each other when saving', function () {
    (new TestSettings())->withVariant('nl')->set('theme', 'orange');
    (new TestSettings())->withVariant('de')->set('theme', 'black');

    expect((new TestSettings())->withVariant('nl')->get('theme'))->toBe('orange')
        ->and((new TestSettings())->withVariant('de')->get('theme'))->toBe('black')
        ->and((new TestSettings())->get('theme'))->toBe('light');
});
