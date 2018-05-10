<?php

	/*----------------------------------------------*\
    	#INVOKE FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('invoke') ) {
    
	    function invoke( $callback, $action = 'wp', $condition = null, $priority = null ) {
		    
		    return WPKit\Invoker\Facades\Invoker::match( $callback, $action, $condition, $priority );
		    
	    }
	    
	}
	
	if ( ! function_exists('action') ) {
	
		function action() {
			
			call_user_func_array(__NAMESPACE__ .'\hook', array_merge([__FUNCTION__], func_get_args()));	
				
		}
		
	}
	
	if ( ! function_exists('filter') ) {
	
		function filter() {
			
			call_user_func_array(__NAMESPACE__ .'\hook', array_merge([__FUNCTION__], func_get_args()));		
			
		}
		
	}
	
	if ( ! function_exists('hook') ) {
	
		function hook( $type, $hook, $callback ) {
			
			$trace = debug_backtrace();
			
			if( ! is_callable( $hook ) ) {
			
				$caller = ! empty( $trace[2]['object'] ) ? $trace[2]['object'] : ( ! empty( $trace[1]['object'] ) ? $trace[1]['object'] : null );
			
				$callback = $caller && method_exists($caller, $callback) ? [$caller, $callback] : $callback;
				
			}
			
			$fn = "add_$type";
			
			if( function_exists( $fn ) ) {
				
				$args = func_get_args();
				
				unset($args[0]);
				
				call_user_func_array( $fn, $args );
				
			}
			
		}
		
	}
