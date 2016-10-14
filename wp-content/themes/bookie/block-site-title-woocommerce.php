<?php

if ( is_page_template( 'page_fullwidth_notitle.php' ) ) {
	return;
}

if ( is_page_template( 'page_visual_composer.php' ) ) {
	return;
}

?>
<section id="page-title" class="page-title" <?php if ( get_header_image() ) echo 'style="background-image: url('.get_header_image().')"'; ?> >
	<div class="container">

		<?php if ( is_tax() ) : ?>

			<h1><?php single_term_title(); ?></h1>

		<?php elseif ( is_search() ) : ?>

			<h1><?php esc_html_e( 'Search Results:', 'bookie-wp' ); ?> <?php echo esc_attr( get_search_query() ); ?></h1>

		<?php elseif( is_404() ) : ?>

			<h1><?php esc_html_e( '404 - Not Found', 'bookie-wp' ); ?></h1>

		<?php else : ?>

			<?php
			$shop_page_id = wc_get_page_id( 'shop' );
			$shop_page    = get_post( $shop_page_id );
			?>

			<?php if ( is_single() ) : ?>
				<h2><?php echo ( $shop_page_id && $shop_page ? get_the_title( $shop_page ) : esc_html__( 'Shop', 'bookie-wp' ) ); ?></h2>
			<?php else : ?>
				<h1><?php echo ( $shop_page_id && $shop_page ? get_the_title( $shop_page ) : esc_html__( 'Shop', 'bookie-wp' ) ); ?></h1>
			<?php endif ?>

		<?php endif ?>

		<?php woocommerce_breadcrumb(); ?>
		
	</div>
</section>
