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
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'query_var' => false,
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
		$fmLink1 = new Fieldmanager_Group(
			array(
				'name'           => 'widget-data-1',
				'serialize_data' => false,
				'label'          => __( 'Call to Action', 'internetorg' ),
				'label_macro'    => __( 'Call to Action: %s', 'internetorg' ),
				'collapsed'      => true,
				'collapsible'    => true,
				'sortable'       => false,
				'limit'          => 1,
				'children'       => array(
					'label' => new Fieldmanager_TextField( __( 'Button Label', 'internetorg' ) ),
					'url'   => new Fieldmanager_Link( __( 'URL', 'internetorg' ) ),
					'image' => new Fieldmanager_Media( __( 'File', 'internetorg' ) ),
				),
			)
		);
		$fmLink1->add_meta_box( __( 'Call to Action', 'internetorg' ), array( 'io_ctntwdgt' ) );

		$fmLink2 = new Fieldmanager_Group(
			array(
				'name'           => 'widget-data-2',
				'serialize_data' => false,
				'label'          => __( 'Call to Action', 'internetorg' ),
				'label_macro'    => __( 'Call to Action: %s', 'internetorg' ),
				'collapsed'      => true,
				'collapsible'    => true,
				'sortable'       => false,
				'limit'          => 1,
				'children'       => array(
					'label' => new Fieldmanager_TextField( __( 'Button Label', 'internetorg' ) ),
					'url'   => new Fieldmanager_Link( __( 'URL', 'internetorg' ) ),
					'image' => new Fieldmanager_Media( __( 'File', 'internetorg' ) ),
				),
			)
		);
		$fmLink2->add_meta_box( __( 'Call to Action', 'internetorg' ), array( 'io_ctntwdgt' ) );
	}
}
