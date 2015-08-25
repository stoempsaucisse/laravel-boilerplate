namespace {{ $Namespace }};

use Illuminate\Support\ServiceProvider;

/**
 * {{ $Resource }}ServiceProvider
 *
 */ 

class {{ $Resource }}ServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
    * Bootstrap the application.
    *
    * @return void
    */
    public function boot()
    {
        // The publication files to publish
        $this->publishes([
            __DIR__ . '/../../../views' => base_path('resources/views/vendor/{{ strtolower($App) }}/{{ $resources }}'),
        ]);

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../../views', '{{ $resources }}');
    }
        
    /**
     * Register everything.
     *
     * @return void
     */
    public function register()
    {
        // Binding the configured implementation of the {{ $Resource }} Contract as singleton
        // \Config::get('{{ $resources }}.handler') should point to the
        // handler implementation (like a Eloquent handler).
        $this->app->singleton(
            '{{ $App }}\Contracts\{{ $Resources }}\{{ $Resource }}',
            \Config::get('{{ $resources }}.handler')
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['{{ $App }}\Contracts\{{ $Resources }}\{{ $Resource }}'];
    }
}