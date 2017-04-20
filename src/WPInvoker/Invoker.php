<?php
    
    namespace WPInvoker;
    
    use Illuminate\Container\Container;
    
    class Invoker {
	    
	    /**
	     * @var \WPKit\Application
	     */
	    protected $app;
	    
	     /**
	     * @var array
	     */
	    protected $routes = array();

		public function __construct(Container $container) {
	    	
	    	$this->container = $container;
	    	
	    }
	    
	    public function invoke( $callback, $action = 'wp', $condition = null, $priority = null ) {
		    
		    $priority = is_null( $priority ) ? ( is_numeric( $condition ) ? $condition : 10 ) : $priority;
		    
		    if( is_null( $condition ) || $condition === $priority ) {
			    
			    return $this->forceInvoke( $callback, $action, $priority );
			    
		    } else {
			    
			    return $this->invokeByCondition( $callback, $action, $condition, $priority );
			    
		    }
		    
	    }
	    
	    public function invokeByCondition( $callback, $action = 'wp', $condition = true, $priority = 10 ) {
			
			add_action( $action, function() use( $action, $route, $condition, $priority ) {
			
				if( ( is_callable( $condition ) && call_user_func( $condition ) ) || ( ! is_callable( $condition ) && $condition ) ) {
			
					add_action( $action, function() use ( $callback ) {
						
						$this->container->call( $this->getCallback( $callback ) );
						
					}, $priority );
				
				}
				
			}, $priority-1 );

		}
		
		public function forceInvoke( $callback, $action = 'wp', $priority = 10 ) {
			
			add_action( $action, function() use ( $route ) {
									
				$this->container->call( $this->getCallback( $callback ) );
				
			}, $priority );
			
			return $route;
			
		}
	    
	    protected function getCallback( $callback ) {
		    
	        if ( is_string( $callback ) ) {
		        
		        if( strpos($callback, '@') === false ) {
				    
	            	$callback .= '@dispatch';
	            	
	            }
	            
	            $callback = explode( $callback, '@' );
	            
	        }
	
	        return $callback;
	        
	    }
	    
	}
