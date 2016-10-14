<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Bookie
 * @since Bookie 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

if ( ! function_exists( 'toko_comment' ) ) :
function toko_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'media' ); ?>>
		<div class="comment-body">
			<?php esc_html_e( 'Pingback:', 'bookie-wp' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( 'Edit', 'bookie-wp' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                	<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
                	<?php printf( '<h3 class="fn">%s</h3>', get_comment_author_link() ); ?>
                </div>
                <!-- .comment-author -->

                <div class="comment-metadata">
                    <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'bookie-wp' ), get_comment_date(), get_comment_time() ); ?>
						</time>
					</a> 
					<?php edit_comment_link( '<span class="fa fa-edit"></span> ' . esc_html__( 'Edit', 'bookie-wp' ), '<span class="edit-link">', '</span>' ); ?>
                </div>
                <!-- .comment-metadata -->

            </footer>
            <!-- .comment-meta -->

            <div class="comment-content">

                <?php if ( '0' == $comment->comment_approved ) : ?>
							<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'bookie-wp' ); ?></p>
						<?php endif; ?>
						
						<?php comment_text(); ?>
						
            </div>
            <!-- .comment-content -->
			<?php if ( $tp_reply = get_comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ) ) : ?>
			<div class="reply comment-reply">
				<?php echo str_replace( 'comment-reply-link', 'comment-reply-link', $tp_reply ); ?>
			</div><!-- .reply -->
			<?php endif; ?>

        </article>

	<?php
	endif;
}
endif;

?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
	<div class="comments-area-inner">

		<h2 class="comments-title">
			<?php
				printf( _nx( 'One Comment on &ldquo;%2$s&rdquo;', '%1$s Comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'bookie-wp' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="comment-navigation">
			<h5 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'bookie-wp' ); ?></h5>
			<ul class="pager">
				<li class="previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'bookie-wp' ) ); ?></li>
				<li class="next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'bookie-wp' ) ); ?></li>
			</ul>
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>

		<ol class="comment-list media-list">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use toko_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define toko_comment() and that will be used instead.
				 * See toko_comment() in includes/template-tags.php for more.
				 */
				wp_list_comments( array( 'callback' => 'toko_comment', 'avatar_size' => 56 ) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation">
			<h5 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'bookie-wp' ); ?></h5>
			<ul class="pager">
				<li class="previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'bookie-wp' ) ); ?></li>
				<li class="next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'bookie-wp' ) ); ?></li>
			</ul>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	</div><!-- .comments-area-inner -->
	<?php endif; // have_comments() ?>
	
	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'bookie-wp' ); ?></p>
	<?php endif; ?>
	
	<?php 
	$commenter = wp_get_current_commenter();
	$req      = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	comment_form( array(
		'format'				=> 'html5',
		'fields'				=> array(
			'author' 			=> '<p class="form-group comment-form-author"><input class="form-control" id="author" name="author" type="text" placeholder="' . esc_html__( 'Your Name', 'bookie-wp' ) . ( $req ? ' *' : '' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
			'email'  			=> '<p class="form-group comment-form-email">' . '<input class="form-control" id="email" name="email" type="email" placeholder="' . esc_html__( 'Your Email', 'bookie-wp' ) . ( $req ? ' *' : '' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
			'url'    			=> '<p class="form-group comment-form-url">' . '<input class="form-control" id="url" name="url" type="url" placeholder="' . esc_html__( 'Your Website', 'bookie-wp' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
		),	
		'id_form'				=> 'commentform',
		'id_submit'				=> 'commentsubmit',
		'class_submit'          => 'btn btn-primary',
		'title_reply'			=> esc_html__( 'Leave a Comment', 'bookie-wp' ), 
		'title_reply_to'		=> esc_html__( 'Leave a Comment to %s', 'bookie-wp' ),
		'cancel_reply_link'		=> esc_html__( 'Cancel Reply', 'bookie-wp' ),
		'label_submit'			=> esc_html__( 'Post Comment', 'bookie-wp' ),
		'comment_field'			=> '<p class="form-group comment-form-comment"><textarea class="form-control" id="comment" name="comment" placeholder="' . esc_html__( 'Your Comment', 'bookie-wp' ) . '" cols="45" rows="5" aria-required="true"></textarea></p>', 
		'comment_notes_after'	=> '<div class="form-allowed-tags"><p>' . __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes:', 'bookie-wp' ) . '</p><div class="alert alert-info">' . allowed_tags() . '</div></div>' 
			  
	)); 
	?>

</div><!-- #comments -->
