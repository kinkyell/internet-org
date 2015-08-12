<?php
/**
 * Footer requirejs bootstrap file.
 *
 * @package Internet.org
 * @author  Richard Aber <raber@nerdery.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print the Requirejs bootstrap markup before closing body tag.
 *
 * @action   internetorg_body_bottom
 * @priority default (10)
 */
function internetorg_requirejs_shim() {

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
		SETTINGS.IS_PRODUCTION = <?php echo 'production' === APP_ENV ? 'true' : 'false'; ?>;
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
		 * Set any RequireJs configuration that is dependent on dynamic
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
			define('jquery', function () { return jQuery; });
		}

		/**
		 * Set route and kick off RequireJs, which begins loading of scripts
		 * starting from main.js.
		 */
		require(['main']);
	</script>

	<?php

}

add_action( 'internetorg_body_bottom', 'internetorg_requirejs_shim' );
