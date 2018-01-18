Better WordPress performance 

In this tutorial we will learn how WordPress loads the scripts and how can we control which script or style will be loaded. We will also see how to block loading of scripts from certain plugins.


Many plugins load their own styles and scripts which can lead to a bloated WordPress site with assets that are not needed on a page. For example, let’s assume that we use WooCommerce or SiteOrigin’s Page Builder. In our example, we do not use any of WooCommerce widgets or the page builder on our home page. We should be able to remove those scripts or styles since those HTTP requests can be saved and rendering of our entire home page improved.

Enqueueing Scripts
When coding your own plugin or theme, you should always enqueue your scripts and styles and not hard code them into your templates. This enables us, other developers, to control the loading of the scripts and styles and even disable their loading on pages where we do not need them.

You can do that just by using simple code snippets like this one:

<?php
// Enqueue Theme scripts and Styles
add_action( 'wp_enqueue_scripts', 'my_theme_scripts' );
function my_theme_scripts() {
  // Add Style
	wp_enqueue_style( 'my-theme-stylesheet', get_stylesheet_uri(), false );
	
	// Add Script that depends on jQuery, is version 1.0 and will render in footer
	wp_enqueue_script( 'my-theme-js', get_stylesheet_directory_uri() . '/js/my-theme.js', array( 'jquery' ), '1.0', true );
	
}
// Enqueue Plugin scripts and Styles
add_action( 'wp_enqueue_scripts', 'my_plugin_scripts' );
// Define constant on the main plugin file so that we can reference anything from our plugin
define( 'MY_PLUGIN_URI', plugin_dir_url( __FILE __) );
function my_plugin_scripts() {
  // Add Style - http://my-wp-site.com/wp-content/plugins/my-plugin/css/my-plugin.css
	wp_enqueue_style( 'my-plugin-stylesheet', MY_PLUGIN_URI . 'css/my-plugin.css', false );
	
	// Add Script that depends on jQuery, is version 1.0 and will render in footer
	// - http://my-wp-site.com/wp-content/plugins/my-plugin/js/my-plugin.js
	wp_enqueue_script( 'my-plugin-js', MY_PLUGIN_URI . '/js/my-plugin.js', array( 'jquery' ), '1.0', true );
	
}
view rawenqueue_example.php hosted with ❤ by GitHub
Can you see what is consistent with both theme and plugin enqueueing? We are using the same action hook wp_enqueue_scripts.

By enqueueing scripts into footer we can improve the WordPress performance by a little. That is great, but when we do not need that script, it only hurts our performance.

Inspecting Action wp_enqueue_scripts
All actions and functions that are hooked into those actions are saved into a global variable $wp_filters. Since this global variable holds many other actions and filters, we will only need the functions registered for action wp_enqueue_scripts.

To inspect this action, we can use this code snippet just for developing and inspecting purposes. Do not use it in production because it will stop your site.

<?php
add_action( 'init', 'ibenic_show_scripts');
function ibenic_show_scripts(){
	global $wp_filter;
	if( is_admin() ){
		return;
	}
	print_r( $wp_filter['wp_enqueue_scripts']);
	die();
}
view rawinspecting.php hosted with ❤ by GitHub
You can add that to your theme’s functions.php file or into a plugin if you want. As this is only for inspecting purposes and to see which scripts are hooked, this code snippet should be removed before going live.

My active theme is Twenty Sixteen and plugins that are installed and activated are WooCommerce and SiteOrigin’s Page Builder. If I load my site’s front, I will get these:

<?php
Array
(
  [5] => Array
    (
      [siteorigin_panels_default_styles_register_scripts] => Array
        (
          [function] => siteorigin_panels_default_styles_register_scripts
          [accepted_args] => 1
        )
    )
  [1] => Array
    (
      [siteorigin_panels_enqueue_styles] => Array
        (
          [function] => siteorigin_panels_enqueue_styles
          [accepted_args] => 1
        )
    )
  [10] => Array
    (
      [siteorigin_panels_live_edit_link_style] => Array
        (
          [function] => siteorigin_panels_live_edit_link_style
          [accepted_args] => 1
        )
      [WC_Frontend_Scripts::load_scripts] => Array
        (
          [function] => Array
          (
            [0] => WC_Frontend_Scripts
            [1] => load_scripts
          )
          [accepted_args] => 1
        )
      [twentysixteen_scripts] => Array
        (
          [function] => twentysixteen_scripts
          [accepted_args] => 1
        )
      [twentysixteen_color_scheme_css] => Array
        (
          [function] => twentysixteen_color_scheme_css
          [accepted_args] => 1
        )
)
[11] => Array
    (
      [twentysixteen_page_background_color_css] => Array
        (
         [function] => twentysixteen_page_background_color_css
          [accepted_args] => 1
        )
      [twentysixteen_link_color_css] => Array
        (
          [function] => twentysixteen_link_color_css
          [accepted_args] => 1
        )
      [twentysixteen_main_text_color_css] => Array
        (
          [function] => twentysixteen_main_text_color_css
          [accepted_args] => 1
        )
      [twentysixteen_secondary_text_color_css] => Array
        (
           [function] => twentysixteen_secondary_text_color_css
           [accepted_args] => 1
        )
    )
)
view rawregistered_hooks.php hosted with ❤ by GitHub
This is an array that shows all the functions added to the action wp_enqueue_scripts. Those functions are the ones that will enqueue scripts and styles.

We can remove those actions as we want to be sure that any script or style from that function will not be enqueued.

Remove Hooked Functions to improve WordPress Performance
To know which function to remove we would need to know the name of that function. To remove a hooked function we can use the function remove_action. For this to work, we need to pass the name of the action, the name of the function and also the priority.

If we know all of them, then it is an easy task to do that. To remove the SiteOrigin styles we can just call the remove_action like this:

<?php
add_action( 'init', 'my_theme_remove_siteorigin', 99);
function my_theme_remove_siteorigin(){
  remove_action( 'wp_enqueue_scripts', 'siteorigin_panels_live_edit_link_style', 10 );
  remove_action( 'wp_enqueue_scripts', 'siteorigin_panels_default_styles_register_scripts', 5 );
  remove_action( 'wp_enqueue_scripts', 'siteorigin_panels_enqueue_styles', 1 );
}
view rawremove_action.php hosted with ❤ by GitHub
We hooked our function on the action init with the priority of 99. By using such high priority, we can be sure that our hooked function will be one of the last ones to execute.

Ok, now we are aware of the hooked functions and we also know how to remove those functions that will add styles and scripts. What about blocking specific scripts and styles?

Find out All Queued Scripts and Style for Rendering
To block specific scripts and styles, we need to know each of them. For this we can’t do the same as before with the hooked functions. This must be done after all custom scripts and styles were already registered and queued for rendering.

The actions where all those assets are enqueued is the wp_enqueue_scripts or for the admin area the admin_enqueue_scripts. In those actions we can also use conditional functions such as is_home() or is_page().

When registering and enqueueing our styles or scripts, we add a handle for each of them. We only need to know about the ones that are also queued because only the queued assets will be rendered. By removing unnecessary scripts or styles we will achieve a better WordPress performance.

Queued Script Handles
Script handles that are in the queue for rendering can be found like this:

<?php
add_action( 'wp_enqueue_scripts', 'ibenic_show_scripts_handles', 99);
function ibenic_show_scripts_handles(){
  
  // Show only in front
  if( is_admin() ){
	return;
   }
  // Get WP_Scripts Object
  $wp_scripts = wp_scripts();
  
  // Print Queued Handles
  print_r( $wp_scripts->queue );
	
  die();
}
view rawscript_handles.php hosted with ❤ by GitHub
This will print our script handles that are hooked before rendering. Since we are using the priority of 99, we are sure that this is the last function that will be executed. Due to that, we will see every single script handle that is queued.

Don’t use it in the production since it will print your handles, break the layout and even stop the site from working because the die(); function stops everything else after the execution.

Here is the list of all the script handles that are queued for my site:

Array
(
    [0] => admin-bar
    [1] => wc-add-to-cart
    [2] => woocommerce
    [3] => wc-cart-fragments
    [4] => twentysixteen-html5
    [5] => twentysixteen-skip-link-focus-fix
    [6] => twentysixteen-script
)
view rawqueued_script_handles.txt hosted with ❤ by GitHub
Queued Style Handles
We can found out about the queued style handles in a similar way as we did for scripts:

<?php
add_action( 'wp_enqueue_scripts', 'ibenic_show_styles_handles', 99);
function ibenic_show_styles_handles(){
  
  // Show only in front
  if( is_admin() ){
	return;
   }
  // Get WP_Scripts Object
  $wp_styles = wp_styles();
  
  // Print Queued Handles
  print_r( $wp_styles->queue );
	
  die();
}
view rawstyle_handles.php hosted with ❤ by GitHub
This will print our style handles that are hooked before rendering.

Don’t use it in the production since it will print your handles, break the layout and even stop the site from working because the die(); function stops everything else after the execution.

Here is the list of the queued script handles that I have for my site:

Array
(
    [0] => admin-bar
    [1] => woocommerce-layout
    [2] => woocommerce-smallscreen
    [3] => woocommerce-general
    [4] => twentysixteen-fonts
    [5] => genericons
    [6] => twentysixteen-style
    [7] => twentysixteen-ie
    [8] => twentysixteen-ie8
    [9] => twentysixteen-ie7
)
view rawqueued_style_handles.txt hosted with ❤ by GitHub
We would have also SiteOrigin’s scripts and styles if we did not remove the appropriate hooked functions previously.

Removing Scripts and Styles from Queue for Better WordPress Performance
Now we know that to have all those styles and scripts enqueued, we have to hook a function with the highest priority number to get executed last. In that function we can then securely perform various actions on those handles.

Let’s remove scripts and styles for the WooCommerce if we are on the front page:

<?php
add_action( 'wp_enqueue_scripts', 'ibenic_remove_woocoomerce_in_frontpage', 97 );
function ibenic_remove_woocoomerce_in_frontpage(){
	// Return if it is not the front page
	if( ! is_front_page() ){
		return;
	}
	// Remove Scripts
	wp_dequeue_script( 'wc-add-to-cart' );
	wp_dequeue_script( 'woocommerce' );
	wp_dequeue_script( 'wc-cart-fragments' );
	// Remove Styles
	wp_dequeue_style( 'woocommerce-layout' );
	wp_dequeue_style( 'woocommerce-smallscreen' );
	wp_dequeue_style( 'woocommerce-general' );
}
view rawremove_woocommerce.php hosted with ❤ by GitHub
We have first checked if we are on the front page. Once we are sure that we are only on the front page we are dequeueing scripts and styles that were queued. Now we only have these script and style handles that will be added:

Scripts to Load
Array
(
    [0] => admin-bar
    [4] => twentysixteen-html5
    [5] => twentysixteen-skip-link-focus-fix
    [6] => twentysixteen-script
)

Styles to Load
Array
(
    [0] => admin-bar
    [4] => twentysixteen-fonts
    [5] => genericons
    [6] => twentysixteen-style
    [7] => twentysixteen-ie
    [8] => twentysixteen-ie8
    [9] => twentysixteen-ie7
)
view rawremoved_scripts_styles.txt hosted with ❤ by GitHub
We have achieved a better WordPress performance by removing unnecessary scripts and styles thus saving about 6 HTTP requests.

How to Smart Enqueue your Plugin’s style or script in the Admin Area
When you write your own plugin, probably there will be some scripts or styles that will have to be included in the admin area for everything to work. When using the action admin_enqueue_scripts you can also get the parameter that is passed to that action. The parameter is $hook and it holds the page on which we are.

By using that hook and some $_GET parameters you can be sure to enqueue your plugin’s scripts and styles on the right page. For the example, we will assume that we have a post_type books. For every book, let’s say that we can enter a datepicker for publishing date and some custom JavaScript that will be held in a file books.js.

We will need to enqueue the datepicker script that comes included into WordPress and also a simple script from our plugin. To be sure that we are on the right page we will look info the parameter $hook. We will need to be on the edit post page or on the new post page.

Here is the code snippet that will do that:

<?php
add_action( 'admin_enqueue_scripts', 'books_enqueue_scripts' );
function books_enqueue_scripts( $hook ){
  $hook_scripts = false;
  if( $hook_suffix == "post-new.php" && isset( $_GET["post_type"] ) && $_GET["post_type"] == "books" ){
		$hook_scripts = true;
	}
	if( $hook_suffix == "post.php" && isset( $_GET["post"] ) && get_post_type( $_GET["post"] ) == "books" ) {
		$hook_scripts = true;
	}
	if( $hook_scripts ){ 
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'gasap-admin-js',  plugins_dir_url( __FILE__ ) . '/admin/assets/js/books.js', array('jquery') );
	}
}
view rawbook_admin_enqueue.php hosted with ❤ by GitHub
