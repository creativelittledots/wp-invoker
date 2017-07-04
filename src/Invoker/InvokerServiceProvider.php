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
    protected function registerRouter()
    {
        
        $this->app->instance(
            'invoker',
            $this->app->make('WPKIt\Invoker\Invoker')
        );
        
    }
    
}
