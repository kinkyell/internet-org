<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Internet.org
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'internet_org' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'internet_org' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'internet_org' ), 'internet_org', '<a href="http://www.nerdery.com" rel="designer">arichard</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->


<?php wp_footer(); ?>

<?php

// #############################################################################

// MANUAL INCLUSION DURING INITIAL INTEGRATION                             START

// #############################################################################

?>

<!-- JAVASCRIPT -->
    <script src="/wp-content/themes/vip/internet_org/_static/web/assets/vendor/jquery/jquery.min.js"></script><!-- replace by WP version -->
    <!-- build:js assets/scripts/require.js -->
    <script src="/wp-content/themes/vip/internet_org/_static/web/assets/vendor/requirejs/require.js"></script>
    <script src="/wp-content/themes/vip/internet_org/_static/web/assets/scripts/config.js"></script>
    <!-- endbuild -->
    <script>
        /**
         * Global constants
         *
         * TODO: ideally these values would be filled in from the server side
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
        SETTINGS.IS_PRODUCTION = false;

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
        SETTINGS.SCRIPT_PATH = '/wp-content/themes/vip/internet_org/_static/web/assets/scripts/';

        /**
         * Set any RequireJs configuration that is dependent on dynamic
         * configuration variables. Note that this config data is merged into
         * any other require.config() statements defined elsewhere.
         */
        require.config({
            // Script path
            baseUrl: SETTINGS.SCRIPT_PATH,

            // Params to append to the end of each js file request
            urlArgs: 'v=' + SETTINGS.APP_VERSION + SETTINGS.CACHE_BUSTER
        });

        /**
         * Set route and kick off RequireJs, which begins loading of scripts
         * starting from main.js.
         */
        require(['main']);
    </script>


<?php

// #############################################################################

// MANUAL INCLUSION DURING INITIAL INTEGRATION                               END

// #############################################################################

?>


</body>
</html>
