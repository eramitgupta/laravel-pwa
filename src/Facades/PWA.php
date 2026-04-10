<?php

namespace EragLaravelPwa\Facades;

use EragLaravelPwa\Services\PWAService;
use Illuminate\Support\Facades\Facade;

class PWA extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PWAService::class;
    }
}
