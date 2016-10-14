<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_shortcode( 'bookie-custom-search', 'bookie_custom_search_shortcode' );
function bookie_custom_search_shortcode( $atts ) {

	ob_start();
	get_template_part( 'block-book-search' );
	return ob_get_clean();

}

add_action( 'vc_before_init', 'bookie_vc_custom_search' );
function bookie_vc_custom_search() {

	vc_map( array(
	   'name'				=> __( 'TP - Custom Search', 'bookie-wp-addons' ),
	   'base'				=> 'bookie-custom-search',
	   'class'				=> '',
	   'icon'				=> 'bookie_icon',
	   'category'			=> 'TokoPress',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'show_settings_on_create' => false
	   )
	);
}