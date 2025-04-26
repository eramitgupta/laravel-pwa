<?php

namespace EragLaravelPwa\Facades;

use Illuminate\Support\Facades\Facade;

class PWA extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \EragLaravelPwa\Services\PWAService::class;
    }
}
