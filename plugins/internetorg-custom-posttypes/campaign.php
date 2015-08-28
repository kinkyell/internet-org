<?php
/**
 * This is a "Campaign" post type
 *
 * @package internetorg
 * @author arichard <arichard@nerdery.com>
 */

/**
 * Class Internetorg_Campaign_PostType
 *
 * @package internetorg
 * @author arichard <arichard@nerdery.com>
 */
class Internetorg_Campaign_PostType extends Internetorg_PostType
{
	/**
	 * @var bool true show this type in the menu
	 */
	protected $showInNavMenu = true;

	/**
	 * constructor
	 */
	public function __construct() {
		Internetorg_PostType::__construct(
			'io_campaign',
			array(
				'labels'        => array(
					'name'          => __( 'Campaigns' ),
					'singular_name' => __( 'Campaign' ),
				),
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 5,
				'menu_icon'     => 'dashicons-admin-site',
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
					'slug'       => 'campaign',
					'with_front' => false,
					'pages'      => true,
				),
			)
		);
	}

	/**
	 * Add meta boxes to this post type
	 *
	 * @note currently there are no custom meta boxes for this post type
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		$fm = new Fieldmanager_Media( array(
			'name'               => 'campaign-background',
			'button_label'       => __( 'Add Background', 'internetorg' ),
			'modal_title'        => __( 'Select Background Image', 'internetorg' ),
			'modal_button_label' => __( 'Use Image as Background', 'internetorg' ),
			'preview_size'       => 'icon',
		) );

		$subtitle = new Fieldmanager_TextArea(
			array(
				'name' => 'page_subtitle',
				'label' => __( 'Subtitle', 'internetorg' ),
				'attributes' => array(
					'rows' => 3,
					'cols' => 30,
				),
			)
		);

		$fm->add_meta_box( __( 'Background Image', 'internetorg' ), array( 'io_campaign' ) );
		$subtitle->add_meta_box( __( 'Subtitle', 'internetorg' ), array( 'io_campaign' ) );
	}
}

