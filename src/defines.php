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
	
?>