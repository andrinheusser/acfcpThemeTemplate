<?php

require_once(WP_PLUGIN_DIR.'/advanced-custom-fields-pro/acf.php');

define('ACF_LITE', true);
add_filter('acf/settings/remove_wp_meta_box', '__return_true');

//  Stop WordPress from using the sticky class (which conflicts with Foundation), and style WordPress sticky posts using the .wp-sticky class instead
function remove_sticky_class($classes) {
	$classes = array_diff($classes, array("sticky"));
	$classes[] = 'wp-sticky';
	return $classes;
}
add_filter('post_class','remove_sticky_class');

add_theme_support('menus');

function my_function_admin_bar(){ return false; }
add_filter( 'show_admin_bar' , 'my_function_admin_bar');


function head_cleanup() {
	// Remove category feeds
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	// Remove post and comment feeds
	remove_action( 'wp_head', 'feed_links', 2 );
	// Remove EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// Remove Windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// Remove index link
	remove_action( 'wp_head', 'index_rel_link' );
	// Remove previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// Remove start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// Remove links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// Remove WP version
	remove_action( 'wp_head', 'wp_generator' );
}
add_action('init', 'head_cleanup');

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

add_action( 'init', function() {

    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');

    // Turn off oEmbed auto discovery.
    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
}, PHP_INT_MAX - 1 );

function custom_admin_footer() {
	_e('<span id="footer-thankyou"></span>.', 'jointswp');
}

// adding it to the admin area
add_filter('admin_footer_text', 'custom_admin_footer');

function site_scripts() {

    // Webpack bundle
    wp_enqueue_script( 'webpack', get_template_directory_uri() . '/dist/js/app.js', array(), '', true );

    // Register main stylesheet
    wp_enqueue_style( 'site-css', get_template_directory_uri() . '/dist/css/style.css', array(), '', 'all' );


}
add_action('wp_enqueue_scripts', 'site_scripts', 999);
