<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_shortcode( 'bookie-featured-product', 'bookie_featured_product_shortcode' );
function bookie_featured_product_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'title'		=> '',
		'name'		=> '',
		'image'		=> '',
		'button_text'	=> '',
		'button_url'	=> '',
		'description'	=> '',
		'style'		=> '1'
	), $atts ) );

	$fe_image = wp_get_attachment_url( $image );

	ob_start();

	?>
	<?php if( "1" == $style ) : ?>
	<div class="toko-featured-book style-1">
		<div class="row">
			<div class="row-md-height">
				<div class="col-md-4 col-md-height col-md-middle">
					<div class="inside">
						<p class="book-label"><?php _e( 'Featured Book', 'bookie-wp-addons' ); ?></p>
						<h2 class="book-title"><?php echo esc_html( $title ); ?></h2>
						<p class="book-author-name"><?php echo esc_html( $name ); ?></p>
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-md-height col-md-middle">
					<div class="inside inside-book-cover">
						<img class="book-image" width="339" height="480" src="<?php echo esc_url( $fe_image ); ?>">
						<p class="book-button-wrap">
							<a class="book-button btn btn-primary" href="<?php echo ( $button_url ) ? esc_url( $button_url ) : '#'; ?>"><?php echo esc_html( $button_text ); ?></a>
						</p>
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-md-height col-md-middle">
					<div class="inside inside-book-description">
						<p><?php echo esc_html( $description ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php else : ?>
	<div class="toko-featured-book style-2">
		<div class="row">
			<div class="row-md-height">
				<div class="col-md-6 col-sm-6 col-md-height col-md-middle">
					<div class="inside">
						<p class="book-label"><?php _e( 'Featured Book', 'bookie-wp-addons' ); ?></p>
						<h2 class="book-title"><?php echo esc_html( $title ); ?></h2>
						<p class="book-author-name"><?php echo esc_html( $name ); ?></p>
					</div>
					<div class="inside inside-book-description">
						<p><?php echo esc_html( $description ); ?></p>
					</div>
					<p class="book-button-wrap">
						<a class="book-button btn btn-primary" href="<?php echo ( $button_url ) ? esc_url( $button_url ) : '#'; ?>"><?php echo esc_html( $button_text ); ?></a>
					</p>
				</div>
				<div class="col-md-6 col-sm-6 col-md-height col-md-middle">
					<div class="inside inside-book-cover">
						<img class="book-image" width="339" height="480" src="<?php echo esc_url( $fe_image ); ?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php

	return ob_get_clean();

}

add_action( 'vc_before_init', 'bookie_vc_featured_product' );
function bookie_vc_featured_product() {

	$params = array(
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Style', 'bookie-wp-addons' ),
				'param_name'	=> 'style',
				'value'			=> array(
									__( 'Style 1', 'bookie-wp-addons' )	=> '1',
									__( 'Style 2', 'bookie-wp-addons' )	=> '2'
								),
				'std'			=> '1',
				'admin_label'	=> true
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Title', 'bookie-wp-addons' ),
				'param_name'	=> 'title',
				'value'			=> '',
				'admin_label'	=> true
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Author Name', 'bookie-wp-addons' ),
				'param_name'	=> 'name',
				'value'			=> '',
			),
			array(
				'type'			=> 'attach_image',
				'heading'		=> __( 'Book Cover', 'bookie-wp-addons' ),
				'param_name'	=> 'image',
				'value'			=> '',
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Button Text', 'bookie-wp-addons' ),
				'param_name'	=> 'button_text',
				'value'			=> '',
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Button URL', 'bookie-wp-addons' ),
				'param_name'	=> 'button_url',
				'value'			=> '',
			),
			array(
				'type'			=> 'textarea',
				'heading'		=> __( 'Description', 'bookie-wp-addons' ),
				'param_name'	=> 'description',
				'value'			=> '',
			),
		);

	vc_map( array(
	   'name'				=> __( 'TP - Featured Book', 'bookie-wp-addons' ),
	   'base'				=> 'bookie-featured-product',
	   'class'				=> '',
	   'icon'				=> 'bookie_icon',
	   'category'			=> 'TokoPress',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> $params,
	   )
	);
}