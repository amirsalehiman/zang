<?php

add_action( 'customize_register', 'toko_customize_register_woocommerce' );
function toko_customize_register_woocommerce( $wp_customize ) {

	if ( ! isset( $wp_customize ) ) {
		return;
	}

	$wp_customize->add_panel(
		'toko_woocommerce',
		array(
			'priority' 			=> 180,
			'capability' 		=> 'edit_theme_options',
			'theme_supports'	=> '',
			'title' 			=> esc_html__( 'WooCommerce', 'bookie-wp' ),
			'description' 		=> '',
		)
	);

		$wp_customize->add_section(
			'toko_woocommerce_shop',
			array(
				'priority' 			=> 10,
				'title'				=> esc_html__( 'Shop (Product Grid)', 'bookie-wp' ),
				'description'		=> '',
				'panel'				=> 'toko_woocommerce',
			)
		);

		$option = THEME_NAME.'[wc_shop_layout]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> 'has_sidebar',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_select',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_shop',
					'type'			=> 'select',
					'label'			=> esc_html__( 'Shop Page Layout', 'bookie-wp' ),
					'description'	=> '',
					'choices'		=> array( 'has_sidebar' => 'With Sidebar', 'no_sidebar' => 'No Sidebar' ),
				)
			);

		/* wc_shop_number */
			$option = THEME_NAME.'[wc_shop_number]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> '12',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_nohtml',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_shop',
					'type'			=> 'number',
					'label'			=> esc_html__( 'Number of products per page', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_shop_columns */
			$option = THEME_NAME.'[wc_shop_columns]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> '3',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_select',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_shop',
					'type'			=> 'select',
					'label'			=> esc_html__( 'Number of columns per row', 'bookie-wp' ),
					'description'	=> '',
					'choices'		=> array( '4' => '4', '3' => '3', '2' => '2', '1' => '1' ),
				)
			);

		/* wc_shop_result_count_disable */
			$option = THEME_NAME.'[wc_shop_result_count_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> true,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_shop',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE result count', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_shop_catalog_ordering_disable */
			$option = THEME_NAME.'[wc_shop_catalog_ordering_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> true,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_shop',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE catalog ordering', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_shop_saleflash_disable */
			$option = THEME_NAME.'[wc_shop_saleflash_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_shop',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE product sale flash', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_shop_title_disable */
			$option = THEME_NAME.'[wc_shop_title_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_shop',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE product title', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_shop_price_disable */
			$option = THEME_NAME.'[wc_shop_price_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_shop',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE product price', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_shop_rating_disable */
			$option = THEME_NAME.'[wc_shop_rating]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_shop',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'ENABLE product rating', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_shop_addtocart */
			$option = THEME_NAME.'[wc_shop_addtocart]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> true,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_shop',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'ENABLE "add to cart" button', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		$wp_customize->add_section(
			'toko_woocommerce_product',
			array(
				'priority' 			=> 20,
				'title'				=> esc_html__( 'Single Product', 'bookie-wp' ),
				'description'		=> '',
				'panel'				=> 'toko_woocommerce',
			)
		);

		/* wc_product_saleflash_disable */
			$option = THEME_NAME.'[wc_product_saleflash_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_product',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE product sale flash', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_product_rating_disable */
			$option = THEME_NAME.'[wc_product_rating_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> true,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_product',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE product rating', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_product_price_disable */
			$option = THEME_NAME.'[wc_product_price_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_product',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE product price', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_product_excerpt_disable */
			$option = THEME_NAME.'[wc_product_excerpt_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_product',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE product short description', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_product_addtocart_disable */
			$option = THEME_NAME.'[wc_product_addtocart_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_product',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE "add to cart" button', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_product_meta_disable */
			$option = THEME_NAME.'[wc_product_meta_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_product',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE product meta (sku, category, tag)', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_product_description_disable */
			$option = THEME_NAME.'[wc_product_description_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_product',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE product description', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_product_attributes_disable */
			$option = THEME_NAME.'[wc_product_attributes_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_product',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE product attributes (additional information)', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_product_reviews_disable */
			$option = THEME_NAME.'[wc_product_reviews_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_product',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE product review', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_product_upsells_disable */
			$option = THEME_NAME.'[wc_product_upsells_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_product',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE upsells products', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_product_related_disable */
			$option = THEME_NAME.'[wc_product_related_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_product',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE related products', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		$wp_customize->add_section(
			'toko_woocommerce_cart',
			array(
				'priority' 			=> 40,
				'title'				=> esc_html__( 'Cart', 'bookie-wp' ),
				'description'		=> '',
				'panel'				=> 'toko_woocommerce',
			)
		);

		/* wc_product_cross_sells_disable */
			$option = THEME_NAME.'[wc_product_cross_sells_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_cart',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'DISABLE cross-sells products', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		$wp_customize->add_section(
			'toko_woocommerce_myaccount',
			array(
				'priority' 			=> 40,
				'title'				=> esc_html__( 'My Account', 'bookie-wp' ),
				'description'		=> '',
				'panel'				=> 'toko_woocommerce',
			)
		);

		/* woocommerce_enable_myaccount_registration */
			$option = 'woocommerce_enable_myaccount_registration';
			$wp_customize->add_setting(
				'woocommerce_enable_myaccount_registration',
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				'woocommerce_enable_myaccount_registration',
				array(
					'settings'		=> 'woocommerce_enable_myaccount_registration',
					'section'		=> 'toko_woocommerce_myaccount',
					'type'			=> 'checkbox',
					'label'			=> esc_html__( 'ENABLE registration on "My Account" page', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		/* wc_redirect_after_login */
			$option = THEME_NAME.'[wc_redirect_after_login]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> '',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_url',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'		=> $option,
					'section'		=> 'toko_woocommerce_myaccount',
					'type'			=> 'url',
					'label'			=> esc_html__( 'Redirect URL After Customer Login', 'bookie-wp' ),
					'description'	=> '',
				)
			);

		$wp_customize->add_section( 'toko_woo_custom_search', array(
	        'title'		=> esc_html__( 'Custom Search - WooCommerce', 'bookie-wp' ),
	        'priority'	=> 10,
	        'panel'		=> 'toko_custom_search_panel'
	    ) );

		/* WooCommerce Custom Search */
			$option = THEME_NAME.'[toko_woo_custom_search_disable]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> false,
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
			        'settings' => $option,
			        'section'  => 'toko_woo_custom_search',
			        'label'    => esc_html__( 'Disable Custom Search in WooCommerce Section', 'bookie-wp' ),
			        'type'     => 'checkbox',
			    )
			);

}
