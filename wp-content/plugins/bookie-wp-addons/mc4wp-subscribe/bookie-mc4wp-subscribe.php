<?php
/**
 * Bookie Subscribe Form
 *
 * @package MailChimp
 * @author Tokopress
 *
 */

add_shortcode( 'bookie_subscribe_form', 'bookie_subscribe_form_sortcode' );
function bookie_subscribe_form_sortcode( $atts ) {

	if( !( defined( 'MC4WP_VERSION' ) || class_exists( "MC4WP_Lite" ) ) )
		return;

	extract( shortcode_atts( array(
		'icon'			=> '',
		'title'			=> '',
		'description'	=> ''
	), $atts ) );

	if ( !trim($title) )
		$title = __( 'Subscribe to our newsletter', 'bookie-wp-addons' );

	if ( !trim($description) )
		$description = __( 'never miss our latest news and events updates', 'bookie-wp-addons' );

	$output = '';
	$output .= '<div class="home-subscribe-form clearfix">';
		$output .= '<div class="row">';
			$output .= '<div class="col-md-6">';
				
				if ( $icon ) {
					$output .= '<div class="subscribe-icon">';
						$output .= '<i class="' . $icon . '"></i>';
					$output .= '</div>';
				}

				$output .= '<div class="subscribe-heading">';
					$output .= '<h2>';
						$output .= $title;
					$output .= '</h2>';
					$output .= '<p>';
						$output .= $description;
					$output .= '</p>';
				$output .= '</div>';

			$output .= '</div>';
			$output .= '<div class="col-md-6">';
				
				/* NOTE: currently using do_shortcode is the best way to output subscribe form*/
				/* TODO: contact mailchimp4wp plugin developer to add direct function call to output subscibe form */
				$output .= do_shortcode( '[mc4wp_form]' );
			
			$output .= '</div>';
		$output .= '</div>';
	$output .= '</div>';

	return $output;

}

add_action( 'vc_before_init', 'bookie_vc_subscribe_form' );
function bookie_vc_subscribe_form() {

	if( !( defined( 'MC4WP_VERSION' ) || class_exists( "MC4WP_Lite" ) ) )
		return;

	vc_map( array(
	   'name'				=> __( 'TP - Subscribe Form', 'bookie-wp-addons' ),
	   'base'				=> 'bookie_subscribe_form',
	   'class'				=> '',
	   'icon'				=> '',
	   'category'			=> 'TokoPress',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> array(
								array(
									'type' 			=> 'iconpicker',
									'heading' 		=> __( 'Icon', 'bookie-wp-addons' ),
									'param_name' 	=> 'icon',
									'settings' 		=> array(
										'emptyIcon' 	=> true, 
										'iconsPerPage' 	=> 4000, 
										'type'			=> 'fontawesome',
									),
								),
								array(
									'type'			=> 'textfield',
									'heading'		=> __( 'Title', 'bookie-wp-addons' ),
									'param_name'	=> 'title',
									'description'	=> __( 'Default:', 'bookie-wp-addons' ).' '.__( 'Subscribe to our newsletter', 'bookie-wp-addons' ),
									'value'			=> ''
								),
								array(
									'type'			=> 'textfield',
									'heading'		=> __( 'Description', 'bookie-wp-addons' ),
									'param_name'	=> 'description',
									'description'	=> __( 'Default:', 'bookie-wp-addons' ).' '.__( 'never miss our latest news and events updates', 'bookie-wp-addons' ),
									'value'			=> ''
								),
							)
	   )
	);
}