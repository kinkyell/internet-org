<?php
/**
 * Internet.org functions and definitions
 *
 * @package Internet.org
 */


// PLUGIN INCLUSION
// WP VIP Helper Plugin -- gives us access to the VIP only functions
define( 'IO_DIR', __DIR__ );
require_once( WP_CONTENT_DIR . '/themes/vip/plugins/vip-init.php' );

wpcom_vip_load_plugin( 'babble', 'internet_org-plugins' );
wpcom_vip_load_plugin( 'iorg-custom-posttypes', 'internet_org-plugins' );
wpcom_vip_load_plugin( 'iorg-custom-fields', 'internet_org-plugins' );
wpcom_vip_load_plugin( 'fieldmanager' );
wpcom_vip_load_plugin( 'wp-google-analytics' );
wpcom_vip_load_plugin( 'responsive-images' );
wpcom_vip_load_plugin( 'cache-nav-menu' );
wpcom_vip_load_plugin( 'facebook' );
wpcom_vip_load_plugin( 'lazy-load' );

// Not sure how to include this one yet, need to work with VIP team
// wpcom_vip_load_plugin( 'vip-search-add-on' );

// MANUAL INCLUSION (Multiple Plugins in one dir)
require_once(__DIR__ . '/../internet_org-plugins/babble/translation-fields.php');

if ( ! function_exists( 'internet_org_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function internet_org_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Internet.org, use a find and replace
	 * to change 'internet_org' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'internet_org', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'internet_org' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'internet_org_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // internet_org_setup
add_action( 'after_setup_theme', 'internet_org_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function internet_org_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'internet_org_content_width', 640 );
}
add_action( 'after_setup_theme', 'internet_org_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function internet_org_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'internet_org' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'internet_org_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function internet_org_scripts() {
	wp_enqueue_style( 'internet_org-style', get_stylesheet_uri() );

	wp_enqueue_script( 'internet_org-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'internet_org-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'internet_org_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';







// put this in functions.php or an include file
add_action( 'fm_post_post', function() {
    $fm = new Fieldmanager_Group( array(
        'name' => 'contact_information',
        'children' => array(
            'name' => new Fieldmanager_Textfield( __( 'Name', 'your-domain' ) ),
            'phone_number' => new Fieldmanager_Textfield( __( 'Phone Number', 'your-domain' ) ),
            'website' => new Fieldmanager_Link( __( 'Website', 'your-domain' ) ),
        ),
    ) );
    $fm->add_meta_box( __( 'Contact Information', 'your-domain' ), 'post' );
} );


add_action( 'fm_post_post', function() {
    $fm = new Fieldmanager_Group( array(
        'name' => 'slideshow',
        'limit' => 0,
        'label' => __( 'New Slide', 'your-domain' ),
        'label_macro' => array( __( 'Slide: %s', 'your-domain' ), 'title' ),
        'add_more_label' => __( 'Add another slide', 'your-domain' ),
        'collapsed' => true,
        'sortable' => true,
        'children' => array(
            'title' => new Fieldmanager_Textfield( __( 'Slide Title', 'your-domain' ) ),
            'slide' => new Fieldmanager_Media( __( 'Slide', 'your-domain' ) ),
            'description' => new Fieldmanager_RichTextarea( __( 'Description', 'your-domain' ) ),
            'posts' => new Fieldmanager_Autocomplete( array(
                'label' => __( 'Related Posts', 'your-domain' ),
                'limit' => 0,
                'sortable' => true,
                'one_label_per_item' => false,
                'add_more_label' => __( 'Add another related link', 'your-domain' ),
                'datasource' => new Fieldmanager_Datasource_Post( array(
                    'query_args' => array(
                        'post_status' => 'any',
                    ),
                ) ),
            ) ),
        ),
    ) );
    $fm->add_meta_box( __( 'Slides', 'your-domain' ), 'post' );
} );
