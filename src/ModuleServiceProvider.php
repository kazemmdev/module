<?php

namespace Kazemmdev\Moduler;

use Illuminate\Support\ServiceProvider;
use Kazemmdev\Moduler\Commands\ModuleCommand;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerCommands();
    }

    protected function registerCommands(): void
    {
        $this->app->bind('command.module:create', ModuleCommand::class);

        $this->commands([
            'command.module:create'
        ]);
    }
}