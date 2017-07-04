<?php

	/*----------------------------------------------*\
    	#INVOKE FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('invoke') ) {
    
	    function invoke( $callback, $action = 'wp', $condition = null, $priority = null ) {
		    
		    return WPKit\Invoker\Facades\Invoker::match( $callback, $action, $condition, $priority );
		    
	    }
	    
	}