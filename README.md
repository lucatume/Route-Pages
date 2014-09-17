#Route Pages

A plugin based on [WP Router](https://wordpress.org/plugins/wp-router/) allowing Laravel-like routing in WordPress and route persistence via post objects.

## Installation
The plugin is not available in the plugin repository (yet) and requires manual installation. Using the command line navigate to your WordPress plugins folder, `/wp-content/plugins` by default, and then clone the plugin there.

    git clone https://github.com/lucatume/Route-Pages.git

The plugin requires [WP Router](https://wordpress.org/plugins/wp-router/) to be installed and activated and will allow for that upon activation.

## Usage

### Requirements
The plugin requires permalinks in the `%postname%` format to be set to work.

### Setting routes
The plugin packs the [wp-routing](https://github.com/lucatume/wp-routing) package at its core allowing for easy route generation. The classes will take care of hooking to the proper filter and will not require the user to do so; using WP Router own example the plugin will allow going from this:

    // file my-routes-plugin.php

    add_action('wp_router_generate_routes', 'generateMyRoutes');

    function generateMyRoutes(WP_Router $router)
    {
        $router->add_route('wp-router-sample', array(
            'path' => '^wp_router/(.*?)$',
            'query_vars' => array(
                'sample_argument' => 1,
            ),
            'page_callback' => array(get_class(), 'sample_callback'),
            'page_arguments' => array('sample_argument'),
            'access_callback' => TRUE,
            'title' => 'WP Router Sample Page',
            'template' => array('sample-page.php', dirname(__FILE__).DIRECTORY_SEPARATOR.'sample-page.php')
        ));
    }

to this

    // file my-routes-plugin.php

    WPRouting_Route::get('wp_router/{word}', function($word){
            echo "Hello $word";
        })->where('word', '.*?')
          ->withTitle('Wp Router Sample Page')
          ->withTemplate(array(
            'sample-page',
            dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sample-page.php'
            );

No hooking and a fluent syntax. See [wp-routing](https://github.com/lucatume/wp-routing) package documentation for details about the `WPRouting_PersistableRoute` class.

### Generating route pages
Based on the `WPRouting_PersistableRoute`, an extension of the `WPRouting_Route` class, the plugin will allow developers to set which routes should generate a route page calling the class and using the `shouldBePersisted` method in the fluent chain

    // file my-routes-plugin.php

    WPRouting_PersistableRoute::get('hello', function(){
            echo "Hello there";
        })->withTitle('WP Router Sample Page')
          ->withTemplate(array(
            'sample-page',
            dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sample-page.php'
            )
          ->shouldBePersisted();

Route page generation will refresh each time the plugins are loaded and will usually result in a check and nothing more if route information has not changed. To have said page generation happening the plugin should hook in the `RoutePages::SHOULD_GENERATE_ROUTE_PAGES` filter and return a *truthy* value like.

    add_filter(RoutePages::SHOULD_GENERATE_ROUTE_POSTS, '__return_true');

In the example above a "WP Router Sample Page" will appear in the page administration screen with a `/hello` permalink.

>**Note**: route pages (or posts) will not be generated for any route with a path containing vars. A path like `hello` will generate a route post as will do `hello/world`; a path like `hello/{name}` will not generate any route post.
