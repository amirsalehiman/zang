<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
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
						<?php get_template_part( 'content', get_post_format() ); ?>
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