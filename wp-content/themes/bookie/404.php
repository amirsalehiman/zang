<?php
/**
 * 404
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
		<div id="content" class="main-content-inner" role="main">
			<?php get_template_part( 'content', '404' ); ?>
		</div>
	</div>

	<?php do_action( 'after_inner_main_content' ); ?>

</div>

<?php do_action( 'after_main_content' ); ?>

<?php get_footer(); ?>