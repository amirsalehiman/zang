<?php
/**
 * Divider Shortcode
 * @package Shortcode
 * @author Toko
 */

add_shortcode( 'toko_divider', 'toko_divider_shortcode' );
function toko_divider_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'title'			=> '',
		'paragraf'		=> '',
		'text_position'	=> 'center',
		'icon'			=> '',
		'icon_position'	=> 'hide',
		'line'			=> 'no',
		'divider_bg'	=> ''
	), $atts ) );

	$wrapper_class = '';

	if( "center" == $text_position ) {
		$wrapper_class .= 'text-center';
	} else if( "right" == $text_position ) {
		$wrapper_class .= 'text-right';
	} else if( "left" == $text_position ) {
		$wrapper_class .= 'text-left';
	} else {
		$wrapper_class .= 'text-no';
	}

	if( "yes" == $line ) {
		$wrapper_class .= ' line-yes';
	} else {
		$wrapper_class .= ' line-no';
	}

	$wrapper_class .= ( $icon || $icon_position !== 'hide' ) ? ' icon-' . $icon_position : ' icon-no';
	
	if( "" == $divider_bg ) {
		$bg = 'background-color: #fff';
	} else {
		$bg = 'background-color: ' . $divider_bg;
	}

	ob_start();

	?>


	<div class="toko-divider <?php echo $wrapper_class; ?>">

		<?php if( "hide" != $text_position || "hide" != $icon_position ) : ?>
			<div class="divider-inner" style="<?php echo $bg; ?>">
		<?php endif; ?>

		<?php
		if( "hide" != $text_position ) {
			if( "right" == $icon_position ) {
				if( "" != $title ) {
					echo '<h3 class="toko-section-title">';
					echo esc_attr( $title );
					echo '<span class="' . toko_vc_icon( $icon ) . '"></span></h3>';
				} else {
					echo '<span class="' . toko_vc_icon( $icon ) . '"></span>';
				}
			} else if( "left" == $icon_position ) {
				if( "" != $title ) {
					echo '<h3 class="toko-section-title">';
					echo '<span class="' . toko_vc_icon( $icon ) . '"></span>';
					echo esc_attr( $title );
					echo '</h3>';
				} else {
					echo '<span class="' . toko_vc_icon( $icon ) . '"></span>';
				}
			} else if( "bottom" == $icon_position ) {
				if( "" != $title ) {
					echo '<h3 class="toko-section-title">';
					echo esc_attr( $title );
					echo '</h3><span class="' . toko_vc_icon( $icon ) . '"></span>';
				} else {
					echo '<span class="' . toko_vc_icon( $icon ) . '"></span>';
				}
			} else if( "top" == $icon_position ) {
				if( "" != $title ) {
					echo '<span class="' . toko_vc_icon( $icon ) . '"></span><h3 class="toko-section-title">';
					echo esc_attr( $title );
					echo '</h3>';
				} else {
					echo '<span class="' . toko_vc_icon( $icon ) . '"></span>';
				}
			} else {
				echo '<h3 class="toko-section-title">';
				echo esc_attr( $title );
				echo '</h3>';
			}
		} else {
			if( "hide" != $icon_position ) {
				echo '<h3 class="toko-section-title">';
				echo '<span class="' . toko_vc_icon( $icon ) . '"></span>';
				echo '</h3>';
			}
		}
		?>

		<?php if( "" != $paragraf ) : ?>
			<p class="paragraf"><?php echo esc_attr( $paragraf ); ?></p>
		<?php endif; ?>

		<?php if( "hide" != $text_position || "hide" != $icon_position ) : ?>
			</div>
		<?php endif ?>
	</div>

	<?php

	return ob_get_clean();
}

add_action( 'vc_before_init', 'toko_vc_divider' );
function toko_vc_divider() {

	$params = array(
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Horizontal Line', 'tp-shortcodes' ),
				'param_name'	=> 'line',
				'value'			=> array(
									__( 'Yes', 'tp-shortcodes' )	=> 'yes',
									__( 'No', 'tp-shortcodes' )		=> 'no'
								),
				'std'			=> 'no'
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Text Position', 'tp-shortcodes' ),
				'param_name'	=> 'text_position',
				'value'			=> array(
									__( 'Center', 'tp-shortcodes' )		=> 'center',
									__( 'Left', 'tp-shortcodes' )		=> 'left',
									__( 'Right', 'tp-shortcodes' )		=> 'right',
									__( 'No Text', 'tp-shortcodes' )	=> 'hide',
								),
				'std'			=> 'center'
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Icon Position', 'tp-shortcodes' ),
				'param_name'	=> 'icon_position',
				'value'			=> array(
									__( 'No Icon', 'tp-shortcodes' )	=> 'hide',
									__( 'Left', 'tp-shortcodes' )		=> 'left',
									__( 'Right', 'tp-shortcodes' )		=> 'right',
									__( 'Top', 'tp-shortcodes' )		=> 'top',
									__( 'Bottom', 'tp-shortcodes' )		=> 'bottom',
								),
				'std'			=> 'hide'
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
				'type' 			=> 'iconpicker',
				'heading' 		=> __( 'Icon', 'tp-shortcodes' ),
				'param_name' 	=> 'icon',
				'settings' 		=> array(
					'emptyIcon' 	=> false, 
					'iconsPerPage' 	=> 4000, 
					'type'			=> 'fontawesome',
				),
				// 'dependency' 	=> array(
				// 	'element' 		=> 'type',
				// 	'value' 		=> 'fontawesome',
				// ),
			),
			array(
		         'type' 		=> 'colorpicker',
		         'heading' 		=> __( 'Divider Background Color', 'tp-shortcodes' ),
		         'description'	=> __( 'Default background is white, please match the background color with the row background color to get the best result', 'tp-shortcodes' ),
		         'param_name' 	=> "divider_bg",
		         'value' 		=> ''
			),
		);

	vc_map( array(
	   'name'				=> __( 'TP - Divider / Heading', 'tp-shortcodes' ),
	   'base'				=> 'toko_divider',
	   'class'				=> '',
	   'icon'				=> 'toko_icon',
	   'category'			=> 'TokoPress',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> $params,
	   )
	);
}