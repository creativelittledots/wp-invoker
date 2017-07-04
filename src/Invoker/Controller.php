<?php
    
    namespace WPInvoker;
    
    use Illuminate\Routing\Controller as BaseController;
    use Themosis\Facades\Asset;
    use Illuminate\Container\Container;
    
    class Controller extends BaseController {
	    
	    /**
	     * @var Static
	     */
	    public static $instances = [];
        
        /**
	     * @var array
	     */
        protected $scripts = [];
        
        /**
	     * Instance function to return only once instance of the controller
	     *
	     * @return \WPKit\Core\Controller
	     */
        public function instance( $app ) {
	        
	        $class = get_called_class();
	        
	        if( empty( static::$instances[$class] ) ) {
		        
		        static::$instances[$class] = $app->make($class, func_get_args()); 
		        
	        }
	        
	        return static::$instances[$class];
	        
        }
		
		/**
	     * Default controller action should the controller be invoked
	     *
	     * @return void
	     */
		public function dispatch() {
			
		}
		
		/**
	     * Execute an action on the controller.
	     *
	     * @param  string  $method
	     * @param  array   $parameters
	     * @return \Symfony\Component\HttpFoundation\Response
	     */
	    public function callAction($method, $parameters) {
		    
		    call_user_func_array([$this, 'enqueueScripts'], $parameters);
		    
	        return call_user_func_array([$this, $method], $parameters);
	        
	    }
        
        /**
	     * Get scripts for controller
	     *
	     * @return array
	     */
        protected function getScripts() {
	        
	        return $this->scripts;
	        
        }
        
        /**
	     * Enqueue scripts for controller
	     *
	     * @return void
	     */
        public function enqueueScripts() {
	        
			foreach($this->getScripts() as $script) {
				
				$script = is_array($script) ? $script : ['file' => $script];
				
				if ( $script['file'] = $this->getScriptPath( ! empty( $script['file'] ) ? $script['file'] : '' ) ) {
						
    				$info = pathinfo( $script['file'] );
    				
    				$extension = ! empty( $info['extension'] ) ? $info['extension'] : ( ! empty( $script['type'] ) ? $script['type'] : false );
    				
    				$script = array_merge(array(
						'dependencies' => array(),
						'version' => '1.0.0',
						'mixed' => $extension == 'js' ? true : false,
						'localize' => false,
						'enqueue' => true
					), $script, array(
						'handle' => ! empty( $script['handle'] ) ? $script['handle'] : $info['filename']
					));
    				
    				Asset::add($script['handle'], $script['file'], $script['dependencies'], $script['version'], $script['mixed']);
    				
                }
				
			}
			
		}
		
		protected function getScriptPath( $file ) {
    		
    		if( ! filter_var( $file , FILTER_VALIDATE_URL) === false ) {
        		
        		return $file;
        		
            } 
            
            else if( $file = get_asset( $file ) ) {
             
                return $file;
                
            }
            
            return false;
    		
		}
        
    }