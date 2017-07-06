<?php

namespace WPKit\Invoker;

use Illuminate\Support\ServiceProvider;

class InvokerServiceProvider extends ServiceProvider
{
    
    /**
     * Register the router instance.
     *
     * @return void
     */
    public function register()
    {
	    WPKit\Invoker\Facades\Invoker::setFacadeApplication($this->app);
        
        $this->app->instance(
            'invoker',
            $this->app->make(Invoker::class)
        );
        
    }
    
}
