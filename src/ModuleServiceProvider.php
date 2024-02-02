<?php

namespace Kazemmdev\Module;

use Illuminate\Support\ServiceProvider;
use Kazemmdev\Module\Commands\ModuleCommand;

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
