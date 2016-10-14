<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * @package	   TGM-Plugin-Activation
 * @subpackage Plugins
 * @author	   Thomas Griffin <thomas@thomasgriffinmedia.com>
 * @author	   Gary Jones <gamajo@gamajo.com>
 * @copyright  Copyright (c) 2012, Thomas Griffin
 * @license	   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
toko_require_file( THEME_DIR . '/inc/core/class-tgm-plugin-activation.php' );

add_action( 'tgmpa_register', 'toko_register_required_plugins' );
function toko_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		/* Required Plugin */
		array(
			'name'		=> 'WooCommerce',
			'slug'		=> 'woocommerce',
			'required'	=> true,
		),

		/* Recommended Plugin */
		array(
			'name'     	=> 'Visual Composer',
			'slug'     	=> 'js_composer',
			'source'   	=> get_template_directory() . '/inc/plugins/js_composer-v4.9.zip',
			'version' 	=> '4.9',
			'required' 	=> true,
		),

		array(
			'name'     	=> 'Toko Sliders',
			'slug'     	=> 'toko-sliders',
			'source'   	=> get_template_directory() . '/inc/plugins/toko-sliders-v1.2.zip',
			'version' 	=> '1.2',
			'required' 	=> true,
		),

		array(
			'name'     	=> 'Toko VC & Shortcodes',
			'slug'     	=> 'toko-shortcodes',
			'source'   	=> get_template_directory() . '/inc/plugins/toko-shortcodes-v1.2.zip',
			'version' 	=> '1.2',
			'required' 	=> true,
		),

		array(
			'name'     	=> 'Bookie WP Addons',
			'slug'     	=> 'bookie-wp-addons',
			'source'   	=> get_template_directory() . '/inc/plugins/bookie-wp-addons-v1.0.zip',
			'version' 	=> '1.0',
			'required' 	=> true,
		),

		array(
			'name'		=> 'WordPress Importer',
			'slug'		=> 'wordpress-importer',
			'source'   	=> get_template_directory() . '/inc/plugins/wordpress-importer-v2.0.zip',
			'version' 	=> '2.0',
			'required' 	=> false,
		),
		
		array(
			'name'		=> 'Widget Importer Exporter',
			'slug'		=> 'widget-importer-exporter',
			'required'	=> false,
		),
		
		array(
			'name'		=> 'Regenerate Thumbnails',
			'slug'		=> 'regenerate-thumbnails',
			'required'	=> false,
		),

		array(
			'name'		=> 'MailChimp for WordPress',
			'slug'		=> 'mailchimp-for-wp',
			'required'	=> false,
		),

	);

	$config = array(
		'id'           => 'toko-tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'toko-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );

}

/* Set Visual Composer as Theme part and disable Visual Composer Updater */
if ( function_exists( 'vc_set_as_theme' ) ) 
	vc_set_as_theme( true );

add_filter('vc_load_default_templates','toko_load_vc_templates');
function toko_load_vc_templates( $args ) {
	$args2 = array ( 
		array(
			'name'=> '1. '.__('Bookie - Home 1','bookie-wp'),
			'image_path'=> THEME_URI . '/images/vc-homepage-1.png', 
			'content'=>'[vc_row full_width="stretch_row_content_no_spaces"][vc_column][bookie-custom-search][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453085217674{margin-bottom: 0px !important;padding-bottom: 60px !important;background-color: #edf3f4 !important;}"][vc_column][toko_divider divider_bg="#edf3f4" title="Popular Books"][toko_wc_products show="bestselling" numbers="4" columns="4"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453113651999{margin-bottom: 0px !important;padding-top: 20px !important;padding-bottom: 60px !important;background-color: #ffffff !important;}"][vc_column width="2/3" css=".vc_custom_1453112775660{padding-right: 50px !important;}"][bookie-featured-product style="2" title="The Complete Idiots Guide to Graphic Design" name="Anggi Krisna" image="2464" button_text="Get This Book" button_url="#" description="From advanced selectors to generated content to web fonts, and from gradients, shadows, and rounded corners to elegant animations, CSS3 hold a universe of creative possibilities. No one can better guide you through these galaxies than Dan Cederholm."][/vc_column][vc_column width="1/3"][bookie-featured-book-category style="2" title="A Complete Idiot Guide to Programming" badge="feature category" image="2676"][/vc_column][/vc_row][vc_row full_width="stretch_row" content_placement="middle" css=".vc_custom_1453089963538{padding-bottom: 60px !important;background-color: #2f2b35 !important;}"][vc_column][vc_custom_heading text="Popular Courses" font_container="tag:h2|font_size:26px|text_align:center|color:%2396919d" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal" el_class="bookie-label"][toko_wc_categories numbers="3"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453091031979{background-image: url(http://demo.toko.press/bookie/standard/wp-content/uploads/2011/05/banner.jpg?id=2593) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column][toko_call_to_action button_type="link" title="Browse Through Our Complete Library" button_text="BROWSE COLLECTION"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453092599145{padding-top: 40px !important;padding-bottom: 40px !important;background-color: #ffffff !important;}"][vc_column width="1/3"][vc_icon type="entypo" icon_entypo="entypo-icon entypo-icon-book-open" color="custom" size="xl" align="center" custom_color="#27c8ea"][vc_custom_heading text="Tons of Books" font_container="tag:h2|font_size:18px|text_align:center" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal"][vc_column_text]From advanced selectors to generated content to web fonts, and from gradients, shadows, and rounded corners. to elegant animations. ,[/vc_column_text][/vc_column][vc_column width="1/3"][vc_icon type="entypo" icon_entypo="entypo-icon entypo-icon-pencil" color="custom" size="xl" align="center" custom_color="#86e154"][vc_custom_heading text="Hundreds of Authors" font_container="tag:h2|font_size:18px|text_align:center" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal"][vc_column_text]To elegant animations. , CSS3 holds a universe of creative possibilities. No one can better guide you through.[/vc_column_text][/vc_column][vc_column width="1/3"][vc_icon type="entypo" icon_entypo="entypo-icon entypo-icon-bookmarks" color="custom" size="xl" align="center" custom_color="#e1dc54"][vc_custom_heading text="Easily Bookmarked" font_container="tag:h2|font_size:18px|text_align:center" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal"][vc_column_text]Shadows, and rounded corners. to elegant animations. , CSS3 holds a universe of creative possibilities. No one can better guide you through.[/vc_column_text][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453093155486{margin-bottom: 0px !important;padding-bottom: 60px !important;background-color: #edf3f4 !important;}"][vc_column][toko_divider divider_bg="#edf3f4" title="New Books"][toko_wc_products show="all" numbers="4" columns="4"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453098699522{padding-top: 40px !important;padding-bottom: 0px !important;background-color: #ffffff !important;}"][vc_column width="1/3"][bookie-featured-book-category title="TRAVEL BOOKS" badge="50% OFF" image="2730" button_text="I want this card" button_url="#"][/vc_column][vc_column width="1/3"][bookie-featured-book-category title="COOKING BOOKS" badge="70% OFF" badge_background="#7d4dde" image="2731" button_text="I want this books" button_url="#"][/vc_column][vc_column width="1/3"][bookie-featured-book-category title="DESIGN BOOKS" badge="70% OFF" badge_background="#dc4dde" image="2732" button_text="I want this books" button_url="#"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453099386743{margin-bottom: 0px !important;padding-top: 0px !important;padding-bottom: 0px !important;background-color: #ffffff !important;}"][vc_column][vc_separator style="dashed"][toko_divider title="From Our Blog"][toko_posts numbers="4" columns="4"][vc_separator style="dashed" css=".vc_custom_1453099626893{margin-bottom: 10px !important;}"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453099526859{padding-bottom: 20px !important;background-color: #ffffff !important;}"][vc_column][vc_single_image image="2624" img_size="full" alignment="center" onclick="custom_link" link="#"][/vc_column][/vc_row]', 
		),
		array(
			'name'=> '2. '.__('Bookie - Home 2','bookie-wp'),
			'image_path'=> THEME_URI . '/images/vc-homepage-2.png', 
			'content'=>'[vc_row full_width="stretch_row_content_no_spaces" css=".vc_custom_1453175725634{padding-top: 200px !important;background-color: #675f74 !important;}"][vc_column css=".vc_custom_1453197877686{padding-right: 300px !important;padding-left: 300px !important;}" el_class="padding-home"][vc_custom_heading text="Vast Collection of Books & Ebooks" font_container="tag:h2|font_size:60px|text_align:center|color:%23ffffff|line_height:1" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal" css=".vc_custom_1453197800616{margin-top: 0px !important;margin-bottom: 0px !important;}"][vc_column_text]Quisque interdum, magna ut ornare sagittis, nisi dui consectetur.[/vc_column_text][vc_btn title="Get Started" style="custom" custom_background="#2f2b35" custom_text="#ffffff" shape="round" size="sm" align="center" link="url:%23||" css=".vc_custom_1453175884741{padding-right: 20px !important;padding-left: 20px !important;}"][/vc_column][/vc_row][vc_row full_width="stretch_row_content_no_spaces" css=".vc_custom_1452925560931{margin-top: 0px !important;margin-bottom: 0px !important;background: #675f74 url(http://demo.toko.press/bookie/standard/wp-content/uploads/2016/01/bs.png?id=2550) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}" el_class="bg-bottom"][vc_column][vc_single_image image="2551" img_size="658x470" alignment="center" css=".vc_custom_1452925387613{margin-bottom: -150px !important;}" el_class="imgfloat"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453176155950{padding-top: 200px !important;background-color: #ffffff !important;}"][vc_column width="1/4"][vc_icon icon_fontawesome="fa fa-mobile" color="custom" size="lg" custom_color="#fc765f" css=".vc_custom_1453176819628{margin-bottom: 0px !important;}"][vc_custom_heading text="Sturdy Materials" font_container="tag:h2|font_size:14px|text_align:left|color:%232f2b35" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal"][vc_column_text]From advanced selectors to generated content to web fonts, and from gradients.[/vc_column_text][/vc_column][vc_column width="1/4"][vc_icon icon_fontawesome="fa fa-area-chart" color="custom" size="lg" custom_color="#5f7dfc" css=".vc_custom_1453176843577{margin-bottom: 0px !important;}"][vc_custom_heading text="High-quality Control" font_container="tag:h2|font_size:14px|text_align:left|color:%232f2b35" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal"][vc_column_text]From advanced selectors to generated content to web fonts, and from gradients.[/vc_column_text][/vc_column][vc_column width="1/4"][vc_icon icon_fontawesome="fa fa-files-o" color="custom" size="lg" custom_color="#fbb437" css=".vc_custom_1453176850508{margin-bottom: 0px !important;}"][vc_custom_heading text="Vast List of Authors" font_container="tag:h2|font_size:14px|text_align:left|color:%232f2b35" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal"][vc_column_text]From advanced selectors to generated content to web fonts, and from gradients.[/vc_column_text][/vc_column][vc_column width="1/4"][vc_icon icon_fontawesome="fa fa-bookmark-o" color="custom" size="lg" custom_color="#38e1f5" css=".vc_custom_1453176858393{margin-bottom: 0px !important;}"][vc_custom_heading text="Great Ideas" font_container="tag:h2|font_size:14px|text_align:left|color:%232f2b35" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal"][vc_column_text]From advanced selectors to generated content to web fonts, and from gradients.[/vc_column_text][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453177128402{padding-bottom: 50px !important;background-color: #ffffff !important;}"][vc_column][vc_btn title="Learn more about bookie" style="custom" custom_background="#2f2b35" custom_text="#ffffff" shape="round" size="sm" align="center" link="url:%23||"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453177090206{background-image: url(http://demo.toko.press/bookie/standard/wp-content/uploads/2015/10/demo-bookie-slider3.jpg?id=2468) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column][toko_call_to_action button_type="link" title="Browse Through Our Complete Library" button_text="BROWSE COLLECTION"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453177216079{margin-top: 0px !important;background-color: #ffffff !important;}"][vc_column css=".vc_custom_1453178470071{padding-top: 20px !important;padding-right: 0px !important;padding-left: 0px !important;}"][bookie-custom-search][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453178142432{margin-top: 0px !important;background-color: #ffffff !important;}"][vc_column width="1/3" css=".vc_custom_1453178329357{margin-top: 0px !important;padding-top: 0px !important;}"][bookie-featured-book-category style="2" title="A Complete Idiot Guide to Programming" badge="FEATURED CATEGORY" image="2759"][/vc_column][vc_column width="1/3" css=".vc_custom_1453178379915{padding-top: 0px !important;}"][bookie-featured-book-category style="2" title="Pellentesque habitant morbi tristique senectus" badge="ECONOMY" badge_background="#2750ea" image="2760"][/vc_column][vc_column width="1/3" css=".vc_custom_1453178388519{padding-top: 0px !important;}"][bookie-featured-book-category style="2" title="Sed vel ipsum convallis, gravida ante" badge="POLITICS" badge_background="#be27ea" image="2761"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453179247235{background-color: #ffffff !important;}"][vc_column width="1/4" css=".vc_custom_1453180698222{padding-top: 70px !important;}"][bookie-featured-book-category style="3" title="Number One Hard Cover Paper Quality" badge="COVER" button_text="LEARN MORE" button_url="#" description="From advanced selectors to generated content to web fonts, and from gradients, shadows."][/vc_column][vc_column width="1/2"][vc_single_image image="2779" img_size="full" alignment="center" css=".vc_custom_1453180596230{margin-bottom: 0px !important;}"][/vc_column][vc_column width="1/4" css=".vc_custom_1453180707819{padding-top: 70px !important;}"][bookie-featured-book-category style="3" title="High-quality paper and printing materials" badge="PAPER" badge_background="#2750ea" button_text="LEARN MORE" button_url="#" description="From advanced selectors to generated content to web fonts, and from gradients, shadows."][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453183751782{padding-top: 0px !important;padding-bottom: 0px !important;background-color: #ffffff !important;}"][vc_column width="1/3"][bookie-featured-book-category title="TRAVEL BOOKS" badge="50% OFF" image="2730" button_text="I want this card" button_url="#"][/vc_column][vc_column width="1/3"][bookie-featured-book-category title="COOKING BOOKS" badge="70% OFF" badge_background="#7d4dde" image="2731" button_text="I want this books" button_url="#"][/vc_column][vc_column width="1/3"][bookie-featured-book-category title="DESIGN BOOKS" badge="70% OFF" badge_background="#dc4dde" image="2732" button_text="I want this books" button_url="#"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453184363132{padding-top: 30px !important;padding-bottom: 50px !important;background-color: #ffffff !important;}"][vc_column width="1/2"][vc_single_image image="2786" img_size="full" alignment="center"][/vc_column][vc_column width="1/2" css=".vc_custom_1453184315421{padding-top: 100px !important;}"][vc_column_text el_class="text" css=".vc_custom_1453184188133{margin-bottom: 0px !important;}"]MOBILE APP[/vc_column_text][vc_custom_heading text="Access Anywhere" font_container="tag:h2|font_size:36|text_align:left" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal" css=".vc_custom_1453184197433{margin-top: 0px !important;margin-bottom: 0px !important;}"][vc_column_text el_class="text" css=".vc_custom_1453184206010{margin-top: 30px !important;}"]We have an app for all the books we have in our library, simply download the app and enjoy our vast collection of books anywhere you like[/vc_column_text][vc_column_text]windowsapp-storegoogle-play[/vc_column_text][/vc_column][/vc_row]', 
		),
		array(
			'name'=> '3. '.__('Bookie - Home 3','bookie-wp'),
			'image_path'=> THEME_URI . '/images/vc-homepage-3.png', 
			'content'=>'[vc_row full_width="stretch_row_content_no_spaces" css=".vc_custom_1453175725634{padding-top: 200px !important;background-color: #675f74 !important;}"][vc_column css=".vc_custom_1453197877686{padding-right: 300px !important;padding-left: 300px !important;}" el_class="padding-home"][vc_custom_heading text="Vast Collection of Books & Ebooks" font_container="tag:h2|font_size:60px|text_align:center|color:%23ffffff|line_height:1" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal" css=".vc_custom_1453197800616{margin-top: 0px !important;margin-bottom: 0px !important;}"][vc_column_text]Quisque interdum, magna ut ornare sagittis, nisi dui consectetur.[/vc_column_text][vc_btn title="Get Started" style="custom" custom_background="#2f2b35" custom_text="#ffffff" shape="round" size="sm" align="center" link="url:%23||" css=".vc_custom_1453175884741{padding-right: 20px !important;padding-left: 20px !important;}"][/vc_column][/vc_row][vc_row full_width="stretch_row_content_no_spaces" css=".vc_custom_1452925560931{margin-top: 0px !important;margin-bottom: 0px !important;background: #675f74 url(http://demo.toko.press/bookie/standard/wp-content/uploads/2016/01/bs.png?id=2550) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}" el_class="bg-bottom"][vc_column][vc_single_image image="2551" img_size="658x470" alignment="center" css=".vc_custom_1452925387613{margin-bottom: -150px !important;}" el_class="imgfloat"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453176155950{padding-top: 200px !important;background-color: #ffffff !important;}"][vc_column width="1/4"][vc_icon icon_fontawesome="fa fa-mobile" color="custom" size="lg" custom_color="#fc765f" css=".vc_custom_1453176819628{margin-bottom: 0px !important;}"][vc_custom_heading text="Sturdy Materials" font_container="tag:h2|font_size:14px|text_align:left|color:%232f2b35" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal"][vc_column_text]From advanced selectors to generated content to web fonts, and from gradients.[/vc_column_text][/vc_column][vc_column width="1/4"][vc_icon icon_fontawesome="fa fa-area-chart" color="custom" size="lg" custom_color="#5f7dfc" css=".vc_custom_1453176843577{margin-bottom: 0px !important;}"][vc_custom_heading text="High-quality Control" font_container="tag:h2|font_size:14px|text_align:left|color:%232f2b35" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal"][vc_column_text]From advanced selectors to generated content to web fonts, and from gradients.[/vc_column_text][/vc_column][vc_column width="1/4"][vc_icon icon_fontawesome="fa fa-files-o" color="custom" size="lg" custom_color="#fbb437" css=".vc_custom_1453176850508{margin-bottom: 0px !important;}"][vc_custom_heading text="Vast List of Authors" font_container="tag:h2|font_size:14px|text_align:left|color:%232f2b35" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal"][vc_column_text]From advanced selectors to generated content to web fonts, and from gradients.[/vc_column_text][/vc_column][vc_column width="1/4"][vc_icon icon_fontawesome="fa fa-bookmark-o" color="custom" size="lg" custom_color="#38e1f5" css=".vc_custom_1453176858393{margin-bottom: 0px !important;}"][vc_custom_heading text="Great Ideas" font_container="tag:h2|font_size:14px|text_align:left|color:%232f2b35" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal"][vc_column_text]From advanced selectors to generated content to web fonts, and from gradients.[/vc_column_text][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453177128402{padding-bottom: 50px !important;background-color: #ffffff !important;}"][vc_column][vc_btn title="Learn more about bookie" style="custom" custom_background="#2f2b35" custom_text="#ffffff" shape="round" size="sm" align="center" link="url:%23||"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453177090206{background-image: url(http://demo.toko.press/bookie/standard/wp-content/uploads/2015/10/demo-bookie-slider3.jpg?id=2468) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column][toko_call_to_action button_type="link" title="Browse Through Our Complete Library" button_text="BROWSE COLLECTION"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453177216079{margin-top: 0px !important;background-color: #ffffff !important;}"][vc_column css=".vc_custom_1453178470071{padding-top: 20px !important;padding-right: 0px !important;padding-left: 0px !important;}"][bookie-custom-search][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453178142432{margin-top: 0px !important;background-color: #ffffff !important;}"][vc_column width="1/3" css=".vc_custom_1453178329357{margin-top: 0px !important;padding-top: 0px !important;}"][bookie-featured-book-category style="2" title="A Complete Idiot Guide to Programming" badge="FEATURED CATEGORY" image="2759"][/vc_column][vc_column width="1/3" css=".vc_custom_1453178379915{padding-top: 0px !important;}"][bookie-featured-book-category style="2" title="Pellentesque habitant morbi tristique senectus" badge="ECONOMY" badge_background="#2750ea" image="2760"][/vc_column][vc_column width="1/3" css=".vc_custom_1453178388519{padding-top: 0px !important;}"][bookie-featured-book-category style="2" title="Sed vel ipsum convallis, gravida ante" badge="POLITICS" badge_background="#be27ea" image="2761"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453179247235{background-color: #ffffff !important;}"][vc_column width="1/4" css=".vc_custom_1453180698222{padding-top: 70px !important;}"][bookie-featured-book-category style="3" title="Number One Hard Cover Paper Quality" badge="COVER" button_text="LEARN MORE" button_url="#" description="From advanced selectors to generated content to web fonts, and from gradients, shadows."][/vc_column][vc_column width="1/2"][vc_single_image image="2779" img_size="full" alignment="center" css=".vc_custom_1453180596230{margin-bottom: 0px !important;}"][/vc_column][vc_column width="1/4" css=".vc_custom_1453180707819{padding-top: 70px !important;}"][bookie-featured-book-category style="3" title="High-quality paper and printing materials" badge="PAPER" badge_background="#2750ea" button_text="LEARN MORE" button_url="#" description="From advanced selectors to generated content to web fonts, and from gradients, shadows."][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453183751782{padding-top: 0px !important;padding-bottom: 0px !important;background-color: #ffffff !important;}"][vc_column width="1/3"][bookie-featured-book-category title="TRAVEL BOOKS" badge="50% OFF" image="2730" button_text="I want this card" button_url="#"][/vc_column][vc_column width="1/3"][bookie-featured-book-category title="COOKING BOOKS" badge="70% OFF" badge_background="#7d4dde" image="2731" button_text="I want this books" button_url="#"][/vc_column][vc_column width="1/3"][bookie-featured-book-category title="DESIGN BOOKS" badge="70% OFF" badge_background="#dc4dde" image="2732" button_text="I want this books" button_url="#"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1453184363132{padding-top: 30px !important;padding-bottom: 50px !important;background-color: #ffffff !important;}"][vc_column width="1/2"][vc_single_image image="2786" img_size="full" alignment="center"][/vc_column][vc_column width="1/2" css=".vc_custom_1453184315421{padding-top: 100px !important;}"][vc_column_text el_class="text" css=".vc_custom_1453184188133{margin-bottom: 0px !important;}"]MOBILE APP[/vc_column_text][vc_custom_heading text="Access Anywhere" font_container="tag:h2|font_size:36|text_align:left" google_fonts="font_family:Montserrat%3Aregular%2C700|font_style:400%20regular%3A400%3Anormal" css=".vc_custom_1453184197433{margin-top: 0px !important;margin-bottom: 0px !important;}"][vc_column_text el_class="text" css=".vc_custom_1453184206010{margin-top: 30px !important;}"]We have an app for all the books we have in our library, simply download the app and enjoy our vast collection of books anywhere you like[/vc_column_text][vc_column_text]windowsapp-storegoogle-play[/vc_column_text][/vc_column][/vc_row]', 
		),
	);
	return array_merge( $args, $args2 );
}
