<?php

use Illuminate\Support\Facades\File;

function publishedMigrations(): array
{
    return collect(File::glob(database_path('migrations/*.php')))
        ->map(fn (string $path): string => basename($path))
        ->reject(fn (string $name): bool => str_starts_with($name, '0001_01_01_'))
        ->values()
        ->all();
}

afterEach(function () {
    foreach (publishedMigrations() as $name) {
        File::delete(database_path("migrations/{$name}"));
    }
});

it('publishes each migration once, also next to ones an app published before', function () {
    File::copy(
        __DIR__.'/../../database/migrations/2025_04_07_143600_create_settings_table.php',
        database_path('migrations/2025_06_01_090000_create_settings_table.php'),
    );
    $this->refreshApplication();

    $this->artisan('vendor:publish', ['--tag' => 'filament-settings-migrations'])->assertSuccessful();
    $this->artisan('vendor:publish', ['--tag' => 'filament-settings-migrations'])->assertSuccessful();

    expect(publishedMigrations())->toBe(['2025_06_01_090000_create_settings_table.php']);
});

it('uses the translations an app published', function () {
    File::ensureDirectoryExists(lang_path('vendor/filament-settings/en'));
    File::put(lang_path('vendor/filament-settings/en/panel.php'), "<?php return ['error_message' => 'Saving failed.'];");

    try {
        expect(__('filament-settings::panel.error_message'))->toBe('Saving failed.')
            ->and(__('filament-settings-lang::panel.error_message'))->toBe('Something went wrong while saving the settings.');
    } finally {
        File::deleteDirectory(lang_path('vendor/filament-settings'));
    }
});
