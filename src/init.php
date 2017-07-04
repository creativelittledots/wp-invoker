<?php
	
	
	if ( ! defined('DS') ) {
		
	    define( 'DS', DIRECTORY_SEPARATOR );
	    
	}
    
    if( ! defined( 'ASSET_DIRS' ) ) {
		
		define( 'ASSET_DIRS', implode(',', [
	        'styles',
	        'scripts',
	        'images'
	    ] ) );
		
	}

	/*----------------------------------------------*\
    	#INVOKE FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('invoke') ) {
    
	    function invoke( $callback, $action = 'wp', $condition = null, $priority = null ) {
		    
		    return WPKit\Invoker\Facades\Invoker::invoke( $callback, $action, $condition, $priority );
		    
	    }
	    
	}
	
	/*----------------------------------------------*\
    	#GET ASSET FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('get_asset') ) {
    
	    function get_asset($file, $server_path = false) {
	        
	        foreach( array_map( 'trim', explode(',', ASSET_DIRS) ) as $dir ) {
	                    
	            if( file_exists( get_stylesheet_directory() . DS . $dir . DS . $file ) ) {
		            
		            $path = $server_path ? get_stylesheet_directory() : get_stylesheet_directory_uri();
	                
	                return $path . DS . $dir . DS . $file;
	                
	            }
	            
	        }
	        
	        return false;
	        
	    }
	    
	}