<?php

namespace ProtoneMedia\BladeOnDemand;

use Illuminate\Mail\Markdown;
use Illuminate\Support\ServiceProvider;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class BladeOnDemandServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        // Register the main class to use with the facade
        $this->app->singleton('laravel-blade-on-demand', function () {
            return new BladeOnDemandRenderer(
                app('view'),
                app(Markdown::class),
                app(CssToInlineStyles::class)
            );
        });
    }
}
