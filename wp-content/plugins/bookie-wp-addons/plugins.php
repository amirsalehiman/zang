<?php
/*
Plugin Name: Bookie WP Addons
Plugin URI: http://toko.press
Description: Addons for Bookie WP Theme
Version: 1.0
Author: TokoPress
Author URI: http://toko.press/
License: GPL
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! defined( 'BOOKIE_ADDONS_PLUGIN_URI' ) )
	define( "BOOKIE_ADDONS_PLUGIN_URI", plugins_url( '', __FILE__ ) );

if( ! defined( 'BOOKIE_ADDONS_PLUGIN_DIR' ) )
	define( "BOOKIE_ADDONS_PLUGIN_DIR", plugin_dir_path( __FILE__ ) );

/**
 * Load plugin textdomain.
 */
add_action( 'plugins_loaded', 'bookie_addon_load_textdomain' );
function bookie_addon_load_textdomain() {
	load_plugin_textdomain( 'bookie-wp-addons', false, plugin_dir_path( __FILE__ ) . '/languages' ); 
}

require_once( 'author/bookie-author-tax.php' );
require_once( 'featured-product/bookie-vc-featured-product.php' );
require_once( 'custom-search/bookie-vc-custom-search.php' );
require_once( 'featured-book-category/bookie-vc-featured-book-category.php' );
require_once( 'mc4wp-subscribe/bookie-mc4wp-subscribe.php' );