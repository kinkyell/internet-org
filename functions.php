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

/** translation related plugins -- commenting out to continue working on theme without blocker/distraction */
//require IO_DIR . '/plugins/bogo/bogo.php';
//require IO_DIR . '/plugins/babble/babble.php';
//require IO_DIR . '/plugins/babble/translation-show-pre-translation.php';
//require IO_DIR . '/plugins/babble/translation-group-tool.php';
//require IO_DIR . '/plugins/babble/translation-fields.php';

/** Other VIP plugins that have caused some wonkiness -- commenting out to continue working on theme without blocker/distraction */
//wpcom_vip_load_plugin( 'wp-google-analytics' );
//wpcom_vip_load_plugin( 'responsive-images' );
//wpcom_vip_load_plugin( 'cache-nav-menu' );
//wpcom_vip_load_plugin( 'facebook' );
//wpcom_vip_load_plugin( 'lazy-load' );

/** Custom Post Types */
require IO_DIR . '/plugins/internetorg-custom-posttypes/internetorg-custom-posttypes.php';

/** Fieldmanager and Fields */
wpcom_vip_load_plugin( 'fieldmanager' );
require IO_DIR . '/plugins/internetorg-custom-fields/internetorg-custom-fields.php';

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

// @todo: Move these custom functions to an include and load in functions.php file

/**
 * Get the "subtitle" from the page_subtitle custom field.
 *
 * @see internetorg_create_after_title_fields_internetorg_page_home()
 *
 * @param int $post_id
 *
 * @return string
 */
function internetorg_get_the_subtitle( $post_id = 0 ) {

	$post_id = absint( $post_id );

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( empty( $post_id ) ) {
		return '';
	}

	$subtitle = get_post_meta( get_the_ID(), 'page_subtitle', true );

	return $subtitle;
}

/**
 * @param int $post_id
 *
 * @return string
 */
function internetorg_get_post_thumbnail( $post_id = 0 ) {

	$post_id = absint( $post_id );

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( empty( $post_id ) ) {
		return '';
	}

	if ( ! has_post_thumbnail( $post_id ) ) {
		return get_stylesheet_directory_uri() . '/_static/web/assets/media/uploads/home.jpg';
	}

	$post_thumbnail_id = get_post_thumbnail_id( $post_id );

	if ( empty( $post_thumbnail_id ) ) {
		return '';
	}

	$attachment_image_src = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );

	if ( empty( $attachment_image_src ) ) {
		return '';
	}

	return $attachment_image_src[0];
}

/**
 * Make custom meta fields available for Babble translation.
 *
 * Hooks the bbl_translated_meta_fields Babble filter to add our fields to
 * the list of fields which have translation configurations and so will show
 * up in the translation UI.
 * It should be noted that this is based on the demonstration of translating WPSEO meta fields in Babble plugin.
 * It "works," however, it appears there is a bug in Babble, see issues 257 and 260 in the Babble Github project.
 * When you update the translated post, the meta for the original is overwritten as well.
 * There are some suggestions in the Babble Github project, issue 257.
 * Two possible solutions:
 * 1. The developer should both filter bbl_translated_meta_fields to specify the translation config for each field AND
 * filter bbl_sync_meta_key to stop those keys being filtered
 * 2. Babble should stop a meta_key which has a translation config (e.g. in bbl_translated_meta_fields) from syncing
 *
 * @see  bbl_wpseo_meta_fields
 *
 * @link https://github.com/Automattic/babble/blob/develop/translation-fields.php Demonstration of translating meta.
 * @link https://github.com/Automattic/babble/issues/257 Clashing keys when using the `bbl_translated_meta_fields`
 *       filter #257
 * @link https://github.com/Automattic/babble/issues/260 Filtering `bbl_translated_meta_fields` doesn't stop
 *       `meta_keys` from syncing #260
 *
 * @param array    $fields An array of instances of the Babble_Meta_Field_* classes
 * @param \WP_Post $post   The WP_Post object which is to be translated
 *
 * @return array An array of instances of the Babble_Meta_Field_* classes
 */
function internetorg_bbl_fm_fields( array $fields, WP_Post $post ) {

	$fields['page_subtitle'] = new Babble_Meta_Field_Textarea(
		$post,
		'page_subtitle',
		_x(
			'Subtitle',
			'Fieldmanager plugin meta field',
			'internetorg'
		)
	);

	return $fields;
}

add_filter( 'bbl_translated_meta_fields', 'internetorg_bbl_fm_fields', 10, 2 );

/**
 * Hooks the bbl_sync_meta_key Babble filter to specify when a meta_key
 * should not be translated. If a key is NOT to be translated, normally
 * you will want Babble to sync the same value to all translations, e.g.
 * for a meta_key which specifies the same custom header colour for a post
 * whichever language it is in. If you want a meta_key to be translated, e.g.
 * for a meta_key which specifies the text for a subheading, then you will
 * want to specify a translation configuration AND stop the key from being
 * synced by returning false in this filter when the $meta_key value is
 * the name of your meta_key.
 *
 * @param bool   $sync     True if the meta_key is NOT to be translated and SHOULD be synced
 * @param string $meta_key The name of the post meta meta_key
 *
 * @return bool True if the meta_key is NOT to be translated and SHOULD be synced
 */
function internetorg_bbl_sync_meta_key( $sync, $meta_key ) {

	$sync_not = array( 'page_subtitle' );

	if ( in_array( $meta_key, $sync_not ) ) {
		return false;
	}

	return $sync;
}

add_filter( 'bbl_sync_meta_key', 'internetorg_bbl_sync_meta_key', 10, 2 );

/**
 * Filter the WP Native Gallery to modify markup to match what our FEDs expect.
 *
 * If the filtered output isn't empty... we're going to ignore it and use roll own anyway.
 *
 * @param string $output   The gallery output. Default empty.
 * @param array  $attr     Attributes of the gallery shortcode.
 * @param int    $instance Unique numeric ID of this gallery shortcode instance.
 *
 * @return string
 */
function internetorg_post_gallery_filter( $output, $attr, $instance ) {

	$atts = shortcode_atts(
		array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post ? $post->ID : 0,
			'itemtag'    => 'li',
			'icontag'    => 'div',
			'captiontag' => 'div',
			'columns'    => 1,
			'size'       => 'full',
			'include'    => '',
			'exclude'    => '',
			'link'       => '',
		),
		$attr,
		'gallery'
	);

	$id = intval( $atts['id'] );

	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts(
			array(
				'include'        => $atts['include'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
			)
		);

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[ $val->ID ] = $_attachments[ $key ];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children(
			array(
				'post_parent'    => $id,
				'exclude'        => $atts['exclude'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
			)
		);
	} else {
		$attachments = get_children(
			array(
				'post_parent'    => $id,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
			)
		);
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}

		return $output;
	}

	$itemtag    = tag_escape( $atts['itemtag'] );
	$captiontag = tag_escape( $atts['captiontag'] );
	$icontag    = tag_escape( $atts['icontag'] );
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) ) {
		$itemtag = 'li';
	}
	if ( ! isset( $valid_tags[ $captiontag ] ) ) {
		$captiontag = 'div';
	}
	if ( ! isset( $valid_tags[ $icontag ] ) ) {
		$icontag = 'div';
	}

	$gallery_style = '';

	$gallery_div = "<div id='js-carouselView{$instance}' class='carousel js-carouselView'>"
	               . "<ul class='handle carousel-handle'>";

	$output = $gallery_style . $gallery_div;

	foreach ( $attachments as $id => $attachment ) {

		$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
		if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
			$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
		} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
			$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
		} else {
			$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
		}

		$output .= "<{$itemtag} class='carousel-handle-slide'>";
		$output .= "
			<{$icontag} class='carousel-handle-slide-media'>
				$image_output
			</{$icontag}>";
		if ( $captiontag && trim( $attachment->post_excerpt ) ) {
			$output .= "
				<{$captiontag} class='carousel-handle-slide-caption'>
				" . wptexturize( $attachment->post_excerpt ) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
	}

	$output .= "</ul><div class='carousel-captionBox'></div></div>\n";

	return $output;
}

add_filter( 'post_gallery', 'internetorg_post_gallery_filter', 10, 3 );
