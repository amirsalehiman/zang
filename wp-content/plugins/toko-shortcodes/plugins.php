<?php
/*
Plugin Name: TokoPress Shortcodes & Visual Composer
Plugin URI: http://toko.press
Description: TokoPress Shortcodes & Visual Composer Addons
Version: 1.2
Author: TokoPress
Author URI: http://toko.press/
License: GPL
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! defined( 'TOKO_SHORTCODE_PLUGIN_URI' ) )
	define( "TOKO_SHORTCODE_PLUGIN_URI", plugins_url( '', __FILE__ ) );

if( ! defined( 'TOKO_SHORTCODE_PLUGIN_DIR' ) )
	define( "TOKO_SHORTCODE_PLUGIN_DIR", plugin_dir_path( __FILE__ ) );

/**
 * Load plugin textdomain.
 */
add_action( 'plugins_loaded', 'toko_shortcode_load_textdomain' );
function toko_shortcode_load_textdomain() {
	load_plugin_textdomain( 'tp-shortcodes', false, plugin_dir_path( __FILE__ ) . '/languages' ); 
}

require_once( 'vc-elements/vc-banners.php' );
require_once( 'vc-elements/vc-call-to-action.php' );
require_once( 'vc-elements/vc-divider.php' );
require_once( 'vc-elements/vc-brands.php' );
require_once( 'vc-elements/vc-posts.php' );

require_once( 'vc-elements/vc-wc-products.php' );
require_once( 'vc-elements/vc-wc-product-categories.php' );

require_once( 'vc-elements/vc-mailchimp.php' );

add_action( 'wp_enqueue_scripts', 'toko_shortcodes_enqueue_register' );
function toko_shortcodes_enqueue_register() {
	wp_register_script( 'toko-owlcarousel', trailingslashit( TOKO_SHORTCODE_PLUGIN_URI ) . 'vendors/owl-carousel/owl.carousel.min.js', array( 'jquery' ), '2', true );
	wp_register_style( 'toko-owlcarousel', trailingslashit( TOKO_SHORTCODE_PLUGIN_URI ) . 'vendors/owl-carousel/owl.carousel.css' );
}

function toko_vc_icon( $icon ) {
	if ( false !== strpos($icon,'fa-') && false === strpos($icon,'fa fa-') ) {
		$icon = str_replace('fa-', 'fa fa-',$icon);
	}
	elseif ( false !== strpos($icon,'sli-') && false === strpos($icon,'sli sli-') ) {
		$icon = str_replace('sli-', 'sli sli-',$icon);
	}
	elseif ( false !== strpos($icon,'icon-') ) {
		$icon = str_replace('icon-', 'sli sli-',$icon);
	}
	return $icon;
}

function toko_vc_getCategoryChilds( $param = 'slug', $parent_id, $pos, $array, $level, &$dropdown ) {
	for ( $i = $pos; $i < count( $array ); $i ++ ) {
		if ( $array[ $i ]->category_parent == $parent_id ) {
			if ( $param == 'slug' ) {
				$data = array(
						str_repeat( "- ", $level ) . $array[ $i ]->name => $array[ $i ]->slug,
				);
			}
			else {
				$data = array(
						str_repeat( "- ", $level ) . $array[ $i ]->name => $array[ $i ]->term_id,
				);
			}
			$dropdown = array_merge( $dropdown, $data );
			toko_vc_getCategoryChilds( $param, $array[ $i ]->term_id, $i, $array, $level + 1, $dropdown );
		}
	}
}
