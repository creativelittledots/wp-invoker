# WPKit Invoker

This is a Wordpress Themosis PHP Component to invoke Controllers based on any condition, hook and closure.

Often, Themosis developers want to group their actions and filters in a more defined context but do not to use a traditional Controller, they would rather invoke a Controller based on a condition rather than a path. 

Sure we can use Themosis Routes, but we cannot pass in Closures directly into the Route definition. With WPKit Invoker you can Invoke Controllers more easily.

Controllers are invoked once, and once only during the lifecycle of the application regardless of the condition, hook or closure.

## Installation

Install via composer from the Themosis route folder:

```php
composer require "creativelittledots/wp-invoker"
```

## Registering Service Provider

Just add the following line of code somewhere in your environment after Themosis has run. For example you could place this in functions.php of your theme.

```php
app()->register(WPKit\Invoker\InvokerServiceProvider::class);
```

## Invoking

WPKit Invoker is pretty flexible, you can use Facades as provided by Themosis\Facades. You can reference a Controller, the Controller must correspond to the exact name-spaced path using prs-4 methodology.

```php

use WPKit\Invoker\Facades\Invoker;

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

Invoker::match( 'SingleProductController@someMethod', 'wp', 'is_product' );

Invoker::match( 'ShopController', 'wp', function() {

	return is_shop() || is_post_type_archive( 'product') || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_tax( 'product_brand' ) || is_tax( 'company_portal' );
	
} );

```

## Controllers

WPKit Invoker comes shipped with a controller that you can extend too to enable you to benefit from the enqueue scripts feature which helps to reduce the amount of code you need to write to output scripts and styles through wp_enqueue_scripts.

```php

namespace App\Controllers;

use WPKit\Invoker\Controller;

class FrontPageController extends Controller {
	
	var $scripts = [
    	'scripts/vendor/modernizr.min.js',
    	'scripts/vendor/foundation.min.js',
    	'scripts/vendor/autocomplete.min.js',
    	'app' => [
    	    'file' => 'scripts/app.min.js'
        ],
    	'scripts/framework/foundation.min.css',
    	'styles/style.css',
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

To register asset locations that scripts and styles are loaded from add paths to the asset.finder library;

```php

container('asset.finder')->addPaths([
    get_stylesheet_directory_uri() . '/some/folder/' => get_stylesheet_directory() . '/some/folder/'
]);
	    
```

## Requirements

Wordpress 4+

PHP 5.6+

## License

WPKit Invoker is open-sourced software licensed under the MIT License.