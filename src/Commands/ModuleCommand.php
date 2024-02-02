<?php

declare(strict_types=1);

namespace Kazemmdev\Moduler\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class ModuleCommand extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Create a new module command';

    public function handle(): void
    {
        $module = $this->argument('name');
        $model = ucfirst(Str::singular($module));
        $namespace = ucfirst(Str::plural($module));
        $targetPath = $this->targetPath() . $namespace;

        // Create the base module directory
        $this->createDirectory($targetPath);

        // Define file types and their respective directories
        $fileTypes = [
            'controller' => 'Controllers',
            'request' => 'Requests',
            'resource' => 'Resources',
            'test' => 'Tests',
            'model' => '',
            'factory' => '',
            'service' => ''
        ];

        // Process each file type
        foreach ($fileTypes as $type => $dir) {
            $filePath = $targetPath . '/' . $dir . '/' . $model . ucfirst($type) . '.php';
            $this->createFileFromStub($type, $filePath, $model, $namespace);
        }
    }

    protected function createDirectory(string $path): void
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
    }

    protected function createFileFromStub(string $type, string $filePath, string $model, string $namespace): void
    {
        $template = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            ["Modules\\$namespace\\" . ucfirst(Str::plural($type)), $model],
            $this->getStub($type)
        );

        // Ensure the directory for the file exists
        $dir = dirname($filePath);
        $this->createDirectory($dir);

        // Write the file
        File::put($filePath, $template);
    }

    protected function targetPath(): string
    {
        return base_path('src/Modules') . '/';
    }

    protected function getStub($type): bool|string
    {
        return file_get_contents(__DIR__ . "/../Stubs/{$type}.stub");
    }
}
