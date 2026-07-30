<?php

use Tests\Support\TestSettingsPage;

it('builds an unscoped settings instance by default', function () {
    $page = new TestSettingsPage();

    expect($page->getSettingsVariant())->toBeNull()
        ->and($page->getSettingsInstance()->getSettingsName())->toBe('test_settings');
});

it('passes the page variant through to the settings instance', function () {
    $page = new TestSettingsPage('nl');

    expect($page->getSettingsInstance()->getSettingsName())->toBe('test_settings.nl');
});
