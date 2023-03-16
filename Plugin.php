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
            'description' => 'CORS pluign that just works',
            'author'      => 'liip',
            'icon'        => 'icon-leaf'
        ];
    }

    public function register()
    {
        $this->app[\Illuminate\Contracts\Http\Kernel::class]
            ->prependMiddleware(CorsMiddleware::class);
    }
}
