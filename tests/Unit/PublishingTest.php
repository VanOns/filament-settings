<?php

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

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

// Laravel only sends CommandFinished outside unit tests, so the tests send it themselves.
function outputAfter(string $command): string
{
    event(new CommandFinished($command, new ArrayInput([]), $output = new BufferedOutput(), 0));

    return $output->fetch();
}

it('names the migrations an app has neither published nor run after migrate', function () {
    DB::table('migrations')->delete();

    expect(outputAfter('migrate'))
        ->toContain('Migrations of filament-settings that are not published: ')
        ->toContain('create_settings_table')
        ->toContain('php artisan vendor:publish --tag=filament-settings-migrations')
        ->and(outputAfter('migrate:status'))->toBe('');
});

it('stays quiet once the migrations are published', function () {
    DB::table('migrations')->delete();
    $this->artisan('vendor:publish', ['--tag' => 'filament-settings-migrations'])->assertSuccessful();

    expect(outputAfter('migrate'))->toBe('');
});

it('stays quiet about migrations that ran but whose files are gone, as after schema:dump --prune', function () {
    DB::table('migrations')->delete();

    foreach (File::files(__DIR__.'/../../database/migrations') as $file) {
        DB::table('migrations')->insert([
            'migration' => '2020_01_01_000000_'.preg_replace('/^\\d{4}_\\d{2}_\\d{2}_\\d{6}_/', '', $file->getBasename('.php')),
            'batch' => 1,
        ]);
    }

    expect(outputAfter('migrate'))->toBe('');
});
