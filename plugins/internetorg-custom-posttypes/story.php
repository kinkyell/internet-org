<?php
/**
 * This is a "Story" post type.
 *
 * @package internetorg
 * @author  arichard <arichard@nerdery.com>
 */

/**
 * Class Internetorg_Story_PostType
 *
 * @package internetorg
 * @author  arichard <arichard@nerdery.com>
 */
class Internetorg_Story_PostType extends Internetorg_PostType {
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
			'io_story',
			array(
				'labels'        => array(
					'name'          => __( 'Stories' ),
					'singular_name' => __( 'Story' ),
				),
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 5,
				'menu_icon'     => 'dashicons-admin-post',
				'supports'      => array(
					'title',
					'editor',
					'author',
					'thumbnail',
					'custom-fields',
				),
				'taxonomies'    => array(
					'category',
					'post_tag',
				),
				'can_export'    => true,
				'rewrite'       => array(
					'slug'       => 'story',
					'with_front' => false,
					'pages'      => true,
				),
			)
		);
	}
}
