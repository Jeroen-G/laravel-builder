<?php

namespace JeroenG\LaravelBuilder;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class LaravelBuilderServiceProvider extends ServiceProvider
{
    /**
     * The console commands.
     * @var array
     */
    protected $commands = [
        'JeroenG\LaravelBuilder\BuildCommand',
        'JeroenG\LaravelBuilder\BuildStubsCommand',
    ];

    /**
     * Perform post-registration booting of services.
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/stubs' => base_path('stubs'),
        ], 'stubs');
    }

    /**
     * Register any package services.
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);

        $this->app->singleton('Builder', function ($app) {
            return new Builder(new Filesystem);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['builder'];
    }
}