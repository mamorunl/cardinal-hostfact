<?php

namespace Tnpdigital\Cardinal\Hostfact;

class HostfactServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/hostfact.php' => config_path('hostfact.php')
        ]);
    }

    public function register()
    {
        //
    }
}