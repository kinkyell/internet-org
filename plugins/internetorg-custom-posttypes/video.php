<?php
/**
 * This is a "Video" post type.
 *
 * @package internetorg
 */

/**
 * Class Internetorg_Video_PostType
 *
 * @package internetorg
 */
class Internetorg_Video_PostType extends Internetorg_PostType {
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
			'io_video',
			array(
				'labels'              => array(
					'name'          => __( 'Videos' ),
					'singular_name' => __( 'Video' ),
				),
				'public'              => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'menu_position'       => 5,
				'menu_icon'           => 'dashicons-video-alt3',
				'supports'            => array(
					'title',
					'author',
					'thumbnail',
					'custom-fields',
				),
				'can_export'          => true,
				'rewrite'             => array(
					'slug'       => 'video',
					'with_front' => false,
					'pages'      => false,
				),
			)
		);
	}
}
