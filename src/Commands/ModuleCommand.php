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
        // Model
        $filePath = $this->targetPath . '/' . $this->model . '.php';
        $this->createFileFromStub('model', $filePath);

        // Factory
        $filePath = $this->targetPath . '/' . $this->model . 'Factory.php';
        $this->createFileFromStub('factory', $filePath);

        // Service
        $filePath = $this->targetPath . '/' . $this->model . 'Service.php';
        $this->createFileFromStub('service', $filePath);

        // Test
        $filePath = $this->targetPath . '/Tests/Feature.php';
        $this->createFileFromStub('test', $filePath);
    }

    protected function createControllers(): void
    {
        // requests
        $filePath = $this->targetPath . '/Requests/StoreRequest.php';
        $this->createFileFromStub('request', $filePath);

        $filePath = $this->targetPath . '/Requests/UpdateRequest.php';
        $this->createFileFromStub('request', $filePath);

        // resources
        $filePath = $this->targetPath . '/Resources/' . $this->model . 'Resource.php';
        $this->createFileFromStub('resource', $filePath);

        // controllers
        $filePath = $this->targetPath . '/Controllers/IndexController.php';
        $this->createFileFromStub('index.controller', $filePath);

        $filePath = $this->targetPath . '/Controllers/ShowController.php';
        $this->createFileFromStub('show.controller', $filePath);

        $filePath = $this->targetPath . '/Controllers/UpdateController.php';
        $this->createFileFromStub('update.controller', $filePath);

        $filePath = $this->targetPath . '/Controllers/StoreController.php';
        $this->createFileFromStub('store.controller', $filePath);

        $filePath = $this->targetPath . '/Controllers/DestroyController.php';
        $this->createFileFromStub('destroy.controller', $filePath);
    }

    protected function createFileFromStub(string $type, string $filePath): void
    {
        $template = str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ lower(namespace) }}', '{{ lower(class) }}'],
            [$this->namespace, $this->model, strtolower($this->namespace), strtolower($this->model)],
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
