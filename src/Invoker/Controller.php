<?php
    
    namespace WPKit\Invoker;
    
    use Illuminate\Contracts\Container\Container;
    use Illuminate\Routing\Controller as BaseController;
    use Illuminate\Support\Facades\Input;
    
    class Controller extends BaseController {
	    
	    /**
	     * @var Illuminate\Contracts\Container\Container
	     */
	    protected $app = null;
	    
	    /**
	     * @var boolean
	     */
	    private $dispatched = false;
        
        /**
	     * @var array
	     */
        protected $scripts = [];
        
        /**
	     * Controller constructor
	     *
	     * @param  \Illuminate\Contracts\Container\Container  $app
	     * @return void
	     */
        public function __construct(Container $app) {
	        
	        $this->app = $app;
	        
        }
		
		/**
	     * Default controller action should the controller be invoked
	     *
	     * @return void
	     */
		public function dispatch(Input $request) {}
        
        /**
	     * Get scripts for controller
	     *
	     * @return array
	     */
        protected function getScripts() {
	        
	        return $this->scripts;
	        
        }
        
        /**
	     * Before filter method used before every action
	     *
	     * @return void
	     */
        public function beforeFilter(Input $request) {
	        
	        $this->app->call([$this, 'enqueueScripts'], ['request' => $request]);
	        
        }
        
        /**
	     * Enqueue scripts for controller
	     *
	     * @return void
	     */
        public function enqueueScripts(Input $request) {
	        
			foreach($this->getScripts() as $script) {
				
				$script = is_array($script) ? $script : ['file' => $script];
				
				if ( $script['file'] = $this->getScriptPath( ! empty( $script['file'] ) ? $script['file'] : '' ) ) {
						
    				$info = pathinfo( $script['file'] );
    				
    				$extension = ! empty( $info['extension'] ) ? $info['extension'] : ( ! empty( $script['type'] ) ? $script['type'] : false );
    				
    				switch( $extension ) {
						
						case 'css' :
						
							$script = array_merge(array(
								'dependencies' => array(),
								'version' => '1.0.0',
								'media' => 'all',
								'enqueue' => true
							), $script, array(
								'handle' => ! empty( $script['handle'] ) ? $script['handle'] : $info['filename']
							));
							
							if( wp_style_is( $script['handle'], 'registered' ) ) {
    							
    							wp_deregister_style( $script['handle'] );
    							
							}
							
							wp_register_style(
								$script['handle'], 
								$script['file'], 
								$script['dependencies'], 
								$script['version'], 
								$script['media']
							);
							
							if( $script['enqueue'] ) {
								
								wp_enqueue_style($script['handle']);
								
							}
						
						break;
						
						default :
						
							$script = array_merge(array(
								'dependencies' => array(),
								'version' => '1.0.0',
								'in_footer' => true,
								'localize' => false,
								'enqueue' => true
							), $script, array(
								'handle' => ! empty( $script['handle'] ) ? $script['handle'] : $info['filename']
							));
							
							if( wp_script_is( $script['handle'], 'registered' ) ) {
    							
    							wp_deregister_script( $script['handle'] );
    							
							}
							
							wp_register_script(
								$script['handle'], 
								$script['file'], 
								$script['dependencies'], 
								$script['version'], 
								$script['in_footer']
							);
							
							if( $script['localize'] ) {
								
								wp_localize_script($script['handle'], $script['localize']['name'], $script['localize']['data']);
								
							}
							
							if( $script['enqueue'] ) {
							
								wp_enqueue_script($script['handle']);
								
							}
						
						break;
						
					}
    				
                }
				
			}
			
		}
		
		/**
	     * Get script file url
	     *
	     * @return string
	     */
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