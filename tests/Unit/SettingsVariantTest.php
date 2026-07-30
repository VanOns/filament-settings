<?php

use Tests\Support\TestSettings;
use VanOns\FilamentSettings\Facades\FilamentSettings;

it('uses the plain settings name when no variant is given', function () {
    $settings = new TestSettings();

    expect($settings->getVariant())->toBeNull()
        ->and($settings->getSettingsName())->toBe('test_settings');
});

it('appends the variant to the settings name', function () {
    $settings = new TestSettings(variant: 'nl');

    expect($settings->getVariant())->toBe('nl')
        ->and($settings->getSettingsName())->toBe('test_settings.nl');
});

it('appends the variant to an overridden settings name', function () {
    $settings = new TestSettings('custom_name', 'de');

    expect($settings->getSettingsName())->toBe('custom_name.de');
});

it('ignores an empty variant', function () {
    $settings = new TestSettings(variant: '');

    expect($settings->getSettingsName())->toBe('test_settings');
});

it('forVariant() builds an instance scoped to that variant', function () {
    $settings = TestSettings::forVariant('nl');

    expect($settings)->toBeInstanceOf(TestSettings::class)
        ->and($settings->getSettingsName())->toBe('test_settings.nl');
});

it('forVariant(null) falls back to the plain settings name', function () {
    expect(TestSettings::forVariant(null)->getSettingsName())->toBe('test_settings');
});

it('reads the row belonging to its variant', function () {
    FilamentSettings::setValue('test_settings.nl', ['theme' => 'orange']);

    expect(TestSettings::forVariant('nl')->get('theme'))->toBe('orange');
});

it('falls back to defaults() when the variant has no row yet', function () {
    FilamentSettings::setValue('test_settings', ['theme' => 'dark']);

    expect(TestSettings::forVariant('nl')->get())->toBe(['theme' => 'light', 'locale' => 'en']);
});

it('keeps variants isolated from each other when saving', function () {
    TestSettings::forVariant('nl')->set('theme', 'orange');
    TestSettings::forVariant('de')->set('theme', 'black');

    expect(TestSettings::forVariant('nl')->get('theme'))->toBe('orange')
        ->and(TestSettings::forVariant('de')->get('theme'))->toBe('black')
        ->and((new TestSettings())->get('theme'))->toBe('light');
});
