<?php
/**
 * Template Name: Contact Form
 *
 * WARNING: This file is part of the Bookie parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category Page
 * @package  Templates
 * @author   TokoPress
 * @link     http://www.tokopress.com
 */

get_header(); ?>

<?php do_action( 'before_main_content' ); ?>

<div class="main-content">

	<?php do_action( 'before_inner_main_content' ); ?>

	<div class="main-content-container container">
		<div class="row">

			<div class="col-md-12">
				<div id="content" class="main-content-inner" role="main">

				<?php if( have_posts() ) : ?>
					<?php while( have_posts() ): the_post(); ?>
						
						<article id="post-<?php echo get_the_ID(); ?>" <?php post_class(); ?>>

				            <div class="entry-content">

				            	<div class="row">

					            	<?php if ( get_the_content() ) : ?>

					            		<div class="col-md-6">
											<?php echo toko_get_contact_form(); ?>
					            		</div>
					            		<div class="col-md-6">
											<?php the_content(); ?>
					            		</div>

					            	<?php else : ?>

					            		<div class="col-md-6 col-md-push-3">
											<?php echo toko_get_contact_form(); ?>
					            		</div>

					            	<?php endif; ?>
					            	
					            </div>
			                    
				            </div>

						</article>

					<?php endwhile; ?>
				<?php else : ?>
					<?php get_template_part( 'content', '404' ); ?>
				<?php endif; ?>

				<?php toko_pagination(); ?>

				</div>
			</div>

		</div>
	</div>

	<?php do_action( 'after_inner_main_content' ); ?>

</div>

<?php do_action( 'after_main_content' ); ?>

<?php 
if( !toko_get_option( 'contact_map_disable' ) ) :

	$tp_latitude = toko_get_option( 'contact_map_lat' );
	if ( !$tp_latitude ) $tp_latitude = -6.903932;

	$tp_longitude = toko_get_option( 'contact_map_lng' );
	if ( !$tp_longitude ) $tp_longitude = 107.610344;

	$tp_marker_title = toko_get_option( 'contact_map_marker_title' );
	if ( !$tp_marker_title ) $tp_marker_title = esc_html__( 'Marker Title', 'bookie-wp' );
	$tp_marker_title = str_replace( "\r\n", "<br/>", $tp_marker_title );

	$tp_marker_content = toko_get_option( 'contact_map_marker_desc' );
	if ( !$tp_marker_content ) $tp_marker_content = esc_html__( 'Marker Content', 'bookie-wp' );
	$tp_marker_content = str_replace( "\r\n", "<br/>", $tp_marker_content );

	wp_enqueue_script( 'toko-maps' );
	wp_enqueue_script( 'toko-gmaps' );

	$tp_js = 'var mapcontact;';
	$tp_js .= 'var mapcontact_lat = '. esc_js( $tp_latitude ) . ';';
	$tp_js .= 'var mapcontact_lng = ' . esc_js( $tp_longitude ) . ';';
	$tp_js .= 'var mapcontact_title = "' . esc_js( $tp_marker_title ) . '";';
	$tp_js .= 'var mapcontact_content = "<h2>' . esc_js( $tp_marker_title ) . '</h2><p>' . esc_js( $tp_marker_content ) . '</p>";';
	$tp_js .= 'jQuery(document).ready(function(){mapcontact=new GMaps({el:"#contact-map",lat:mapcontact_lat,lng:mapcontact_lng,zoom:15,scrollwheel:false});mapcontact.addMarker({lat:mapcontact_lat,lng:mapcontact_lng,title:mapcontact_title,infoWindow:{content:mapcontact_content}})})';

	toko_enqueue_js ( $tp_js );

	echo '<div id="contact-map"></div>';

endif;
?>

<?php get_footer(); ?>