<?php
/**
 * This is a "Page" post type
 *
 * @package internetorg
 * @author arichard <arichard@nerdery.com>
 */

/**
 * Class Internetorg_Page_PostType -- basic page
 *
 * @package internetorg
 * @author arichard <arichard@nerdery.com>
 */
class Internetorg_Page_PostType extends Internetorg_PostType
{
	/**
	 * @var bool true show this type in the menu
	 */
	protected $showInNaveMenu = true;

	/**
	 * constructor
	 */
	public function __construct() {
		Internetorg_PostType::__construct( 'page' );
	}

	/**
	 * register the post type - here we don't do anything because "page" is a default post type
	 *
	 * @return void
	 */
	public function register() {
		$this->add_meta_boxes();
	}

	/**
	 * Add meta boxes to this post type
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		$fm = new Fieldmanager_Group( array(
			'name'           => 'home-content-section',
			'label'          => __( 'Section', 'internetorg' ),
			'label_macro'    => __( 'Section: %s', 'internetorg' ),
			'add_more_label' => __( 'Add another Content Area', 'internetorg' ),
			'collapsed'      => false,
			'sortable'       => true,
			'children'       => array(
				'title'          => new Fieldmanager_TextField( __( 'Section Title', 'internetorg' ) ),
				'content'        => new Fieldmanager_RichTextarea( __( 'Description', 'internetorg' ) ),
				'call-to-action' => new Fieldmanager_Group( array(
					'label'       => __( 'Call to action', 'internetorg' ),
					'label_macro' => __( 'Call to action: %s', 'internetorg' ),
					'add_more_label'    => __( 'Add another CTA', 'internetorg' ),
					'limit'       => 2,
					'children'    => array(
						'link'  => new Fieldmanager_TextField( __( 'Link', 'internetorg' ) ),
						'image' => new Fieldmanager_Media( __( 'Image', 'internetorg' ) ),
					),
				) ),
			),
			'limit' => 0,
		) );

		$fm->add_meta_box( __( 'Content Areas', 'internetorg' ), array( 'page' ) );
	}
}

