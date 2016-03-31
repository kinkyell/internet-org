<?php
/**
 * Footer RequireJs template part.
 *
 * Print the Requirejs bootstrap markup before closing body tag.
 * WPCOM_IS_VIP_ENV constant used to determine if we are on production or not for FED build.
 * Adapted from VIP documentation regarding loading plugins, same concept applies.
 *
 * @see     https://vip.wordpress.com/documentation/quickstart/#loading-plugins
 *
 * @package Internet.org
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Current Language URL Prefix.
 *
 * @var string $url_prefix
 */
$url_prefix = internetorg_get_current_language();
?>

<script>
	/**
	 * Global constants
	 *
	 * @class SETTINGS
	 * @static
	 */
	window.SETTINGS = {};

	/**
	 * Indicates whether we are running on a production environment
	 *
	 * @property IS_PRODUCTION
	 * @type Boolean
	 * @final
	 */
	SETTINGS.IS_PRODUCTION = <?php echo esc_html( defined( 'WPCOM_IS_VIP_ENV' ) && true === WPCOM_IS_VIP_ENV ? 'true' : 'false' ); ?>;

	/**
	 * Appended to query string for versioning of network resources (CSS,
	 * JavaScript, etc). This version number should be updated in production
	 * for every release.
	 *
	 * @property APP_VERSION
	 * @type {String}
	 * @final
	 */
	SETTINGS.APP_VERSION = '1.0.0';

	/**
	 * Set to true to allow application to output to browser console, false
	 * to silence all console output. This should be set to `false` on
	 * production.
	 *
	 * @property LOG_CONSOLE
	 * @type Boolean
	 * @final
	 */
	SETTINGS.LOG_CONSOLE = !SETTINGS.IS_PRODUCTION;

	/**
	 * Appended to query string to defeat caching of network resources (CSS,
	 * JavaScript, etc). Should be set to '' on production
	 *
	 * @property CACHE_BUSTER
	 * @type String
	 * @final
	 */
	SETTINGS.CACHE_BUSTER = SETTINGS.IS_PRODUCTION ? '' : '&bust=' + Math.random();

	/**
	 * Root path for all JavaScript files
	 *
	 * @property SCRIPT_PATH
	 * @type String
	 * @final
	 */
	SETTINGS.SCRIPT_PATH = '<?php echo esc_url( get_stylesheet_directory_uri() . '/_static/web/assets/scripts/' ); ?>';

	/**
	 * Root path for all static files
	 *
	 * @property STATIC_PATH
	 * @type String
	 * @final
	 */
	SETTINGS.STATIC_PATH = '<?php echo esc_url( get_stylesheet_directory_uri() . '/_static/web/' ); ?>';

	/**
	 * Root-relative path for search results
	 *
	 * @property SEARCH_PATH
	 * @type String
	 * @final
	 */
	SETTINGS.SEARCH_PATH = '<?php echo esc_html( $url_prefix . '/io-ajax-search/' ); ?>';

	/**
	 * URL prefix for current language
	 *
	 * @property CURRENT_LANGUAGE_URL_PREFIX
	 * @type String
	 */
	SETTINGS.CURRENT_LANGUAGE_URL_PREFIX = '<?php echo esc_html( $url_prefix ); ?>';

	/**
	 * Powered by logo string
	 *
	 * @property COPYRIGHT_STRING
	 * @type String
	 * @final
	 */
	SETTINGS.COPYRIGHT_STRING = '<?php internetorg_vip_powered_wpcom(); ?>';

	/**
	 * Read more text translation
	 *
	 * @property READ_MORE_TEXT
	 * @type String
	 * @final
	 */
	SETTINGS.READ_MORE_TEXT = '<?php esc_html_e( 'Read More', 'internetorg' ); ?>';

	/**
	 * Show more text translation
	 *
	 * @property SHOW_MORE_TEXT
	 * @type String
	 * @final
	 */
	SETTINGS.SHOW_MORE_TEXT = '<?php esc_html_e( 'Show More', 'internetorg' ); ?>';

	/**
	 * Results Found text translation
	 *
	 * @property RESULTS_FOUND_TEXT
	 * @type String
	 * @final
	 */
	SETTINGS.RESULTS_FOUND_TEXT = '<?php esc_html_e( '%d Results Found', 'internetorg' ); ?>';

	/**
	 * routes for static and WP endpoints
	 *
	 * @property ROUTES
	 * @type obj
	 * @final
	 */
	SETTINGS.ROUTES = {
		'pressResults': '<?php echo esc_html( $url_prefix . '/io-ajax-posts/press/' ); ?>',
		'searchResults': window.SETTINGS.SEARCH_PATH,
		'404': 'not-found/index.html'
	};


	/**
	 * Set any RequireJs config/uration that is dependent on dynamic
	 * configuration variables. Note that this config data is merged into
	 * any other require.config() statements defined elsewhere.
	 */
	require.config(
		{
			// Script path
			baseUrl: SETTINGS.SCRIPT_PATH,
			// Params to append to the end of each js file request
			urlArgs: 'v=' + SETTINGS.APP_VERSION + SETTINGS.CACHE_BUSTER
		});

	/**
	 * This assumes jquery was loaded before the require call.
	 * If so, then this approach means requirejs will not load another version of jquery.
	 * https://github.com/jrburke/requirejs/issues/622#issuecomment-16817939
	 */
	if (typeof jQuery === 'function') {
		define('jquery', function () {
			return jQuery;
		});
	}

	/**
	 * Set route and kick off RequireJs, which begins loading of scripts
	 * starting from main.js.
	 */
	require(['main']);
</script>
