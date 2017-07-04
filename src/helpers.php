<?php

	/*----------------------------------------------*\
    	#INVOKE FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('invoke') ) {
    
	    function invoke( $callback, $action = 'wp', $condition = null, $priority = null ) {
		    
		    return WPKit\Invoker\Facades\Invoker::invoke( $callback, $action, $condition, $priority );
		    
	    }
	    
	}