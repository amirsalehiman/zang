<?php
/*
Contributors: Persianscript, hannanstd, mahdiy
Plugin Name: ووکامرس پارسی
Plugin URI: http://woocommerce.ir
Description: بسته فارسی ساز ووکامرس پارسی به راحتی سیستم فروشگاه ساز ووکامرس را فارسی می کند. با فعال سازی افزونه ، واحد پولی ریال و تومان ایران و همچنین لیست استان های ایران به افزونه افزوده می شوند. پشتیبانی در <a href="http://www.woocommerce.ir/" target="_blank">ووکامرس پارسی</a>.
Version: 2.5.2
Requires at least: 4.0
Author: ووکامرس فارسی
Author URI: http://woocommerce.ir
*/

	if ( ! defined( 'ABSPATH' ) )
		exit; // Exit if accessed directly

	if ( !defined('PERSIAN_WOOCOMMERCE_VERSION') )
		define('PERSIAN_WOOCOMMERCE_VERSION', '2.5.1');
	
	require_once( dirname(__FILE__) . '/include/persian-woocommerce.php');
		
	global $PW;
	$PW = new Persian_Wooommerce_Plugin( __FILE__ );

	require_once( dirname(__FILE__) . '/include/class-tools.php');
	require_once( dirname(__FILE__) . '/include/class-address.php');
	require_once( dirname(__FILE__) . '/include/class-currency.php');
	require_once( dirname(__FILE__) . '/include/class-rtl.php');
	require_once( dirname(__FILE__) . '/include/class-widget.php');
	
	register_activation_hook( __FILE__ , array('Persian_Wooommerce_Plugin', 'persian_wc_install') );
	
?>