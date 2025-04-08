<?php

namespace VanOns\FilamentSettings\Traits;

use Filament\Support\Commands\Concerns\CanManipulateFiles as FilamentCanManipulateFiles;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait CanManipulateFiles
{
    use FilamentCanManipulateFiles;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws FileNotFoundException
     */
    protected function copyStubToApp(string $stubPath, string $targetPath, array $replacements = []): void
    {
        $filesystem = app(Filesystem::class);

        if (!$this->fileExists($realStubPath = base_path($stubPath))) {
            $last = \Str::afterLast($stubPath, '/');
            $realStubPath = $this->getDefaultStubPath() . "/{$last}";
        }

        $stub = str($filesystem->get($realStubPath));

        foreach ($replacements as $key => $replacement) {
            $stub = $stub->replace("{{ {$key} }}", $replacement);
        }

        $stub = (string) $stub;

        $this->writeFile($targetPath, $stub);
    }
}
