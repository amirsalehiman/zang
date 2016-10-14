<?php

// Register the Metabox
function toko_metabox_header_settings_add() {
	add_meta_box( 'tokopress-meta-box-event', esc_html__( 'Header Settings', 'bookie-wp' ), 'toko_metabox_header_settings_output', 'page', 'normal', 'high' );
}
add_action( 'admin_init', 'toko_metabox_header_settings_add', 4 );

// Output the Metabox
function toko_metabox_header_settings_output( $post ) {
	// create a nonce field
	wp_nonce_field( 'toko_metabox_header_settings_nonce', 'toko_metabox_nonce' ); 

	$header_slider = get_post_meta( $post->ID, '_toko_header_slider', true );
	$header_absolute = get_post_meta( $post->ID, '_toko_header_absolute', true );

	$args = array( 'posts_per_page' => -1, 'post_type' => 'toko_slider' );
	$sliders = get_posts( $args );
	?>
	
	<table class="form-table">
		<tr>
			<th>
				<label for="toko_header_slider"><?php esc_html_e( 'Header Slider', 'bookie-wp' ); ?>:</label>
			</th>
			<td>
				<?php if ( !empty($sliders) ) : ?>
					<select name="toko_header_slider" id="toko_header_slider">
						<option value="">-- <?php esc_html_e( 'Choose Slider', 'bookie-wp' ); ?> --</option>
						<?php foreach ($sliders as $slider) : ?>
							<option value="<?php echo esc_attr($slider->ID); ?>" <?php echo ( $header_slider && $header_slider == $slider->ID ? 'selected="selected"' : '' ); ?> >
								<?php echo esc_attr($slider->ID).' - '.esc_attr($slider->post_title); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<br>
				<?php endif; ?>
				<span class="description"><a href="<?php echo admin_url('edit.php?post_type=toko_slider'); ?>"><?php esc_html_e( 'Manage Sliders', 'bookie-wp' ); ?></a></span>
			</td>
	    </tr>
	    <tr>
	    	<th>
	    		<label for="toko_header_absolute"><?php esc_html_e( 'Absolute Header', 'bookie-wp' ) ?>:</label>
	    	</th>
	    	<td>
	    		<select name="toko_header_absolute" id="toko_header_absolute">
	    			<option value=""><?php esc_html_e( 'No', 'bookie-wp' ); ?></option>
	    			<option value="yes" <?php echo ( $header_absolute && $header_absolute == "yes" ? 'selected="sekected"' : '' ); ?>><?php esc_html_e( 'Yes', 'bookie-wp' ); ?></option>
	    		</select>
	    	</td>
	    </tr>
    </table>
    
	<?php
}

// Save the Metabox values
function toko_metabox_header_settings_save( $post_id ) {
	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Verify the nonce. If insn't there, stop the script
	if( !isset( $_POST['toko_metabox_nonce'] ) || !wp_verify_nonce( $_POST['toko_metabox_nonce'], 'toko_metabox_header_settings_nonce' ) ) return;

	// Stop the script if the user does not have edit permissions
	if( !current_user_can( 'edit_post', $post_id ) ) return;

	if( isset( $_POST['toko_header_slider'] ) )
		update_post_meta( $post_id, '_toko_header_slider', wp_kses_post( $_POST['toko_header_slider'] ) );

	if( isset( $_POST['toko_header_absolute'] ) )
		update_post_meta( $post_id, '_toko_header_absolute', wp_kses_post( $_POST['toko_header_absolute'] ) );
}
add_action( 'save_post', 'toko_metabox_header_settings_save' );

add_action( 'admin_head', 'toko_fix_notice_position' );
function toko_fix_notice_position() {
	echo '<style>#update-nag, .update-nag { display: block; float: none; }</style>';
}
