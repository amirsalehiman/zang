<?php
/**
 * Call To Action Shortcode
 * @package Shortcode
 * @author Toko
 */

add_shortcode( 'toko_call_to_action', 'toko_call_to_action_shortcode' );
function toko_call_to_action_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'layout'		=> 'default',
		'title'			=> 'Call To Action',
		'paragraf'		=> '',
		'button_text'	=> '',
		'button_url'	=> '',
		'button_type'	=> 'button',
		'text_position'	=> 'center'
	), $atts ) );

	if( "center" == $text_position ) {
		$wrapper_class = 'text-center';
	} else if( "right" == $text_position ) {
		$wrapper_class = 'text-right';
	} else {
		$wrapper_class = 'text-left';
	}

	ob_start();

	?>

	<div class="toko-cta <?php echo $wrapper_class; ?>">
		<?php if( $layout == "alternate" ) : ?>
			<?php if( "" != $paragraf ) : ?>
		        <p class="toko-cta-description"><?php echo $paragraf; ?></p>
		    <?php endif; ?>

		    <?php if( "" != $title ) : ?>
		        <h2 class="toko-cta-title"><?php echo $title; ?></h2>
		    <?php endif; ?>
		<?php else : ?>
		    <?php if( "" != $title ) : ?>
		        <h2 class="toko-cta-title"><?php echo $title; ?></h2>
		    <?php endif; ?>

			<?php if( "" != $paragraf ) : ?>
		        <p class="toko-cta-description"><?php echo $paragraf; ?></p>
		    <?php endif; ?>
		<?php endif; ?>

        <?php if( "" != $button_text ) : ?>
	        <a href="<?php echo ( "" != $button_url ) ? esc_url( $button_url ) : '#'; ?>" class="<?php echo 'toko-cta-' . $button_type; ?>"><?php echo $button_text; ?></a>
	    <?php endif; ?>
    </div>

	<?php

	return ob_get_clean();
}

add_action( 'vc_before_init', 'toko_vc_call_to_action' );
function toko_vc_call_to_action() {

	$params = array(
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'CTA Layout', 'tp-shortcodes' ),
				'param_name'	=> 'layout',
				'value'			=> array(
									__( 'Default Layout', 'tp-shortcodes' )		=> 'default',
									__( 'Alternative Layout', 'tp-shortcodes' )	=> 'alternate',
								),
				'std'			=> 'default'
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Title', 'tp-shortcodes' ),
				'param_name'	=> 'title',
				'admin_label'	=> true,
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Description', 'tp-shortcodes' ),
				'param_name'	=> 'paragraf',
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Button Text', 'tp-shortcodes' ),
				'param_name'	=> 'button_text',
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Button URL', 'tp-shortcodes' ),
				'param_name'	=> 'button_url',
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Button Type', 'tp-shortcodes' ),
				'param_name'	=> 'button_type',
				'value'			=> array(
									__( 'Button', 'tp-shortcodes' )	=> 'button',
									__( 'Link', 'tp-shortcodes' )	=> 'link'
								),
				'std'			=> 'button'
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Text Position', 'tp-shortcodes' ),
				'param_name'	=> 'text_position',
				'value'			=> array(
									__( 'Center', 'tp-shortcodes' )	=> 'center',
									__( 'Left', 'tp-shortcodes' )	=> 'left',
									__( 'Right', 'tp-shortcodes' )	=> 'right',
								),
				'std'			=> 'center'
			),
		);

	vc_map( array(
	   'name'				=> __( 'TP - Call To Action', 'tp-shortcodes' ),
	   'base'				=> 'toko_call_to_action',
	   'class'				=> '',
	   'icon'				=> 'toko_icon',
	   'category'			=> 'TokoPress',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> $params,
	   )
	);
}