<?php

namespace WPKit\Invoker;

use Themosis\Foundation\ServiceProvider;

class InvokerServiceProvider extends ServiceProvider
{
    
    /**
     * Register the router instance.
     *
     * @return void
     */
    public function register()
    {
        
        $this->app->instance(
            'invoker',
            $this->app->make('WPKit\Invoker\Invoker')
        );
        
    }
    
}
