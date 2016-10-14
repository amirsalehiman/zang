<?php
/**
 * WooCommerce Shortcode
 *
 * @package Shortcode
 * @author Toko
 */

/**
 * Products Shortcode
 */
add_shortcode( 'toko_wc_products', 'toko_shortcode_wc_products' );
function toko_shortcode_wc_products( $atts ) {

	if ( ! class_exists( 'woocommerce' ) )
		return;

	global $woocommerce_loop;

	extract( shortcode_atts( array(
		'show'				=> 'all',
		'category' 			=> '',
		'numbers' 			=> '6',
		'columns' 			=> '3',
		'orderby' 			=> 'date',
		'order' 			=> 'desc',
		'carousel'			=> 'no',
		'paginate'			=> 'false',
		'hide_free'			=> 'no',
		'show_hidden'		=> 'no',
	), $atts ) );

	if ( $show == 'incategory' && !$category )
		return;

	$query_args = array(
		'posts_per_page' => $numbers,
		'post_status'    => 'publish',
		'post_type'      => 'product',
		'no_found_rows'  => 1,
		// 'order'          => $order,
		'meta_query'     => array()
	);

	if ( $show_hidden == 'yes' ) {
		$query_args['meta_query'][] = WC()->query->visibility_meta_query();
		$query_args['post_parent']  = 0;
	}

	if ( $hide_free == 'yes' ) {
		$query_args['meta_query'][] = array(
			'key'     => '_price',
			'value'   => 0,
			'compare' => '>',
			'type'    => 'DECIMAL',
		);
	}

	if ( $show != 'toprated' ) {
		switch ( $orderby ) {
			case 'price' :
				$query_args['meta_key'] = '_price';
				$query_args['orderby']  = 'meta_value_num';
				break;
			case 'rand' :
				$query_args['orderby']  = 'rand';
				break;
			case 'sales' :
				$query_args['meta_key'] = 'total_sales';
				$query_args['orderby']  = 'meta_value_num';
				break;
			default :
				$query_args['orderby']  = 'date';
		}
	}

	switch ( $show ) {
		case 'featured' :
			$query_args['meta_query'][] = array(
				'key'   => '_featured',
				'value' => 'yes'
			);
			$query_args['order']  	= $order;
			break;
		case 'onsale' :
			$product_ids_on_sale    = wc_get_product_ids_on_sale();
			$product_ids_on_sale[]  = 0;
			$query_args['post__in'] = $product_ids_on_sale;
			$query_args['order']  	= $order;
			break;
		case 'bestselling' :
			$query_args['meta_key'] = 'total_sales';
			$query_args['orderby']  = 'meta_value_num';
			$query_args['order']  	= 'desc';
			break;
		case 'toprated' :
			break;
		case 'incategory' :
			$query_args['tax_query'] = array(
				array(
					'taxonomy' 		=> 'product_cat',
					'terms' 		=> array_map( 'sanitize_title', explode( ',', $category ) ),
					'field' 		=> 'slug',
					'operator' 		=> 'IN',
				)
			);
			$query_args['order']  	= $order;
			break;
		default :
			$query_args['orderby']  = 'date';
			$query_args['order']  	= $order;
	}

	$query_args['meta_query'][] = WC()->query->stock_status_meta_query();
	$query_args['meta_query']   = array_filter( $query_args['meta_query'] );

	if ( intval( $columns ) > 5 )
		$columns = 3;

	if ( intval( $columns ) < 1 )
		$columns = 3;

	$wrapper_class = '';
	$wrapper_class .= 'columns-' . intval( $columns );
	$wrapper_class .= ( "yes" == $carousel ? ' toko-carousel' : ' toko-no-carousel' );

	ob_start();

	if ( $show == 'toprated' ) {
		add_filter( 'posts_clauses',  array( WC()->query, 'order_by_rating_post_clauses' ) );
	}

	// echo '<pre>'; var_dump( $query_args ); echo '</pre>';

	$products = new WP_Query( $query_args );

	$woocommerce_loop['columns'] = $columns;
	$carousel_id = intval( rand( 1, 1000) ); ?>

	<div class="toko-woocommerce woocommerce <?php echo $wrapper_class; ?> clearfix">

	<?php if ( $products->have_posts() ) : ?>

		<ul class="products <?php echo ( "yes" == $carousel ) ? "owl-carousel" : ""; ?>" id="toko-carousel-<?php echo $carousel_id ?>">

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; ?>

		</ul>
		
		<?php if( "yes" == $carousel ) : ?>
			<?php wp_enqueue_script( 'toko-owlcarousel' ); ?>
			<?php wp_enqueue_style( 'toko-owlcarousel' ); ?>
			<?php $js_code = "$('#toko-carousel-{$carousel_id}').owlCarousel({responsive:{ 0:{items:1}, 461:{items:2}, 992:{items:{$columns}} },loop: true, nav :{$paginate}, navText : ['<i class=\"fa fa-chevron-left\"></i>','<i class=\"fa fa-chevron-right\"></i>'], lazyLoad : true, autoPlay : true, dots: false });"; ?>
			<?php wc_enqueue_js( $js_code ); ?>
		<?php endif; ?>

	<?php else : ?>

		<p class="woocommerce-info"><?php _e( 'No products were found matching your selection.', 'tp-shortcodes' ); ?></p>

	<?php endif; ?>

	</div>

	<?php

	if ( $show == 'toprated' ) {
		remove_filter( 'posts_clauses',  array( WC()->query, 'order_by_rating_post_clauses' ) );
	}

	wp_reset_postdata();

	return ob_get_clean();
}

add_action( 'vc_before_init', 'toko_vc_woo_products' );
function toko_vc_woo_products() {

	if ( ! class_exists( 'woocommerce' ) )
		return;

	$args = array(
		'type' 			=> 'post',
		'child_of' 		=> 0,
		'parent' 		=> '',
		'orderby' 		=> 'name',
		'order' 		=> 'ASC',
		'hide_empty' 	=> false,
		'hierarchical' 	=> 1,
		'exclude' 		=> '',
		'include' 		=> '',
		'number' 		=> '',
		'taxonomy' 		=> 'product_cat',
		'pad_counts' 	=> false,

	);
	$categories = get_categories( $args );
	$product_categories_dropdown = array();
	toko_vc_getCategoryChilds( 'slug', 0, 0, $categories, 0, $product_categories_dropdown );

	$params = array(
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Show', 'tp-shortcodes' ),
				'param_name'	=> 'show',
				'value'			=> array(
									__( 'All Products', 'tp-shortcodes' )			=> 'all',
									__( 'Featured Products', 'tp-shortcodes' )		=> 'featured',
									__( 'On-sale Products', 'tp-shortcodes' )		=> 'onsale',
									__( 'Best Selling Products', 'tp-shortcodes' )	=> 'bestselling',
									__( 'Top Rated Products', 'tp-shortcodes' )		=> 'toprated',
									__( 'Products In A Category', 'tp-shortcodes' )	=> 'incategory',
								),
				'std'			=> '',
				'admin_label'	=> true,
			),
			array(
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Category', 'tp-shortcodes' ),
				'value' 		=> $product_categories_dropdown,
				'param_name' 	=> 'category',
				'dependency'	=> array(
									'element' 	=> 'show',
									'value' 	=> array( 'incategory' ),
								),
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Numbers', 'tp-shortcodes' ),
				'description'	=> __( 'How many products to show', 'tp-shortcodes' ),
				'param_name'	=> 'numbers',
				'value'			=> "6",
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Columns', 'tp-shortcodes' ),
				'description'	=> __( 'How many columns per row', 'tp-shortcodes' ),
				'param_name'	=> 'columns',
				'value'			=> array(
									__( '1 Column', 'tp-shortcodes' )	=> '1',
									__( '2 Columns', 'tp-shortcodes' )	=> '2',
									__( '3 Columns', 'tp-shortcodes' )	=> '3',
									__( '4 Columns', 'tp-shortcodes' )	=> '4',
									__( '5 Columns', 'tp-shortcodes' )	=> '5'
								),
				'std'			=> '3',
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Order By', 'tp-shortcodes' ),
				'param_name'	=> 'orderby',
				'value'			=> array(
									__( 'Date', 'tp-shortcodes' ) 		=> 'date',
									__( 'Price', 'tp-shortcodes' ) 		=> 'price',
									__( 'Sales', 'tp-shortcodes' ) 		=> 'sales',
									__( 'Random', 'tp-shortcodes' )		=> 'rand',
									__( 'Title', 'tp-shortcodes' )		=> 'title',
									__( 'ID', 'tp-shortcodes' )			=> 'id',
								),
				'std'			=> 'date',
				'dependency'	=> array(
									'element' 	=> 'show',
									'value' 	=> array( '', 'featured', 'onsale', 'incategory' ),
								),
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Order', 'tp-shortcodes' ),
				'param_name'	=> 'order',
				'value'			=> array(
									__( 'Descending', 'tp-shortcodes' )	=> 'desc',
									__( 'Ascending', 'tp-shortcodes' )	=> 'asc',
								),
				'std'			=> 'desc',
				'dependency'	=> array(
									'element' 	=> 'show',
									'value' 	=> array( '', 'featured', 'onsale', 'incategory' ),
								),
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Carousel', 'tp-shortcodes' ),
				'param_name'	=> 'carousel',
				'value'			=> array(
									__( 'Yes', 'tp-shortcodes' )	=> 'yes',
									__( 'No', 'tp-shortcodes' )		=> 'no'
								),
				'std'			=> 'no',
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Carousel Control', 'tp-shortcodes' ),
				'param_name'	=> 'paginate',
				'value'			=> array(
									__( 'Yes', 'tp-shortcodes' )	=> 'true',
									__( 'No', 'tp-shortcodes' )		=> 'false'
								),
				'std'			=> 'false',
				'dependency'	=> array(
									'element' 	=> 'carousel',
									'value' 	=> array( 'yes' ),
								),
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Hide Free Products', 'tp-shortcodes' ),
				'param_name'	=> 'hide_free',
				'value'			=> array(
									__( 'No', 'tp-shortcodes' )		=> 'no',
									__( 'Yes', 'tp-shortcodes' )	=> 'yes',
								),
				'std'			=> 'no',
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Show Hidden Products', 'tp-shortcodes' ),
				'param_name'	=> 'show_hidden',
				'value'			=> array(
									__( 'No', 'tp-shortcodes' )		=> 'no',
									__( 'Yes', 'tp-shortcodes' )	=> 'yes',
								),
				'std'			=> 'no',
			),
		);

	vc_map( array(
	   'name'				=> __( 'TP - WooCommerce Products', 'tp-shortcodes' ),
	   'base'				=> 'toko_wc_products',
	   'class'				=> '',
	   'icon'				=> 'woocommerce_icon',
	   'category'			=> 'TokoPress',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> $params,
	   )
	);
}