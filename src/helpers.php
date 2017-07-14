<?php
	
	if( ! defined( 'DS' ) ) {
		
		define( 'DS', DIRECTORY_SEPARATOR );
		
	}

	/*----------------------------------------------*\
    	#INVOKE FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('invoke') ) {
    
	    function invoke( $callback, $action = 'wp', $condition = null, $priority = null ) {
		    
		    return WPKit\Invoker\Facades\Invoker::match( $callback, $action, $condition, $priority );
		    
	    }
	    
	}
	
	if ( ! function_exists('get_asset') ) {
    
	    function get_asset( $file, $server_path = false ) {
		    
		    if( file_exists( get_stylesheet_directory() . DS . $file ) ) {
		            
	            $path = $server_path ? get_stylesheet_directory() : get_stylesheet_directory_uri();
                
                return $path . DS . $file;
                
            }
		    
	    }
	    
	}
