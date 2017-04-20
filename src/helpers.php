<?php
	
	use Illuminate\Container\Container;
	use WPInvoker\Facades\Invoker;
	
	if ( ! function_exists('app') ) {
		
	    /**
	     * Helper function to quickly retrieve an instance.
	     *
	     * @param null  $abstract   The abstract instance name.
	     * @param array $parameters
	     *
	     * @return mixed
	     */
	    function app($abstract = null, array $parameters = [])
	    {
	        if (is_null($abstract)) {
	            return Container::getInstance();
	        }
	        return Container::getInstance()->make($abstract, $parameters);
	    }
	    
	}
	
	/*----------------------------------------------*\
    	#INVOKE FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('invoke') ) {
    
	    function invoke( $callback, $action = 'wp', $condition = null, $priority = null ) {
		    
		    return Invoker::invoke( $callback, $action, $condition, $priority );
		    
	    }
	    
	}
	
	/*----------------------------------------------*\
    	#GET ASSET FUNCTION
    \*----------------------------------------------*/
    
    if ( ! function_exists('get_asset') ) {
    
	    function get_asset($file, $server_path = false) {
	        
	        foreach( array_map( 'trim', explode(',', ASSET_DIRS) ) as $dir ) {
	                    
	            if( file_exists( THEME_DIR . DS . $dir . DS . $file ) ) {
		            
		            $path = $server_path ? THEME_DIR : THEME_URI;
	                
	                return $path . DS . $dir . DS . $file;
	                
	            }
	            
	        }
	        
	        return false;
	        
	    }
	    
	}