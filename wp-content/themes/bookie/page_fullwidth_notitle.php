<?php
/**
 * Template Name: Full Width No Title
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

			                    <?php the_content(); ?>
			                    <?php toko_link_pages(); ?>
				            
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

<?php get_footer(); ?>