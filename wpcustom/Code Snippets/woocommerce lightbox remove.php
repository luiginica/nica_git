function your_prefix_setup() {
    remove_theme_support( 'wc-product-gallery-lightbox' );
}

add_action( 'wp', 'your_prefix_setup' );