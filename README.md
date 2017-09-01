# wp-kit/invoker

This is a wp-kit component that handles the invoking of controllers and closures based on a condition. 

This component was built to run within an [```Illuminate\Container\Container```](https://github.com/illuminate/container/blob/master/Container.php) so is perfect for frameworks such as [```Themosis```](http://framework.themosis.com/), [```Assely```](https://assely.org/) and [```wp-kit/theme```](https://github.com/wp-kit/theme).

Often, WordPress developers want to group their [actions and filters](https://codex.wordpress.org/Plugin_API) in a more defined context but do not want to use a traditional controller and would rather invoke a controller based on a condition rather than a path. 

Sure, if we are using ```Themosis``` we can use [```Routes```](http://framework.themosis.com/docs/master/routing/), but we cannot pass in closures directly into the ```Route``` condition. With ```wp-kit/invoker```, you can invoke controllers more easily. Examples are below.

Lastly, as expected a [```Controller```](https://github.com/wp-kit/invoker/blob/master/src/Invoker/Controller.php) is invoked once, and once only during the lifecycle of the application regardless of the number of times the condition is met to invoke the ```Controller```.

## Installation

If you're using ```Themosis```, install via [```Composer```](https://getcomposer.org/) in the root fo your ```Themosis``` installtion, otherwise install in your ```Composer``` driven theme folder:

```php
composer require "wp-kit/invoker"
```

## Setup

### Add Service Provider

Just register the service provider and facade in the providers config and theme config:

```php
//inside theme/resources/config/providers.config.php

return [
	WPKit\Invoker\InvokerServiceProvider::class, // make sure it's first
    Theme\Providers\RoutingService::class
];
```

### Add Facade

If you are using Themosis or another ```Iluminate``` driven framework, you may want to add ```Facades```, simply add them to your aliases:

```php
//inside theme/resource/config/theme.config.php

'aliases' => [
    //
    'Invoker' => WPKit\Invoker\Facades\Invoker::class,
    //
]
```

### Add Config File

The recommended method of installing config files for ```wp-kit``` components is via ```wp kit vendor:publish``` command.

First, [install WP CLI](http://wp-cli.org/), and then install this component, ```wp kit vendor:publish``` will automatically be installed with ```wp-kit/utils```, once installed you can run:

```wp kit vendor:publish```

For more information, please visit [```wp-kit/utils```](https://github.com/wp-kit/utils#commands).

Alternatively, you can place the [config file(s)](config) in your ```theme/resources/config``` directory manually.

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

This may see back to front in terms of how [```Router::match```](https://github.com/illuminate/routing/blob/master/Router.php#L255) works however we feel it is more intuitive to lead with the callback when using the Invoker.

### Controllers

```wp-kit/invoker``` comes shipped with a [```Controller```](https://github.com/wp-kit/invoker/blob/master/src/Invoker/Controller.php) that you can extend too to enable you to benefit from the enqueue scripts feature which helps to reduce the amount of code you need to write to output scripts and styles through ```wp_enqueue_scripts```.

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

## Get Involved

To learn more about how to use ```wp-kit``` check out the docs:

[View the Docs](https://github.com/wp-kit/theme/tree/docs/README.md)

Any help is appreciated. The project is open-source and we encourage you to participate. You can contribute to the project in multiple ways by:

- Reporting a bug issue
- Suggesting features
- Sending a pull request with code fix or feature
- Following the project on [GitHub](https://github.com/wp-kit)
- Sharing the project around your community

For details about contributing to the framework, please check the [contribution guide](https://github.com/wp-kit/theme/tree/docs/Contributing.md).

## Requirements

Wordpress 4+

PHP 5.6+

## License

wp-kit/invoker is open-sourced software licensed under the MIT License.
