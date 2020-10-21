<?php 

namespace Liip\Cors;

use System\Classes\PluginBase;

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
            'description' => 'CORS pluign based on fruitcake/laravel-cors',
            'author'      => 'liip',
            'icon'        => 'icon-leaf'
        ];
    }

    public function register()
    {
        $this->app->register(\Liip\Cors\Providers\CorsServiceProvider::class);
        $this->app['router']->middleware('cors', \Fruitcake\Cors\HandleCors::class);        
    }
}
