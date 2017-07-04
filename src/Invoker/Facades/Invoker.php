<?php

	namespace WPInvoker\Facades;
	
	use Themosis\Facades\Facade;

	class Invoker extends Facade {
		
	    /**
	     * Get the registered name of the component.
	     *
	     * @return string
	     */
	    protected static function getFacadeAccessor()
	    {
	        return 'invoker';
	    }
	    
	}