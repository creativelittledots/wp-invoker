<?php
    
    namespace WPKit\Invoker;
    
    use Themosis\Route\BaseController as BaseController;
    use Illuminate\Http\Request;
    use Themosis\Facades\Asset;
    
    class Controller extends BaseController {
	    
	    /**
	     * @var boolean
	     */
	    private $dispatched = false;
        
        /**
	     * @var array
	     */
        protected $scripts = [];
		
		/**
	     * Default controller action should the controller be invoked
	     *
	     * @return void
	     */
		public function dispatch(Request $request) {}
        
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
        public function beforeFilter(Request $request) {
	        
	        app()->call([$this, 'enqueueScripts'], ['request' => $request]);
	        
        }
        
        /**
	     * Enqueue scripts for controller
	     *
	     * @return void
	     */
        public function enqueueScripts(Request $request) {
	        
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
		
		/**
	     * Get script file url
	     *
	     * @return string
	     */
		protected function getScriptPath( $file ) {
    		
    		if( ! filter_var( $file , FILTER_VALIDATE_URL) === false ) {
        		
        		return $file;
        		
            } 
            
            else if( $file = container('asset.finder')->find( $file ) ) {
             
                return $file;
                
            }
            
            return false;
    		
		}
        
    }