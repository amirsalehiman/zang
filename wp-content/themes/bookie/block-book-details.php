<?php
global $product, $post;

$tp_has_book_details = ( $product && ( $product->has_attributes() || ( $product->enable_dimensions_display() && ( $product->has_dimensions() || $product->has_weight() ) ) ) ) ? true : false;

$tp_has_book_authors = ( class_exists( 'Bookie_Author_tax' ) && $tp_book_authors = wp_get_object_terms( get_the_ID(), 'book_author' ) ) ? true : false;

if ( !$tp_has_book_details && !$tp_has_book_authors ) {
	return;
}
?>

<div class="section-book-details clearfix">
    <div class="row">

    	<?php if ( $tp_has_book_details ) : ?>
	        <div class="col-sm-6">
	        	<div class="book-details">
					<h3><?php echo apply_filters( 'toko_title_book_details', esc_html__( 'Book Details', 'bookie-wp' ) ); ?></h3>
					<?php $product->list_attributes(); ?>
	        	</div>
	        </div>
	    <?php endif; ?>

    	<?php if ( $tp_has_book_authors ) : ?>
        <div class="col-sm-6">
            
            <div class="book-authors">
		    	<h3><?php echo apply_filters( 'toko_title_book_authors', esc_html__( 'About The Author', 'bookie-wp' ) ); ?></h3>
				<?php if ( ! empty( $tp_book_authors ) ) : ?>
					<?php foreach( $tp_book_authors as $author ) : ?>

						<?php $thumbnail_id = get_woocommerce_term_meta( $author->term_id, 'thumbnail_id', true ); ?>
						<?php if ( $thumbnail_id ) : ?>
							<?php echo wp_get_attachment_image( $thumbnail_id, 'thumbnail', false, array( 'class' => 'img-circle pull-right', 'alt' => $author->name ) ); ?>
							<!-- <img class="img-circle pull-right" src="<?php echo wp_get_attachment_url( $thumbnail_id ); ?>" alt="<?php echo esc_attr( $author->name ); ?>" width="75" height="75"> -->
						<?php endif; ?>

						<?php if ( $author->name ) : ?>
							<h4><?php echo esc_html( $author->name ); ?></h4>
						<?php endif; ?>
						<?php if ( $author->description ): ?>
							<?php echo wpautop( $author->description ); ?>
						<?php endif; ?>

					<?php endforeach; ?>
				<?php endif; ?>
            </div>
        </div>    
        <?php endif; ?>
                                                
    </div>
</div>
