WordPress move jquery to footer to resolve for Render Blocking
October 3, 2017James Mascarenhas
If facing render blocking due to WordPress default jquery wp-includes/js/jquery/jquery.js you need to move the script to footer and to do that add the following codes in the themes functions.php file.

Code for WordPress  to move jquery to footer

function starter_scripts() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', includes_url( '/js/jquery/jquery.js' ), false, NULL, true );
    wp_enqueue_script( 'jquery' );

    wp_enqueue_style( 'starter-style', get_stylesheet_uri() );
    wp_enqueue_script( 'includes', get_template_directory_uri() . '/js/min/includes.min.js', '', '', true );
}
add_action( 'wp_enqueue_scripts', 'starter_scripts' );
 

Now you jquery script in WordPress blog will be in footer and hopelly will resolve the render blocking issues in Google pagespeed insights.

https://pagespeedoptimization.wordpress.com/2017/10/03/wordpress-move-jquery-to-footer-to-resolve-for-render-blocking/