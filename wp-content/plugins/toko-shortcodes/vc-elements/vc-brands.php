<?php
/**
 * Brands Shortcode
 * @package Shortcode
 * @author Toko
 */

add_shortcode( 'toko_brands', 'toko_shortcode_brands' );
function toko_shortcode_brands( $atts ) {
	extract( shortcode_atts( array(
		'columns'				=> '5',
		'paginate'				=> 'false',
		'carousel'				=> 'no',
		'navigation'			=> 'no',
		'image_id_1'			=> '',
		'link_1'				=> '#',
		'image_id_2'			=> '',
		'link_2'				=> '#',
		'image_id_3'			=> '',
		'link_3'				=> '#',
		'image_id_4'			=> '',
		'link_4'				=> '#',
		'image_id_5'			=> '',
		'link_5'				=> '#',
		'image_id_6'			=> '',
		'link_6'				=> '#',
		'image_id_7'			=> '',
		'link_7'				=> '#',
		'image_id_8'			=> '',
		'link_8'				=> '#',
		'image_id_9'			=> '',
		'link_9'				=> '#',
		'image_id_10'			=> '',
		'link_10'				=> '#',
		'image_id_11'			=> '',
		'link_11'				=> '#',
		'image_id_12'			=> '',
		'link_12'				=> '#'
	), $atts ) );

	$wrapper_class = '';
	$wrapper_class .= 'columns-' . $columns;
	$wrapper_class .= ( "yes" == $carousel ? ' toko-carousel' : ' toko-no-carousel' );
	$carousel_id = intval( rand( 1, 1000) );

	ob_start();

	?>
	<div class="toko-brands-wrap <?php echo $wrapper_class; ?>">

		<div class="toko-brands <?php echo ( "yes" == $carousel ) ? "owl-carousel" : ""; ?>" id="toko-carousel-<?php echo $carousel_id ?>">
			
			<?php for( $i=1; $i<=12; $i++ ) : ?>
			<?php if( ${"image_id_".$i} ) : ?>
				<?php if( $image = wp_get_attachment_image( ${"image_id_".$i}, 'full' ) ) : ?>
					<div class="toko-brand">
						<a href="<?php echo ( ${"link_".$i} ) ? esc_url(${"link_".$i}) : "#"; ?>">
							<?php echo $image; ?>
						</a>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<?php endfor; ?>

			<?php if( "yes" == $carousel ) : ?>
				<?php wp_enqueue_script( 'toko-owlcarousel' ); ?>
				<?php wp_enqueue_style( 'toko-owlcarousel' ); ?>
				<?php $js_code = "$('#toko-carousel-{$carousel_id}').owlCarousel({responsive:{ 0:{items:1}, 460:{items:2}, 992:{items:{$columns}} },loop: true, nav :{$paginate}, navText : ['<i class=\"fa fa-chevron-left\"></i>','<i class=\"fa fa-chevron-right\"></i>'], lazyLoad : true, autoPlay : true, dots: false });"; ?>
				<?php toko_enqueue_js( $js_code ); ?>
			<?php endif; ?>

		</div>
	</div>
	<?php

	return ob_get_clean();
}

add_action( 'vc_before_init', 'toko_vc_brands' );
function toko_vc_brands() {

	$params = array(
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Carousel', 'tp-shortcodes' ),
				'param_name'	=> 'carousel',
				'value'			=> array(
									__( 'Yes', 'tp-shortcodes' )	=> 'yes',
									__( 'No', 'tp-shortcodes' )		=> 'no'
								),
				'std'			=> 'no'
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Carousel Control', 'tp-shortcodes' ),
				'param_name'	=> 'paginate',
				'value'			=> array(
									__( 'Yes', 'tp-shortcodes' )	=> 'true',
									__( 'No', 'tp-shortcodes' )		=> 'false'
								),
				'std'			=> 'false'
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
				'std'			=> '5'
			),
		);
	
	for( $i=1; $i <= 12; $i++ ) {
		$params[] = array(
				'type'			=> 'attach_image',
				'heading'		=> sprintf( __( 'Image #%s', 'tp-shortcodes' ), $i ),
				'param_name'	=> 'image_id_' . $i,
			);
		$params[] = array(
				'type'			=> 'textfield',
				'heading'		=> sprintf( __( 'Image URL #%s', 'tp-shortcodes' ), $i ),
				'param_name'	=> 'link_' . $i,
			);
	}

	vc_map( array(
	   'name'				=> __( 'TP - Brands', 'tp-shortcodes' ),
	   'base'				=> 'toko_brands',
	   'class'				=> '',
	   'icon'				=> 'toko_icon',
	   'category'			=> 'TokoPress',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> $params,
	   )
	);
}