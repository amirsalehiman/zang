<?php
/**
 * Banners Shortcode
 * @package Shortcode
 * @author Toko
 */

add_shortcode( 'toko_banner', 'toko_shortcode_banner' );
function toko_shortcode_banner( $atts ) {

	extract( shortcode_atts( array(
		'banner_image'	=> '',
		'banner_url'	=> '',
		'title'			=> '',
		'paragraf1'		=> '',
		'paragraf2'		=> '',
		'text_position'	=> 'left',
		'banner_type'	=> 'medium'
	), $atts ) );

	if( "center" == $text_position ) {
		$wrapper_class = 'toko-banner-center';
	} else if( "right" == $text_position ) {
		$wrapper_class = 'toko-banner-right';
	} else {
		$wrapper_class = 'toko-banner-left';
	}

	$image = wp_get_attachment_url( $banner_image );

	ob_start();

	?>

	<div class="toko-banner <?php echo $wrapper_class; ?>" <?php echo ( $image ? 'style="background-image:url('.$image.')"' : '' ) ?> >
	<?php if( "" != $banner_url ) : ?>
		<a href="<?php echo esc_url( $banner_url ); ?>">
	<?php endif; ?>
        <?php if( "" != $title || "" != $paragraf1 || "" != $paragraf2 ) : ?>
        <div class="toko-banner-detail">
            <p class="paragraf1"><?php echo $paragraf1; ?></p>
            <h3 class="text-<?php echo $banner_type; ?>"><?php echo $title; ?></h3>
            <p class="paragraf2"><?php echo $paragraf2; ?></p>
        </div>
	    <?php endif; ?>
	<?php if( "" != $banner_url ) : ?>
		</a>
	<?php endif; ?>
    </div>

	<?php

	return ob_get_clean();
}

add_action( 'vc_before_init', 'toko_vc_banner' );
function toko_vc_banner() {

	$params = array(
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Banner URL', 'tp-shortcodes' ),
				'param_name'	=> 'banner_url',
			),
			array(
				'type'			=> 'attach_image',
				'heading'		=> __( 'Banner Image', 'tp-shortcodes' ),
				'param_name'	=> 'banner_image',
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Banner Title', 'tp-shortcodes' ),
				'param_name'	=> 'title',
				'admin_label'	=> true,
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Top Description', 'tp-shortcodes' ),
				'param_name'	=> 'paragraf1',
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Bottom Description', 'tp-shortcodes' ),
				'param_name'	=> 'paragraf2',
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Text Alignment', 'tp-shortcodes' ),
				'param_name'	=> 'text_position',
				'value'			=> array(
									__( 'Left', 'tp-shortcodes' )	=> 'left',
									__( 'Right', 'tp-shortcodes' )	=> 'right',
									__( 'Center', 'tp-shortcodes' )	=> 'center',
								),
				'std'			=> 'left'
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Banner Type', 'tp-shortcodes' ),
				'param_name'	=> 'banner_type',
				'value'			=> array(
									__( 'Big', 'tp-shortcodes' )		=> 'big',
									__( 'Medium', 'tp-shortcodes' )	=> 'medium',
								),
				'std'			=> 'medium'
			),
		);

	vc_map( array(
	   'name'				=> __( 'TP - Banner', 'tp-shortcodes' ),
	   'base'				=> 'toko_banner',
	   'class'				=> '',
	   'icon'				=> 'toko_icon',
	   'category'			=> 'TokoPress',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> $params,
	   )
	);
}