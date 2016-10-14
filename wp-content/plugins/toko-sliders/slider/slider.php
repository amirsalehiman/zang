<?php

// Register Custom Post Type
function toko_slider_post_type() {

	$labels = array(
		'name'                => _x( 'Sliders', 'Post Type General Name', 'tp-slider' ),
		'singular_name'       => _x( 'Slider', 'Post Type Singular Name', 'tp-slider' ),
		'menu_name'           => __( 'Sliders', 'tp-slider' ),
		'name_admin_bar'      => __( 'Sliders', 'tp-slider' ),
		'parent_item_colon'   => __( 'Parent Item:', 'tp-slider' ),
		'all_items'           => __( 'All Sliders', 'tp-slider' ),
		'add_new_item'        => __( 'Add New Slider', 'tp-slider' ),
		'add_new'             => __( 'Add New', 'tp-slider' ),
		'new_item'            => __( 'New Slider', 'tp-slider' ),
		'edit_item'           => __( 'Edit Slider', 'tp-slider' ),
		'update_item'         => __( 'Update Slider', 'tp-slider' ),
		'view_item'           => __( 'View Slider', 'tp-slider' ),
		'search_items'        => __( 'Search Slider', 'tp-slider' ),
		'not_found'           => __( 'Not found', 'tp-slider' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'tp-slider' ),
	);
	$args = array(
		'label'               => __( 'Simple Slider', 'tp-slider' ),
		'description'         => __( 'Simple Slider', 'tp-slider' ),
		'labels'              => $labels,
		'supports'            => array( 'title', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-images-alt',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'toko_slider', $args );

}

// Hook into the 'init' action
add_action( 'init', 'toko_slider_post_type', 0 );

add_filter('manage_edit-toko_slider_columns', 'toko_manage_edit_toko_slider_columns');
function toko_manage_edit_toko_slider_columns( $columns ) {
	$columns['shortcode'] = __( 'Shortcode', 'tp-slider' );
	return $columns;
}

add_action( 'manage_toko_slider_posts_custom_column' , 'toko_manage_toko_slider_posts_custom_column', 10, 2 );
function toko_manage_toko_slider_posts_custom_column( $column, $post_id ) {
    if ($column == 'shortcode'){
        echo '<code>[toko_slider id="'.get_the_ID().'"]</code>';
    }
}

function toko_slider_metaboxes( array $meta_boxes ) {

	$slides = array(
		array( 'id' => 'desc',  'name' => __( 'Description', 'tp-slider' ), 'type' => 'text', 'cols' => 6 ),
		array( 'id' => 'title',  'name' => __( 'Title', 'tp-slider' ), 'type' => 'text', 'cols' => 6 ),
		array( 'id' => 'btn_text',  'name' => __( 'Button Text', 'tp-slider' ), 'type' => 'text', 'cols' => 6 ),
		array( 'id' => 'btn_url',  'name' => __( 'Button URL', 'tp-slider' ), 'type' => 'url', 'cols' => 6 ),
		array( 'id' => 'image', 'name' => __( 'Background Image', 'tp-slider' ), 'type' => 'image', 'show_size' => false ),
	);

	$meta_boxes[] = array(
		'title' => __( 'Edit Slides', 'tp-slider' ),
		'pages' => 'toko_slider',
		'fields' => array(
			array(
				'id' => '_info',
				'name' => __( 'Shortcode', 'tp-slider' ),
				'type' => 'slider_shortcode',
			),
			array(
				'id' => '_slides',
				'name' => '',
				'type' => 'group',
				'repeatable' => true,
				'sortable' => true,
				'fields' => $slides,
				'desc' => ''
			),
		)
	);

	return $meta_boxes;

}
add_filter( 'cmb_meta_boxes', 'toko_slider_metaboxes' );

add_shortcode( 'toko_slider', 'toko_shortcode_slider' );
function toko_shortcode_slider( $atts ) {
	extract( shortcode_atts( array(
		'id'	=> '',
		'slug'	=> '',
	), $atts ) );

	$slides = get_post_meta( $id, '_slides' );
	if ( empty( $slides ) )
		return;

	$output = '';

	$count = 0;
	foreach ( $slides as $slide ) {
		$output_slide = '';
		if ( trim( $slide['desc'] ) ) {
			$output_slide .= '<p class="toko-slide-desc">'.$slide['desc'].'</p>';
		}
		if ( trim( $slide['title'] ) ) {
			$output_slide .= '<h2 class="toko-slide-title">'.$slide['title'].'</h2>';
		}
		if ( trim( $slide['btn_text'] ) ) {
			$url = $slide['btn_url'] ? esc_url( $slide['btn_url'] ) : '#';
			$output_slide .= '<a class="toko-slide-button" href="'.$url.'">'.$slide['btn_text'].'</a>';
		}
		if ( $output_slide ) {
			$count++;
			$style = '';
			if ( $slide['image'] ) {
				$image = wp_get_attachment_url( $slide['image'] );
				if ( $image ) {
					$style = 'style="background-image:url('.$image.');background-size:cover;background-position:center right;background-repeat:no-repeat;" ';
				}
			}
			$output .= '<div class="toko-slide" '.$style.'><div class="toko-slide-inner"><div class="toko-slide-detail">'.$output_slide.'</div></div></div>';
		}
	}

	if ( $output ) {
		$class = $count > 1 ? 'toko-slider-active owl-carousel owl-theme owl-loaded' : '';
		$output = '<div class="toko-slider-wrap"><div class="toko-slides '.$class.'">'.$output.'</div></div>';
		if ( $count > 1 ) {
			wp_enqueue_script( 'toko-owlcarousel' );
			wp_enqueue_style( 'toko-owlcarousel' );
			$js_code = "$('.toko-slider-active').owlCarousel({items:1, loop: true, nav:false, lazyLoad: true, autoplay: true, autoplayHoverPause: true, dots: true, stopOnHover: true, animateOut: 'fadeOut' });";
			toko_enqueue_js( $js_code );
		}
	}

	return $output;
}

class Toko_Slider_Shortcode_CMB_Field extends CMB_Field {

    public function html() {
        ?>
        <p>
            <?php echo '<code>[toko_slider id="'.get_the_ID().'"]</code>'; ?>
        </p>
        <?php
    }

}

add_filter( 'cmb_field_types', function( $cmb_field_types ) {
    $cmb_field_types['slider_shortcode'] = 'Toko_Slider_Shortcode_CMB_Field';
    return $cmb_field_types;
} );


add_action( 'vc_before_init', 'toko_vc_sliders' );
function toko_vc_sliders() {

	$args = array( 'numberposts' => '-1', 'post_type' => 'toko_slider' );
	$sliders = get_posts( $args );

	if ( empty( $sliders ) )
		return;

	$sliders_arr = array();
	foreach( $sliders as $slider ) {
		$sliders_arr[ $slider->post_title . ' (ID=' . $slider->ID . ')' ] = $slider->ID;
	}

	$params = array(
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Slider ID', 'tp-slider' ),
				'param_name'	=> 'id',
				'value'			=> $sliders_arr,
			),
		);

	vc_map( array(
	   'name'				=> __( 'TP - Slider', 'tp-slider' ),
	   'base'				=> 'toko_slider',
	   'class'				=> '',
	   'icon'				=> 'toko_icon',
	   'category'			=> 'TokoPress',
	   'admin_enqueue_js' 	=> '',
	   'admin_enqueue_css' 	=> '',
	   'params'				=> $params,
	   )
	);
}