<?php

use Illuminate\Support\Facades\File;

afterEach(function () {
    File::delete(app_path('Settings/GeneralSettings.php'));
    File::delete(app_path('Filament/Pages/GeneralSettingsPage.php'));
});

it('generates a settings class and a settings page for a given name', function () {
    $this->artisan('make:filament-settings-page', ['name' => 'general-settings'])
        ->assertSuccessful();

    $settingPath = app_path('Settings/GeneralSettings.php');
    $settingPagePath = app_path('Filament/Pages/GeneralSettingsPage.php');

    expect(File::exists($settingPath))->toBeTrue();
    expect(File::exists($settingPagePath))->toBeTrue();
    expect(File::get($settingPath))->toContain('class GeneralSettings');
    expect(File::get($settingPagePath))->toContain('class GeneralSettingsPage');
});

it('fails without creating a file when the name is blank', function () {
    $this->artisan('make:filament-settings-page', ['name' => ''])
        ->assertFailed();

    expect(File::exists(app_path('Settings/.php')))->toBeFalse();
});

it('fails without creating a file when the name contains path separators', function () {
    $this->artisan('make:filament-settings-page', ['name' => '../../etc/passwd'])
        ->assertFailed();

    expect(File::exists(app_path('Settings/../../etc/passwd.php')))->toBeFalse();
    expect(File::exists(base_path('etc/passwd.php')))->toBeFalse();
});
