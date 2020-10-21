<?php 

namespace Liip\Cors;

use System\Classes\PluginBase;
use Illuminate\Contracts\Http\Kernel;

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
            'description' => 'CORS pluign that just works',
            'author'      => 'liip',
            'icon'        => 'icon-leaf'
        ];
    }       

    public function register()
    {
        $kernel = $this->app->make(Kernel::class);
        $kernel->prependMiddleware(CorsMiddleware::class);
    }
}
