<?php
/**
 * This is a "Press" post type
 *
 * @package internetorg
 * @author arichard <arichard@nerdery.com>
 */

/**
 * Class Internetorg_Press_PostType
 *
 * @package internetorg
 * @author arichard <arichard@nerdery.com>
 */
class Internetorg_Press_PostType extends Internetorg_PostType
{
	/**
	 * @var bool true show this type in the menu
	 */
	protected $showInNaveMenu = true;

	/**
	 * constructor
	 */
	public function __construct() {
		Internetorg_PostType::__construct(
			'io_press',
			array(
				'labels'        => array(
					'name'          => __( 'Press Articles' ),
					'singular_name' => __( 'Press Article' ),
				),
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 5,
				'menu_icon'     => 'dashicons-media-document',
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
					'slug'       => 'press',
					'with_front' => false,
					'pages'      => true,
				),
			)
		);
	}
}

