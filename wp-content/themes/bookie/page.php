<?php
/**
 * The default template for displaying content
 *
 * Used for both page
 *
 * @package WordPress
 * @subpackage Bookie
 * @since Bookie 1.0
 */

get_header(); ?>

<?php do_action( 'before_main_content' ); ?>

<div class="main-content">

	<?php do_action( 'before_inner_main_content' ); ?>

	<div class="main-content-container container">
		<div class="row">

			<div class="col-md-8">
				<div id="content" class="main-content-inner" role="main">

				<?php if( have_posts() ) : ?>
					<?php while( have_posts() ): the_post(); ?>
						
						<article id="post-<?php echo get_the_ID(); ?>" <?php post_class(); ?>>

				            <div class="entry-content">

			                    <?php the_content(); ?>
			                    <?php toko_link_pages(); ?>
				            
				            </div>

						</article>

                        <?php if ( apply_filters( "toko_{$post->post_type}_comment", true ) ) : ?>
							<?php if ( comments_open() || '0' != get_comments_number() ) comments_template(); ?>
						<?php endif; ?>

					<?php endwhile; ?>
				<?php else : ?>
					<?php get_template_part( 'content', '404' ); ?>
				<?php endif; ?>

				<?php toko_pagination(); ?>

				</div>
			</div>

			<?php get_sidebar(); ?>

		</div>
	</div>

	<?php do_action( 'after_inner_main_content' ); ?>

</div>

<?php do_action( 'after_main_content' ); ?>

<?php get_footer(); ?>