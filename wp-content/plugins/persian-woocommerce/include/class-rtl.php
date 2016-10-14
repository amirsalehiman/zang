<?php

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Persian_Wooommerce_RTL' ) ) :

class Persian_Wooommerce_RTL extends Persian_Wooommerce_Plugin {
	
	public function __construct(){
		
		if(!is_admin()) 
			return false;
		
		add_action('admin_enqueue_scripts', array($this, 'pw_rtl_admin_styles'), 11 );
		add_filter('woocommerce_pagination_args', array($this, 'pw_rtl_paginate_links') );
		add_filter('woocommerce_comment_pagination_args', array($this, 'pw_rtl_paginate_comments_links') );	
	}
	
	public function pw_rtl_admin_styles() {
		global $PW;
		
		if ( is_rtl() ) {
		
			// menu css
			wp_dequeue_style( 'woocommerce_admin_menu_styles' );
			wp_enqueue_style( 'woocommerce_admin_menu_styles_rtl', $PW->plugin_url('include/assets/css/menu.rtl.css'), array(), WC_VERSION );
		
			// admin css
			if ( function_exists('wc_get_screen_ids') && in_array( get_current_screen()->id, wc_get_screen_ids() ) ) {
				wp_dequeue_style( 'woocommerce_admin_styles' );
				wp_enqueue_style( 'woocommerce_admin_styles_rtl', $PW->plugin_url('include/assets/css/admin.rtl.css'), array(), WC_VERSION );
			}
		
			// dashboard css
			if ( in_array( get_current_screen()->id, array( 'dashboard' ) ) ) {
				wp_dequeue_style( 'woocommerce_admin_dashboard_styles' );
				wp_enqueue_style( 'woocommerce_admin_dashboard_styles_rtl', $PW->plugin_url('include/assets/css/dashboard.rtl.css'), array(), WC_VERSION );
			}
		
			// reports-print css
			if ( in_array( get_current_screen()->id, array( 'woocommerce_page_wc-reports' ) ) ) {
				wp_dequeue_style( 'woocommerce_admin_dashboard_styles' );
				wp_enqueue_style( 'woocommerce_admin_print_reports_styles_rtl', $PW->plugin_url('include/assets/css/reports-print.rtl.css'), array(), WC_VERSION, 'print' );
			}
			
			//about page	
			if ( isset($_GET['page']) && $_GET['page'] == 'persian-wc-about' ) {
				wp_register_style( 'pw-admin-fonts', $PW->plugin_url('include/assets/css/admin.font.css'), array() , PERSIAN_WOOCOMMERCE_VERSION, false );
				wp_enqueue_style( 'pw-admin-fonts' );	
			}
		
		}
	
	}
	
	public function pw_rtl_paginate_links( $args ) {
		if ( is_rtl() ) {
			$args['prev_text'] = '&rarr;';
			$args['next_text'] = '&larr;';
			return $args;
		}
	}
	
	public function pw_rtl_paginate_comments_links( $args ) {
		if ( is_rtl() ) {
			$args['prev_text'] = '&rarr;';
			$args['next_text'] = '&larr;';
			return $args;
		}
	}
	
}

endif;

$PW->rtl = new Persian_Wooommerce_RTL();

?>