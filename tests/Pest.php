<?php

use Kazemmdev\Tests\Module\TestCase;

uses(TestCase::class)->in(__DIR__);

expect()->extend('toBeOne', extend: function () {
    return $this->toBe(1);
});
