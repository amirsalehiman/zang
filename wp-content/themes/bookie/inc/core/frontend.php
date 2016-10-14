<?php
/**
 * Frontend related functions
 *
 * @package WordPress
 * @subpackage Toko
 */

/**
 * Remove Emoji
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Charset 
 */
add_action( 'wp_head', 'tokopress_wphead_charset', 0);
function tokopress_wphead_charset() {
?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php 
}

/**
 * Filter WP Title
 */
add_filter( 'wp_title', 'toko_wp_title', 10, 2 );
if ( ! function_exists( 'toko_wp_title' ) ) :
function toko_wp_title( $title, $sep ) {

	if ( is_feed() )
		return $title;

	// Add the blog name & description for the home/front page.
	if ( is_home() || is_front_page() ) {
		$title = get_bloginfo( 'name' );
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description )
			$title .= " $sep $site_description";
	}

	return $title;
}
endif;

/**
 * Body class
 */
add_filter( 'body_class', 'toko_body_class' );
if ( ! function_exists( 'toko_body_class' ) ) :
function toko_body_class( $classes ) {
	$classes[] = 'header-large';
	if ( !is_rtl() ) {
		$classes[] = 'ltr';
	}
	return $classes;
}
endif;

/**
 * Post class
 */
add_filter( 'post_class', 'toko_post_class', 50 );
if ( ! function_exists( 'toko_post_class' ) ) :
function toko_post_class( $classes ) {
	if ( in_array( 'hentry', $classes ) ) {
		$classes = array_diff( $classes, array( 'hentry' ) );
		$classes[] = 'entry';
	}
	return $classes;
}
endif;

/**
 * Entry meta - Post Type
 */
if ( ! function_exists( 'toko_meta_type' ) ) :
function toko_meta_type( $before = '', $after = '' ) {
	$object = get_post_type_object( get_post_type() );
	if ( isset( $object->name ) ) $type = $object->name;
	if ( isset( $object->labels->name ) ) $type = $object->labels->name;
	if ( isset( $object->labels->singular_name ) ) $type = $object->labels->singular_name;
	if ( isset( $type ) ) {
		echo '<span class="entry-meta-item entry-meta-type">'.$before.$type.$after.'</span>';
	}
}
endif;

/**
 * Entry meta - Post Categories
 */
if ( ! function_exists( 'toko_meta_categories' ) ) :
function toko_meta_categories( $before = '', $after = '', $separator = ', ' ) {
	echo get_the_term_list( get_the_ID(), 'category', '<span class="entry-meta-item entry-meta-categories">'.$before, $separator, $after.'</span>' );
}
endif;

/**
 * Entry meta - Post Tags
 */
if ( ! function_exists( 'toko_meta_tags' ) ) :
function toko_meta_tags( $before = '', $after = '', $separator = ', ' ) {
	echo get_the_term_list( get_the_ID(), 'post_tag', '<span class="entry-meta-item entry-meta-tags">'.$before, $separator, $after.'</span>' );
}
endif;

/**
 * Entry meta - Date
 */
if ( ! function_exists( 'toko_meta_date' ) ) :
function toko_meta_date( $before = '', $after = '' ) {
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$meta_time = '<time class="published" datetime="%1$s">%2$s</time>';
		$meta_time .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}
	else {
		$meta_time = '<time class="published updated" datetime="%1$s">%2$s</time>';
	}
	$meta_time = '<span class="entry-meta-item entry-meta-time">'.$before.'<a href="%5$s" rel="bookmark">'.$meta_time.'</a>'.$after.'</span>';
	printf( $meta_time,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() ),
		esc_url( get_permalink() )
	);
}
endif;

/**
 * Entry meta - Author
 */
if ( ! function_exists( 'toko_meta_author' ) ) :
function toko_meta_author( $before = '', $after = '' ) {
	echo '<span class="entry-meta-item entry-meta-author author vcard">'.$before.'<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" class="post-author-link url fn n" rel="author"><span>'.get_the_author().'</span></a>'.$after.'</span>'; 
}
endif;

/**
 * Entry meta - Image Size
 */
if ( ! function_exists( 'toko_meta_imagesize' ) ) :
function toko_meta_imagesize( $before = '', $after = '' ) {
	$metadata = wp_get_attachment_metadata();
	echo '<span class="entry-meta-item entry-meta-imagesize">'.$before.'<a href="'.esc_url( wp_get_attachment_url() ).'" title="Link to full-size image">'.$metadata['width'].' &times; '.$metadata['height'].'</a>'.$after.'</span>';
}
endif;

/**
 * Entry meta - Parent
 */
if ( ! function_exists( 'toko_meta_parent' ) ) :
function toko_meta_parent( $before = '', $after = '' ) {
	global $post;
	echo '<span class="entry-meta-item entry-meta-parent">'.$before.'<a href="'.esc_url( get_permalink( $post->post_parent ) ).'" title="Return to '.esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ).'" rel="gallery">'.get_the_title( $post->post_parent ).'</a>'.$after.'</span>';
}
endif;

/**
 * Entry meta - Edit
 */
if ( ! function_exists( 'toko_meta_edit' ) ) :
function toko_meta_edit( $before = '', $after = '' ) {
	edit_post_link( esc_html__( 'Edit', 'bookie-wp' ), '<span class="entry-meta-item entry-meta-edit">'.$before, $after.'</span>' );
}
endif;

/**
 * Content filter - add Table class
 */
add_filter( 'the_content', 'toko_the_content' );
if ( ! function_exists( 'toko_the_content' ) ) :
function toko_the_content( $content ) {
	$content = str_replace( '<table>', '<table class="table table-bordered">', $content );
	return $content;
}
endif;

/**
 * Comment filter - add Table class
 */
add_filter( 'comment_text', 'toko_comment_text' );
if ( ! function_exists( 'toko_comment_text' ) ) :
function toko_comment_text( $content ) {
	$content = str_replace( '<table>', '<table class="table table-bordered">', $content );
	return $content;
}
endif;

/**
 * More Link filter - add Bootstrap primary button class
 */
add_filter( 'the_content_more_link', 'toko_content_more_link' );
if ( ! function_exists( 'toko_content_more_link' ) ) :
function toko_content_more_link( $content ) {
	$content = str_replace( 'more-link', 'more-link btn btn-primary', $content );
	return $content;
}
endif;

/**
 * Password Form filter - BootStrap-ed
 */
add_filter( 'the_password_form', 'toko_the_password_form' );
if ( ! function_exists( 'toko_the_password_form' ) ) :
function toko_the_password_form( $output ) {
	preg_match_all('/\d+/', $output, $matches);
	$id = $matches[0][0];
	$label = 'pwbox-' . $id;
	$output = '<p>' . esc_html__( 'This content is password protected. To view it please enter your password below:', 'bookie-wp' ) . '</p>' .
		'<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="form-inline post-password-form" method="post">' . 
		'<div class="form-group">' . 
		'<label for="' . $label . '" class="sr-only">' . esc_html__( 'Password:', 'bookie-wp'  ) . '</label>' . 
		'<input name="post_password" id="' . $label . '" type="password" size="20" class="form-control" placeholder="'.__( 'Password', 'bookie-wp'  ).'"/>' . 
		'</div>' . 
		'<div class="form-group"><button class="btn btn-default" type="submit">' . esc_attr__( 'Submit', 'bookie-wp' ) . '</button></div>' . 
		'</form>';
	return $output;
}
endif;

/**
 * Prints the attached image with a link to the next attached image.
 */
if ( ! function_exists( 'toko_the_attached_image' ) ) :
function toko_the_attached_image() {
	$post                = get_post();
	$attachment_size     = apply_filters( 'toko_attachment_size', array( 1200, 1200 ) );
	$next_attachment_url = wp_get_attachment_url();

	/**
	 * Grab the IDs of all the image attachments in a gallery so we can get the
	 * URL of the next adjacent image in a gallery, or the first image (if
	 * we're looking at the last image in a gallery), or, in a gallery of one,
	 * just the link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
	}

	printf( '<a class="thumbnail" href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

/**
 * Link Pages filter - BootStrap-ed
 */
if ( ! function_exists( 'toko_link_pages' ) ) :
function toko_link_pages() {
	add_filter( 'wp_link_pages_link', 'toko_link_pages_link' );
	wp_link_pages( array(
		'before' => '<ul class="pagination page-links"><li class="disabled"><span>' . esc_html__( 'Pages:', 'bookie-wp' ) . '</span></li>',
		'after'  => '</ul>',
	) );
	remove_filter( 'wp_link_pages_link', 'toko_link_pages_link' );
}
endif;

/**
 * Link Pages link - BootStrap-ed
 */
if ( ! function_exists( 'toko_link_pages_link' ) ) :
function toko_link_pages_link( $link ) {
	if ( strpos($link, '</a>') === false ) 
		return '<li class="active"><span>' . $link . '</span></li>';
	else
		return '<li>' . $link . '</li>';
}
endif;

/**
 * Pagination - BootStrap-ed
 */
if ( ! function_exists( 'toko_pagination' ) ) :
function toko_pagination( $query = '' ) {
	global $wp_query;
	if ( ! $query ) {
		$query = $wp_query;
	}
	if( $query->max_num_pages <= 1 ) 
		return;
	echo '<nav class="paging-navigation">';
	echo '<h4 class="sr-only">'.__( 'Post navigation', 'bookie-wp' ).'</h4>';
	echo toko_paginate_links( array(
		'base' 			=> str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
		'format' 		=> '',
		'current' 		=> max( 1, get_query_var('paged') ),
		'total' 		=> $query->max_num_pages,
		'type'			=> 'list',
		'prev_text' 	=> '<i class="fa fa-chevron-left"></i>',
		'next_text' 	=> '<i class="fa fa-chevron-right"></i>',
	) );
	echo '</nav>';
}
endif;

/**
 * Paginate links - BootStrap-ed
 */
if ( ! function_exists( 'toko_paginate_links' ) ) :
function toko_paginate_links( $args = '' ) {
	$defaults = array(
		'base' => '%_%', // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
		'format' => '?page=%#%', // ?page=%#% : %#% is replaced by the page number
		'total' => 1,
		'current' => 0,
		'show_all' => false,
		'prev_next' => true,
		'prev_text' => '&laquo;',
		'next_text' => '&raquo;',
		'end_size' => 1,
		'mid_size' => 2,
		'type' => 'list',
		'add_args' => false, // array of query args to add
		'add_fragment' => ''
	);

	$args = wp_parse_args( $args, $defaults );
	extract($args, EXTR_SKIP);

	// Who knows what else people pass in $args
	$total = (int) $total;
	if ( $total < 2 )
		return;
	$current  = (int) $current;
	$end_size = 0  < (int) $end_size ? (int) $end_size : 1; // Out of bounds?  Make it the default.
	$mid_size = 0 <= (int) $mid_size ? (int) $mid_size : 2;
	$add_args = is_array($add_args) ? $add_args : false;
	$r = '';
	$page_links = array();
	$n = 0;
	$dots = false;

	if ( $prev_next && $current && 1 < $current ) :
		$link = str_replace('%_%', 2 == $current ? '' : $format, $base);
		$link = str_replace('%#%', $current - 1, $link);
		if ( $add_args )
			$link = add_query_arg( $add_args, $link );
		$link .= $add_fragment;
		$page_links[] = '<li class="pagination-prev"><a class="prev page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $prev_text . '</a></li>';
	endif;
	for ( $n = 1; $n <= $total; $n++ ) :
		$n_display = number_format_i18n($n);
		if ( $n == $current ) :
			$page_links[] = "<li class='pagination-list active'><span class='page-numbers current'>$n_display</span></li>";
			$dots = true;
		else :
			if ( $show_all || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
				$link = str_replace('%_%', 1 == $n ? '' : $format, $base);
				$link = str_replace('%#%', $n, $link);
				if ( $add_args )
					$link = add_query_arg( $add_args, $link );
				$link .= $add_fragment;
				$page_links[] = "<li class='pagination-list'><a class='page-numbers' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>$n_display</a></li>";
				$dots = true;
			elseif ( $dots && !$show_all ) :
				$page_links[] = '<li class="pagination-dots disabled"><span class="page-numbers dots">' . esc_html__( '&hellip;', 'bookie-wp' ) . '</span></li>';
				$dots = false;
			endif;
		endif;
	endfor;
	if ( $prev_next && $current && ( $current < $total || -1 == $total ) ) :
		$link = str_replace('%_%', $format, $base);
		$link = str_replace('%#%', $current + 1, $link);
		if ( $add_args )
			$link = add_query_arg( $add_args, $link );
		$link .= $add_fragment;
		$page_links[] = '<li class="pagination-next"><a class="next page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $next_text . '</a></li>';
	endif;
	/* always return list */
	$r .= "<ul class='pagination'>";
	$r .= join("", $page_links);
	$r .= "</ul>\n";
	return $r;
}
endif;

/**
 * Post Navigation - BootStrap-ed
 */
if ( ! function_exists( 'toko_post_navigation_link' ) ) :
function toko_post_navigation_link() {
	global $wp_query, $post;

	if ( ! is_single() )
		return;
		
	// Don't print empty markup on single pages if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous )
		return;

	echo '<nav class="post-navigation clearfix">';
	echo '<h4 class="sr-only">'.__( 'Post navigation', 'bookie-wp' ).'</h4>';
	echo '<ul class="pager">';
	previous_post_link( '<li class="previous">%link</li>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'bookie-wp' ) . '</span> %title' );
	next_post_link( '<li class="next">%link</li>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'bookie-wp' ) . '</span>' );
	echo '</ul>';
	echo '</nav>';
}
endif;

/**
 * Disable recent comments styling
 * http://www.narga.net/how-to-remove-or-disable-comment-reply-js-and-recentcomments-from-wordpress-header
 */
add_action( 'widgets_init', 'toko_remove_recent_comments_style' );
function toko_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}

/**
 * Queue some JavaScript code to be output in the footer.
 * @param string $code
 * @source WooCommerce Core Function
 */
function toko_enqueue_js( $code ) {
	global $toko_queued_js;

	if ( empty( $toko_queued_js ) ) {
		$toko_queued_js = '';
	}

	$toko_queued_js .= "\n" . $code . "\n";
}
/**
 * Output any queued javascript code in the footer.
 */
add_action( 'wp_footer', 'toko_print_js', 50 );
function toko_print_js() {
	global $toko_queued_js;

	if ( ! empty( $toko_queued_js ) ) {

		echo "<!-- TokoPress JavaScript -->\n<script type=\"text/javascript\">\njQuery(function($) {";

		// Sanitize
		$toko_queued_js = wp_check_invalid_utf8( $toko_queued_js );
		$toko_queued_js = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", $toko_queued_js );
		$toko_queued_js = str_replace( "\r", '', $toko_queued_js );

		printf( '%s', $toko_queued_js . "});\n</script>\n" );

		unset( $toko_queued_js );
	}
}

add_filter( 'navigation_markup_template', 'toko_filter_navigation_template', 10, 2 );
function toko_filter_navigation_template( $template, $class ) {
	$template = '
	<nav class="navigation %1$s">
		<h2 class="screen-reader-text">%2$s</h2>
		<div class="nav-links">%3$s</div>
	</nav>';

	return $template;
}
