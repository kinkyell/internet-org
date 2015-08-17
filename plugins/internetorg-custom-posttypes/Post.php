<?php
/**
 * This is a "Post" post type
 *
 * @package internetorg
 * @author arichard <arichard@nerdery.com>
 */

/**
 * Class Internetorg_Post_PostType -- basic post
 *
 * @package internetorg
 * @author arichard <arichard@nerdery.com>
 */
class Internetorg_Post_PostType extends Internetorg_PostType
{
	/**
	 * @var bool true show this type in the menu
	 */
	protected $showInNaveMenu = true;

	/**
	 * constructor
	 */
	public function __construct() {
		Internetorg_PostType::__construct( 'post' );
	}

	/**
	 * register the post type - here we don't do anything because "post" is the original post type
	 *
	 * @return void
	 */
	public function register() {
		$this->add_meta_boxes();
	}

	/**
	 * Add meta boxes to this post type
	 *
	 * @note currently there are no custom meta boxes for this post type
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		// no-op
	}
}

