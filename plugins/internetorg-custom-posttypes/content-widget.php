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
}
