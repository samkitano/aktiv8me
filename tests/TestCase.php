<?php

namespace Tests;

abstract class TestCase extends Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return ['Kitano\Aktiv8me\Aktiv8meServiceProvider'];
    }
}
