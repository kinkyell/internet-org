<?php
/**
 * This is a "FreeService" post type.
 *
 * @package internetorg
 * @author  arichard <arichard@nerdery.com>
 */

/**
 * Class Internetorg_FreeService_PostType
 *
 * @package internetorg
 * @author  arichard <arichard@nerdery.com>
 */
class Internetorg_FreeService_PostType extends Internetorg_PostType {
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
			'io_freesvc',
			array(
				'labels'        => array(
					'name'          => 'Free Services',
					'singular_name' => 'Free Service',
				),
				'public'        => true,
				'has_archive'   => false,
				'menu_position' => 10,
				'menu_icon'     => 'dashicons-portfolio',
				'supports'      => array(
					'title',
					'excerpt',
					'thumbnail',
					'custom-fields',
					'link',
				),
				'taxonomies'    => array(
					'categories',
				),
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'query_var' => false,
				'can_export'    => true,
				'rewrite'       => array(
					'slug'        => 'free-service',
					'with_front=' => false,
					'pages'       => false,
				),
			)
		);
	}
}

