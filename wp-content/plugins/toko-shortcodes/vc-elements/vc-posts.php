<?php
/**
 * Recent Post Shortcode
 * @package Shortcode
 * @author Toko
 */

add_shortcode( 'toko_posts', 'toko_recent_posts_shortcode' );
function toko_recent_posts_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'post_type' 			=> 'post',
		'numbers' 				=> '3',
		'columns'				=> '3',
		'orderby' 				=> 'date',
		'order' 				=> 'desc',
	), $atts ) );

	if( "4" == $columns ) {
		$post_column = "col-xs-12 col-sm-6 col-md-3";
	} else if( "3" == $columns ) {
		$post_column = "col-xs-12 col-sm-6 col-md-4";
	} else if( "2" == $columns ) {
		$post_column = "col-xs-12 col-sm-6 col-md-6";
	} else {
		$post_column = "col-xs-12 col-sm-12 col-md-12";
	}

	$args = array(
			'post_type'				=> $post_type,
			'posts_per_page'		=> (int)($numbers),
			'orderby'				=> $orderby,
			'order'					=> $order,
			'ignore_sticky_posts'	=> 1
		);
	$query = new WP_Query( $args );
	?>

	<?php ob_start(); ?>

	<?php if( $query->have_posts() ) : ?>

	<section class="toko-posts-grid">
		
		<div class="toko-post-row row">

		<?php $i = 0; ?>
		<?php while ( $query->have_posts() ) : ?>
			<?php $query->the_post(); ?>
			<?php $i++; ?>

			<div class="toko-post <?php echo $post_column; ?>">
				<?php if ( $thumbnail = get_the_post_thumbnail( get_the_ID(), 'blog-thumbnail' ) ) : ?>
					<div class="toko-post-image">
						<a href="<?php echo get_permalink(); ?>">
							<?php printf( $thumbnail ) ?>
						</a>
					</div>
				<?php endif; ?>

				<div class="toko-post-detail">
					<h3 class="post-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
					<span class="post-date"><?php echo get_the_date(); ?></span>
					<p><?php echo wp_trim_words( get_the_excerpt(), 21, '&hellip;' ); ?></p>
					<a href="<?php the_permalink(); ?>" rel="bookmark" class="more-link"><i class="ficon-arrow-right"></i></a>
				</div>
			</div>

			<?php if ( $i%$columns == 0 ) : ?>
				<div class="clearfix visible-md visible-lg"></div>
			<?php endif; ?>
			<?php if ( $i%2 == 0 ) : ?>
				<div class="clearfix visible-sm"></div>
			<?php endif; ?>
				
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>

		</div>

	</section>

	<?php endif; ?>

	<?php
	return ob_get_clean();

}

add_action( 'vc_before_init', 'toko_vc_posts' );
function toko_vc_posts() {

	$params = array(
			array(
				'type'			=> 'posttypes',
				'heading'		=> __( 'Post Type', 'tp-shortcodes' ),
				'param_name'	=> 'post_type',
				'value'			=> 'post'
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Number Of Posts', 'tp-shortcodes' ),
				'param_name'	=> 'numbers',
				'value'			=> '3'
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Columns', 'tp-shortcodes' ),
				'description'	=> __( 'How many columns per row', 'tp-shortcodes' ),
				'param_name'	=> 'columns',
				'value'			=> array(
									__( '4 Columns', 'tp-shortcodes' )	=> '4',
									__( '3 Columns', 'tp-shortcodes' )	=> '3',
									__( '2 Columns', 'tp-shortcodes' )	=> '2',
									__( '1 Column', 'tp-shortcodes' )	=> '1',
								),
				'std'			=> '3'
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Order By', 'tp-shortcodes' ),
				'param_name'	=> 'orderby',
				'value'			=> array(
									__( 'Date', 'tp-shortcodes' )			=> 'date',
									__( 'ID', 'tp-shortcodes' )				=> 'ID',
									__( 'Title', 'tp-shortcodes' )			=> 'title',
									__( 'Name', 'tp-shortcodes' )			=> 'name',
									__( 'Comment Count', 'tp-shortcodes' )	=> 'comment_count',
									__( 'Author', 'tp-shortcodes' )			=> 'author',
								),
				'std'			=> 'date'
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Order', 'tp-shortcodes' ),
				'param_name'	=> 'order',
				'value'			=> array(
									__( 'Descending', 'tp-shortcodes' )	=> 'desc',
									__( 'Ascending', 'tp-shortcodes' )	=> 'asc',
								),
				'std'			=> 'desc'
			),
		);

	vc_map( array(
	   'name'				=> __( 'TP - Posts Grid', 'tp-shortcodes' ),
	   'base'				=> 'toko_posts',
	   'class'				=> '',
	   'icon'				=> 'toko_icon',
	   'category'			=> 'TokoPress',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> $params,
	   )
	);
}