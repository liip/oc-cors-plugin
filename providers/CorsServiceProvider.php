<?php

namespace Liip\Cors\Providers;

use Config;
use Asm89\Stack\CorsService;
use Fruitcake\Cors\HandleCors;
use Fruitcake\Cors\HandlePreflight;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class CorsServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            CorsService::class,
            function ($app) {
                $options = $app['config']->get('cors');

                if (isset($options['allowedOrigins'])) {
                    foreach ($options['allowedOrigins'] as $origin) {
                        if (strpos($origin, '*') !== false) {
                            $options['allowedOriginsPatterns'][] = $this->convertWildcardToPattern($origin);
                        }
                    }
                }

                return new CorsService($options);
            }
        );
    }

    /**
     * Add the Cors middleware to the router.
     *
     */
    public function boot()
    {
        $this->loadConfiguration();
        
        $kernel = $this->app->make(Kernel::class);
        
        // When the HandleCors middleware is not attached globally, add the PreflightCheck
        if (!$kernel->hasMiddleware(HandleCors::class)) {
            $kernel->prependMiddleware(HandlePreflight::class);
        }

        $this->app['router']->middleware('cors', HandleCors::class);
    }

    /**
     * Load plugin configuration
     *
     * @return void
     */
    protected function loadConfiguration()
    {
        Config::set(
            'cors',
            [
                'path' => '/*',
                'supportsCredentials' => true,
                /*
                'allowedOrigins' => '*',
                'allowedHeaders' => '*',
                'allowedMethods' => '*',
                'exposedHeaders' => '*',
                'maxAge' => 0
                */
            ]
        );
    }
}
