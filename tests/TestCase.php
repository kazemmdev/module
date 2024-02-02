<?php

namespace Kazemmdev\Tests\Moduler;

use Kazemmdev\Moduler\ModuleServiceProvider;
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