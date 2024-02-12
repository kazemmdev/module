<?php

declare(strict_types=1);

namespace Kazemmdev\Module\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleCommand extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Create a new module command';

    protected string $model;
    protected string $namespace;
    protected string $targetPath;


    public function handle(): void
    {
        $module = $this->argument('name');

        $this->model      = ucfirst(Str::singular($module));
        $this->namespace  = ucfirst(Str::plural($module));
        $this->targetPath = $this->targetPath() . $this->namespace;

        $this->createDirectory($this->targetPath);

        $this->createBaseModel();
        $this->createControllers();
    }

    protected function createBaseModel(): void
    {
        $fileTypes = [
            'test'    => 'Tests',
            'model'   => '',
            'factory' => '',
            'service' => ''
        ];

        foreach ($fileTypes as $type => $dir) {
            $filePath = $this->targetPath . '/' . $dir . '/' . $this->model . ucfirst($type) . '.php';
            $this->createFileFromStub($type, $filePath);
        }
    }

    protected function createControllers(): void
    {
        // requests
        $filePath = $this->targetPath . '/Requests/Store' . $this->model . 'Request.php';
        $this->createFileFromStub('request', $filePath);

        $filePath = $this->targetPath . '/Requests/Update' . $this->model . 'Request.php';
        $this->createFileFromStub('request', $filePath);

        // resources
        $filePath = $this->targetPath . '/Resources/' . $this->model . 'Resource.php';
        $this->createFileFromStub('resource', $filePath);

        // controllers
        $filePath = $this->targetPath . '/Controllers/Index' . $this->model . 'Controller.php';
        $this->createFileFromStub('controller', $filePath);

        $filePath = $this->targetPath . '/Controllers/Show' . $this->model . 'Controller.php';
        $this->createFileFromStub('controller', $filePath);

        $filePath = $this->targetPath . '/Controllers/Update' . $this->model . 'Controller.php';
        $this->createFileFromStub('controller', $filePath);

        $filePath = $this->targetPath . '/Controllers/Store' . $this->model . 'Controller.php';
        $this->createFileFromStub('controller', $filePath);

        $filePath = $this->targetPath . '/Controllers/Destroy' . $this->model . 'Controller.php';
        $this->createFileFromStub('controller', $filePath);
    }

    protected function createFileFromStub(string $type, string $filePath): void
    {
        $template = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            ["Modules\\$this->namespace\\" . ucfirst(Str::plural($type)), $this->model],
            $this->getStub($type)
        );

        // Ensure the directory for the file exists
        $dir = dirname($filePath);
        $this->createDirectory($dir);

        // Write the file
        File::put($filePath, $template);
    }

    protected function createDirectory(string $path): void
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
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
