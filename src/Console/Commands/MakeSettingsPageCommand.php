<?php

namespace VanOns\FilamentSettings\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Str;
use Throwable;
use VanOns\FilamentSettings\Traits\CanManipulateFiles;

class MakeSettingsPageCommand extends Command implements PromptsForMissingInput
{
    use CanManipulateFiles;

    protected $signature = 'make:filament-settings-page {name}';

    protected $description = 'Create a new Filament settings page';

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => 'What should the settings page be named?',
        ];
    }

    /**
     * @throws Throwable
     */
    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));
        if (empty($name)) {
            return self::INVALID;
        }

        $plainName = Str::before(Str::kebab($name), '-');
        $pageName = $name . 'Page';
        $displayName = str($name)
            ->kebab()
            ->replace('-', ' ')
            ->title();

        $settingPath = app_path('Settings/' . $name . '.php');

        $this->copyStub(
            'settings.stub',
            $settingPath,
            compact('name', 'plainName')
        );

        $settingPagePath = app_path('Filament/Pages/' . $pageName . '.php');

        $this->copyStub(
            'settings-page.stub',
            $settingPagePath,
            compact('name', 'pageName', 'displayName')
        );

        $this->info('Setting page created successfully:');
        $this->info($settingPagePath);
        $this->info($settingPath);

        return self::SUCCESS;
    }

    /**
     * @throws Throwable
     */
    public function copyStub(string $stub, string $targetPath, array $replacements = []): void
    {
        if ($this->fileExists($targetPath)) {
            $this->fail('This setting already exists.');
        }

        try {
            $this->copyStubToApp($stub, $targetPath, $replacements);
        } catch (Exception) {
            $this->fail('Something went wrong');
        }
    }
}
