<?php
/**
 * This is the main class for the CPT plugin.
 *
 * @package IOrgCustomPosttypes
 * @author  arichard <arichard@nerdery.com>
 */

defined( 'ABSPATH' ) or exit( 'No direct access' );

/**
 * Class Internetorg_CustomPostTypes
 *
 * @package internetorg
 * @author  arichard <arichard@nerdery.com>
 */
class Internetorg_CustomPostTypes {
	/**
	 * Singleton instance of class.
	 *
	 * @var Internetorg_CustomPostTypes $__instance Current instance of the object.
	 */
	protected static $__instance;


	/**
	 * Obtain the singleton instance of this object.
	 *
	 * @return Internetorg_CustomPostTypes Current instance.
	 */
	public static function get_instance() {
		if ( ! is_a( self::$__instance, __CLASS__ ) ) {
			self::$__instance = new self;
		}

		return self::$__instance;
	}

	/**
	 * Constructor.
	 */
	protected function __construct() {
	}

	/**
	 * Create post types.
	 */
	public function create_post_types() {
		$internetorg_cpt_story       = new Internetorg_Story_PostType();
		$internetorg_cpt_widget      = new Internetorg_ContentWidget_PostType();
		$internetorg_cpt_video      = new Internetorg_Video_PostType();

		$internetorg_cpt_story->register();
		$internetorg_cpt_widget->register();
		$internetorg_cpt_video->register();
	}

	/**
	 * Activate plugin, pass this to activation hook.
	 *
	 * @note Flushes rewrite rules.
	 *
	 * @return void
	 */
	public function activate() {
		$this->create_post_types();

		// Only flush once, not every page load.
		flush_rewrite_rules();
	}

	/**
	 * Deactivate plugin, pass this to deactivation hook.
	 *
	 * Currently this plugin will change nothing on deactivation to avoid deleting content, post types in the DB will
	 * remain unchanged. However, rewrite rules will be flushed since we make be taking some away.
	 *
	 * @return void
	 */
	public function deactivate() {
		flush_rewrite_rules();
	}

}
