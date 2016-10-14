<?php

if ( !class_exists( 'woocommerce' ) ) {
	return;
}

if ( toko_get_option( 'toko_book_search_disable' ) ) {
	return;
}

?>

<div class="books-search">
	<div class="container">
	    <form class="" method="get" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	        <div class="row">
            	<div class="<?php echo ( class_exists( 'Bookie_Author_tax' ) ? 'col-sm-6 col-md-3' : 'col-sm-12 col-md-6' ); ?>">
	            	<div class="form-group">
	                    <input name="s" value="<?php echo get_search_query(); ?>" type="text" class="form-control" id="keyword" placeholder="<?php esc_html_e('Book Title','bookie-wp'); ?>">
	                </div>
	            </div>
	            <div class="col-sm-6 col-md-3">
	                <div class="form-group">
						<?php
						wp_dropdown_categories(
							array(
								'name' => 'product_cat',
								'taxonomy' => 'product_cat',
								'value_field' => 'slug',
								'hierarchical' => 1, 
								'show_option_all' => esc_html__( 'Book Category', 'bookie-wp' ),
								'selected' => ( isset($_GET['product_cat']) ? esc_attr( $_GET['product_cat'] ) : '' ),
								'class' => 'form-control',
							)
						);
						?>
						<i class='select-arrow fa fa-angle-down'></i>
	                </div>
	            </div>

		        <?php if( class_exists( 'Bookie_Author_tax' ) ) : ?>
	            <div class="col-sm-6 col-md-3">
	                <div class="form-group">
						<?php
						wp_dropdown_categories(
							array(
								'name' => 'book_author',
								'taxonomy' => 'book_author',
								'value_field' => 'slug',
								'hierarchical' => 1, 
								'show_option_all' => esc_html__( 'Book Author', 'bookie-wp' ),
								'selected' => ( isset($_GET['book_author']) ? esc_attr( $_GET['book_author'] ) : '' ),
								'class' => 'form-control',
							)
						);
						?>
						<i class='select-arrow fa fa-angle-down'></i>
	                </div>
	            </div>
		        <?php endif; ?>
	
	            <div class="col-sm-6 col-md-3">
	                <div class="form-group">
			            <input type="hidden" name="post_type" value="product" />
		                <button type="submit" class="btn btn-primary btn-block">
			            	<i class="simple-icon-magnifier"></i> &nbsp; 
			            	<?php esc_html_e( 'Find Book', 'bookie-wp' ); ?>
			            </button>
			        </div>
	            </div>

	        </div>
	    </form>
	</div>
</div>