# WP Invoker

This is a Wordpress Themosis PHP Component to help organise actions and filters in a scalable way. 

Often, Themosis developers want to group their actions and filters in a more defined context but do not to use a traditional Controller, they would rather invoke a Controller based on a condition rather than a path. 

Sure we can use Themosis Routes, but we cannot pass in Closures directly into the Route definition. With WP Invoker you can Invoke Controllers more easily.

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

WP Invoker is pretty flexible, you can use Facades as provided by Themosis\Facades. You can reference a Controller, the Controller must correspond to the exact name-spaced path using prs-4 methodology.

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

Invoker::invoke( 'SingleProductController@someMethod', 'wp', 'is_product' );

Invoker::invoke( 'ShopController', 'wp', function() {

	return is_shop() || is_post_type_archive( 'product') || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_tax( 'product_brand' ) || is_tax( 'company_portal' );
	
} );

```

## Controllers

WP Invoker comes shipped with a controller that you can extend too to enable you to benefit from the enqueue scripts feature which helps to reduce the amount of code you need to write to output scripts and styles through wp_enqueue_scripts.

```php

namespace App\Controllers;

use WPKit\Invoker\Controller;

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