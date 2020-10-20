<?php

namespace Liip\Cors;

use Asm89\Stack\CorsService;
use Fruitcake\Cors\HandleCors;
use Fruitcake\Cors\HandlePreflight;
use Fruitcake\Cors\ServiceProvider as CorsServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use System\Classes\PluginBase;
use Config;

/**
 * cors Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Cors',
            'description' => 'CORS pluign based on asm89/cors and barryvds/laravel-cors',
            'author'      => 'liip',
            'icon'        => 'icon-leaf'
        ];
    }

    public function register()
    {
        $this->app->singleton(CorsService::class, function ($app) {
            $options = $app['config']->get('cors');

            if (isset($options['allowedOrigins'])) {
                foreach ($options['allowedOrigins'] as $origin) {
                    if (strpos($origin, '*') !== false) {
                        $options['allowedOriginsPatterns'][] = $this->convertWildcardToPattern($origin);
                    }
                }
            }

            return new CorsService($options);
        });
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        // enable CORS middleware
        $this->app['Illuminate\Contracts\Http\Kernel']->pushMiddleware(HandleCors::class);

        $this->bootPackages();

        $kernel = $this->app->make(Kernel::class);
        // When the HandleCors middleware is not attached globally, add the PreflightCheck
        if (!$kernel->hasMiddleware(HandleCors::class)) {
            $kernel->prependMiddleware(HandlePreflight::class);
        }
    }

    public function bootPackages()
    {
        // Get the namespace of the current plugin to use in accessing the Config of the plugin
        $pluginNamespace = str_replace('\\', '.', strtolower(__NAMESPACE__));

        // Get the packages to boot
        $packages = Config::get($pluginNamespace . '::packages');

        // Boot each package
        foreach ($packages as $name => $options) {
            // Setup the configuration for the package, pulling from this plugin's config
            if (!empty($options['config']) && !empty($options['config_namespace'])) {
                Config::set($options['config_namespace'], $options['config']);
            }
        }
    }

    /**
     * Create a pattern for a wildcard, based on Str::is() from Laravel
     *
     * @see https://github.com/laravel/framework/blob/5.5/src/Illuminate/Support/Str.php
     * @param $pattern
     * @return string
     */
    protected function convertWildcardToPattern($pattern)
    {
        $pattern = preg_quote($pattern, '#');

        // Asterisks are translated into zero-or-more regular expression wildcards
        // to make it convenient to check if the strings starts with the given
        // pattern such as "library/*", making any string check convenient.
        $pattern = str_replace('\*', '.*', $pattern);

        return '#^' . $pattern . '\z#u';
    }
}
