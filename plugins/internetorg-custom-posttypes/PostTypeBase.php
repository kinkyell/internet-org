<?php
/**
 * This is where I'm keeping the Abstract PostType class
 *
 * @package internetorg
 * @author arichard <arichard@nerdery.com>
 */

/**
 * Class Internetorg_PostType (Abstract)
 *
 * @package internetorg
 * @author arichard <arichard@nerdery.com>
 */
abstract class Internetorg_PostType
{
	/**
	 * @var string $postType holds the CPT name
	 */
	protected $postType;

	/**
	 * @var bool $showInNavMenu determine if the CPT will appear in the menu
	 */
	protected $showInNavMenu = false;

	/**
	 * @var array $registrationData the configuration of the CPT
	 */
	protected $registrationData;

	/**
	 * constructor
	 *
	 */
	public function __construct() {
		$this->registrationData = func_get_args();
		$this->postType = $this->registrationData[0];
	}

	/**
	 * registers the CPT
	 *
	 * @return void
	 */
	public function register() {
		// This is an alternative way of adding custom meta if you don't use
		// the fieldmananger plugin
		$this->registrationData[1]['show_in_nav_menus'] = $this->showInNavMenu;
		call_user_func_array( 'register_post_type', $this->registrationData );

		// add the custom meta fields if this CPT needs them
		$this->add_meta_boxes();
	}

	/**
	 * Add custom fields to this CPT
	 *
	 * @return void
	 */
	public function add_meta_boxes() {}

	/**
	 * save the custom meta field for this custom post type
	 *
	 * @param int $postId the post related to this meta being saved
	 * @param array $data form data to be saved
	 * @return void
	 */
	public function save_meta_data( $postId, $data ) {}
}