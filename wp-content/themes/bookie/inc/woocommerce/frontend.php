<?php

/* WooCommerce Theme Support */
add_theme_support( 'woocommerce' );

/* Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php) */
add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	$fragments['.site-header .site-quicknav .navbar-right .dropdown .dropdown-toggle .topnav-label'] = '<span class="topnav-label"><span class="amount">'. WC()->cart->cart_contents_count .'</span></span>';
	return $fragments;
}

/* Remove Breadcrumb */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/* Placement Page Title */
add_action( 'woocommerce_before_main_content', 'toko_site_title', 5 );

/* Place Custom Search */
add_action( 'woocommerce_before_main_content', 'toko_custom_book_search', 7 );

/* Shop Page - Products Per Page */
add_filter( 'loop_shop_per_page', 'toko_wc_shop_products_per_page', 20 );
function toko_wc_shop_products_per_page( $number ) {
	$per_page = intval( toko_get_option( 'wc_shop_number' ) );
	if ( $per_page < 1 ) $per_page = 12;
	return $per_page;
}

add_action( 'wp', 'toko_wc_setup_shop_page' );
function toko_wc_setup_shop_page() {

	/* Shop Page - Number of Products Columns Per Row */	
	add_filter( 'loop_shop_columns', 'toko_wc_shop_columns', 20 );
	add_filter( 'body_class', 'toko_wc_body_class_product_columns' );

	/* Shop Page - Wrapper Result Count & Catalog Ordering */
	if( !toko_get_option( 'wc_shop_result_count_disable' ) || !toko_get_option( 'wc_shop_catalog_ordering_disable' ) ) {
		add_action( 'woocommerce_before_shop_loop', 'toko_woo_wrap_result_order_start', 15 );
		add_action( 'woocommerce_before_shop_loop', 'toko_woo_wrap_result_order_end', 35 );
	}

	/* Shop Page - Results Count */
	if( toko_get_option( 'wc_shop_result_count_disable' ) ) {
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	}

	/* Shop Page - Catalog Ordering */
	if( toko_get_option( 'wc_shop_catalog_ordering_disable' ) ) {
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	}

	/* Shop Page - Wrapper Product Item */
	add_action( 'woocommerce_before_shop_loop_item', 'toko_shop_item_wrap_start', 1 );
	add_action( 'woocommerce_after_shop_loop_item', 'toko_shop_item_wrap_end', 999 );

	/* Shop Page - Sale Flash */
	if( toko_get_option( 'wc_shop_saleflash_disable' ) ) {
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	}

	/* Shop Page - Product Thumbnail */
	add_action( 'woocommerce_before_shop_loop_item_title', 'toko_shop_wrap_thumbnail_start', 5 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'toko_shop_wrap_thumbnail_end', 99 );

	/* Shop Page */
	if( toko_get_option( 'wc_shop_title_disable' ) ) {
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	}

	/* Shop Page - Product Price Box */
	if( !toko_get_option( 'wc_shop_price_disable' ) || toko_get_option( 'wc_shop_rating' ) || toko_get_option( 'wc_shop_addtocart' ) ) {
		add_action( 'woocommerce_shop_loop_item_title', 'toko_wrapper_detail_item_start', 5 );
		add_action( 'woocommerce_after_shop_loop_item_title', 'toko_wrapper_detail_item_end', 20 );
	}

	/* Shop Page - Product Price */
	if( toko_get_option( 'wc_shop_price_disable' ) ) {
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	}

	/* Shop Page - Product Rating */
	if( !toko_get_option( 'wc_shop_rating' ) ) {
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	}

	/* Shop Page - Product "Add To Cart" Button */
	add_filter( 'woocommerce_product_add_to_cart_text', 'toko_custom_add_to_cart_text' );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	add_action( 'woocommerce_after_shop_loop_item', 'toko_add_to_cart_wrapper_start', 21 );
	add_action( 'woocommerce_after_shop_loop_item', 'toko_add_to_cart_wrapper_end', 25 );
	add_action( 'woocommerce_after_shop_loop_item', 'toko_add_detail_button', 21 );
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 22 );
	// if( ! toko_get_option( 'wc_shop_addtocart' ) ) {
	// 	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 23 );
	// }

	/* Shop Page - Pagination Style */
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
	add_action( 'woocommerce_after_shop_loop', 'toko_pagination', 10 );

}

function toko_wc_shop_columns( $columns ) {
	$columns = intval( toko_get_option( 'wc_shop_columns' ) );
	if ( $columns < 1 ) $columns = 3;
	if ( $columns > 4 ) $columns = 4;
	return $columns;
}
function toko_wc_body_class_product_columns( $classes ) {
	$columns = 0;
	if ( is_woocommerce() ) {
		if ( is_singular() ) {
			$columns = 4;
		}
		else {
			$columns = apply_filters( 'loop_shop_columns', 3 );
			if ( $columns < 1 ) $columns = 3;
			if ( $columns > 4 ) $columns = 4;
		}
	}
	if ( is_cart() ) {
		if( is_page_template( 'page_fullwidth.php' ) || is_page_template( 'page_fullwidth_notitle.php' ) || is_page_template( 'page_visual_composer.php' ) ) {
			$columns = 4;
		}
		else {
			$columns = 3;
		}
	}
	if ( $columns ) {
		$classes[] = 'columns-' . $columns;
	}
	return $classes;
}
function toko_custom_add_to_cart_text() {
	return esc_html__( 'Buy', 'bookie-wp' );
}
function toko_add_to_cart_wrapper_start() {
	echo '<div class="woo-button-wrapper">';
	echo '<div class="woo-button-border">';
}
function toko_add_to_cart_wrapper_end() {
	echo '</div>';
	echo '</div>';
}
function toko_add_detail_button() {
	echo '<a href="' . get_the_permalink() . '" class="button product-button">' . esc_html__( 'Detail', 'bookie-wp' ) . '</a>';
}
function toko_woo_wrap_result_order_start() {
	echo '<div class="result_order_wrap">';
}
function toko_woo_wrap_result_order_end() {
	echo '</div>';
}
function toko_shop_item_wrap_start() {
	echo '<div class="product-inner">';
}
function toko_shop_item_wrap_end() {
	echo '</div>';
}
function toko_shop_wrap_thumbnail_start() {
	echo '<figure class="product-image-box">';
}
function toko_shop_wrap_thumbnail_end() {
	echo '</figure>';
}
function toko_wrapper_detail_item_start() {
	echo '<div class="product-price-box clearfix">';
}
function toko_wrapper_detail_item_end() {
	echo '</div>';
}

add_action( 'wp', 'toko_wc_setup_product_page' );
function toko_wc_setup_product_page() {

	// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

	/* Single Product - Sale Flash */
	if( toko_get_option( 'wc_product_saleflash_disable' ) ) {
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
	}

	/* Single Product - Rating */
	if( toko_get_option( 'wc_product_rating_disable' ) ){
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	}

	/* Single Product - Price */
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	if( !toko_get_option( 'wc_product_price_disable' ) ) {
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );
	}

	/* Single Product - Short Description */
	if( toko_get_option( 'wc_product_excerpt_disable' ) ){
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	}

	/* Single Product - Add To Cart */
	if( toko_get_option( 'wc_product_addtocart_disable' ) ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	}

	/* Single Product - Meta */
	if( toko_get_option( 'wc_product_meta_disable' ) ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	}

	if( is_product() ) {
		/* Single Product - Upsells */
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		add_action( 'woocommerce_after_main_content', 'woocommerce_upsell_display', 1050 );
		if( toko_get_option( 'wc_product_upsells_disable' ) ) {
			remove_action( 'woocommerce_after_main_content', 'woocommerce_upsell_display', 1050 );
		}

		/* Single Product - Related */
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		add_action( 'woocommerce_after_main_content', 'woocommerce_output_related_products', 1100 );
		if( toko_get_option( 'wc_product_related_disable' ) ) {
			remove_action( 'woocommerce_after_main_content', 'woocommerce_output_related_products', 1100 );
		}
	}

}

/* Single Product - Product Thumbnail Columns */
add_filter( 'woocommerce_product_thumbnails_columns', 'toko_default_product_thumbnail_columns' );
function toko_default_product_thumbnail_columns( $column ) {
	$column = 4;
	return $column;
}

add_filter( 'woocommerce_product_tabs', 'toko_wc_product_tabs', 99 );
function toko_wc_product_tabs( $tabs ) {

	/* Single Product - Tabs - Description */
	if( toko_get_option( 'wc_product_description_disable' ) ) {
		unset( $tabs['description'] );
	}

	/* Single Product - Tabs - Attributes */
	if( toko_get_option( 'wc_product_attributes_disable' ) ) {
		unset( $tabs['additional_information'] );
	}

	/* Single Product - Tabs - Reviews */
	if( toko_get_option( 'wc_product_reviews_disable' ) ) {
	    unset( $tabs['reviews'] );
	}
    return $tabs;
}

/* Single Product - Upsells */
function woocommerce_upsell_display( $posts_per_page = '-1', $columns = 3, $orderby = 'rand' ) {
	// $columns = apply_filters( 'loop_shop_columns', 3 );
	// if ( $columns < 1 ) $columns = 3;
	// if ( $columns > 4 ) $columns = 4;
	$columns = 4;
	wc_get_template( 'single-product/up-sells.php', array(
			'posts_per_page'	=> $posts_per_page,
			'orderby'			=> apply_filters( 'woocommerce_upsells_orderby', $orderby ),
			'columns'			=> $columns
		) );
}

/* Single Product - Related */
add_filter( 'woocommerce_output_related_products_args', 'toko_wc_output_related_products_args' );
function toko_wc_output_related_products_args( $args ) {
	// $columns = apply_filters( 'loop_shop_columns', 3 );
	// if ( $columns < 1 ) $columns = 3;
	// if ( $columns > 4 ) $columns = 4;
	$columns = 4;
	$args = array(
		'posts_per_page' 	=> $columns,
		'columns' 			=> $columns,
		'orderby' 			=> 'rand'
	);
	return $args;
}

add_action( 'wp', 'toko_wc_setup_cart_page' );
function toko_wc_setup_cart_page() {
	if( toko_get_option( 'wc_product_cross_sells_disable' ) ) {
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	}
}

/* Cart Page - Cross Sells */
function woocommerce_cross_sell_display( $posts_per_page = 3, $columns = 3, $orderby = 'rand' ) {
	// $columns = apply_filters( 'loop_shop_columns', 3 );
	// if ( $columns < 1 ) $columns = 3;
	// if ( $columns > 4 ) $columns = 4;
	if( is_page_template( 'page_fullwidth.php' ) || is_page_template( 'page_fullwidth_notitle.php' ) || is_page_template( 'page_visual_composer.php' ) ) {
		$columns = 4;
	}
	else {
		$columns = 3;
	}
	$posts_per_page = $columns;
	wc_get_template( 'cart/cross-sells.php', array(
			'posts_per_page' => $posts_per_page,
			'orderby'        => $orderby,
			'columns'        => $columns
		) );
}

/* My Account - Login Redirect */
add_filter( 'woocommerce_login_redirect', 'toko_wc_login_redirect' );
function toko_wc_login_redirect( $redirect_to ) {
	if( toko_get_option( 'wc_redirect_after_login' ) ) {
	    $redirect_to = esc_url( toko_get_option( 'wc_redirect_after_login' ) );
	}
    return $redirect_to;
}

/**
 * Remove additional information tabs
 */
add_filter( 'woocommerce_product_tabs', 'toko_remove_product_attributes_tab', 98 );
function toko_remove_product_attributes_tab( $tabs ) {
    unset( $tabs['additional_information'] );  	// Remove the additional information tab
    return $tabs;
}

/**
 * Load Book Details section
 */
add_action( 'woocommerce_after_single_product_summary', 'toko_wc_book_details', 5 );
function toko_wc_book_details() {
	get_template_part( 'block-book-details' );
}

/**
 * Remove Product Description Heading
 */
add_filter( 'woocommerce_product_description_heading', 'toko_woocommerce_product_description_heading' );
function toko_woocommerce_product_description_heading( $heading ) {
	return false;
}

/**
 * Add Author Name in Product Loop
 */

add_action( 'woocommerce_shop_loop_item_title', 'toko_wc_author_name', 15 );
function toko_wc_author_name() {
	if( class_exists( 'Bookie_Author_tax' ) ) {
		bookie_addon_get_author_name();
	}
}

/**
 * Filter Breadcrumb Itemprop
 */
add_filter( 'woocommerce_breadcrumb_defaults', 'toko_wc_filter_breadcrumb_markup' );
function toko_wc_filter_breadcrumb_markup( $args ) {
	$args['wrap_before'] = '<nav class="woocommerce-breadcrumb">';

	return $args;
}