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

require IO_DIR . '/plugins/internetorg-custom-posttypes/internetorg-custom-posttypes.php';
require IO_DIR . '/plugins/internetorg-custom-fields/internetorg-custom-fields.php';
require IO_DIR . '/plugins/bogo/bogo.php';
wpcom_vip_load_plugin( 'fieldmanager' );
wpcom_vip_load_plugin( 'wp-google-analytics' );
wpcom_vip_load_plugin( 'responsive-images' );
wpcom_vip_load_plugin( 'cache-nav-menu' );
wpcom_vip_load_plugin( 'facebook' );
wpcom_vip_load_plugin( 'lazy-load' );

if ( ! function_exists( 'internetorg_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function internetorg_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Internet.org, use a find and replace
		 * to change 'internetorg' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'internetorg', get_template_directory() . '/languages' );

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
		register_nav_menus(
			array(
				'primary'         => esc_html__( 'Primary Menu', 'internetorg' ),
				'primary-sub-nav' => esc_html__( 'Primary Menu Sub Nav', 'internetorg' ),
		    )
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'internetorg_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);
	}
endif; // internetorg_setup

add_action( 'after_setup_theme', 'internetorg_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function internetorg_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'internetorg_content_width', 640 );
}

add_action( 'after_setup_theme', 'internetorg_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function internetorg_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'internetorg' ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		)
	);
}

add_action( 'widgets_init', 'internetorg_widgets_init' );

if ( ! function_exists( 'internetorg_extend_search_post_type_range' ) ) :
	/**
	 * Add our custom post types to the search query
	 *
	 * The pre_get_posts hook is called after the query variable object is created,
	 * but before the actual DB query is run.
	 * The $query object is passed by reference.
	 * Technically uou do not need to declare globals or return a value.
	 * Any changes to the $query object are made to the original WP_Query immediately.
	 *
	 * @action   pre_get_posts
	 * @priority default (10)
	 *
	 * @link     http://goo.gl/UI9Yyj pre_get_posts action reference
	 * @link     http://goo.gl/8QQlj1 WP_Query class reference
	 *
	 * @param WP_Query $query the WP_Query object to operate on
	 *
	 * @return WP_Query
	 */
	function internetorg_extend_search_post_type_range( $query ) {

		$search = $query->get( 's', null );

		if ( ! empty( $search ) ) {
			$query->set(
				'post_type',
				array(
					'post', // default PT
					'page', // default PT
					'io_press', // CPT
					'io_story', // CPT
					'io_campaign', // CPT
				)
			);
			$query->is_search = true;
		}

		return $query;
	}

endif;

add_filter( 'pre_get_posts', 'internetorg_extend_search_post_type_range' );

if ( ! function_exists( 'internetorg_get_free_services' ) ) :
	/**
	 * Get a list of the free services offered
	 *
	 * This method will return an empty array if there are no services, if there
	 * are services your array will look similar to:
	 *
	 * array(
	 *     array(
	 *         'post_id' => #,
	 *         'title'   => String,
	 *         'excerpt' => String,
	 *         'image'   => String:URL|false
	 *     ),
	 *     ...
	 * )
	 *
	 * @note This function uses caching functions (wp_cache_get, wp_cache_set)
	 *
	 * @see  wp_get_attachment_image_src
	 * @see  wp_reset_postdata
	 *
	 * @return array of free services or empty array if there are no services
	 */
	function internetorg_get_free_services() {

		// check the cache first
		$services = wp_cache_get( 'internetorg_free_services_list' );

		// no cache, query
		if ( false === $services ) {
			$args = array(
				'post_type'   => 'io_freesvc',
				'post_status' => 'publish',
			);

			$services = array();
			$svcqry   = new WP_Query( $args );

			// build array of services so we are not passing around a query object
			while ( $svcqry->have_posts() ) : $svcqry->the_post();
				$postId     = get_the_ID();
				$services[] = array(
					'post_id' => $postId,
					'slug'    => $svcqry->post->post_name,
					'title'   => get_the_title(),
					'excerpt' => get_the_excerpt(),
					'image'   => wp_get_attachment_image_src( get_post_thumbnail_id( $postId ), 'thumbnail' ),
				);
			endwhile;

			// caching the compiled array and not the query (86400 == 1 day)
			wp_cache_set( 'internetorg_free_services_list', $services, null, 86400 );

			// reset the query to before we started mucking with it
			wp_reset_postdata();
		}

		return $services;
	}
endif;

/**
 * Enqueue Scripts and Styles.
 */
require get_template_directory() . '/inc/enqueue-scripts.php';

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

/**
 * Custom menu walkers
 */
require get_template_directory() . '/inc/menu-walkers.php';
