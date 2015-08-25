<?php namespace Stoempsaucisse\LaravelBoilerplate;

use Illuminate\Support\ServiceProvider;
use Stoempsaucisse\LaravelBoilerplate\Console\Commands\BoilerplateServiceProviderCommand;

/**
 * BoilerplateCommandsServiceProvider
 *
 */ 

class BoilerplateCommandsServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
    * Bootstrap the application.
    *
    * @return void
    */
    public function boot()
    {
        // The publication files to publish
        $this->publishes([
            __DIR__ . '/../../views' => base_path('resources/views/vendor/stoempsaucisse/boilerplate'),
        ]);

        // Register commands
        $this->commands('boilerplate');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../views', 'boilerplate');
    }
        
    /**
     * Register everything.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('boilerplate', function ($app) {
            return new BoilerplateServiceProviderCommand();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['boilerplate'];
    }
}