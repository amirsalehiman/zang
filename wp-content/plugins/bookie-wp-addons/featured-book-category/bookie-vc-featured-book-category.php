<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_shortcode( 'bookie-featured-book-category', 'bookie_featured_book_category_shortcode' );
function bookie_featured_book_category_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'title'		=> '',
		'badge'		=> '',
		'badge_background' => '#27c8ea',
		'badge_text_color' => '#ffffff',
		'image'		=> '',
		'button_text'	=> '',
		'button_url'	=> '',
		'description'	=> '',
		'style'		=> '1'
	), $atts ) );

	$fe_image = wp_get_attachment_url( $image );

	ob_start();

	?>
	<div class="toko-featured-book-category style-<?php echo $style; ?>">
		<div class="inside">

			<?php if ( $image ) : ?>
			<img class="book-image" width="339" height="480" src="<?php echo esc_url( $fe_image ); ?>">
			<?php endif; ?>

			<div class="inside-detail">
				<?php if ( $badge ) : ?>
				<span class="book-badge" style="background-color:<?php echo $badge_background; ?>;color:<?php echo $badge_text_color; ?>;"><?php echo esc_html( $badge ); ?></span>
				<?php endif; ?>
				
				<h2 class="book-title"><?php echo esc_html( $title ); ?></h2>
				
				<?php if ( $description ) : ?>
				<div class="inside inside-book-description">
					<p><?php echo esc_html( $description ); ?></p>
				</div>
				<?php endif; ?>
				
				<?php if ( $button_url ) : ?>
				<p class="book-button-wrap">
					<a class="book-button btn btn-primary" href="<?php echo ( $button_url ) ? esc_url( $button_url ) : '#'; ?>"><?php echo esc_html( $button_text ); ?></a>
				</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php

	return ob_get_clean();

}

add_action( 'vc_before_init', 'bookie_vc_featured_book_catgeory' );
function bookie_vc_featured_book_catgeory() {

	$params = array(
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Style', 'bookie-wp-addons' ),
				'param_name'	=> 'style',
				'value'			=> array(
									__( 'Style 1', 'bookie-wp-addons' )	=> '1',
									__( 'Style 2', 'bookie-wp-addons' )	=> '2',
									__( 'Style 3', 'bookie-wp-addons' )	=> '3'
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
				'heading'		=> __( 'Badge Text', 'bookie-wp-addons' ),
				'param_name'	=> 'badge',
				'value'			=> '',
			),
			array(
				'type'			=> 'colorpicker',
				'heading'		=> __( 'Badge Background Color', 'tokopress' ),
				'param_name'	=> 'badge_background',
				'value'			=> '#27c8ea'
			),
			array(
				'type'			=> 'colorpicker',
				'heading'		=> __( 'Badge Text Color', 'tokopress' ),
				'param_name'	=> 'badge_text_color',
				'value'			=> '#ffffff'
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
	   'name'				=> __( 'TP - Featured Book Catgeory', 'bookie-wp-addons' ),
	   'base'				=> 'bookie-featured-book-category',
	   'class'				=> '',
	   'icon'				=> 'bookie_icon',
	   'category'			=> 'TokoPress',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> $params,
	   )
	);
}