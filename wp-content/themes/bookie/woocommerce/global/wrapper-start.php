<?php
/**
 * Content wrappers
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="main-content-container container">

	<?php if( !is_single() && "has_sidebar" == toko_get_option( 'wc_shop_layout' ) ) : ?>
	    <div class="row">
	        <div class="col-md-8" >
    <?php endif; ?>
    
        <div id="content" class="main-content-inner" role="main">