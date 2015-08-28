<?php
/**
 * This file defines the Content Widget CPT.
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */

/**
 * Class Internetorg_ContentWidget_PostType
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */
class Internetorg_ContentWidget_PostType extends Internetorg_PostType {
	/**
	 * Show this type in the menu.
	 *
	 * @var bool $showInNavMenu
	 */
	protected $showInNavMenu = true;

	/**
	 * Constructor.
	 */
	public function __construct() {
		Internetorg_PostType::__construct(
			'io_ctntwdgt',
			array(
				'labels'        => array(
					'name'          => __( 'Content Widgets' ),
					'singular_name' => __( 'Content Widget' ),
				),
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 5,
				'menu_icon'     => 'dashicons-text',
				'supports'      => array(
					'title',
					'editor',
					'custom-fields',
				),
				'can_export'    => true,
			)
		);
	}

	/**
	 * Add meta boxes to this post type.
	 *
	 * @note Currently there are no custom meta boxes for this post type.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		$fmLink = new Fieldmanager_Group(
			array(
				'name'        => 'widget-data',
				'label'       => __( 'Call to Action', 'internetorg' ),
				'label_macro' => __( 'Call to Action: %s', 'internetorg' ),
				'collapsed'   => false,
				'sortable'    => false,
				'limit'       => 0,
				'children'    => array(
					'label' => new Fieldmanager_TextField( __( 'Button Label', 'internetorg' ) ),
					'url'   => new Fieldmanager_Link( __( 'URL', 'internetorg' ) ),
					'image' => new Fieldmanager_Media( __( 'File', 'internetorg' ) ),
				),
			)
		);
		$fmLink->add_meta_box( __( 'Call to Action', 'internetorg' ), array( 'io_ctntwdgt' ) );
	}


}
