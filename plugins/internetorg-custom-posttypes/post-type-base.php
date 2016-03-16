<?php
/**
 * This is where I'm keeping the Abstract PostType class.
 *
 * @package internetorg
 * @author  arichard <arichard@nerdery.com>
 */

/**
 * Class Internetorg_PostType (Abstract)
 *
 * @package internetorg
 * @author  arichard <arichard@nerdery.com>
 */
abstract class Internetorg_PostType {
	/**
	 * Holds the CPT name.
	 *
	 * @var string $postType
	 */
	protected $postType;

	/**
	 * Determine if the CPT will appear in the menu.
	 *
	 * @var bool $showInNavMenu
	 */
	protected $showInNavMenu = false;

	/**
	 * The configuration of the CPT.
	 *
	 * @var array $registrationData
	 */
	protected $registrationData;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->registrationData = func_get_args();
		$this->postType         = $this->registrationData[0];
	}

	/**
	 * Registers the CPT.
	 *
	 * @return void
	 */
	public function register() {
		// This is an alternative way of adding custom meta if you don't use the fieldmananger plugin.
		$this->registrationData[1]['show_in_nav_menus'] = $this->showInNavMenu;
		call_user_func_array( 'register_post_type', $this->registrationData );

		// Add the custom meta fields if this CPT needs them.
		$this->add_meta_boxes();
	}

	/**
	 * Add custom fields to this CPT.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
	}

	/**
	 * Save the custom meta field for this custom post type.
	 *
	 * @param int   $postId ID of the post related to this meta being saved.
	 * @param array $data   Form data to be saved.
	 *
	 * @return void
	 */
	public function save_meta_data( $postId, $data ) {
	}
}
