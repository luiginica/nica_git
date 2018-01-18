These days, WordPress automatically loads jQuery for you, whenever some other script requires it. The smart loading requires that you specify jquery as the $deps parameter when enqueuing your other JavaScript files, for example:

wp_enqueue_script('my-custom-script', get_template_directory_uri() .'/js/my-custom-script.js', array('jquery'), null, true);
This declares jQuery as a dependency for my-custom-script, so WordPress automatically will load its own copy of jQuery. For reference, here are the parameters used for wp_enqueue_script():

wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer )

So when we declare our custom script, we set the $deps parameter equal to array('jquery') and call it a day.

Now, let's say that we want to use a version of jQuery that is different than the one that is included with WordPress. We could simply enqueue it, but then there would be two copies/versions of jQuery loaded on the page (yours and WP's). So before we enqueue our own version of jQuery, we must de-register the WP version. Here is the final code to make it happen:

// include custom jQuery
function shapeSpace_include_custom_jquery() {

	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);

}
add_action('wp_enqueue_scripts', 'shapeSpace_include_custom_jquery');
Here we use wp_deregister_script() to deregister WP's jQuery before including our own version, which for this example is the Google-hosted jQuery library, version 3.1.1. You will of course want to change the $src parameter to match the URL of whatever jQuery script you want to use.

So that's the current "right way to include jQuery in WordPress". One important note for theme developers: publicly released themes should use WP's jQuery and not de-register it.