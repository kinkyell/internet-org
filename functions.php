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
 * This will fix the template being used to render content, there are instances
 * where single.php is being used for page (mainly the home page) when it should
 * be using the page.php (or front-page.php) template
 *
 * @param string $singleTemplate initial template to be used (default)
 * @return string single template
 */
function iorg_correct_template_selection( $singleTemplate ) {
	global $post;

	switch ( $post->post_type ) {
		case 'page':
			$singleTemplate = dirname( __FILE__ ) . '/page.php';
			if ( 'home' === $post->post_name ) {
				$singleTemplate = dirname( __FILE__ ) . '/front-page.php';
			}
			break;
		default:
			//template remains unchanged
			break;
	}

	return $singleTemplate;
}
add_filter( 'single_template', 'iorg_correct_template_selection' );


if ( ! function_exists( 'iorg_extend_search_post_type_range' ) ) :
	/**
	 * Add our custom post types to the search query
	 *
	 * @param WP_Query $query the current query object
	 * @return mixed the possibly updated search query
	 */
	function iorg_extend_search_post_type_range( $query ) {
		if ( isset( $_GET['s'] ) && $query->is_main_query() ) {
			$query->set(
				'post_type',
				array(
					'post', // default PT
					'page', // default PT
					'iorg_press', // CPT
					'iorg_story', // CPT
					'iorg_freesvc', // CPT
					'iorg_campaign', // CPT
				)
			);
			$query->is_search = true;
			$query->is_home   = false;
		}

		return $query;
	}
endif;
add_filter( 'pre_get_posts', 'iorg_extend_search_post_type_range' );

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

/**
 * Language related functionality
 */
require get_template_directory() . '/inc/language.php';



