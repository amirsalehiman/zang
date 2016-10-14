<?php
/**
 * WooCommerce Shortcode
 *
 * @package Shortcode
 * @author Toko
 */

/**
 * Product Category
 */
add_shortcode( 'toko_wc_categories', 'toko_shortcode_product_categories' );
function toko_shortcode_product_categories( $atts ){

	if ( ! class_exists( 'woocommerce' ) )
		return;

	global $woocommerce_loop;

	extract( shortcode_atts( array(
		'numbers'     		=> '6',
		'columns' 	 		=> '3',
		'hide_empty' 		=> 1,
		'orderby'    		=> 'none',
		'order'     		=> 'asc',
		'parent'     		=> '',
		'timePlay'	 		=> 'true',
		'carousel'			=> 'no',
		'paginate'			=> 'false'
	), $atts ) );

	if ( isset( $atts[ 'ids' ] ) ) {
		$ids = explode( ',', $atts[ 'ids' ] );
		$ids = array_map( 'trim', $ids );
	} else {
		$ids = array();
	}

	$hide_empty = ( $hide_empty == true || $hide_empty == 1 ) ? 1 : 0;

	if ( $parent == 'top' ) {
		$parent = '0';
	}

	// get terms and workaround WP bug with parents/pad counts
	$args = array(
		'orderby'    => $orderby,
		'order'      => $order,
		'hide_empty' => $hide_empty,
		'include'    => $ids,
		'pad_counts' => true,
		'child_of'   => $parent
	);

	$product_categories = get_terms( 'product_cat', $args );

	if ( $parent !== "" ) {
		$product_categories = wp_list_filter( $product_categories, array( 'parent' => $parent ) );
	}

	if ( $hide_empty ) {
		foreach ( $product_categories as $key => $category ) {
			if ( $category->count == 0 ) {
				unset( $product_categories[ $key ] );
			}
		}
	}

	if ( $numbers ) {
		$product_categories = array_slice( $product_categories, 0, $numbers );
	}

	if ( intval( $columns ) > 5 )
		$columns = 3;

	if ( intval( $columns ) < 1 )
		$columns = 3;

	$wrapper_class = '';
	$wrapper_class .= 'columns-' . intval( $columns );
	$wrapper_class .= ( "yes" == $carousel ? ' toko-carousel' : ' toko-no-carousel' );

	ob_start();

	$woocommerce_loop['columns'] = $columns;
	$carousel_id = intval( rand( 1, 1000) ); ?>

	<div class="toko-woocommerce-category woocommerce <?php echo $wrapper_class; ?> clearfix">
	<?php if ( $product_categories ) : ?>

		<ul class="products product-category <?php echo ( "yes" == $carousel ) ? "owl-carousel" : ""; ?>" id="toko-carousel-<?php echo $carousel_id ?>">
		
		<?php
		foreach ( $product_categories as $category ) {

			wc_get_template( 'content-product_cat.php', array(
				'category' => $category
			) );

		} ?>

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
	woocommerce_reset_loop();

	return ob_get_clean();
}

add_action( 'vc_before_init', 'toko_vc_wc_categories' );
function toko_vc_wc_categories() {

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
	toko_vc_getCategoryChilds( 'id', 0, 0, $categories, 0, $product_categories_dropdown );

	$product_categories_dropdown_begin = array(
									__( '[All Categories]', 'tp-shortcodes' )	=> '',
									__( '[Top Level Only]', 'tp-shortcodes' )	=> 'top',
								);
	$product_categories_dropdown = array_merge( $product_categories_dropdown_begin, $product_categories_dropdown );

	$params = array(
			array(
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Parent Category', 'tp-shortcodes' ),
				'value' 		=> $product_categories_dropdown,
				'param_name' 	=> 'parent',
				'description' 	=> __( 'Useful to show subcategories', 'tp-shortcodes' ),
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Numbers', 'tp-shortcodes' ),
				'description'	=> __( 'How many categories to show', 'tp-shortcodes' ),
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
									__( 'None', 'tp-shortcodes' )		=> 'none',
									__( 'Name', 'tp-shortcodes' )		=> 'name',
									__( 'Count', 'tp-shortcodes' )		=> 'count',
									__( 'Slug', 'tp-shortcodes' )		=> 'slug',
									__( 'ID', 'tp-shortcodes' )			=> 'id'
								),
				'std'			=> 'none',
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Order', 'tp-shortcodes' ),
				'param_name'	=> 'order',
				'value'			=> array(
									__( 'Ascending', 'tp-shortcodes' )	=> 'asc',
									__( 'Descending', 'tp-shortcodes' )	=> 'desc'
								),
				'std'			=> 'asc',
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
		);

	vc_map( array(
	   'name'				=> __( 'TP - WooCommerce Product Categories', 'tp-shortcodes' ),
	   'base'				=> 'toko_wc_categories',
	   'class'				=> '',
	   'icon'				=> 'woocommerce_icon',
	   'category'			=> 'TokoPress',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> $params,
	   )
	);
}