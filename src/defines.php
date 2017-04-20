<?php
	
	if ( ! defined('DS') ) {
		
	    define( 'DS', DIRECTORY_SEPARATOR );
	    
	}
	
	if( ! defined( 'THEME_DIR' ) ) {
	
		define( 'THEME_DIR', get_stylesheet_directory() );
		
	}
	
	if( ! defined( 'THEME_URI' ) ) {
    
    	define( 'THEME_URI', get_stylesheet_directory_uri() );	
    	
    }
    
    if( ! defined( 'ASSET_DIRS' ) ) {
		
		define( 'ASSET_DIRS', implode(',', [
	        'styles',
	        'scripts',
	        'images'
	    ] ) );
		
	}
	
?>