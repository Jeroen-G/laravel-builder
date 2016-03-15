<?php

namespace JeroenG\LaravelBuilder;

use Illuminate\Support\Facades\Facade;

class LaravelBuilderFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'builder';
    }
}
