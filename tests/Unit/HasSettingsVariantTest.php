<?php

use Tests\Support\LegacyConstructorSettings;
use Tests\Support\TestSettingsPage;
use VanOns\FilamentSettings\Facades\FilamentSettings;

it('builds an unscoped settings instance by default', function () {
    $page = new TestSettingsPage();

    expect($page->getSettingsVariant())->toBeNull()
        ->and($page->getSettingsInstance()->getSettingsName())->toBe('test_settings');
});

it('passes the page variant through to the settings instance', function () {
    $page = new TestSettingsPage('nl');

    expect($page->getSettingsInstance()->getSettingsName())->toBe('test_settings.nl');
});

it('saves through the page variant without touching the unscoped row', function () {
    FilamentSettings::setValue('test_settings', ['theme' => 'dark']);

    (new TestSettingsPage('nl'))->getSettingsInstance()->save(['theme' => 'orange']);

    expect(FilamentSettings::getValue('test_settings.nl'))->toBe(['theme' => 'orange'])
        ->and(FilamentSettings::getValue('test_settings'))->toBe(['theme' => 'dark']);
});

it('supports a settings class that declares its own constructor', function () {
    $page = new TestSettingsPage(null, LegacyConstructorSettings::class);
    $settings = $page->getSettingsInstance();

    expect($settings->constructorRan)->toBeTrue()
        ->and($settings->getSettingsName())->toBe('legacy_settings');
});

it('applies a variant to a settings class that declares its own constructor', function () {
    $page = new TestSettingsPage('nl', LegacyConstructorSettings::class);

    expect($page->getSettingsInstance()->getSettingsName())->toBe('legacy_settings.nl');
});
