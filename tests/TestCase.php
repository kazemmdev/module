<?php

namespace Kazemmdev\Tests\Module;

use Kazemmdev\Module\ModuleServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ModuleServiceProvider::class
        ];
    }
}
