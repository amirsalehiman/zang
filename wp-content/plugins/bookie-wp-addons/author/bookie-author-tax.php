<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Bookie_Author_tax {

	public function __construct() {
		
		// hook into the init action and call create_book_taxonomies when it fires
		add_action( 'init', array( $this, 'bookie_addon_register_author_taxonomy' ), 0 );

		add_action( 'book_author_add_form_fields', array( $this, 'add_book_author_fields' ) );
		add_action( 'book_author_edit_form_fields', array( $this, 'edit_book_author_fields' ), 10 );
		add_filter( 'manage_edit-book_author_columns', array( $this, 'bookie_addon_book_author_columns' ) );
		add_filter( 'manage_book_author_custom_column', array( $this, 'bookie_addon_book_author_column' ), 10, 3 );
		add_action( 'created_term', array( $this, 'bookie_addon_save_book_author_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'bookie_addon_save_book_author_fields' ), 10, 3 );

	}

	// create two taxonomies, genres and authors for the post type "book"
	public function bookie_addon_register_author_taxonomy() {
		// Add new taxonomy, NOT hierarchical (like tags)
		$labels = array(
			'name'                       => _x( 'Book Authors', 'taxonomy general name', 'bookie-wp-addons' ),
			'singular_name'              => _x( 'Book Author', 'taxonomy singular name', 'bookie-wp-addons' ),
			'search_items'               => __( 'Search Book Authors', 'bookie-wp-addons' ),
			'popular_items'              => __( 'Popular Book Authors', 'bookie-wp-addons' ),
			'all_items'                  => __( 'All Book Authors', 'bookie-wp-addons' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Book Author', 'bookie-wp-addons' ),
			'update_item'                => __( 'Update Book Author', 'bookie-wp-addons' ),
			'add_new_item'               => __( 'Add New Book Author', 'bookie-wp-addons' ),
			'new_item_name'              => __( 'New Book Author Name', 'bookie-wp-addons' ),
			'separate_items_with_commas' => __( 'Separate book authors with commas', 'bookie-wp-addons' ),
			'add_or_remove_items'        => __( 'Add or remove book authors', 'bookie-wp-addons' ),
			'choose_from_most_used'      => __( 'Choose from the most used book authors', 'bookie-wp-addons' ),
			'not_found'                  => __( 'No book authors found.', 'bookie-wp-addons' ),
			'menu_name'                  => __( 'Book Authors', 'bookie-wp-addons' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'book-author' ),
		);

		register_taxonomy( 'book_author', 'product', $args );
	}

	public function add_book_author_fields() {
		wp_enqueue_media();
		?>

		<div class="form-field">
			<label><?php _e( 'Thumbnail', 'bookie-wp-addons' ); ?></label>
			<div id="book_author_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
			<div style="line-height: 60px;">
				<input type="hidden" id="book_author_thumbnail_id" name="book_author_thumbnail_id" />
				<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'bookie-wp-addons' ); ?></button>
				<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'bookie-wp-addons' ); ?></button>
			</div>
			<script type="text/javascript">

				// Only show the "remove image" button when needed
				if ( ! jQuery( '#book_author_thumbnail_id' ).val() ) {
					jQuery( '.remove_image_button' ).hide();
				}

				// Uploading files
				var file_frame;

				jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php _e( "Choose an image", "bookie-wp-addons" ); ?>',
						button: {
							text: '<?php _e( "Use image", "bookie-wp-addons" ); ?>'
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						var attachment = file_frame.state().get( 'selection' ).first().toJSON();

						jQuery( '#book_author_thumbnail_id' ).val( attachment.id );
						jQuery( '#book_author_thumbnail' ).find( 'img' ).attr( 'src', attachment.sizes.thumbnail.url );
						jQuery( '.remove_image_button' ).show();
					});

					// Finally, open the modal.
					file_frame.open();
				});

				jQuery( document ).on( 'click', '.remove_image_button', function() {
					jQuery( '#book_author_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
					jQuery( '#book_author_thumbnail_id' ).val( '' );
					jQuery( '.remove_image_button' ).hide();
					return false;
				});

			</script>
			<div class="clear"></div>
		</div>

		<?php
	}

	public function edit_book_author_fields( $term ) {
		wp_enqueue_media();

		$thumbnail_id = absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );

		if ( $thumbnail_id ) {
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		} else {
			$image = wc_placeholder_img_src();
		}
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'bookie-wp-addons' ); ?></label></th>
			<td>
				<div id="book_author_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="book_author_thumbnail_id" name="book_author_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
					<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'bookie-wp-addons' ); ?></button>
					<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'bookie-wp-addons' ); ?></button>
				</div>
				<script type="text/javascript">

					// Only show the "remove image" button when needed
					if ( '0' === jQuery( '#book_author_thumbnail_id' ).val() ) {
						jQuery( '.remove_image_button' ).hide();
					}

					// Uploading files
					var file_frame;

					jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php _e( "Choose an image", "bookie-wp-addons" ); ?>',
							button: {
								text: '<?php _e( "Use image", "bookie-wp-addons" ); ?>'
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							var attachment = file_frame.state().get( 'selection' ).first().toJSON();

							jQuery( '#book_author_thumbnail_id' ).val( attachment.id );
							jQuery( '#book_author_thumbnail' ).find( 'img' ).attr( 'src', attachment.sizes.thumbnail.url );
							jQuery( '.remove_image_button' ).show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					jQuery( document ).on( 'click', '.remove_image_button', function() {
						jQuery( '#book_author_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
						jQuery( '#book_author_thumbnail_id' ).val( '' );
						jQuery( '.remove_image_button' ).hide();
						return false;
					});

				</script>
				<div class="clear"></div>
			</td>
		</tr>
		<?php
	}

	public function bookie_addon_book_author_columns( $columns ) {
		$new_columns          = array();
		$new_columns['cb']    = $columns['cb'];
		$new_columns['thumb'] = __( 'Image', 'bookie-wp-addons' );

		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}

	public function bookie_addon_book_author_column( $columns, $column, $id ) {

		if ( 'thumb' == $column ) {

			$thumbnail_id = get_woocommerce_term_meta( $id, 'thumbnail_id', true );

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = wc_placeholder_img_src();
			}

			// Prevent esc_url from breaking spaces in urls for image embeds
			// Ref: http://core.trac.wordpress.org/ticket/23605
			$image = str_replace( ' ', '%20', $image );

			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'bookie-wp-addons' ) . '" class="wp-post-image" height="48" width="48" />';

		}

		return $columns;
	}

	public function bookie_addon_save_book_author_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( isset( $_POST['book_author_thumbnail_id'] ) && 'book_author' === $taxonomy ) {
			update_woocommerce_term_meta( $term_id, 'thumbnail_id', absint( $_POST['book_author_thumbnail_id'] ) );
		}
	}

}

new Bookie_Author_tax();

function bookie_addon_get_author_name() {
	$authors = wp_get_object_terms( get_the_ID(), 'book_author' );

	if ( ! empty( $authors ) ) {
		shuffle($authors);
		foreach( $authors as $author ){
			echo '<span class="person-name vcard">';
			echo $author->name;
			echo '</span>';
			return;
		}
	}
}