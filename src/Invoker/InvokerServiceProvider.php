<?php

namespace WPKit\Invoker;

use Illuminate\Support\ServiceProvider;
use WPKit\Invoker\Facades\Invoker as Facade;
use Illuminate\Support\Facades\Input as BaseInput;
use Themosis\Facades\Input;

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
	    
	    if( class_exists( Input::class ) ) {
		    
		    $this->app->alias(Input::class, BaseInput::class);
		    
	    }
        
        $this->app->instance(
            'invoker',
            $this->app->make(Invoker::class)
        );
        
    }
    
}
