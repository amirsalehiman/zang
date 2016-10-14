<?php
load_theme_textdomain( 'javad', get_template_directory().'/languages' );
load_theme_textdomain( 'bookie-wp', get_template_directory().'/languages' );
load_theme_textdomain( 'bookie-wp-addons', get_template_directory().'/languages' );

/**
 * Theme functions and definitions
 *
 * @package WordPress
 * @subpackage Bookie
 * @since Bookie 1.0
 */

define( 'THEME_NAME' , 'bookie-wp' );
define( 'THEME_VERSION', '1.1.2' );

define( 'THEME_DIR', get_template_directory() );
define( 'THEME_URI', get_template_directory_uri() );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 800; /* pixels */


if ( ! function_exists( 'toko_setup' ) ) :
/**
 * Set up theme defaults and register support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function toko_setup() {

    // This theme styles the visual editor with editor-style.css to match the theme style.
    add_editor_style();

    if ( function_exists( 'add_theme_support' ) ) {

		/**
		 * Add HTML5 support
		*/
		add_theme_support( 'html5' );

		/**
		 * Add Title Tag support
		 */
		add_theme_support( 'title-tag' );
		
		/**
		 * Add default posts and comments RSS feed links to head
		*/
		add_theme_support( 'automatic-feed-links' );
		
		/**
		 * Enable support for Post Thumbnails on posts and pages
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		*/
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 748, 282, true ); 

		/**
		 * Enable support for Post Formats
		*/
		add_theme_support( 'post-formats', array( 'image', 'gallery', 'video', 'audio', 'link', 'quote', 'status', 'aside', 'chat' ) );
		
		/**
		 * Setup the WordPress core custom background feature.
		*/
		add_theme_support( 'custom-background', apply_filters( 'toko_custom_background_args', array(
		) ) );
	
		/**
		 * Setup the WordPress core custom header feature.
		*/
		add_theme_support( 'custom-header', apply_filters( 'toko_custom_header_args', array(
			'flex-width' => true,
			'flex-height' => true,
			'header-text' => false,
		) ) );
	
    }

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	*/
	load_theme_textdomain( 'tokopress', get_template_directory() . '/languages' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	*/ 
    register_nav_menus( array(
        'navbar-header'  => esc_html__( 'Header Navigation', 'bookie-wp' ),
        'navbar-footer'  => esc_html__( 'Footer Navigation', 'bookie-wp' ),
    ) );

}
endif; // toko_setup
add_action( 'after_setup_theme', 'toko_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function toko_widgets_init() {

	register_sidebar( array(
		'name'          => esc_html__( 'Primary Sidebar', 'bookie-wp' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-wrap widget-inside">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'bookie-wp' ),
		'id'            => 'sidebar-shop',
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-wrap widget-inside">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

}
add_action( 'widgets_init', 'toko_widgets_init' );

/**
 * Enqueue scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'toko_scripts' );
function toko_scripts() {

	// load bootstrap
	// note: we use 992px ( not 768px ) for navbar breakpoint 
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/vendor/bootstrap/css/bootstrap.min.css', array(), '3.3.5' );
	wp_enqueue_script('bootstrap', get_template_directory_uri().'/vendor/bootstrap/js/bootstrap.min.js', array('jquery'), '3.3.5', true );

	// load smartmenu
	wp_enqueue_script('smartmenu', get_template_directory_uri().'/vendor/smartmenu/jquery.smartmenus.min.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script('smartmenu-bootstrap', get_template_directory_uri().'/vendor/smartmenu/jquery.smartmenus.bootstrap.min.js', array('jquery'), '0.2.0', true );
	wp_enqueue_style( 'smartmenu', get_template_directory_uri() . '/vendor/smartmenu/jquery.smartmenus.bootstrap.css', array(), '1.0.0' );

	// load fontawesome
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/vendor/fontawesome/css/font-awesome.min.css', array(), '4.3.0' );
		
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// load google map for contact page template
	wp_register_script( 'googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true', array( 'jquery' ), '3', true );
	wp_register_script( 'gmaps', trailingslashit( THEME_URI ) . 'vendor/gmaps/gmaps-v0.4.12.js', array( 'jquery' ), '0.4.12', true );
	
	// OWL Carousel
	wp_register_script( 'owl-carousel', THEME_URI . '/vendor/owl-carousel/owl.carousel.min.js', array( 'jquery' ), '2.0', true );
	wp_register_style( 'owl-carousel', THEME_URI . '/vendor/owl-carousel/owl.carousel.css' );
	if( class_exists( 'woocommerce' ) && is_product() ) {
		wp_enqueue_script( 'owl-carousel' );
		wp_enqueue_style( 'owl-carousel' );
	}
	
	// load script js
	wp_enqueue_script( 'toko-script', get_template_directory_uri() . '/script.js', array('jquery'), THEME_VERSION, true );

}

/**
 * Register Google Fonts
 */
function toko_google_fonts_url() {
    $font_url = '';
    /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
     */
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'bookie-wp' ) ) {
        $font_url = add_query_arg( 'family', urlencode( 'Montserrat|PT Serif' ), "https://fonts.googleapis.com/css" );
    }
    return $font_url;
}
function toko_google_fonts_scripts() {
    wp_enqueue_style( 'toko-google-fonts', toko_google_fonts_url(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'toko_google_fonts_scripts' );

/**
 * Enqueue main style, late init
 */
add_action( 'wp_enqueue_scripts', 'toko_main_style', 15 );
function toko_main_style() {

	// load simple-line-icon & dripicon
	wp_enqueue_style( 'simple-line-icon', get_template_directory_uri() . '/vendor/simple-icon/simple-line-icons.css', array(), '4.2.0' );
	wp_enqueue_style( 'drip-icon', get_template_directory_uri() . '/vendor/drip-icon/webfont.css', array(), '4.2.0' );

	/* Load theme stylesheet. */
	wp_enqueue_style( 'toko-style-theme', trailingslashit( get_template_directory_uri() ) . 'style-theme.css', array(), THEME_VERSION );

	/* Load woocommerce stylesheet. */
	if( class_exists( 'woocommerce' ) ) {
		wp_enqueue_style( 'toko-style-woocommerce', trailingslashit( get_template_directory_uri() ) . 'style-woocommerce.css', array(), THEME_VERSION );
	}

	if( defined( 'TOKO_SHORTCODE_PLUGIN_URI' ) || defined( 'TOKO_SLIDERS_PLUGIN_URI ') ) {
		wp_enqueue_style( 'toko-style-shortcodes', trailingslashit( get_template_directory_uri() ) . 'style-shortcodes.css', array(), THEME_VERSION );
	}

	/* Load main stylesheet. */
	wp_enqueue_style( 'toko-style', get_stylesheet_uri(), array(), THEME_VERSION );

}

function toko_get_option( $name, $default = false ) {
	$options = get_option( THEME_NAME );
	if ( isset( $options[$name] ) )
		return $options[$name];
	return $default;
}
function toko_include_file( $file ) {
	include_once( $file );
}
function toko_require_file( $file ) {
	require_once( $file );
}

/**
 * [CORE] Load sanitization callback functions.
 */
toko_include_file( trailingslashit( THEME_DIR ) . 'inc/core/sanitization.php' );

/**
 * [CORE] Load Post Formats UI, admin only.
 */
if ( is_admin() ) {
	toko_include_file( trailingslashit( THEME_DIR ) . 'inc/core/post-formats-ui.php' );
}

/**
 * [CORE] Load Bootstrap Nav Walker, frontend only.
 */
if ( !is_admin() ) {
	toko_include_file( trailingslashit( THEME_DIR ) . 'inc/core/navwalker.php' );
}

/**
 * [CORE] Load frontend-related functions.
 */
if ( !is_admin() ) {
	toko_include_file( trailingslashit( THEME_DIR ) . 'inc/core/frontend.php' );
}

/**
 * [CORE] Load Bootstrap Cleaner Gallery, frontend only.
 */
if ( !is_admin() ) {
	toko_include_file( trailingslashit( THEME_DIR ) . 'inc/core/cleaner-gallery.php' );
}

/**
 * [CORE] Load Format Grabber, frontend only.
 */
if ( !is_admin() ) {
	toko_include_file( trailingslashit( THEME_DIR ) . 'inc/core/format-grabber.php' );
}

/**
 * [CORE] Load Hybrid Media Grabber extension, frontend only.
 */
if ( !is_admin() ) {
	toko_include_file( trailingslashit( THEME_DIR ) . 'inc/core/hybrid-media-grabber.php' );
}

/**
 * [CORE] Load Theme Update file.
 */
toko_include_file( trailingslashit( THEME_DIR ) . 'inc/core/update.php' );

/**
 * [THEME] Load Breadcrumb Functions
 */
toko_include_file( trailingslashit( THEME_DIR ) . 'inc/theme/breadcrumb.php' );

/**
 * [THEME] Load Frontend Functions
 */
toko_include_file( trailingslashit( THEME_DIR ) . 'inc/theme/frontend.php' );

/**
 * [THEME] Load Admin Functions
 */
toko_include_file( trailingslashit( THEME_DIR ) . 'inc/theme/admin.php' );

/**
 * [THEME] Load Customizer Functions
 */
toko_include_file( trailingslashit( THEME_DIR ) . 'inc/theme/customize.php' );

/**
 * [THEME] Load Plugins Funtions
 */
toko_include_file( trailingslashit( THEME_DIR ) . 'inc/theme/plugins.php' );

/**
 * [WOOCOMMERCE] Load WooCommerce Frontend Functions
 */
if( class_exists( 'woocommerce' ) ) {
	toko_include_file( trailingslashit( THEME_DIR ) . 'inc/woocommerce/frontend.php' );
}

/**
 * [WOOCOMMERCE] Load WooCommerce Customizer Functions
 */
if( class_exists( 'woocommerce' ) ) {
	toko_include_file( trailingslashit( THEME_DIR ) . 'inc/woocommerce/customize.php' );
}


add_filter( 'woocommerce_checkout_fields' , 'EMAD_woocommerce_fields' );
function EMAD_woocommerce_fields( $fields ) {
     unset($fields['billing']['billing_city']);
     
     return $fields;
}