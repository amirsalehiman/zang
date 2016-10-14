<?php
/**
 * Post Formats UI
 *
 * @package WordPress
 * @subpackage Toko
 */

if ( ! is_admin() )
	return;

global $pagenow;
if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) )
	return;

/**
 * Format buttons placeholder
 */
add_action( 'edit_form_top', 'toko_post_formats_ui_buttons' );
if ( ! function_exists( 'toko_post_formats_ui_buttons' ) ) :
function toko_post_formats_ui_buttons() { 
	if ( ! current_theme_supports( 'post-formats' ) )
		return;

	$type = get_post_type();
	if ( ! post_type_supports( $type, 'post-formats' ) )
		return;

	echo '<div id="formatdiv-placeholder"></div>';
}
endif;

/**
 * Image and gallery placeholder
 */
add_action( 'edit_form_after_title', 'toko_post_formats_ui_placeholder', 10 );
if ( ! function_exists( 'toko_post_formats_ui_placeholder' ) ) :
function toko_post_formats_ui_placeholder() { 
	if ( ! current_theme_supports( 'post-formats' ) )
		return;

	$type = get_post_type();
	if ( ! post_type_supports( $type, 'post-formats' ) )
		return;

	echo '<div id="postimagediv-placeholder"></div>';
	echo '<div id="postgallerydiv-placeholder"></div>';
}
endif;

/**
 * Enqueue JS & CSS
 */
add_action( 'admin_enqueue_scripts', 'toko_post_formats_ui_enqueue' );
function toko_post_formats_ui_enqueue() {
	if ( ! current_theme_supports( 'post-formats' ) )
		return;

	$type = get_post_type();
	if ( ! post_type_supports( $type, 'post-formats' ) )
		return;

	wp_enqueue_style( 'post-formats-ui', get_template_directory_uri() . '/inc/core/css/post-formats.css', null, '' );
	wp_enqueue_script( 'post-formats-ui', get_template_directory_uri() . '/inc/core/js/post-formats.js', array( 'jquery' ), '', true );
}

/**
 * Metaboxes
 */
add_action( 'admin_menu', 'toko_post_formats_ui_metaboxes' );
if ( ! function_exists( 'toko_post_formats_ui_metaboxes' ) ) :
function toko_post_formats_ui_metaboxes() { 
	if ( ! current_theme_supports( 'post-formats' ) )
		return;

	$type = 'post';
	if ( ! post_type_supports( $type, 'post-formats' ) )
		return;

	add_meta_box( 'postgallerydiv', esc_html__( 'Gallery Images', 'bookie-wp' ), 'toko_post_formats_ui_gallery_box', $type, 'side' );
}
endif;

/**
 * Gallery images metabox
 * Credits: WooCommerce Product Gallery Images
 */
if ( ! function_exists( 'toko_post_formats_ui_gallery_box' ) ) :
function toko_post_formats_ui_gallery_box( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'toko_gallery_nonce' );
	$type = get_post_type() ? get_post_type() : 'post';
	?>
	<div id="gallery_images_container">
		<ul class="gallery_images">
			<?php
				if ( metadata_exists( $type, $post->ID, '_format_gallery_ids' ) ) {
					$gallery_image = get_post_meta( $post->ID, '_format_gallery_ids', true );
				} else {
					// Backwards compat
					$attachment_ids = get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids' );
					$attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
					$gallery_image = implode( ',', $attachment_ids );
				}

				$attachments = array_filter( explode( ',', $gallery_image ) );

				if ( $attachments ) {
					foreach ( $attachments as $attachment_id ) {
						echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
							' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '
							<ul class="actions">
								<li><a href="#" class="delete">' . esc_html__( 'Delete', 'bookie-wp' ) . '</a></li>
							</ul>
						</li>';
					}
				}
			?>
		</ul>

		<input type="hidden" id="gallery_image_ids" name="gallery_image_ids" value="<?php echo esc_attr( $gallery_image ); ?>" />

	</div>
	<p class="add_gallery_images hide-if-no-js">
		<a href="#" data-choose="<?php esc_html_e( 'Add Images to Gallery', 'bookie-wp' ); ?>" data-update="<?php esc_html_e( 'Add to gallery', 'bookie-wp' ); ?>" data-delete="<?php esc_html_e( 'Delete image', 'bookie-wp' ); ?>" data-text="<?php esc_html_e( 'Delete', 'bookie-wp' ); ?>"><?php esc_html_e( 'Add gallery images', 'bookie-wp' ); ?></a>
	</p>
	<?php
}
endif;

/**
 * Save gallery images
 */
add_action( 'save_post', 'toko_post_formats_ui_gallery_save', 10, 2 );
if ( ! function_exists( 'toko_post_formats_ui_gallery_save' ) ) :
function toko_post_formats_ui_gallery_save( $post_id, $post ) {
	if ( !isset( $_POST['toko_gallery_nonce'] ) || !wp_verify_nonce( $_POST['toko_gallery_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

	$attachment_ids = array_filter( explode( ',', esc_attr( $_POST['gallery_image_ids'] ) ) );
	update_post_meta( $post_id, '_format_gallery_ids', implode( ',', $attachment_ids ) );

}
endif;

