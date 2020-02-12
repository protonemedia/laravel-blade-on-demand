<?php

namespace ProtoneMedia\BladeOnDemand\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ProtoneMedia\LaravelBladeOnDemand\Skeleton\SkeletonClass
 */
class BladeOnDemand extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-blade-on-demand';
    }
}
