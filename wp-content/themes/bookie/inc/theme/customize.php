<?php

add_action( 'customize_register', 'toko_customize_register_theme' );
function toko_customize_register_theme( $wp_customize ) {

	if ( ! isset( $wp_customize ) ) {
		return;
	}

	$wp_customize->get_section( 'background_image' )->priority = 140;
	$wp_customize->get_section( 'background_image' )->title = esc_html__( 'Background', 'bookie-wp' );
	$wp_customize->get_control( 'background_color' )->section = 'background_image';

	$wp_customize->remove_control('header_textcolor');

	$wp_customize->get_section( 'header_image' )->panel = 'toko_header_panel';
	$wp_customize->get_section( 'header_image' )->priority = 20;
}

/**
 * Header Customizer
 */	
add_action( 'customize_register', 'toko_header_customize_register' );
function toko_header_customize_register( $wp_customize ) {

	if ( ! isset( $wp_customize ) ) {
		return;
	}

	/* Header Customize */
	$wp_customize->add_panel( 'toko_header_panel', array(
		'title' 		=> esc_html__( 'Header', 'bookie-wp' ),
		'description'	=> '',
		'priority'		=> 150,
	) );

	    $wp_customize->add_section( 'toko_header_logo', array(
	        'title'		=> esc_html__( 'Header Logo', 'bookie-wp' ),
	        'priority'	=> 10,
	        'panel'		=> 'toko_header_panel',
	    ));
    
    	/* Header - logo Text */
		    $option = THEME_NAME.'[header_logo_text]';
		    $wp_customize->add_setting(
		    	$option,
		    	array(
					'default'			=> '',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_nohtml',
			    )
			);
			$wp_customize->add_control(
				$option,
				array(
					'settings'	=> $option,
				    'section'	=> 'toko_header_logo',
				    'label'		=> esc_html__( 'Header Logo Text', 'bookie-wp' ),
				)
			);
	
		/* Header - Logo Image */
			$option = THEME_NAME.'[header_logo_image]';
		    $wp_customize->add_setting(
		    	$option,
		    	array(
					'default'			=> '',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_image',
			    )
			);
		    $wp_customize->add_control( new WP_Customize_Image_Control(
		    	$wp_customize, 
		    	$option,
		    	array(
		    		'settings'	=> $option,
			        'section'	=> 'toko_header_logo',
			        'label'		=> esc_html__( 'Header Logo Image', 'bookie-wp' ),
				)
			));

		/* Header - Site Description */
			$option = THEME_NAME.'[toko_header_description_disable]';
		    $wp_customize->add_setting(
		    	$option,
		    	array(
					'default'			=> '',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
		        'settings' => $option,
		        'section'  => 'toko_header_logo',
		        'label'    => esc_html__( 'Disable Site Description', 'bookie-wp' ),
		        'type'     => 'checkbox',
		    ) );

	    $wp_customize->add_section( 'toko_header_nav', array(
	        'title'		=> esc_html__( 'Header Navigation', 'bookie-wp' ),
	        'priority'	=> 15,
	        'panel'		=> 'toko_header_panel',
	    ));
	
		/* Header - Navigation */
		    $option = THEME_NAME.'[toko_header_menu_disable]';
		    $wp_customize->add_setting(
		    	$option,
		    	array(
					'default'			=> '',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
		        'settings' => $option,
		        'section'  => 'toko_header_nav',
		        'label'    => esc_html__( 'Disable Site Navigation', 'bookie-wp' ),
		        'type'     => 'checkbox',
		    ) );

		/* Header - Mini Cart */
			if( class_exists( 'woocommerce' ) ) {
			    $option = THEME_NAME.'[toko_header_minicart_disable]';
			    $wp_customize->add_setting(
			    	$option,
			    	array(
						'default'			=> '',
						'type'				=> 'option',
						'capability'		=> 'edit_theme_options',
						'sanitize_callback'	=> 'toko_sanitize_checkbox',
					)
				);
				$wp_customize->add_control(
					$option,
					array(
			        'settings' => $option,
			        'section'  => 'toko_header_nav',
			        'label'    => esc_html__( 'Disable Mini Cart', 'bookie-wp' ),
			        'type'     => 'checkbox',
			    ) );
			}

		$wp_customize->add_section( 'toko_book_search', array(
	        'title'		=> esc_html__( 'Book Search', 'bookie-wp' ),
	        'priority'	=> 20,
	        'panel'		=> 'toko_header_panel'
	    ) );

		/* Book Search */
			$option = THEME_NAME.'[toko_book_search_disable]';
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
			        'section'  => 'toko_book_search',
			        'label'    => esc_html__( 'Disable Book Search', 'bookie-wp' ),
			        'type'     => 'checkbox',
			    )
			);

}

/**
 * Footer Customizer
 */
add_action( 'customize_register', 'toko_footer_customize_register' );
function toko_footer_customize_register( $wp_customize ) {

	if ( ! isset( $wp_customize ) ) {
		return;
	}

	$wp_customize->add_panel( 'toko_footer_panel', array(
		'title'			=> esc_html__( 'Footer', 'bookie-wp' ),
		'description'	=> '',
		'priority'		=> 160,
	) );

		$wp_customize->add_section( 'toko_footer_banner', array(
	        'title'		=> esc_html__( 'Footer Banner', 'bookie-wp' ),
	        'priority'	=> 5,
	        'panel'		=> 'toko_footer_panel'
	    ) );

		/* Footer - Banner Disable */
		    $option = THEME_NAME.'[toko_footer_banner_disable]';
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
			        'section'  => 'toko_footer_banner',
			        'label'    => esc_html__( 'Disable Footer Banner', 'bookie-wp' ),
			        'type'     => 'checkbox',
			    )
			);

		/* Footer - Banner Component */
			$option = THEME_NAME.'[toko_footer_banner_image]';
			$wp_customize->add_setting(
		    	$option,
		    	array(
					'default'			=> '',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_image',
			    )
			);
		    $wp_customize->add_control( new WP_Customize_Image_Control(
		    	$wp_customize, 
		    	$option,
		    	array(
		    		'settings'	=> $option,
			        'section'	=> 'toko_footer_banner',
			        'label'		=> esc_html__( 'Footer Banner Image', 'bookie-wp' ),
				)
			));

			$option = THEME_NAME.'[toko_footer_banner_title]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> '',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_nohtml',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
				'settings'		=> $option,
			    'section'		=> 'toko_footer_banner',
			    'label'			=> esc_html__( 'Footer Banner Title', 'bookie-wp' ),
			    'description'	=> '',
			) );

			$option = THEME_NAME.'[toko_footer_banner_url]';
			$wp_customize->add_setting(
				$option,
				array(
					'default' 			=> '',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_url',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
				    'settings'	=> $option,
				    'section'	=> 'toko_footer_banner',
				    'label'		=> esc_html__( 'Footer Banner Link URL', 'bookie-wp' ),
				)
			);

			$option = THEME_NAME.'[toko_footer_banner_link]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> '',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_nohtml',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
				'settings'		=> $option,
			    'section'		=> 'toko_footer_banner',
			    'label'			=> esc_html__( 'Footer Banner Link Text', 'bookie-wp' ),
			    'description'	=> '',
			) );

			$wp_customize->add_section( 'toko_footer_social', array(
		        'title'		=> esc_html__( 'Footer Social Icon', 'bookie-wp' ),
		        'priority'	=> 10,
		        'panel'		=> 'toko_footer_panel'
		    ) );

    	/* Footer - Social Icon Disable */
		    $option = THEME_NAME.'[toko_footer_social_disable]';
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
			        'section'  => 'toko_footer_social',
			        'label'    => esc_html__( 'Disable Social Icons', 'bookie-wp' ),
			        'type'     => 'checkbox',
			    )
			);
	
		/* Footer - Social Icon */
			$socials = array( 'facebook', 'twitter', 'google-plus', 'instagram', 'rss', 'e-mail','youtube', 'flickr', 'linkedin', 'pinterest', 'dribbble', 'github', 'lastfm', 'vimeo', 'tumblr', 'soundcloud', 'behance', 'deviantart' );
			for( $scn=0;$scn<18;$scn++ ) {
				$option = THEME_NAME.'[toko_footer_social_'.$scn.']';
		        $social = ucwords( str_replace( '-', ' ', $socials[$scn] ) );
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
					    'section' 		=> 'toko_footer_social',
					    'label'			=> sprintf( esc_html__( '%s URL', 'bookie-wp' ), $social ),
					    'description'	=> '',
					)
				);
			}

			/* Footer Credit */
		    $wp_customize->add_section( 'toko_footer_credit', array(
		        'title'		=> esc_html__( 'Footer Credit', 'bookie-wp' ),
		        'priority'	=> 100,
		        'panel'		=> 'toko_footer_panel',
		    ));

		    $option = THEME_NAME.'[footer_credit]';
			$wp_customize->add_setting(
				$option,
				array(
					'default'			=> '',
					'type'				=> 'option',
					'capability'		=> 'edit_theme_options',
					'sanitize_callback'	=> 'toko_sanitize_html',
				)
			);
			$wp_customize->add_control(
				$option,
				array(
				'settings'		=> $option,
			    'section'		=> 'toko_footer_credit',
			    'type'			=> 'textarea',
			    'label'			=> esc_html__( 'Footer Credit Text', 'bookie-wp' ),
			    'description'	=> '',
			) );

}

/**
 * Contact Page Template Customizer
 */
add_action( 'customize_register', 'toko_contact_customize_register' );
function toko_contact_customize_register( $wp_customize ) {

	if ( ! isset( $wp_customize ) ) {
		return;
	}

	/* Contact Setting Panel */
	$wp_customize->add_section( 'toko_contact', array(
        'title'		=> esc_html__( 'Contact Page Template', 'bookie-wp' ),
        'priority'	=> 170,
        'panel'		=> ''
    ) );
	$option = THEME_NAME.'[contact_map_disable]';
    $wp_customize->add_setting(
    	$option,
    	array(
			'default'			=> '',
			'type'				=> 'option',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'toko_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		$option,
		array(
	        'settings' => $option,
	        'section'  => 'toko_contact',
	        'label'    => esc_html__( 'DISABLE Google Map', 'bookie-wp' ),
	        'type'     => 'checkbox',
	    )
	);
	$option = THEME_NAME.'[contact_map_lat]';
    $wp_customize->add_setting(
    	$option,
    	array(
			'default'			=> '',
			'type'				=> 'option',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'toko_sanitize_nohtml',
		)
	);
	$wp_customize->add_control(
		$option,
		array(
		    'settings'	=> $option,
		    'section'	=> 'toko_contact',
		    'label'		=> esc_html__( 'Map Latitude', 'bookie-wp' ),
		)
	);
	$option = THEME_NAME.'[contact_map_lng]';
	$wp_customize->add_setting(
		$option,
		array(
			'default'			=> '',
			'type'				=> 'option',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'toko_sanitize_nohtml',
		)
	);
	$wp_customize->add_control(
		$option,
		array(
		    'settings'	=> $option,
		    'section'	=> 'toko_contact',
		    'label'		=> esc_html__( 'Map Longitude', 'bookie-wp' ),
		)
	);
	$option = THEME_NAME.'[contact_map_marker_title]';
	$wp_customize->add_setting(
		$option,
		array(
			'default'			=> '',
			'type'				=> 'option',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'toko_sanitize_nohtml',
		)
	);
	$wp_customize->add_control(
		$option,
		array(
		    'settings'	=> $option,
		    'section'	=> 'toko_contact',
		    'label'		=> esc_html__( 'Marker Title', 'bookie-wp' ),
		)
	);
	$option = THEME_NAME.'[contact_map_marker_desc]';
	$wp_customize->add_setting(
		$option,
		array(
			'default'			=> '',
			'type'				=> 'option',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'toko_sanitize_html',
		)
	);
	$wp_customize->add_control(
		$option,
		array(
		    'settings'	=> $option,
		    'section'	=> 'toko_contact',
			'type'		=> 'textarea',
		    'label'		=> esc_html__( 'Marker Description', 'bookie-wp' ),
		)
	);

}

