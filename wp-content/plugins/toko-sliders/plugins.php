<?php
/*
Plugin Name: TokoPress Slider
Plugin URI: http://toko.press
Description: TokoPress Slider Addons
Version: 1.2
Author: TokoPress
Author URI: http://toko.press/
License: GPL
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! defined( "TOKO_SLIDERS_PLUGIN_URI" ) )
	define( "TOKO_SLIDERS_PLUGIN_URI", plugins_url( '', __FILE__ ) );

if( ! defined( "TOKO_SLIDERS_PLUGIN_DIR" ) )
	define( "TOKO_SLIDERS_PLUGIN_DIR", plugin_dir_path( __FILE__ ) );

/**
 * Load plugin textdomain.
 */
add_action( 'plugins_loaded', 'toko_sliders_load_textdomain' );
function toko_sliders_load_textdomain() {
	load_plugin_textdomain( 'tp-slider', false, TOKO_SLIDERS_PLUGIN_DIR . '/languages' ); 
}

add_action( 'wp_enqueue_scripts', 'toko_slider_enqueue_register' );
function toko_slider_enqueue_register() {
	wp_register_script( 'toko-owlcarousel', trailingslashit( TOKO_SLIDERS_PLUGIN_URI ) . 'vendors/owl-carousel/owl.carousel.min.js', array( 'jquery' ), '2', true );
	wp_register_style( 'toko-owlcarousel', trailingslashit( TOKO_SLIDERS_PLUGIN_URI ) . 'vendors/owl-carousel/owl.carousel.css' );
}

require_once( 'Custom-Meta-Boxes/custom-meta-boxes.php' );
require_once( 'slider/slider.php' );
