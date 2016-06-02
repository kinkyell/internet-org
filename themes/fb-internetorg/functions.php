<?php
/**
 * Internet.org functions and definitions
 *
 * @package Internet.org
 */

// WP VIP Helper Plugin -- gives us access to the VIP only functions.
define( 'IO_DIR', __DIR__ );

// Load the Shortcake UI VIP Plugin.
wpcom_vip_load_plugin( 'shortcode-ui' );

// Load the Multiple Post Thumbnails VIP Plugin.
wpcom_vip_load_plugin( 'multiple-post-thumbnails' );

// Load the Google Analytics VIP plugin.
wpcom_vip_load_plugin( 'wp-google-analytics' );

// Load the Cache Nave Menu VIP plugin.
wpcom_vip_load_plugin( 'cache-nav-menu' );

/** Filtering functions. */
require IO_DIR . '/inc/internetorg-filters.php';

/** Custom Post Types. */
wpcom_vip_load_plugin( 'internetorg-custom-posttypes' );

/** Fieldmanager and Fields. */
wpcom_vip_load_plugin( 'fieldmanager' );
wpcom_vip_load_plugin( 'internetorg-custom-fields' );

/** Babble */
require IO_DIR . '/inc/babble-fieldmanager-context.php';

/** Image helpers */
require IO_DIR . '/inc/internetorg-images.php';

/** Responisve images */
require IO_DIR . '/inc/internetorg-responsive-images.php';

/** Disable emoji from loading */
function disable_wp_emojicons() {
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'init', 'disable_wp_emojicons' );

function disable_emojicons_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

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
		 * to change 'internetorg' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'internetorg', WP_CONTENT_DIR . '/languages' );

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

		// Register navigation menus.
		internetorg_register_menus();

		/*
		 * Switch default core markup for search form, comment form, and comments to output valid HTML5.
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
		 * @link http://codex.wordpress.org/Post_Formats
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
/*
*Get thumbnail url of a youtube video using youtube id 
*/
function internetorg_get_youtube_thumbnail_url( $id ) {
	$maxres = 'http://img.youtube.com/vi/' . $id . '/maxresdefault.jpg';
	$response = wp_remote_head( $maxres );
	if ( !is_wp_error( $response ) && $response['response']['code'] == '200' ) {
		$result = $maxres;
	} else {
		$result = 'http://img.youtube.com/vi/' . $id . '/0.jpg';
	}
	return $result;
}

/*
*Extract youtube id from youtube video url 
*/
function internetorg_scan_for_youtube_thumbnail( $markup ) {

	$regexes = array(
		'#(?:https?:)?//www\.youtube(?:\-nocookie)?\.com/(?:v|e|embed)/([A-Za-z0-9\-_]+)#', // Comprehensive search for both iFrame and old school embeds
		'#(?:https?(?:a|vh?)?://)?(?:www\.)?youtube(?:\-nocookie)?\.com/watch\?.*v=([A-Za-z0-9\-_]+)#', // Any YouTube URL. After http(s) support a or v for Youtube Lyte and v or vh for Smart Youtube plugin
		'#(?:https?(?:a|vh?)?://)?youtu\.be/([A-Za-z0-9\-_]+)#', // Any shortened youtu.be URL. After http(s) a or v for Youtube Lyte and v or vh for Smart Youtube plugin
		'#<div class="lyte" id="([A-Za-z0-9\-_]+)"#', // YouTube Lyte
		'#data-youtube-id="([A-Za-z0-9\-_]+)"#' // LazyYT.js
	);


	foreach ( $regexes as $regex ) {
		if ( preg_match( $regex, $markup, $matches ) ) {
			return internetorg_get_youtube_thumbnail_url( $matches[1] );
		}
	}
}

/*
*Get thumbnail url of a vimeo video using vimeo id 
*/
function internetorg_get_vimeo_thumbnail_url( $id ) {
		// Get our settings
	$request = "http://vimeo.com/api/oembed.json?url=http%3A//vimeo.com/$id";
	$response = wp_remote_get( $request );
	if( is_wp_error( $response ) ) {
		$result = '';
	} elseif ( $response['response']['code'] == 404 ) {
		$result = '';
	} elseif ( $response['response']['code'] == 403 ) {
		$result = '';
	} else {
		$result = json_decode( $response['body'] );
		$result = $result->thumbnail_url;
	}
	
	return $result;
}

/*
*Extract vimeo id from vimeo video url 
*/
function internetorg_scan_for_vimeo_thumbnail( $markup ) {

	$regexes = array(
		'#<object[^>]+>.+?http://vimeo\.com/moogaloop.swf\?clip_id=([A-Za-z0-9\-_]+)&.+?</object>#s', // Standard Vimeo embed code
		'#(?:https?:)?//player\.vimeo\.com/video/([0-9]+)#', // Vimeo iframe player
		'#\[vimeo id=([A-Za-z0-9\-_]+)]#', // JR_embed shortcode
		'#\[vimeo clip_id="([A-Za-z0-9\-_]+)"[^>]*]#', // Another shortcode
		'#\[vimeo video_id="([A-Za-z0-9\-_]+)"[^>]*]#', // Yet another shortcode
		'#(?:https?://)?(?:www\.)?vimeo\.com/([0-9]+)#', // Vimeo URL
		'#(?:https?://)?(?:www\.)?vimeo\.com/channels/(?:[A-Za-z0-9]+)/([0-9]+)#' // Channel URL
	);


	foreach ( $regexes as $regex ) {
		if ( preg_match( $regex, $markup, $matches ) ) {
			return internetorg_get_vimeo_thumbnail_url( $matches[1] );
		}
	}
}

/*
*check if the video is from youtube or vimeo
*then get the thumbnail url of the respective video 
*/
function internetorg_get_thumbnail($url) {
	$pos = strpos($url, "youtube.com");
	if($pos===false) {
		$pos = strpos($url, "vimeo.com");
		if($pos!==false) {
			return internetorg_scan_for_vimeo_thumbnail($url);	
		} else {
			return '';
		}
	} else {
		return internetorg_scan_for_youtube_thumbnail($url);
	}
}



/**
 * Register additional image sizes.
 */
function internetorg_setup_image_sizes() {

	// Hard cropped image 960 x 1200 for use in "Panel".
	add_image_size( 'panel-image', 960, 1200, true );

	// Soft cropped image 720 x whatever for use in content or the "mobile only" thumbnail.
	add_image_size( 'inline-image', 720, 9999 );

	// Hard cropped image 315 x 390 for use in "listings" like press.
	add_image_size( 'listing-image', 315, 390, array( 'left', 'center' ) );
}

add_action( 'after_setup_theme', 'internetorg_setup_image_sizes' );

/**
 * Add custom image sizes to the media chooser.
 *
 * Default values include 'Thumbnail', 'Medium', 'Large', 'Full Size'.
 *
 * @param array $sizes Registered image sizes and their names.
 *
 * @return array
 */
function internetorg_custom_sizes( $sizes ) {
	return array_merge( $sizes, array( 'inline-image' => __( 'Inline Image' ) ) );
}

add_filter( 'image_size_names_choose', 'internetorg_custom_sizes' );

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

		/**
		 * An array of cached io_freesvc data, else false on failure.
		 *
		 * @var array|bool $services
		 */
		$services = wp_cache_get( 'internetorg_free_services_list' );

		// Return early if the cached data was successfully returned.
		if ( false !== $services ) {
			return $services;
		}

		/**
		 * An array of arguments for a new WP_Query.
		 *
		 * @var array $args
		 */
		$args = array(
			'post_type'   => 'io_freesvc',
			'post_status' => 'publish',
		);

		/**
		 * An array to hold io_freesvc data.
		 *
		 * @var array $services
		 */
		$services = array();

		/**
		 * A WP_Query to retrieve io_freesvc post objects.
		 *
		 * @var WP_Query $svcqry
		 */
		$svcqry = new WP_Query( $args );

		/** Build array of services so we are not passing around a query object. */
		while ( $svcqry->have_posts() ) :

			/** Set up the current post object. */
			$svcqry->the_post();

			if ( ! has_post_thumbnail( $svcqry->post->ID ) ) {
				/**
				 * URL to a featured image or default image file.
				 *
				 * @var string $image_url
				 */
				$image_url = get_stylesheet_directory_uri()
										 . '/_static/web/assets/media/images/icons/png/icon-services-dictionary.png';
			} else {
				$image_url = internetorg_get_post_thumbnail( $svcqry->post->ID, 'listing-image' );
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

	/**
	 * An array of the unserialized data of the page_intro_block custom field meta
	 *
	 * @var array $intro_meta
	 */
	$intro_meta = get_post_meta( $post_id, 'page_intro_block', true );

	if ( empty( $intro_meta ) ) {
		return '';
	}

	/**
	 * The keys that we are allowed to retrieve specifically from the $intro_meta array.
	 *
	 * @var array $allowed_keys
	 */
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
 * @param int    $post_id Post ID to retrieve featured image from.
 * @param string $size    Optional. The registered image size to retrieve. Defaults to 'full'.
 *
 * @return string
 */
function internetorg_get_post_thumbnail( $post_id = 0, $size = 'full' ) {

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

	$attachment_image_src = wp_get_attachment_image_src( $post_thumbnail_id, $size );

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

	// Lets enabled a way to filter widget content.
	if ( isset( $widget['meta']['widget-data'] ) && is_array( $widget['meta']['widget-data'] ) ) {
		foreach ( $widget['meta']['widget-data'] as $key => $widget_data ) {

			if ( isset( $widget_data['label'] ) ) {
				$widget['meta']['widget-data'][$key]['label'] = apply_filters( 'widget_data_label_filter', $widget_data['label'] );
			}

			if ( isset( $widget_data['url'] ) ) {
				$widget['meta']['widget-data'][$key]['url']   = apply_filters( 'widget_data_url_filter',   $widget_data['url']   );
			}
		}
	}

	if ( ! empty( $widget ) || ( isset( $widget['post'] ) && empty( $widget['post'] ) ) ) {
		$meta = ( ! empty( $widget['meta'] ) ? $widget['meta'] : null );
		$post = $widget['post'];

		$out .= '<div class="topicBlock-hd"><h2 class="hdg hdg_8 mix-hdg_bold">' . esc_html( $post->post_title ) . '</h2></div>';
		$out .= '<div class="topicBlock-bd">';
		$out .= '<p class="bdcpy">' . wp_kses_post( $post->post_content ) . '</p>';

		if ( ! empty( $meta ) && ! empty( $meta['widget-data'] ) ) {
			foreach ( $meta['widget-data'] as $cta ) {
				$label = ( ! empty( $cta['label'] ) ? $cta['label'] : '' );
				$url   = ( ! empty( $cta['url'] ) ? $cta['url'] : '' );
				$file  = ( ! empty( $cta['image'] ) ? $cta['image'] : '' );

				$link = $url ? $url : $file;

				if ( ! empty( $link ) ) {

					if ( internetorg_is_internal_url( $link ) ) {
						$target = '';
					} else {
						$target = 'target="_blank"';
					}

					$out .=
						'<div class="topicBlock-cta"><a href="' . esc_url( ! empty( $link ) ? apply_filters( 'iorg_url', $link ) : '' )
						. '" class="' . ( $cta_as_button ? 'btn' : 'link link_twoArrows' )
						. '" ' . $target . '>' . esc_html( $label ) . '</a></div>';
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

	/**
	 * Associative array of URL components returned by parse_url for the provided url.
	 *
	 * @var array $link_parsed
	 */
	$link_parsed = parse_url( $url );

	if ( empty( $link_parsed['host'] ) ) {
		return false;
	}

	/**
	 * Associative array of URL components returned by parse_url for the home_url.
	 *
	 * @var array $home_parsed
	 */
	$home_parsed = parse_url( home_url() );

	// Hostname match.
	if ( strtolower( $link_parsed['host'] ) === strtolower( $home_parsed['host'] ) ) {
		return true;
	}

	/**
	 * Remove www. for comparison.
	 *
	 *  @var string $link_parsed_host
	 */
	$link_parsed_host = str_ireplace( 'www.', '', $link_parsed['host'] );

	/**
	 * Remove www. for comparison.
	 *
	 * @var string $home_parsed_host
	 */
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
 * @param string|array $size          Optional. Registered image size name or width/height array. Defaults to full.
 *
 * @return string The URL of the image attachment or empty string on failure.
 */
function internetorg_get_media_image_url( $attachment_id = 0, $size = 'panel-image' ) {

	$attachment_id = absint( $attachment_id );

	if ( empty( $attachment_id ) ) {
		return '';
	}

	/**
	 * An array of image data, else false on failure.
	 *
	 * @var bool|array $image_src
	 */
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

	/** Add the ajax_search_term rewrite tag. */
	add_rewrite_tag( '%ajax_search_term%', '(.+)' );

	/** Add a rewrite rule to match ajax_search_term with paging param. */
	add_rewrite_rule(
		'io-ajax-search/([^/]+)/page/?([0-9]{1,})/?$',
		'index.php?ajax_search_term=$matches[1]&paged=$matches[2]',
		'top'
	);

	/** Add a rewrite rule to match ajax_search_term. */
	add_rewrite_rule( 'io-ajax-search/([^/]+)/?$', 'index.php?ajax_search_term=$matches[1]', 'top' );

	/** Add the ajax_post_type rewrite tag. */
	add_rewrite_tag( '%ajax_post_type%', '(.+)' );

	/** Add the ajax_year rewrite tag. */
	add_rewrite_tag( '%ajax_year%', '(/d{4})' );

	/** Add a rewrite rule to match ajax_post_type with ajax_year and paging param. */
	add_rewrite_rule(
		'io-ajax-posts/([^/]+)/year/?([0-9]{4})/?page/?([0-9]{1,})/?$',
		'index.php?ajax_post_type=$matches[1]&ajax_year=$matches[2]&paged=$matches[3]',
		'top'
	);

	/** Add a rewrite rule to match ajax_post_type with ajax_year param. */
	add_rewrite_rule(
		'io-ajax-posts/([^/]+)/year/?([0-9]{4})/?$',
		'index.php?ajax_post_type=$matches[1]&ajax_year=$matches[2]',
		'top'
	);

	/** Add a rewrite rule to match ajax_post_type with paging param. */
	add_rewrite_rule(
		'io-ajax-posts/([^/]+)/page/?([0-9]{1,})/?$',
		'index.php?ajax_post_type=$matches[1]&paged=$matches[2]',
		'top'
	);

	/** Add a rewrite rule to match ajax_post_type. */
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
	$public_query_vars[] = 'ajax_year';

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

	/**
	 * The search term query var.
	 *
	 * @var string $ajax_search_term
	 */
	$ajax_search_term = get_query_var( 'ajax_search_term' );

	// No ajax search term, return early and let template_redirect run it's course.
	if ( empty( $ajax_search_term ) ) {
		return;
	}

	$ajax_search_term = sanitize_text_field( urldecode( $ajax_search_term ) );

	/**
	 * Pagination query var if present else 0.
	 *
	 * @var int $ajax_search_paged
	 */
	$ajax_search_paged = absint( get_query_var( 'paged' ) );

	if ( empty( $ajax_search_paged ) ) {
		$ajax_search_paged = 1;
	}

	/**
	 * An array of WP_Query args.
	 *
	 * @var array $args
	 */
	$args = array(
		's'     => $ajax_search_term,
		'paged' => $ajax_search_paged,
	);

	/**
	 * A WP_Query for the specified "page" of search results.
	 *
	 * @var \WP_Query $query
	 */
	$query = new WP_Query( $args );

	if ( is_wp_error( $query ) || ! $query->have_posts() ) {
		wp_send_json_error( array() );
	}

	/**
	 * Return data array for wp_send_json_success.
	 *
	 * @var array $data
	 */
	$data = array(
		'found_posts'   => absint( $query->found_posts ),
		'paged'         => absint( $query->get( 'paged' ) ),
		'max_num_pages' => absint( $query->max_num_pages ),
		'posts'         => array(),
	);

	while ( $query->have_posts() ) {
		$query->the_post();

		/**
		 * URL of the post thumbnail or an empty string.
		 *
		 * @var string $post_thumbnail
		 */
		$post_thumbnail = internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'listing-image' );

		/**
		 * URL of the panel-image sized version of the post thumbnail.
		 *
		 * @var string $post_thumbnail
		 */
		$panel_image = ( internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'panel-image' ) )
			? internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'panel-image' )
			: '';

		/**
		 * If the post story is io_story but does not have an image, the current single will show
		 * standard home image so we will have to do the same here.
		 */
		if ( empty( $panel_image ) && get_post_type( get_the_ID() ) == 'io_story' ) {
			$panel_image = get_stylesheet_directory_uri() . '/_static/web/assets/media/uploads/home.jpg';
		}

		/**
		 * URL of the inline image sized version of the "mobile featured image".
		 *
		 * @var string $post_thumbnail
		 */
		$mobile_image = esc_url(
			internetorg_get_mobile_featured_image(
				get_post_type(
					get_the_ID()
				),
				get_the_ID()
			)
		);

		$post_type = get_post_type();

		$showDate = get_post_custom_values('iorg_display_date', get_the_ID()); 
		 $displayDate = "show";
		 $displayFooterPosts = "show";

		if(is_array($showDate) && strtolower($showDate[0])=="n") {
		 	$displayDate = "hide";

		 } else {
		 	$displayDate = "show";

		 }

		$showMedia = get_post_custom_values('iorg_hero_vdo_url', get_the_ID()); 
		$showImage = get_post_custom_values('iorg_hero_image', get_the_ID()); 
		$showHero = get_post_custom_values('iorg_show_hero', get_the_ID()); 
		if(is_array($showHero) && ($showHero[0]!="Y")) {
			if(is_array($showImage)) {
				$showImage[0] = "";
			}
			if(is_array($showMedia)) {
				$showMedia[0] = "";
			}	
		}

		$story_page = get_post_custom_values('iorg_story_page', get_the_ID()); 
		$display_story = "half_screen";
		if(is_array($story_page) && $story_page[0]!="") {
				$display_story = $story_page[0];
			} 
		$header_color = get_post_custom_values('iorg_header_color', get_the_ID()); 
		$header_img_color = get_post_custom_values('iorg_header_img_color', get_the_ID()); 

		if(is_array($showImage) && $showImage[0]=="") {

			if(is_array($showMedia) && $showMedia[0]!="") {

				$thumbnail = internetorg_get_thumbnail($showMedia[0]);
				$showImage[0] = $thumbnail;
			}
		}

		

		$data['posts'][] = array(
			'ID'             => get_the_ID(),
			'post_title'     => get_the_title(),
			'post_excerpt'   => get_the_excerpt(),
			'permalink'      => get_the_permalink(),
			'post_thumbnail' => $post_thumbnail,
			'mobile_image'   => $mobile_image,
			'panel_image'    => $panel_image,
			'post_type'      => $post_type,
			'data-date'      => ($displayDate=='show') ? get_the_date( '' ) : '',
			'data-image-display'      =>  ($display_story=="full_screen")? (is_array($showImage) && $showImage[0]!="") ? $showImage[0] : '' : '',
			'data-video'      => ($display_story=="full_screen")?  (is_array($showMedia) && $showMedia[0]!="")? $showMedia[0] : '' : '',
			'data-story-page'      => $display_story,
			'data-header-color'      => (is_array($header_color) && $header_color[0]!="")? $header_color[0] : '',
			'data-header-img-color'      => (is_array($header_img_color) && $header_img_color[0]!="")? $header_img_color[0] : '',
			'media_embed'    => internetorg_media_embed( true ),
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

	/**
	 * The post type query var.
	 *
	 * @var string $ajax_post_type
	 */
	$ajax_post_type = get_query_var( 'ajax_post_type' );

	// No ajax post type, return early and let template_redirect run it's course.
	if ( empty( $ajax_post_type ) ) {
		return;
	}

	$ajax_post_type = sanitize_title_for_query( urldecode( $ajax_post_type ) );

	if ( 'press' === $ajax_post_type ) {
		$ajax_post_type = 'post';
	}

	/**
	 * A whitelist array of public post types to compare against.
	 *
	 * @var array $allowed_post_types
	 */
	$allowed_post_types = get_post_types( array( 'public' => true ), 'names' );

	if ( ! in_array( $ajax_post_type, $allowed_post_types ) ) {
		wp_send_json_error( array() );
	}

	/**
	 * An array of WP_Query args.
	 *
	 * @var array $args
	 */
	$args = array(
		'post_type' => $ajax_post_type,
	);

	/**
	 * The year query var if present, else 0.
	 *
	 * @var int $ajax_year
	 */
	$ajax_year = absint( get_query_var( 'ajax_year' ) );

	if ( ! empty( $ajax_year ) ) {
		$args['year'] = $ajax_year;
	}

	/**
	 * Pagination query var if present else 0.
	 *
	 * @var int $ajax_paged
	 */
	$ajax_paged = absint( get_query_var( 'paged' ) );

	if ( empty( $ajax_paged ) ) {
		$ajax_paged = 1;
	}

	$args['paged'] = $ajax_paged;

	/**
	 * A WP_Query for the specified "page" of post type archive results.
	 *
	 * @var \WP_Query $query
	 */
	$query = new WP_Query( $args );

	if ( is_wp_error( $query ) || ! $query->have_posts() ) {
		wp_send_json_error( array() );
	}

	/**
	 * Return data array for wp_send_json_success.
	 *
	 * @var array $data
	 */
	$data = array(
		'found_posts'   => absint( $query->found_posts ),
		'paged'         => absint( $query->get( 'paged' ) ),
		'max_num_pages' => absint( $query->max_num_pages ),
		'posts'         => array(),
	);

	while ( $query->have_posts() ) {
		global $post;
		$query->the_post();

		/**
		 * URL of the post thumbnail or an empty string.
		 *
		 * @var string $post_thumbnail
		 */
		$post_thumbnail = internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'listing-image' );

		$post_html = '';

		// Press
		if ( $post->post_type === 'post' ) {
			ob_start();
			get_template_part( 'template-parts/content', 'press-item' );
			$post_html = ob_get_contents();
			ob_end_clean();
		}

		$data['posts'][] = array(
			'ID'             => get_the_ID(),
			'post_title'     => get_the_title(),
			'post_date'      => get_the_date(),
			'post_excerpt'   => get_the_excerpt(),
			'permalink'      => get_the_permalink(),
			'post_thumbnail' => $post_thumbnail,
			'media_embed' 	 => internetorg_media_embed( true ),
			'post_html'			 => $post_html
		);
	}

	wp_reset_postdata();

	wp_send_json_success( $data );

}

add_action( 'template_redirect', 'internetorg_do_ajax_more_posts' );

/**
 * Add "Mobile Featured Image" via VIP approved Multiple Post Thumbnails plugin.
 *
 * Appears the 'post_type' param does not accept an array or comma delimited string of post types. Hence two calls.
 *
 * @link https://github.com/voceconnect/multi-post-thumbnails/wiki
 */
if ( class_exists( 'MultiPostThumbnails' ) ) {
	new MultiPostThumbnails(
		array(
			'label'     => 'Mobile Featured Image',
			'id'        => 'mobile-featured-image',
			'post_type' => 'page',
		)
	);
	new MultiPostThumbnails(
		array(
			'label'     => 'Mobile Featured Image',
			'id'        => 'mobile-featured-image',
			'post_type' => 'io_story',
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
 * @param int    $post_id   The ID of the post that we are retrieving the "mobile featured image" for.
 * @param string $size      Optional. The registered image size to retrieve. Defaults to "inline-image".
 *
 * @return string
 */
function internetorg_get_mobile_featured_image( $post_type, $post_id, $size = 'inline-image' ) {

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

	/**
	 * Allowed post_type whitelist.
	 *
	 * @var array $allowed_post_types
	 */
	$allowed_post_types = get_post_types();

	if ( ! in_array( $post_type, $allowed_post_types ) ) {
		return '';
	}

	if ( ! class_exists( 'MultiPostThumbnails' ) ) {
		return '';
	}

	/**
	 * The ID (or name) of the additional featured image registered with MultiPostThumbnails plugin.
	 *
	 * @var string $id
	 */
	$id = 'mobile-featured-image';

	/**
	 * The MultiPostThumbnails plugin prefixes the 'additional' featured images with the post_type.
	 * Because Babble uses "shadow post_types," for additional languages, we need to account for this.
	 * The mobile featured image meta will be synced as page_mobile-featured-image_thumbnail_id (for example),
	 * not page_ar_mobile-featured-image_thumbnail_id (for example).
	 * So we will use the base post type to get the proper meta_key.
	 */

	/**
	 * An array of "base" post_type objects (excluding the shadow post types registered by Babble).
	 *
	 * @var stdClass[] $base_post_type_objects
	 */
	$base_post_type_objects = internetorg_get_base_post_types();

	/**
	 * An array of just the "base" post_type names.
	 *
	 * @var array $base_post_types
	 */
	$base_post_types = wp_list_pluck( $base_post_type_objects, 'name' );

	/**
	 * If the provided $post_type is a shadow post_type, let's get the base equivalent.
	 */
	if ( ! in_array( $post_type, $base_post_types ) ) {
		$post_type = internetorg_get_base_post_type( $post_type );
	}

	/**
	 * Conditional check for the mobile-featured-image.
	 *
	 * @var bool $has_post_thumbnail
	 */
	$has_post_thumbnail = MultiPostThumbnails::has_post_thumbnail( $post_type, $id, $post_id );

	if ( empty( $has_post_thumbnail ) ) {
		return '';
	}

	/**
	 * Thumbnail url or false if the post doesn't have a thumbnail for the given post type, and id.
	 *
	 * @var string|bool $img_url
	 */
	$img_url = MultiPostThumbnails::get_post_thumbnail_url( $post_type, $id, $post_id, $size );

	if ( empty( $img_url ) ) {
		return '';
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

	/**
	 * An array of possible "page themes".
	 *
	 * @var array $allowed_themes
	 */
	$allowed_themes = array(
		'Approach',
		'Mission',
		'Impact',
	);

	/**
	 * A default page theme if one cannot be determined.
	 *
	 * @var string $default_theme
	 */
	$default_theme = $allowed_themes[0];

	$post_id = absint( $post_id );

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( empty( $post_id ) ) {
		return $default_theme;
	}

	// Make sure we have the base post ID (original language).
	$original_post_id = $post_id;
	if ( function_exists( 'bbl_get_default_lang_post' ) ) {
		$original_post_id = bbl_get_default_lang_post( $post_id );
	}

	/**
	 * The name of the page template, else empty string or false.
	 *
	 * @var string|bool $page_template_slug
	 */
	$page_template_slug = get_page_template_slug( $original_post_id );

	if ( empty( $page_template_slug ) ) {
		return $default_theme;
	}

	$page_template_slug = str_ireplace( '.php', '', $page_template_slug );

	/**
	 * Array of strings from exploded $page_template_slug.
	 *
	 * @var array $slug_array
	 */
	$slug_array = explode( '-', $page_template_slug );

	/**
	 * The last element of the $slug_array.
	 *
	 * @var string $slug
	 */
	$slug = end( $slug_array );

	/**
	 * Uppercased version of slug.
	 *
	 * @var string $theme
	 */
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

/**
 * IO Video shortcode.
 *
 * Generate the markup for the skinned video player preview based on an io_video post_id.
 *
 * @param array $atts Array of shortcode atts, only uses id at this time.
 *
 * @return string
 */
function internetorg_video_shortcode( $atts = array() ) {

	/**
	 * A merged array of incoming and default values.
	 *
	 * @var array $atts
	 */
	$atts = shortcode_atts(
		array(
			'id' => '0',
		),
		$atts,
		'io_video'
	);

	/**
	 * The io_video post_id to look up the data from.
	 *
	 * @var int $post_id
	 */
	$post_id = absint( $atts['id'] );

	if ( empty( $post_id ) ) {
		return '';
	}
	/**
	 * URL to the Vimeo video.
	 *
	 * @var string $url
	 */
	$url = get_post_meta( $post_id, 'video-url', true );

	if ( empty( $url ) ) {
		return '';
	}

	/**
	 * The io_video post title.
	 *
	 * @var string $title
	 */
	$title = get_the_title( $post_id );

	/**
	 * URL to the io_video featured image.
	 *
	 * @var string $image
	 */
	$image = internetorg_get_post_thumbnail( $post_id, 'inline-image' );

	/**
	 * String representing the duration of the video.
	 *
	 * @var string $duration
	 */
	$duration = get_post_meta( $post_id, 'video-duration', true );

	/**
	 * The return markup.
	 *
	 * @var string $markup_template
	 */
	$markup_template = '
	<div class="contentOnMedia">
		<img class="contentOnMedia-media" src="%1$s" alt="">
		<!--<div class="contentOnMedia-details">
			<div class="contentOnMedia-details-title">%2$s</div>
			<div class="contentOnMedia-details-duration">%3$s</div>
		</div>-->
		<a href="%4$s" class="contentOnMedia-link contentOnMedia-link_ct js-videoModal swipebox-video" rel="vimeo2">
			<span class="circleBtn circleBtn_play"></span>
		</a>
	</div>
	';

	/**
	 * The assembled markup.
	 *
	 * @var string $markup
	 */
	$markup = sprintf(
		$markup_template,
		esc_url( $image ),
		esc_html( $title ),
		esc_html( $duration ),
		esc_url( $url )
	);

	return $markup;
}

add_shortcode( 'io_video', 'internetorg_video_shortcode' );

/**
 * Shortcode UI Registration for Video.
 *
 * @link https://github.com/fusioneng/Shortcake
 */
function internetorg_register_video_shortcode_ui() {

	/**
	 * Register a UI for the Custom Link shortcode.
	 *
	 * @param string The shortcode tag
	 * @param array  The various fields, name of ui element and other attributes
	 */
	shortcode_ui_register_for_shortcode(
		'io_video',
		array(
			'label'         => esc_html__( 'Video', 'internetorg' ),
			'listItemImage' => 'dashicons-format-video',
			'attrs'         => array(
				array(
					'label' => esc_html__( 'Video To Insert', 'internetorg' ),
					'attr'  => 'id',
					'type'  => 'post_select',
					'query' => array(
						'post_type' => internetorg_get_multiple_shadow_post_types_for_ajax(
							array(
								'io_video',
							)
						),
					),
				),
			),
		)
	);
}

add_action( 'init', 'internetorg_register_video_shortcode_ui' );

/**
 * IO Custom Link shortcode.
 *
 * Generate the markup for a custom link.
 *
 * @param array $attr Array of shortcode atts.
 *
 * @return string
 */
function internetorg_custom_link_shortcode( $attr = array() ) {

	$attr = wp_parse_args(
		$attr,
		array(
			'css_class' => 'link',
			'source'    => '',
			'image'     => '',
			'link_type' => 'internal',
			'external_url' => '',
			'link_text' => esc_html__( 'Click Me', 'internetorg' ),
			'link_image_id' => '',
		)
	);

	$source 	   = absint( $attr['source'] );
	$link_image_id = '';

	if ( ! empty( $attr['link_src'] ) ) {
		$link_type = $attr['link_src'];
	}

	if ( ! empty( $attr['link_image_id'] ) ) {
		$link_image_id = $attr['link_image_id'];
	}

	// Return early if we don't have a url or source ID.
	if ( empty( $source ) && empty( $attr['external_url'] ) ) {
		return '';
	}

	$post_type  = get_post_type( $source );
	$data_type  = 'titled';
	$lg_image   = '';
	$sm_image   = '';
	$data_theme = '';
	$data_social = 'false';

	if ( get_post_thumbnail_id( $source ) ) {
		$lg_image = internetorg_get_media_image_url( get_post_thumbnail_id( $source ), 'panel-image' );
		$sm_image = internetorg_get_mobile_featured_image( get_post_type( $source ), $source );
		$data_type = 'panel';
	}

	if ( 'post' === $post_type ) {
		$data_social = 'true';
	}

	if ( 'io_story' === $post_type ) {
		$data_theme = 'Approach';
	}

	$url = get_permalink( $source );

	/**
	 * The return markup if it's an internal Link.
	 *
	 * @var string $markup_template
	 */

	$markup_template = '
		<a 	class="%1$s js-stateLink"
			href="%2$s"
			data-title="%3$s"
			data-date="%5$s"
			data-theme="%6$s"
			data-image="%7$s"
			data-mobile-image="%8$s"
			data-social="%9$s"
			data-type="%10$s"
		>' . ( empty( $link_image_id ) ? '%11$s' : '<img src="%12$s">' ) . '</a>';

	/**
	 * The return markup if it's an external Link.
	 *
	 * @var string $markup_template_alt
	 */
	$markup_template_alt = '<a class="%1$s" target="_blank" href="%2$s">%3$s</a>';

	if ( ! empty( $link_type ) && 'external' === $link_type ) {
		/**
		 * The assembled markup.
		 *
		 * @var string $markup
		 */
		$markup = sprintf(
			$markup_template_alt,
			esc_attr( $attr['css_class'] ),
			esc_url( $attr['external_url'] ),
			esc_html( $attr['link_text'] )
		);
	} else {
		/**
		 * The assembled markup.
		 *
		 * @var string $markup
		 */
		$link_image_scr = wp_get_attachment_image_src( $link_image_id, "medium" )[0];

		$markup = sprintf(
			$markup_template,
			esc_attr( $attr['css_class'] ),
			esc_url( $url ),
			esc_attr( get_the_title( $source ) ),
			esc_attr( get_post_field( 'post_excerpt', $source ) ),
			esc_attr( get_the_date( '', $source ) ),
			esc_attr( $data_theme ),
			esc_url( $lg_image ),
			esc_url( $sm_image ),
			esc_attr( $data_social ),
			esc_attr( $data_type ),
			esc_html( $attr['link_text'] ),
			esc_url( $link_image_scr )
		);
	}

	return $markup;

}

add_shortcode( 'io-custom-link', 'internetorg_custom_link_shortcode' );

/**
 * Shortcode UI Registration for Dynamic Link.
 *
 * @link https://github.com/fusioneng/Shortcake
 */
function internetorg_register_custom_link_shortcode_ui() {

	/**
	 * Register a UI for the Custom Link shortcode.
	 *
	 * @param string The shortcode tag.
	 * @param array  The various fields, name of ui element and other attributes.
	 */
	shortcode_ui_register_for_shortcode(
		'io-custom-link',
		array(
			'label'         => esc_html__( 'Link', 'internetorg' ),
			'listItemImage' => 'dashicons-admin-links',
			'attrs'         => array(
				array(
					'label'   => esc_html__( 'Link CSS Class', 'internetorg' ),
					'attr'    => 'css_class',
					'type'    => 'radio',
					'options' => array(
						'link'             => esc_attr__( 'Arrow Link', 'internetorg' ),
						'link link_inline' => esc_attr__( 'Inline Link', 'internetorg' ),
						'link_title'       => esc_attr__( 'Title Link', 'internetorg' ),
						'link_image'       => esc_attr__( 'Image Link', 'internetorg' ),
					),
				),
				array(
					'label'   => esc_html__( 'Link Type', 'internetorg' ),
					'attr'    => 'link_src',
					'type'    => 'select',
					'options' => array(
						'internal' => esc_attr__( 'Internal Link', 'internetorg' ),
						'external' => esc_attr__( 'External Link', 'internetorg' ),
					),
				),
				array(
					'label' => esc_html__( 'External URL', 'internetorg' ),
					'attr'  => 'external_url',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'URL', 'internetorg' ),
					'attr'  => 'source',
					'type'  => 'post_select',
					'query' => array(
						'post_type' => internetorg_get_multiple_shadow_post_types_for_ajax(
							array(
								'page',
								'post',
								'io_story',
							)
						),
					),
				),
				array(
					'label' => esc_html__( 'Link Text', 'internetorg' ),
					'attr'  => 'link_text',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Image Library ID', 'internetorg' ),
					'attr'  => 'link_image_id',
					'type'  => 'text',
				),
			),
		)
	);
}

add_action( 'init', 'internetorg_register_custom_link_shortcode_ui' );

/**
 * Change the confirmation message from the contact form.
 *
 * @filter grunion_contact_form_success_message
 *
 * @param string $msg Default response message.
 *
 * @return string New response message.
 */
function internetorg_change_contact_form_response( $msg ) {
	return '<div class="vr vr_x1"><div class="hdg hdg_3 mix-hdg_centerInMobile">'
				 . esc_html__( 'Thank you!', 'internetorg' )
				 . '</div></div>';
}

add_filter( 'grunion_contact_form_success_message', 'internetorg_change_contact_form_response' );

/**
 * Check if specified url is a video URL.
 *
 * Note: currently only checks for vimeo.com or youtube.com in the URL,
 * if more video hosts are added this function will need to be updated.
 *
 * @param string $url The URL to check.
 *
 * @return boolean True if URL is a video url.
 */
function internetorg_is_video_url( $url ) {
	$check_vimeo_val = 'vimeo.com';
	$isVimeo = internetorg_video_type($url, $check_vimeo_val);
	$check_youtube_val = 'youtube.com';
	$isYoutube = internetorg_video_type($url, $check_youtube_val);
	return ($isVimeo || $isYoutube);
}

/**
 * Check if specified url is a video for $check_val.
 *
 * @param string $url The URL to check.
 * @param string $check_val The type to check. Ex: youtube or vimeo.
 *
 * @return boolean True if URL is a video url.
 */
function internetorg_video_type( $url , $check_val) {

	// URL too short, go away.
	if ( strlen( $url ) <= strlen( $check_val ) ) {
		return false;
	}

	$found_loc = strpos( $url, $check_val );

	if ( false === $found_loc ) {
		return false;
	}

	return true;
}
/**
 * Wrap oEmbed videos with a responsive video wrapper div.
 *
 * @filter embed_oembed_html
 *
 * @param mixed  $html    The cached HTML result, stored in post meta.
 * @param string $url     The attempted embed URL.
 * @param array  $attr    An array of shortcode attributes.
 * @param int    $post_ID Post ID.
 *
 * @return string New oEmbed markup with a video div wrapper.
 */
function internetorg_wrap_oembed_html( $html, $url, $attr, $post_ID ) {
	return '<div class="video">' . $html . '</div>';
}

add_filter( 'embed_oembed_html', 'internetorg_wrap_oembed_html', 99, 4 );

/**
 * Retrieve a list of "years" that have posts.
 *
 * @uses wp_get_archives, preg_match_all, array_unique
 *
 * @return array An array of years, else empty array on failure.
 */
function internetorg_get_archives_years() {

	/**
	 * An array of arguments to pass to wp_get_archives.
	 *
	 * @link https://developer.wordpress.org/reference/functions/wp_get_archives/
	 *
	 * @var array $args
	 */
	$args = array(
		'type'            => 'yearly',
		'limit'           => '',
		'format'          => 'custom',
		'before'          => '',
		'after'           => '',
		'show_post_count' => false,
		'echo'            => 0,
		'order'           => 'DESC',
	);

	/**
	 * A string of anchor "links" to yearly archives.
	 *
	 * @var string $archive_list
	 */
	$archive_list = wp_get_archives( $args );

	if ( empty( $archive_list ) ) {
		return array();
	}

	/**
	 * An array of 4 digit matches representing years, else false on failure.
	 *
	 * @var array|bool $matches
	 */
	preg_match_all( '/\d{4}/', $archive_list, $matches );

	if ( empty( $matches ) ) {
		return array();
	}

	/**
	 * Remove duplicates matches.
	 */
	$matches = array_unique( $matches[0] );

	return $matches;
}

/**
 * Print the "press filter" markup.
 *
 * @uses internetorg_get_archives_years
 *
 * @param array $years An array of "years." Optional. Defaults to result of internetorg_get_archives_years().
 */
function internetorg_the_press_filter( $years = array() ) {

	if ( empty( $years ) ) {
		$years = internetorg_get_archives_years();
	}

	if ( empty( $years ) ) {
		echo '';
	}

	?>

	<select id="press-filter" name="year" class="js-select select_inline">
		<option value="post"><?php esc_html_e( 'All Posts', 'internetorg' ); ?></option>
		<?php
		foreach ( $years as $year ) {
			echo '<option value="' . esc_attr( absint( $year ) ) . '">' . absint( $year ) . '</option>';
		}
		?>
	</select>

	<?php
}

/**
 * Wrapper function for register_nav_menus.
 *
 * Registers a default Primary and Secondary nav menu and returns early if Babble is not present.
 * If Babble is present, registers a Primary and Secondary menu for each available language.
 *
 * @used-by internetorg_setup
 */
function internetorg_register_menus() {

	if ( ! function_exists( 'bbl_get_active_langs' ) ) {

		// This theme uses wp_nav_menu() in two locations, if Babble's bbl_get_active_langs is not present.
		register_nav_menus(
			array(
				'primary'   => esc_html__( 'Primary Menu', 'internetorg' ),
				'secondary' => esc_html__( 'Secondary Menu', 'internetorg' ),
			)
		);

		return;
	}

	/**
	 * An array of active language stdClass objects returned by Babble.
	 *
	 * Babble will return an array that is structured thusly...
	 *
	 *     $bbl_active_langs = Array
	 *     (
	 *         [en] => stdClass Object
	 *             (
	 *                 [name] => English (US)
	 *                 [code] => en_US
	 *                 [url_prefix] => en
	 *                 [text_direction] => ltr
	 *                 [display_name] => English (US)
	 *             )
	 *     )
	 *
	 * @var stdClass[] $bbl_active_langs
	 */
	$bbl_active_langs = bbl_get_active_langs();

	foreach ( $bbl_active_langs as $lang ) {
		register_nav_menus(
			array(
				'primary-' . $lang->code => sprintf(
					esc_html__( '%s Primary Menu', 'internetorg' ),
					$lang->display_name
				),
				'secondary-' . $lang->code => sprintf(
					esc_html__( '%s Secondary Menu', 'internetorg' ),
					$lang->display_name
				),
			)
		);
	}

	return;
}

/**
 * Get the current content language code.
 *
 * Wrapper for bbl_get_current_content_lang_code. If Babble's not available, returns empty string.
 *
 * @return string Current content language code according to Babble, else empty string.
 */
function internetorg_get_current_lang_code() {

	$lang_code = '';

	if ( function_exists( 'bbl_get_current_content_lang_code' ) ) {
		$lang_code = bbl_get_current_content_lang_code();
	}

	return $lang_code;
}

/**
 * Output a nav menu.
 *
 * Wrapper for wp_nav_menu, if Babble is available, outputs menu based on location and current content language.
 *
 * @param string $location The general theme location of the menu. Allowed values, primary, secondary.
 */
function internetorg_nav_menu( $location = 'primary' ) {

	/**
	 * Allowed menu locations in the theme.
	 *
	 * When Babble is not present, primary and secondary are the only menu locations.
	 * However, when Babble is present, each language has a primary and secondary location specific to that language.
	 * primary-en_US and secondary-en_US, primary-es_MX and secondary-es_MX, for example.
	 * If Babble is present, the primary and secondary $allowed_locations will be concatenated with the $lang_code.
	 *
	 * @see internetorg_register_menus
	 *
	 * @var array $locations_whitelist
	 */
	$locations_whitelist = array(
		'primary',
		'secondary',
	);

	if ( empty( $location ) || ! in_array( $location, $locations_whitelist ) ) {
		$location = 'primary';
	}

	$lang_code = internetorg_get_current_lang_code();

	/**
	 * An array of non-default wp_nav_menu parameters, to be merged with the $args array and passed to wp_nav_menu.
	 *
	 * Refer to the default wp_nav_menu parameters in the Codex to determine if you need to override them here.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/wp_nav_menu#Parameters
	 *
	 * @var array $defaults
	 */
	$nav_override_params = array(
		'menu_class' => '',
		'menu_id'    => '',
	);

	/**
	 * An array of arguments to be merged with the $nav_override_params array and passed to wp_nav_menu.
	 *
	 * @var array $args
	 */
	$args = array();

	if ( 'primary' === $location ) {
		$args['container_class'] = 'mainMenu-panel-primary';
		$args['walker']          = new Internetorg_Main_Nav_Walker();
	} else {
		$args['container_class'] = 'mainMenu-panel-secondary';
		$args['menu_class']      = 'borderBlocks borderBlocks_2up';
		$args['walker']          = new Internetorg_Main_SubNav_Walker();
	}

	if ( empty( $lang_code ) || ! class_exists( 'Babble' ) ) {
		$args['theme_location'] = $location;
	} else {
		$args['theme_location'] = $location . '-' . $lang_code;
	}

	$args = wp_parse_args( $args, $nav_override_params );

	wp_nav_menu( $args );

	return;
}

/**
 * Output a call to action for the contact page.
 *
 * @param array  $fieldset       Array of custom field data.
 * @param string $theme          The "theme" (color styling) to apply.
 * @param int    $fieldset_image Attachment ID.
 */
function internetorg_contact_call_to_action( $fieldset = array(), $theme = 'approach', $fieldset_image ) {

	if ( empty( $fieldset ) ) {
		return;
	}

	foreach ( $fieldset as $cta ) {

		if ( empty( $cta ) ) {
			continue;
		}

		/**
		 * Deal with offsite links early to avoid extra processing.
		 */
		if ( 'page' !== $cta['cta_src'] && ! empty( $cta['link'] ) && ! internetorg_is_internal_url( $cta['link'] ) ) {
			internetorg_external_cta_link( $cta['link'] );
			continue;
		}

		/**
		 * The value for the data-social attribute on the call to action link.
		 *
		 * @var string $social_attr
		 */
		$social_attr = 'false';

		/**
		 * The value for the data-type attribute on the call to action link.
		 *
		 * @var string $type
		 */
		$type = 'titled';

		/**
		 * The value for the URL
		 *
		 * @var string $url
		 */
		$url = '';

		if ( 'page' === $cta['cta_src'] ) {
			if ( ! empty( $cta['link_src'] ) ) {
				$url = get_the_permalink( $cta['link_src'] );
			}
		} else {
			if ( ! empty( $cta['link'] ) ) {
				$url = $cta['link'];
			}
		}

		if ( empty( $url ) ) {
			continue;
		}

		$linkName = (! empty( $cta['title'] ) ? $cta['title'] : 'Learn More');

		if ( ! empty( $cta['cta_src'] ) && 'page' === $cta['cta_src'] && ! empty( $cta['link_src'] ) ) {

			/**
			 * Title for the link.
			 *
			 * @var string $title
			 */
			$title = get_the_title( $cta['link_src'] );

			/**
			 * Description.
			 *
			 * @var string $desc
			 */
			$desc = get_post_field( 'post_excerpt', $cta['link_src'] );

			/**
			 * Panel image URL.
			 *
			 * @var string $panel_image
			 */
			$panel_image = internetorg_get_media_image_url( get_post_thumbnail_id( $cta['link_src'] ), 'panel-image' );

			/**
			 * Mobile image URL.
			 *
			 * @var string $mobile_image
			 */
			$mobile_image = internetorg_get_mobile_featured_image( get_post_type( $cta['link_src'] ), $cta['link_src'] );

			if ( in_array( $cta['link_src'], internetorg_get_shadow_post_types_for_ajax( 'io_story' ) ) ) {
				$type = 'panel';
			}
		} else {

			$title = ( ! empty( $cta['title'] ) ? $cta['title'] : '' );

			$desc = ( ! empty( $cta['text'] ) ? strip_tags( nl2br( $cta['text'] ) ) : '' );

			$panel_image = ( ! empty( $fieldset_image )
				? internetorg_get_media_image_url( $fieldset_image, 'panel-image' )
				: ''
			);

			$mobile_image = ( ! empty( $fieldset_image )
				? internetorg_get_media_image_url( $fieldset_image, 'inline-image' )
				: ''
			);
		}

		if ( ! empty( $cta['link_src'] ) && in_array( $cta['link_src'], internetorg_get_shadow_post_types_for_ajax( 'post' ) ) ) {
			$social_attr = 'true';
		}

		$theme = ( ! empty( $theme ) )
			? $theme
			: 'approach';

		?>

		<div class="feature-cta">
			<a href="<?php echo esc_url( apply_filters( 'iorg_url', $url ) ); ?>"
				 class="link js-stateDefault"
				 data-type="<?php esc_attr( $type ); ?>"
				 data-social="<?php echo esc_attr( $social_attr ); ?>"
				 data-theme="<?php echo esc_attr( strtolower( $theme ) ); ?>"
				 data-title="<?php echo esc_attr( $title ); ?>"
				<?php if ( ! empty( $mobile_image ) ) : ?>
					data-mobile-image="<?php echo esc_url( $mobile_image ); ?>"
				<?php endif; ?>
				<?php if ( ! empty( $panel_image ) ) : ?>
					data-image="<?php echo esc_url( $panel_image ); ?>"
				<?php endif; ?>>
				<?php echo esc_html__( $linkName, 'internetorg' ); ?>
			</a>
		</div>

		<?php
	}
}

/**
 * Output an offsite CTA link.
 *
 * @param string $link The destination URL.
 */
function internetorg_external_cta_link( $link = '' ) {

	if ( empty( $link ) ) {
		return;
	}

	?>
	<div class="feature-cta">
		<a href="<?php echo esc_url( $link ); ?>" class="link" target="_blank">
			<?php echo esc_html__( 'Learn More', 'internetorg' ); ?>
		</a>
	</div>
	<?php
}

/**
 * Recursively run array_filter to scrub empty values from multidimensional array.
 *
 * @param array $array The array to act on.
 *
 * @return array The recursively filtered array.
 */
function internetorg_array_filter_recursive( $array ) {
	foreach ( $array as &$value ) {
		if ( is_array( $value ) ) {
			$value = internetorg_array_filter_recursive( $value );
		}
	}

	return array_filter( $array );
}

/**
 * Recursively unset unwanted keys from a multidimensional array.
 *
 * @param array  $array        The array to act on.
 * @param string $unwanted_key The key to recursively unset.
 */
function internetorg_recursive_unset( &$array, $unwanted_key ) {
	unset( $array[ $unwanted_key ] );
	foreach ( $array as &$value ) {
		if ( is_array( $value ) ) {
			internetorg_recursive_unset( $value, $unwanted_key );
		}
	}
}

/**
 * Force usage of a pages proper template
 */

function internetorg_force_page_template() {
	global $posts;
	internetorg_preview_post_setup();
	$template = get_page_template_slug( $posts[ 0 ]->ID );
	if ( $template && validate_file( TEMPLATEPATH . '/' . $template ) ) {
		include TEMPLATEPATH . '/' . $template;
		die();
	}
}

/**
 * Resetup post information based on query params
 */

function internetorg_preview_post_setup() {
	global $posts;
	parse_str( $_SERVER[ 'QUERY_STRING' ] );

	if ( !isset( $p ) ) {
		$p = null;
	}

	$posts = array( get_post( $p ) );
}

/**
 * Find and route in url
 */

function internetorg_search_url( $url, $find ) {
	$parts = explode( '/', $url );
	foreach( $parts as $key => $val ) {
		if ( $val === $find ) {
			return true;
		}
	}
	return false;
}

/**
 * Find and replace from url
 */

function internetorg_search_replace_url( $url, $find, $replace, $exact = false ) {
	$parts = explode( '/', $url );
	foreach( $parts as $key => $val ) {
		if ( $exact ) {
			$parts[ $key ] = ( $val === $find ) ? $replace : $val;
		} else {
			$parts[ $key ] = ( strpos( $val, $find ) !== false ) ? $replace : $val;
		}
	}
	$url = implode( '/', $parts );
	return $url;
}

/**
 * Redirect scripts
 */

function vip_fb_legacy_redirects() {

	// Set post information if it's a preview
	if ( is_preview() ) {
		internetorg_preview_post_setup();
		add_action( 'template_redirect', 'internetorg_force_page_template' );
	}

	// To reduce overhead, only run if the requested page is 404.
	if ( !is_404() ) {
		return;
	}

	$url = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );

	// Define static mapping of old routes
	$routes = array(
		'contact' => 'contact-us',
		'innovationchallenge' => 'story/innovation-challenge'
	);

	// Check for custom routes to map directly
	foreach( $routes as $key => $val ){
		if ( internetorg_search_url( $url, $key ) ) {
			$url = internetorg_search_replace_url( $url, $key, $val, true );
			wp_safe_redirect( $url, 301 );
			exit;
		}
	}

	// Check specifically for old story routes and map accordingly
	if ( strpos( $url, '/story_' ) !== false ) {
		$url = internetorg_search_replace_url( $url, 'story_', 'story' );
		wp_safe_redirect( $url, 301 );
		exit;
	}

	return;
}


add_filter( 'template_redirect', 'vip_fb_legacy_redirects',0 , 2 );

/**
 * Fixes an issue with a 404 error from Widget json
 */

function vip_fb_internetorg_en_locale( $locale ) {
		if ( 'en_US' === $locale && wp_in( 'Jetpack_Likes->likes_master', wp_debug_backtrace_summary() ) ) {
				return 'en';
		}
		return $locale;
}
add_filter( 'locale', 'vip_fb_internetorg_en_locale', 1000, 1 );

/**
 * Customize JetPack Open Graph Meta Tags implementation
 */

add_filter( 'jetpack_open_graph_base_tags', function( $og_tags ) {

	global $post;

	$fields = false;

	if ( isset( $post->ID ) ) {
		$fields = get_post_meta( $post->ID, 'internetorg_custom_og', true );
	}

	if ( $fields ) {

		if ( isset( $fields['iorg_title'] ) ) {
			$og_tags['og:title'] = $fields['iorg_title'];
		}

		if ( isset( $fields['iorg_description'] ) ) {
			$og_tags['og:description'] = $fields['iorg_description'];
		}

		if ( isset( $fields['iorg_image'] ) ) {
			$og_tags['og:image'] = $fields['iorg_image'];
		}

	}

	return $og_tags;

}, 11 );

/**
	* Get the Proper Media Embed for the current article
	*/

function internetorg_media_embed ( $ret = false ) {

	global $post;

	$fields = get_post_meta( $post->ID, 'internetorg_media_embed', true );
	$output = '';

	if ( $fields ) {

		$url = $fields['iorg_media_embed_url'];
		$visibility = $fields['iorg_media_embed_visibility'];

		// Return early if the visibility doesn't match
		if ( is_archive() && $visibility === 'single' || is_single() && $visibility === 'listing' ) {
			return '';
		}

		if ( strpos( $url, 'youtube.com' ) !== false ) {
			parse_str( parse_url( $url, PHP_URL_QUERY ), $parsed );
			$output = '<div class="feature-video"><iframe src="https://www.youtube.com/embed/' . esc_attr( $parsed[ 'v' ] ) . '" frameborder="0" allowfullscreen></iframe></div>';
		}

		if ( strpos( $url, 'vimeo.com' ) !== false ) {
			$parsed = substr( parse_url( $url, PHP_URL_PATH ), 1 );
			$output = '<div class="feature-video"><iframe src="https://player.vimeo.com/video/' . esc_attr( $parsed ) . '" frameborder="0" allowfullscreen></iframe></div>';
		}

		if ( strpos( $url, 'facebook.com' ) !== false ) {
			$output = '<div class="fb-post" data-href="' . esc_url( $url ) . '"></div>';
		}

	}

	if ( $ret ) {
		return $output;
	} else {
		echo $output;
	}
}

/**
	* Add custom fields for Customizing Open Graph Tags & Media Embed
	*/

add_action( 'fm_post_post', 'internetorg_open_graph_fields' );
add_action( 'fm_post_io_story', 'internetorg_open_graph_fields' );

function internetorg_open_graph_fields () {
 $fm = new Fieldmanager_Group( array(
			'name' => 'internetorg_custom_og',
			'children' => array(
				'iorg_title' => new Fieldmanager_Textfield( __( 'og:title' ) ),
				'iorg_description' => new Fieldmanager_TextArea( __( 'og:description' ) ),
				'iorg_image' => new Fieldmanager_Media( __( 'og:image - Image Size Specs: HD ( 1200 x 630px ) Small ( 600 x 315px ) Minimum ( 200 x 200px )' ) ),
			),
	) );

	$fm->add_meta_box( __( 'Customize Meta Data' ), 'io_story' );
	$fm->add_meta_box( __( 'Customize Meta Data' ), 'post' );

	$fm = new Fieldmanager_Group( array(
			'name' => 'internetorg_media_embed',
			'children' => array(
				'iorg_media_embed_url' => new Fieldmanager_Textfield( __( 'Link to Media (ex. YouTube video url, Vimeo video url or Facebook Post)' ) ),
				'iorg_media_embed_visibility' => new Fieldmanager_Select( array(
						'name' => 'iorg_media_embed_visibility',
						'label' => __( 'Where should Media be visible?' ),
						'options' => array(
							'both' => 'Both on the Listing & Single Pages',
							'listing' =>'Only on the Listing Page',
							'single' => 'Only on Single Page'
						)
					)
				)
			),
	) );

	$fm->add_meta_box( __( 'Media Embed Options' ), 'post' );

}

/**
	* Add meta data character count functionality
	*/
add_action('admin_enqueue_scripts', 'internetorg_open_graph_limiter');
function internetorg_open_graph_limiter() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'request-scripts', get_bloginfo( 'template_directory' ) . '/js/limit.js' );
}

function internetorg_update_preview_link( $link ) {
	$id = get_the_ID();
	$link = str_replace( '?post_type=io_story&', 'story/?', $link );
	$type = get_post_type( $id );
	if ( $type === 'post' || $type === 'page' ) {
		$link = str_replace( '?preview=true', '?post_type=' . $type . '&p=' . $id . '&preview=true', $link );
	}
	return $link;
}

add_filter( 'preview_page_link', 'internetorg_update_preview_link');
add_filter( 'preview_post_link', 'internetorg_update_preview_link');


/**
 * Return current lanuage in the form of `en` or `fr` using
 * the path of the multisite. If facebook_sdk is set to true
 * we provide the correct locale tag for facebook sdk.
 */
function internetorg_get_current_language( $facebook_sdk = false ) {
	$language     	  = 'en';
	$current_blog 	  = get_blog_details();
	$facebook_locales = array(
		'en' => 'en_US',
		'ar' => 'ar_AR',
		'bn' => 'bn_IN',
		'es' => 'es_LA',
		'fr' => 'fr_FR',
		'hi' => 'hi_IN',
		'id' => 'id_ID',
		'ja' => 'ja_JP',
		'pa' => 'pa_IN',
		'pt' => 'pt_PT',
		'ru' => 'ru_RU',
		'ur' => 'ur_PK'
	);

	if ( isset( $current_blog->path ) ) {
		$language = trim( $current_blog->path, '/' );
	}

	if ( $facebook_sdk ) {
		if ( isset( $facebook_locales[$language] ) ) {
			return $facebook_locales[$language];
		}
		return 'en_US';
	}

	return $language;
}


function internetorg_translate_required_text( $required_text ) {
	return esc_attr__( '(required)', 'internetorg' );
}

add_filter( 'jetpack_required_field_text', 'internetorg_translate_required_text' );


/**
 * Return each avaiable language allowing a language
 * selector to be shown.
 */
function internetorg_get_switcher_links() {

	$languages = mlp_get_available_languages( true );
	$menu	   = array();

	foreach( $languages as $site => $language ) {

		$site_details 	= get_blog_details( $site );
		$site_prefix  	= mlp_get_blog_language( $site );
		$text_direction = 'ltr';
		$active         = false;

		if ( internetorg_is_rtl( $site_prefix ) ) {
			$text_direction = 'rtl';
		}

		if ( get_current_blog_id() == $site_details->blog_id ) {
			$active = true;
		}

		$menu[] = array(
			'href' 			 => '/' . $site_prefix,
			'active' 		 => $active,
			'text_direction' => $text_direction,
			'display_name' 	 => esc_attr__( $site_details->blogname, 'internetorg' ),
		);
    }

    return $menu;
}


/**
 * Catches links within the post/page content
 * if the link has /en/ but we are not on the English
 * site then we must replace it so link is correct.
 */
function internetorg_alter_links_to_match_language( $content ) {

	$site_prefix = mlp_get_blog_language( get_current_blog_id() );

	if ( $site_prefix != 'en' )
	{
		$content = str_replace( 'href="/en/', 'href="/' . $site_prefix . '/', $content );
		$content = str_replace( 'href=\'/en/', 'href=\'/' . $site_prefix . '/', $content );
	}

    return $content;
}
add_filter( 'the_content', 'internetorg_alter_links_to_match_language' );

/**
 * Catches links within the widget content
 * if the link has /en/ but we are not on the English
 * site then we must replace it so link is correct.
 */
function internetorg_alter_widget_url_to_match_language( $url ) {

	$site_prefix = mlp_get_blog_language( get_current_blog_id() );

	if ( $site_prefix != 'en' )
	{
		$url = str_replace( '/en/', '/' . $site_prefix . '/', $url );
	}

    return $url;
}
add_filter( 'widget_data_url_filter', 'internetorg_alter_widget_url_to_match_language' );


/**
 * Remove domain and setting them english based links
 * from insert link option in the editor. Example linking
 * to a post on the French or English site would result in
 * /en/some-slug
 */

function internetorg_strip_domain_from_insert_link( $permalink, $post )
{
	$protocols = array( 'http://', 'https://' );
	$site_url  = str_replace( $protocols, '', get_site_url() );
	$permalink = str_replace( $protocols, '', $permalink );
	$permalink = str_replace( $site_url, '/en', $permalink );

    return $permalink;
}

function internetorg_add_link_filters( $query ) {
	add_filter( 'post_link', 	  'internetorg_strip_domain_from_insert_link', 10, 2 );
	add_filter( 'post_type_link', 'internetorg_strip_domain_from_insert_link', 10, 2 );
	add_filter( 'page_link', 	  'internetorg_strip_domain_from_insert_link', 10, 2 );
	return $query;
}

function internetorg_remove_link_filters( $query ) {
	remove_filter( 'post_link', 	 'internetorg_strip_domain_from_insert_link', 10 );
	remove_filter( 'post_type_link', 'internetorg_strip_domain_from_insert_link', 10 );
	remove_filter( 'page_link', 	 'internetorg_strip_domain_from_insert_link', 10 );
	return $query;
}

add_filter( 'wp_link_query_args', 'internetorg_add_link_filters'    );
add_filter( 'wp_link_query', 	  'internetorg_remove_link_filters' );

/**
 * Disable responsive images on local dev (public URL is required for photon image resize).
 */
if ( stripos( get_site_url(), '/internet-org.app' ) !== false ) {
	add_filter( 'internetorg_responsive_images_disabled', '__return_true' );
}

/**
 * Set up the custom fields which the exporter/importer
 * should handle.
 */
function internetorg_cei_handle_custom_fields( $fields ) {
	$fields = array(
	    'home-content-section' => array(
	        'tag' => 'wp-section',
	        'repeater' => true,
	        'structure' => array(
	            'title' => 'wp-section-title',
	            'name'  => 'wp-section-name',
	            'content' => 'wp-section-description',
	            'src' => 'wp-section-source',
	            'url-src' => 'wp-section-source-url',
	            'slug' => 'wp-section-source-slug',
	            'theme' => 'wp-section-theme',
	            'image' => 'wp-section-background-image',
	            'call-to-action' => array(
	                'parent' => 'wp-section-ctas',
	                'tag' => 'wp-section-cta',
	                'repeater' => true,
	                'structure' => array(
	                    'title' => 'wp-section-cta-title',
	                    'text' => 'wp-section-cta-content',
	                    'cta_src' => 'wp-section-cta-source',
	                    'link_src' => 'wp-section-cta-source-url',
	                    'link' => 'wp-section-cta-source-slug',
	                    'image' => 'wp-section-cta-image',
	                )
	            )
	        )
	    ),
	    'page_intro_block' => array(
	        'tag' => 'wp-page-intro',
	        'repeater' => false,
	        'structure' => array(
	            'intro_title' => 'wp-page-intro-title',
	            'intro_content' => 'wp-page-intro-copy',
	        )
	    ),
	    'page_subtitle' => array(
	        'parent' => 'wp-page-config',
	        'tag' => 'wp-page-config-subtitle',
	        'repeater' => false,
	        'structure' => array()
	    ),
	    'internetorg_custom_og' => array(
	        'tag' => 'wp-meta-data',
	        'repeater' => false,
	        'structure' => array(
	            'iorg_title' => 'wp-meta-data-og-title',
	            'iorg_description' => 'wp-meta-data-og-description',
	            'iorg_image' => 'wp-meta-data-og-image',
	        )
	    ),
	);
	return $fields;
}

add_filter( 'iorg_cei_custom_fields_filter', 'internetorg_cei_handle_custom_fields' );
