<?php
/**
 * Internet.org functions and definitions
 *
 * @package Internet.org
 */

// WP VIP Helper Plugin -- gives us access to the VIP only functions.
define( 'IO_DIR', __DIR__ );
require_once( WP_CONTENT_DIR . '/themes/vip/plugins/vip-init.php' );

wpcom_vip_load_plugin( 'multiple-post-thumbnails' );

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
endif;

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
	 * @param WP_Query $query the WP_Query object to operate on.
	 *
	 * @return WP_Query
	 */
	function internetorg_extend_search_post_type_range( $query ) {

		$search = $query->get( 's', null );

		if ( ! empty( $search ) ) {
			$query->set(
				'post_type',
				array(
					'post',
					'page',
					'io_press',
					'io_story',
					'io_campaign',
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
	 * Retrieve an array of free services offered.
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

		/** @var array|bool $services An array of cached io_freesvc data, else false on failure. */
		$services = wp_cache_get( 'internetorg_free_services_list' );

		// Return early if the cached data was successfully returned.
		if ( false !== $services ) {
			return $services;
		}

		/** @var array $args An array of arguments for a new WP_Query. */
		$args = array(
			'post_type'   => 'io_freesvc',
			'post_status' => 'publish',
		);

		/** @var array $services An array to hold io_freesvc data. */
		$services = array();

		/** @var WP_Query $svcqry A WP_Query to retrieve io_freesvc post objects. */
		$svcqry = new WP_Query( $args );

		/** Build array of services so we are not passing around a query object. */
		while ( $svcqry->have_posts() ) :

			/** Set up the current post object. */
			$svcqry->the_post();

			if ( ! has_post_thumbnail( $svcqry->post->ID ) ) {
				/** @var string $image_url URL to a featured image or default image file. */
				$image_url = get_stylesheet_directory_uri()
				             . '/_static/web/assets/media/images/icons/png/icon-services-dictionary.png';
			} else {
				$image_url = internetorg_get_post_thumbnail( $svcqry->post->ID );
			}

			$services[] = array(
				'post_id' => $svcqry->post->ID,
				'slug'    => $svcqry->post->post_name,
				'title'   => $svcqry->post->post_title,
				'excerpt' => $svcqry->post->post_excerpt,
				'image'   => $image_url,
			);

		endwhile;

		// Caching the compiled array and not the query (86400 == 1 day).
		wp_cache_set( 'internetorg_free_services_list', $services, null, 86400 );

		// Reset the query to before we started mucking with it.
		wp_reset_postdata();

		return $services;
	}
endif;

/**
 * Enqueue Scripts and Styles.
 */
require get_template_directory() . '/inc/enqueue-scripts.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Language related functionality
 */
require get_template_directory() . '/inc/language.php';

/**
 * Custom menu walkers
 */
require get_template_directory() . '/inc/menu-walkers.php';

/**
 * Get the "subtitle" from the page_subtitle custom field.
 *
 * @see internetorg_create_after_title_fields_internetorg_page_home()
 *
 * @param int $post_id The post ID from which to retrieve the subtitle meta.
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
 * Get the page_intro_block custom field data.
 *
 * @param int    $post_id The post ID to retrieve the page_intro_block for.
 * @param string $key The intro_title or intro_content key. Defaults to empty (all key => value pairs).
 *
 * @return string|array Specified value of key=>value pair as string, the entire array, else empty string on failure.
 */
function internetorg_get_the_intro_block( $post_id = 0, $key = '' ) {

	$post_id = absint( $post_id );

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( empty( $post_id ) ) {
		return '';
	}

	/** @var array $intro_meta An array of the unserialized data of the page_intro_block custom field meta */
	$intro_meta = get_post_meta( $post_id, 'page_intro_block', true );

	if ( empty( $intro_meta ) ) {
		return '';
	}

	/** @var array $allowed_keys The keys that we are allowed to retrieve specifically from the $intro_meta array */
	$allowed_keys = array(
		'intro_title',
		'intro_content',
	);

	if ( in_array( $key, $allowed_keys ) ) {
		return $intro_meta[ $key ];
	}

	return $intro_meta;
}

/**
 * Get the post thumbnail (featured image) for the supplied post by post ID.
 *
 * @param int $post_id Post ID to retrieve featured image from.
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
 * @param array    $fields An array of instances of the Babble_Meta_Field_* classes.
 * @param \WP_Post $post   The WP_Post object which is to be translated.
 *
 * @return array An array of instances of the Babble_Meta_Field_* classes.
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
 * @param bool   $sync     True if the meta_key is NOT to be translated and SHOULD be synced.
 * @param string $meta_key The name of the post meta meta_key.
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
 * If the filtered output isn't empty... we're going to ignore it and roll own anyway.
 *
 * @param string $output   The gallery output. Default empty.
 * @param array  $attr     Attributes of the gallery shortcode.
 * @param int    $instance Unique numeric ID of this gallery shortcode instance.
 *
 * @return string
 */
function internetorg_post_gallery_filter( $output, $attr, $instance ) {

	$post = get_post();

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
				'include'          => $atts['include'],
				'post_status'      => 'inherit',
				'post_type'        => 'attachment',
				'post_mime_type'   => 'image',
				'order'            => $atts['order'],
				'orderby'          => $atts['orderby'],
				'suppress_filters' => false,
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

	$selector = "js-carouselView{$instance}";

	$gallery_div = "<div id='{$selector}' class='carousel js-carouselView'>";
	$gallery_div .= '<ul class="handle carousel-handle">';

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

/**
 * Filter the image caption shortcode output to remove the inline width style and image width/height attributes.
 *
 * @param  string $output The image caption markup to filter.
 *
 * @return string
 */
function internetorg_img_caption_filter( $output ) {
	$output = preg_replace( '/style=\"width: [0-9]{1,}px\"/', '', $output );
	$output = preg_replace( '/(width|height)=\"\d{1,}\"\s/', '', $output );

	return $output;
}

add_filter( 'img_caption_shortcode', 'internetorg_img_caption_filter' );

/**
 * Get the Markup for a "content widget".
 *
 * @param string $widget_slug   Slug of the widget to lookup.
 * @param bool   $cta_as_button Whether to add the 'btn' class or not.
 *
 * @return string
 */
function get_internet_org_get_content_widget_html( $widget_slug, $cta_as_button = true ) {

	$out = '<div class="topicBlock">';

	$widget = internetorg_get_content_widget_by_slug( $widget_slug );

	if ( ! empty( $widget ) || ( isset( $widget['post'] ) && empty( $widget['post'] ) ) ) {
		$meta = ( ! empty( $widget['meta'] ) ? $widget['meta'] : null );
		$post = $widget['post'];

		$out .= '<div class="topicBlock-hd"><h2 class="hdg hdg_3">' . esc_html( $post->post_title ) . '</h2></div>';
		$out .= '<div class="topicBlock-bd">';
		$out .= '<p class="bdcpy">' . wp_kses_post( $post->post_content ) . '</p>';

		if ( ! empty( $meta ) && ! empty( $meta['widget-data'] ) ) {
			foreach ( $meta['widget-data'] as $cta ) {
				$label = ( ! empty( $cta['label'] ) ? $cta['label'] : '' );
				$url   = ( ! empty( $cta['url'] ) ? $cta['url'] : '' );
				$file  = ( ! empty( $cta['image'] ) ? $cta['image'] : '' );

				$link = $url ? $url : $file;
				if ( ! empty( $link ) ) {
					$out .=
						'<div class="topicBlock-cta"><a href="' . esc_url( ! empty( $link ) ? $link : '' )
						. '" class="' . ( $cta_as_button ? 'btn' : 'link link_twoArrows' )
						. '">' . esc_html( $label ) . '</a></div>';
				}
			}
		}

		$out .= '</div>';
	}

	$out .= '</div>';

	return $out;
}

/**
 * Escape the Markup for a "content widget".
 *
 * @param string $widget_slug   Slug of the widget to lookup.
 * @param bool   $cta_as_button Whether to add the 'btn' class or not.
 */
function internet_org_get_content_widget_html( $widget_slug, $cta_as_button = true ) {
	echo wp_kses_post( get_internet_org_get_content_widget_html( $widget_slug, $cta_as_button ) );
}

/**
 * Conditionally check if provided URL appears to be an internal link.
 *
 * @param string $url URL to test.
 *
 * @return bool
 */
function internetorg_is_internal_url( $url ) {

	// No URL to test.
	if ( empty( $url ) ) {
		return false;
	}

	// Relative URL beginning with forward slash.
	if ( substr( $url, 0, 1 ) === '/' ) {
		return true;
	}

	// Relative URL beginning with two dots.
	if ( substr( $url, 0, 3 ) === '../' ) {
		return true;
	}

	/** @var array $link_parsed Associative array of URL components returned by parse_url for the provided url. */
	$link_parsed = parse_url( $url );

	if ( empty( $link_parsed['host'] ) ) {
		return false;
	}

	/** @var array $home_parsed Associative array of URL components returned by parse_url for the home_url. */
	$home_parsed = parse_url( home_url() );

	// Hostname match.
	if ( strtolower( $link_parsed['host'] ) === strtolower( $home_parsed['host'] ) ) {
		return true;
	}

	/** @var string $link_parsed_host remove www. for comparison. */
	$link_parsed_host = str_ireplace( 'www.', '', $link_parsed['host'] );

	/** @var string $home_parsed_host remove www. for comparison. */
	$home_parsed_host = str_ireplace( 'www.', '', $home_parsed['host'] );

	if ( strtolower( $link_parsed_host ) === strtolower( $home_parsed_host ) ) {
		return true;
	}

	return false;
}

/**
 * Get the URL of a valid image attachment by attachment ID.
 *
 * @param int          $attachment_id Image attachment ID. Required.
 * @param string|array $size          Optional. Registered image size name or width/height array.
 *
 * @return string The URL of the image attachment or empty string on failure.
 */
function internetorg_get_media_image_url( $attachment_id = 0, $size = 'single-post-thumbnail' ) {

	$attachment_id = absint( $attachment_id );

	if ( empty( $attachment_id ) ) {
		return '';
	}

	/** @var bool|array $image_src An array of image data, else false on failure */
	$image_src = wp_get_attachment_image_src( $attachment_id, $size );

	if ( empty( $image_src ) ) {
		return '';
	}

	return $image_src[0];

}

/**
 * Register rewrite endpoints for AJAX calls.
 *
 * @link https://vip.wordpress.com/documentation/wp_rewrite/
 * @link https://10up.github.io/Engineering-Best-Practices/php/#ajax-endpoints
 *
 * @action init
 */
function internetorg_add_ajax_endpoints() {

	/** AJAX search endpoint */
	add_rewrite_tag( '%ajax_search_term%', '(.+)' );
	add_rewrite_rule( 'io-ajax-search/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?ajax_search_term=$matches[1]&paged=$matches[2]', 'top' );
	add_rewrite_rule( 'io-ajax-search/([^/]+)/?$', 'index.php?ajax_search_term=$matches[1]', 'top' );

	/** AJAX load more posts endpoint */
	add_rewrite_tag( '%ajax_post_type%', '(.+)' );
	add_rewrite_rule( 'io-ajax-posts/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?ajax_post_type=$matches[1]&paged=$matches[2]', 'top' );
	add_rewrite_rule( 'io-ajax-posts/([^/]+)/?$', 'index.php?ajax_post_type=$matches[1]', 'top' );
}

add_action( 'init', 'internetorg_add_ajax_endpoints' );

/**
 * Add our ajax query vars to the public_query_vars array.
 *
 * This will allow us to retrieve query var data without using global $wp_query->get.
 *
 * @filter query_vars
 *
 * @param array $public_query_vars The array of public query variables.
 *
 * @return array
 */
function internetorg_add_ajax_query_vars( $public_query_vars ) {
	$public_query_vars[] = 'ajax_search_term';
	$public_query_vars[] = 'ajax_post_type';

	return $public_query_vars;
}

add_filter( 'query_vars', 'internetorg_add_ajax_query_vars' );

/**
 * Do the ajax search.
 *
 * If the ajax_search_term query var is empty, return so that we don't hijack all template redirects.
 *
 * @link https://vip.wordpress.com/documentation/wp_rewrite/
 * @link https://10up.github.io/Engineering-Best-Practices/php/#ajax-endpoints
 *
 * @action template_redirect
 */
function internetorg_do_ajax_search() {

	/** @var string $ajax_search_term The search term query var. */
	$ajax_search_term = get_query_var( 'ajax_search_term' );

	// No ajax search term, return early and let template_redirect run it's course.
	if ( empty( $ajax_search_term ) ) {
		return;
	}

	$ajax_search_term = sanitize_text_field( urldecode( $ajax_search_term ) );

	/** @var int $ajax_search_paged Pagination query var if present else 0. */
	$ajax_search_paged = absint( get_query_var( 'paged' ) );

	if ( empty( $ajax_search_paged ) ) {
		$ajax_search_paged = 1;
	}

	/** @var array $args An array of WP_Query args. */
	$args = array(
		's'     => $ajax_search_term,
		'paged' => $ajax_search_paged,
	);

	/** @var \WP_Query $query A WP_Query for the specified "page" of search results. */
	$query = new WP_Query( $args );

	if ( is_wp_error( $query ) || ! $query->have_posts() ) {
		wp_send_json_error( array() );
	}

	/** @var array $data Return data array for wp_send_json_success. */
	$data = array(
		'found_posts'   => absint( $query->found_posts ),
		'paged'         => absint( $query->get( 'paged' ) ),
		'max_num_pages' => absint( $query->max_num_pages ),
		'posts'         => array(),
	);

	while ( $query->have_posts() ) {
		$query->the_post();

		/** @var string $post_thumbnail URL of the post thumbnail or an empty string. */
		$post_thumbnail = internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), array( 210, 260 ) );

		$data['posts'][] = array(
			'ID'           => get_the_ID(),
			'post_title'   => get_the_title(),
			'post_excerpt' => get_the_excerpt(),
			'permalink'    => get_the_permalink(),
			'post_thumbnail' => $post_thumbnail,
		);
	}

	wp_send_json_success( $data );

}

add_action( 'template_redirect', 'internetorg_do_ajax_search' );

/**
 * Do the ajax load more posts.
 *
 * If the ajax_post_type query var is empty, return so that we don't hijack all template redirects.
 *
 * @link https://vip.wordpress.com/documentation/wp_rewrite/
 * @link https://10up.github.io/Engineering-Best-Practices/php/#ajax-endpoints
 */
function internetorg_do_ajax_more_posts() {

	/** @var string $ajax_post_type The post type query var. */
	$ajax_post_type = get_query_var( 'ajax_post_type' );

	// No ajax post type, return early and let template_redirect run it's course.
	if ( empty( $ajax_post_type ) ) {
		return;
	}

	$ajax_post_type = sanitize_title_for_query( urldecode( $ajax_post_type ) );

	if ( 'press' == $ajax_post_type ) {
		$ajax_post_type = 'post';
	}

	/** @var array $allowed_post_types A whitelist array of public post types to compare against. */
	$allowed_post_types = get_post_types( array( 'public' => true ), 'names' );

	if ( ! in_array( $ajax_post_type, $allowed_post_types ) ) {
		wp_send_json_error( array() );
	}

	/** @var int $ajax_paged Pagination query var if present else 0. */
	$ajax_paged = absint( get_query_var( 'paged' ) );

	if ( empty( $ajax_paged ) ) {
		$ajax_paged = 1;
	}

	/** @var array $args An array of WP_Query args. */
	$args = array(
		'post_type' => $ajax_post_type,
		'paged'     => $ajax_paged,
	);

	/** @var \WP_Query $query A WP_Query for the specified "page" of post type archive results. */
	$query = new WP_Query( $args );

	if ( is_wp_error( $query ) || ! $query->have_posts() ) {
		wp_send_json_error( array() );
	}

	/** @var array $data Return data array for wp_send_json_success. */
	$data = array(
		'found_posts'   => absint( $query->found_posts ),
		'paged'         => absint( $query->get( 'paged' ) ),
		'max_num_pages' => absint( $query->max_num_pages ),
		'posts'         => array(),
	);

	while ( $query->have_posts() ) {
		$query->the_post();

		/** @var string $post_thumbnail URL of the post thumbnail or an empty string. */
		$post_thumbnail = internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), array( 210, 260 ) );

		$data['posts'][] = array(
			'ID'             => get_the_ID(),
			'post_title'     => get_the_title(),
			'post_date'      => get_the_date(),
			'post_excerpt'   => get_the_excerpt(),
			'permalink'      => get_the_permalink(),
			'post_thumbnail' => $post_thumbnail,
		);
	}

	wp_send_json_success( $data );

}

add_action( 'template_redirect', 'internetorg_do_ajax_more_posts' );

if ( class_exists( 'MultiPostThumbnails' ) ) {
	new MultiPostThumbnails(
		array(
			'label'     => 'Mobile Featured Image',
			'id'        => 'mobile-featured-image',
			'post_type' => 'page',
		)
	);
}

/**
 * Get the "mobile featured image" as registered with the Multiple Post Thumbnails plugin.
 *
 * If the MultiPostThumbnails class is not present, or the "mobile featured image" has not been populated in the post,
 * then use the internetorg_get_post_thumbnail function to retrieve the standard featured image.
 *
 * @see MultiPostThumbnails::has_post_thumbnail
 * @see MultiPostThumbnails::get_post_thumbnail_url
 * @see internetorg_get_post_thumbnail
 *
 * @param string $post_type The post type that we are retrieving the "mobile featured image" for.
 * @param int    $post_id The ID of the post that we are retrieving the "mobile featured image" for.
 *
 * @return string
 */
function internetorg_get_mobile_featured_image( $post_type, $post_id ) {

	$post_id = absint( $post_id );

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( empty( $post_id ) ) {
		return '';
	}

	$post_type = sanitize_title( $post_type );

	if ( empty( $post_type ) ) {
		$post_type = get_post_type( $post_id );
	}

	$allowed_post_types = get_post_types();

	if ( ! in_array( $post_type, $allowed_post_types ) ) {
		return '';
	}

	if ( ! class_exists( 'MultiPostThumbnails' ) ) {
		return internetorg_get_post_thumbnail( $post_id );
	}

	$id = 'mobile-featured-image';

	$has_post_thumbnail = MultiPostThumbnails::has_post_thumbnail( $post_type, $id, $post_id );

	if ( empty( $has_post_thumbnail ) ) {
		return '';
	}

	$img_url = MultiPostThumbnails::get_post_thumbnail_url( $post_type, $id, $post_id, 'full' );

	if ( empty( $img_url ) ) {
		return internetorg_get_post_thumbnail( $post_id );
	}

	return $img_url;
}

/**
 * Get the "page theme" attribute of the page-template by post ID.
 *
 * @param int $post_id The post ID of the page to retrieve theme for.
 *
 * @return string The page theme, defined by page-template else Approach.
 */
function internetorg_get_page_theme( $post_id = 0 ) {

	/** @var array $allowed_themes An array of possible "page themes". */
	$allowed_themes = array(
		'Approach',
		'Mission',
		'Impact',
	);

	/** @var string $default_theme A default page theme if one cannot be determined. */
	$default_theme = $allowed_themes[0];

	$post_id = absint( $post_id );

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( empty( $post_id ) ) {
		return $default_theme;
	}

	/** @var string|bool $page_template_slug The name of the page template, else empty string or false. */
	$page_template_slug = get_page_template_slug( $post_id );

	if ( empty( $page_template_slug ) ) {
		return $default_theme;
	}

	$page_template_slug = str_ireplace( '.php', '', $page_template_slug );

	/** @var array $slug_array Array of strings from exploded $page_template_slug. */
	$slug_array = explode( '-', $page_template_slug );

	/** @var string $slug The last element of the $slug_array. */
	$slug = end( $slug_array );

	/** @var string $theme Uppercased version of slug. */
	$theme = ucwords( $slug );

	if ( ! in_array( $theme, $allowed_themes ) ) {
		return $default_theme;
	}

	return $theme;
}

/**
 * Get the permalink to the current archive.
 *
 * @see get_the_archive_title for inspiration.
 *
 * @return string
 */
function internetorg_get_archive_link() {
	if ( is_category() ) {
		$link = get_category_link( get_queried_object_id() );
	} elseif ( is_tag() ) {
		$link = wpcom_vip_get_term_link( get_queried_object() );
	} elseif ( is_author() ) {
		$link = get_author_posts_url( get_queried_object_id() );
	} elseif ( is_year() ) {
		$link = get_year_link( '' );
	} elseif ( is_month() ) {
		$link = get_month_link( '', '' );
	} elseif ( is_day() ) {
		$link = get_day_link( '', '', '' );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$link = get_post_format_link( 'post-format-aside' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$link = get_post_format_link( 'post-format-gallery' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$link = get_post_format_link( 'post-format-image' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$link = get_post_format_link( 'post-format-video' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$link = get_post_format_link( 'post-format-quote' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$link = get_post_format_link( 'post-format-link' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$link = get_post_format_link( 'post-format-status' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$link = get_post_format_link( 'post-format-audio' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$link = get_post_format_link( 'post-format-chat' );
		}
	} elseif ( is_post_type_archive() ) {
		$link = get_post_type_archive_link( get_post_type() );
	} elseif ( is_tax() ) {
		$link = wpcom_vip_get_term_link( get_queried_object() );
	} else {
		$link = home_url( '/' );
	}

	if ( empty( $link ) || is_wp_error( $link ) ) {
		$link = home_url( '/' );
	}

	return $link;
}
