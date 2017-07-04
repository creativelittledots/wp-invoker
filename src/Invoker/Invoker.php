<?php
    
    namespace WPKit\Invoker;
    
    use Illuminate\Http\Request;
    
    class Invoker {
	    
	    /**
	     * @var static array
	     */
	    private static $invoked = [];
	    
	    /**
	     * Invoke junction
	     *
	     * @return void
	     */
	    public function invoke( $callback, $action = 'wp', $condition = null, $priority = null ) {
		    
		    $priority = is_null( $priority ) ? ( is_numeric( $condition ) ? $condition : 10 ) : $priority;
		    
		    if( is_null( $condition ) || $condition === $priority ) {
			    
			    $this->forceInvoke( $callback, $action, $priority );
			    
		    } else {
			    
			    $this->invokeByCondition( $callback, $action, $condition, $priority );
			    
		    }
		    
	    }
	    
	    /**
	     * Invoke by condition
	     *
	     * @return void
	     */
	    protected function invokeByCondition( $callback, $action = 'wp', $condition = true, $priority = 10 ) {
			
			add_action( $action, function() use( $action, $callback, $condition, $priority ) {
			
				if( ( is_callable( $condition ) && call_user_func( $condition ) ) || ( ! is_callable( $condition ) && $condition ) ) {
					
					$this->forceInvoke( $callback, $action, $priority );
				
				}
				
			}, $priority-1 );

		}
		
		/**
	     * Force invoke
	     *
	     * @return void
	     */
		protected function forceInvoke( $callback, $action = 'wp', $priority = 10 ) {
			
			$callback = $this->getCallback( $callback );
			
			if( ! $this->invoked( $callback ) ) {
			
				add_action( $action, function() use( $action, $callback ) {
					
					$request = Request::capture();
					
					$filter = implode( '@', [ explode( '@', $callback )[0], 'beforeFilter' ] );
					
					if( is_string( $callback ) && ! $this->invoked( $filter ) ) {
					
						app()->call( $filter, [ 'request' => $request ] );
						
						$this->markAsInvoked( $filter, $action );
						
					}
					
					app()->call( $callback, [ 'request' => $request ] );
					
				}, $priority );
				
				$this->markAsInvoked( $callback, $action );
			
			}
			
		}
		
		/**
	     * Mark as invoked
	     *
	     * @return void
	     */
		protected function markAsInvoked( $callback, $action ) {
			
			self::$invoked[$callback] = $action;
			
		}
		
		/**
	     * Check if is invoked
	     *
	     * @return boolean
	     */
		protected function invoked( $callback ) {
			
			return ! empty( self::$invoked[$callback] ) ? self::$invoked[$callback] : false;
			
		}
	    
	    /**
	     * Get callback
	     *
	     * @return string/closure
	     */
	    protected function getCallback( $callback ) {
		    
	        if ( is_string( $callback ) ) {
		        
		        if( strpos($callback, '@') === false ) {
				    
	            	$callback .= '@dispatch';
	            	
	            }
	            
	        }
	
	        return $callback;
	        
	    }
	    
	}
