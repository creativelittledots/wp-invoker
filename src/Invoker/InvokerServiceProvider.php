<?php

namespace WPKit\Invoker;

use Illuminate\Support\ServiceProvider;
use WPKit\Invoker\Facades\Invoker as Facade;

class InvokerServiceProvider extends ServiceProvider
{
    
    /**
     * Register the router instance.
     *
     * @return void
     */
    public function register()
    {
	    Facade::setFacadeApplication($this->app);
        
        $this->app->instance(
            'invoker',
            $this->app->make(Invoker::class)
        );
        
    }
    
}
