<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Github extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "github";
    }
}
