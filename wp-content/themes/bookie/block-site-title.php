<?php

if ( is_front_page() ) {
	return;
}

if ( 'page' != get_option('show_on_front') && is_single() ) {
	return;
}

if ( is_page_template( 'page_fullwidth_notitle.php' ) ) {
	return;
}

if ( is_page_template( 'page_visual_composer.php' ) ) {
	return;
}

?>
<section id="page-title" class="page-title" <?php if ( get_header_image() ) echo 'style="background-image: url('.get_header_image().')"'; ?> >
	<div class="container">

		<?php if( is_front_page() ) : ?>

			<h1><?php esc_html_e( 'Home', 'bookie-wp' ); ?></h1>
		
		<?php elseif ( is_home() ) : ?>

			<h1><?php echo get_post_field( 'post_title', get_queried_object_id() ); ?></h1>

		<?php elseif ( is_category() ) : ?>

			<h1><?php single_cat_title(); ?></h1>

		<?php elseif ( is_tag() ) : ?>

			<h1><?php printf( esc_html__( 'Tag: %s', 'bookie-wp' ), single_tag_title( '', false ) ); ?></h1>

		<?php elseif ( is_tax() ) : ?>

			<h1><?php single_term_title(); ?></h1>

		<?php elseif ( is_author() ) : ?>

			<h1 class="loop-title fn n"><?php the_author_meta( 'display_name', get_query_var( 'author' ) ); ?></h1>

		<?php elseif ( is_search() ) : ?>

			<h1><?php echo esc_attr( get_search_query() ); ?></h1>

		<?php elseif ( is_day() ) : ?>

			<h1><?php echo get_the_time( esc_html__( 'F d, Y', 'bookie-wp' ) ); ?></h1>

		<?php elseif ( is_month() ) : ?>

			<h1><?php echo get_the_time( esc_html__( 'F Y', 'bookie-wp' ) ); ?></h1>
			
		<?php elseif ( is_year() ) : ?>

			<h1><?php echo get_the_time( esc_html__( 'Y', 'bookie-wp' ) ); ?></h1>
			
		<?php elseif ( is_archive() ) : ?>

				<?php if( is_post_type_archive( 'project' ) ) : ?>

					<h1><?php esc_html_e( 'Projects', 'bookie-wp' ); ?></h1>

				<?php elseif ( class_exists( 'woocommerce' ) && is_post_type_archive( 'product' ) ) : ?>

					<h1><?php esc_html_e( 'Shop', 'bookie-wp' ); ?></h1>

				<?php else : ?>

					<h1><?php esc_html_e( 'Archives', 'bookie-wp' ); ?></h1>

				<?php endif; ?>

		<?php elseif ( is_single() ) : ?>

			<?php if( is_singular( 'post' ) && get_option('show_on_front') == 'page' && get_option('page_for_posts') > 0 ) : ?>

				<h2><?php echo get_post_field( 'post_title', get_option('page_for_posts') ); ?></h2>

			<?php elseif( is_singular( 'attachment' ) ) : ?>

				<h1><?php esc_html_e( 'Media', 'bookie-wp' ); ?></h1>

			<?php else : ?>

				<h1><?php the_title(); ?></h1>

			<?php endif; ?>

		<?php elseif ( is_page() ) : ?>
		
			<h1><?php the_title(); ?></h1>
		
		<?php elseif( is_404() ) : ?>

			<h1><?php esc_html_e( '404 - Not Found', 'bookie-wp' ); ?></h1>

		<?php endif; ?>

		<?php toko_breadcrumb(); ?>
		
	</div>
</section>
