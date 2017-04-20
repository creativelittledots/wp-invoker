# WP Invoker

This is a Wordpress PHP Component to help organise actions and filters in a scalable way. Often developers want to group their actions and filters in a more defined context. With WP Invoker you can create a controller, group all of your actions and filters within this contextual controller and invoke the controller at defined points in your application.

WP Invoker is built on top of illuminate/container and illuminate/routing. It is fully compatible with themosis/framework and creativelittledots/wp-kit which is the intended use.

## Installation

Install via composer, always.

```php
composer require "creativelittledots/wp-invoker"
```

## How to Use

WP Invoker is pretty flexible, you can use Facades as provided by illuminate/support. You can reference a controller, the controller must correspond to the exact name-spaced path using prs-4 methodology.

```php

use WPInvoker\Facades\Invoker;

// as php function as below

// $callback 	( string / array / callable )
// $hook 		( string )
// $condition 	( string / callable )
// $priority 	( int )
// invoke( $callback, $hook, $condition, $priority );

invoke( 'AppController' );

invoke( 'ProductController@someMethod' );

invoke( function() {

	add_filter( 'pre_get_posts', function($query) {
		
		$query->posts_per_page = 10;
		
	});

}, 'wp', 'is_front_page', 80 );

invoke( 'App\Controllers\SingleProductController', 'wp', 'is_product' );

invoke( \App\Controllers\CartController::class, 'wp', 'is_cart' );

invoke( 'ShopController', 'wp', function() {

	return is_shop() || is_post_type_archive( 'product') || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_tax( 'product_brand' ) || is_tax( 'company_portal' );
	
} );

// using facade

Invoker::invoke( 'AppController' );

Invoker::invoke( 'SingleProductController@someMethod', 'wp', 'is_product' );

Invoker::invoke( 'ShopController', 'wp', function() {

	return is_shop() || is_post_type_archive( 'product') || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_tax( 'product_brand' ) || is_tax( 'company_portal' );
	
} );

```

WP Invoker comes shipped with a controller that you can extend too to enable you to benefit from the enqueue scripts feature which helps to reduce the amount of code you need to write to output scripts and styles through wp_enqueue_scripts.

```php

namespace App\Controllers;

use WPInvoker\Controller;

class FrontPageController extends Controller {
	
	var $scripts = [
    	'vendor/modernizr.min.js',
    	'vendor/foundation.min.js',
    	'vendor/autocomplete.min.js',
    	'app' => [
    	    'file' => 'app.min.js'
        ],
    	'framework/foundation.min.css',
    	'style.css',
	];
	
	public function getScripts() {
    	
    	wp_deregister_script('jquery-serialize-object');
    	
    	$this->scripts['app']['localize'] = [
            'name' => 'myAjax',
            'data' => [ 
                'ajax_url' => admin_url( 'admin-ajax.php' )
            ]
        ];
        
        return parent::getScripts();
		
	}
	
}

```

To set the asset directories that scripts and styles are loaded from just set this constant in functions.php

```php

define( 'ASSET_DIRS', implode(',', [
    'styles',
    'scripts',
    'images'
] ) );
	    
```

## Requirements

Wordpress 4+

PHP 5.6+

## License

WP Kit Core is open-sourced software licensed under the MIT License.