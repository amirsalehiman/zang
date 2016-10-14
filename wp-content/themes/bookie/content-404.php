<div class="entry post-not-found">

	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

		<h1 class="entry-title"><?php esc_html_e( 'Not Found', 'bookie-wp' ); ?></h1>

		<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'bookie-wp' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

	<?php elseif ( is_search() ) : ?>

		<h1 class="entry-title"><?php esc_html_e( 'No Results', 'bookie-wp' ); ?></h1>

		<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'bookie-wp' ); ?></p>

		<?php get_search_form(); ?>

	<?php else : ?>

		<h1 class="entry-title"><?php esc_html_e( 'Not Found', 'bookie-wp' ); ?></h1>

		<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'bookie-wp' ); ?></p>

		<?php get_search_form(); ?>

	<?php endif; ?>

</div>