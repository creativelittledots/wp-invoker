<?php

namespace WPInvoker;

use Illuminate\Support\ServiceProvider;

class InvokerServiceProvider extends ServiceProvider
{
    
    /**
     * Register the router instance.
     *
     * @return void
     */
    protected function registerRouter()
    {
        
        $this->app->instance(
            'invoker',
            $this->app->make('WPInvoker\Invoker', ['app' => $this->app])
        );
        
    }
    
}
