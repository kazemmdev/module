<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it("creates a new module", function () {
    $moduleName   = 'SampleModule';
    $expectedPath = base_path("src/Modules/{$moduleName}");

    Artisan::call("make:module {$moduleName}");

//    dd(
//        $expectedPath,
//        File::files(base_path('src/Modules'))
//    );

});
