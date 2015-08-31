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

	/**
	 * Add meta boxes to this post type.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		$video_duration = new Fieldmanager_TextField(
			array(
				'name'  => 'video-duration',
				'label' => __( 'Video Duration', 'internetorg' ),
			)
		);

		$video_url = new Fieldmanager_Link(
			array(
				'name'  => 'video-url',
				'label' => __( 'Video URL', 'internetorg' ),
			)
		);

		$video_duration->add_meta_box( __( 'Video Duration', 'internetorg' ), array( 'io_video' ) );
		$video_url->add_meta_box( __( 'Video URL', 'internetorg' ), array( 'io_video' ) );
	}
}
