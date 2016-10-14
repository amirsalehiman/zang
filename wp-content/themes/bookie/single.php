<?php get_header(); ?>

<?php do_action( 'before_main_content' ); ?>

<div class="main-content">

	<?php do_action( 'before_inner_main_content' ); ?>

	<div class="main-content-container container">
		<div class="row">

			<div class="col-md-8">
				<div id="content" class="main-content-inner" role="main">

				<?php if( have_posts() ) : ?>

					<?php while( have_posts() ): the_post(); ?>

						<?php if ( get_post_format() != '' ) : ?>
                            <?php get_template_part( 'content', get_post_format() ); ?>
                        <?php else : ?>
                            <?php get_template_part( 'content', get_post_type() ); ?>
                        <?php endif; ?>

					    <?php // get_template_part( 'block-share' ); ?>

                        <?php
						the_post_navigation( array(
							'prev_text' => '<div class="desc-control col-xs-6 pull-left">' .
								'<i class="icon-label drip-icon-chevron-left"></i>' .
								'<span class="post-title" aria-hidden="true">%title</span> ' .
								'<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'bookie-wp' ) . '</span> ' .
								'<span class="post-date">%date</span>' .
								'</div>',
							'next_text' => '<div class="desc-control col-xs-6 text-right pull-right">' .
								'<i class="icon-label drip-icon-chevron-right"></i>' .
								'<span class="post-title" aria-hidden="true">%title</span> ' .
								'<span class="screen-reader-text">' . esc_html__( 'Next post:', 'bookie-wp' ) . '</span> ' .
								'<span class="post-date">%date</span>' .
								'</div>',
						) );
						?>

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