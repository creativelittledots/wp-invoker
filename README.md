# wp-kit/invoker

This is a Wordpress PHP Component that handles the invoking of callbacks to closures or controllers based on any condition, hook and closure. 

This PHP Component was built to run within an Illuminate Container so is perfect for frameworks such as ```Themosis```.

Often, Wordpress developers want to group their actions and filters in a more defined context but do not want to use a traditional controller and would rather invoke a controller based on a condition rather than a path. 

Sure, if we are using ```Themosis``` we can use ```Routes```, but we cannot pass in closures directly into the ```Route``` condition. With ```wp-kit/invoker```, you can Invoke controllers more easily. 

```Routes``` also kill the request at the end of the callback, however with the ```wp-kit/invoker``` you are simply attaching a callback or a controller to the process allowing Wordpress to continue it's request into its templating engine.

A controller is invoked once, and once only during the lifecycle of the application regardless of the condition, hook or closure.

## Installation

If you're using ```Themosis```, install via ```Composer``` in the root fo your ```Themosis``` installtion, otherwise install in your ```Composer``` driven theme folder:

```php
composer require "wp-kit/invoker"
```

## Setup

### Add Service Provider

**Within Themosis Theme**

Just register the service provider and facade in the providers config and theme config:

```php
//inside themosis-theme/resources/config/providers.config.php

return [
	WPKit\Invoker\InvokerServiceProvider::class, // make sure it's first
    Theme\Providers\RoutingService::class
];
```

```php
//inside themosis-theme/resource/config/theme.config.php

'aliases' => [
    //
    'Invoker' => WPKit\Invoker\Facades\Invoker::class,
    //
]
```

**Within functions.php**

If you are just using this component standalone then add the following the ```functions.php```

```php
// within functions.php

// make sure composer has been installed
if( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	
	wp_die('Composer has not been installed, try running composer', 'Dependancy Error');
	
}

// Use composer to load the autoloader.
require __DIR__ . '/vendor/autoload.php';

$container = new Illuminate\Container\Container(); // create new app container

$provider = new WPKit\Invoker\InvokerServiceProvider($container); // inject into service provider

$provider->register(); //register service provider
```

### Add Facade (Themosis Only)

```php
//inside themosis-theme/resource/config/theme.config.php

'aliases' => [
    //
    'Invoker' => WPKit\Invoker\Facades\Invoker::class,
    //
]
```

## How To Use

### Invoking

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

Invoker::match( 'SingleProductController@someMethod', 'wp', 'is_product' );

Invoker::match( 'ShopController', 'wp', function() {

	return is_shop() || is_post_type_archive( 'product') || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_tax( 'product_brand' ) || is_tax( 'company_portal' );
	
} );

```

This may see back to front in terms of how Route::match works however we feel it is more intuitive to lead with the callback when using the Invoker.

### Controllers

```wp-kit/invoker``` comes shipped with a ```controller``` that you can extend too to enable you to benefit from the enqueue scripts feature which helps to reduce the amount of code you need to write to output scripts and styles through ```wp_enqueue_scripts```.

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

## Requirements

Wordpress 4+

PHP 5.6+

## License

wp-kit/invoker is open-sourced software licensed under the MIT License.
