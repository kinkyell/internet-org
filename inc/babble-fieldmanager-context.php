<?php
/**
 * Babble Fieldmanager Context Class.
 *
 * @package Internet.org
 */

/**
 * Class Babble_Fieldmanager_Context
 *
 * Extends the Fieldmanager_Context_Storable class for Babble to use.
 */
class Babble_Fieldmanager_Context extends Fieldmanager_Context_Storable {

	/**
	 * Title of meta box.
	 *
	 * @var string $title
	 */
	public $title = '';

	/**
	 * Base field.
	 *
	 * @var Fieldmanager_Group $fm
	 */
	public $fm = null;

	/**
	 * Add a context to a Fieldmanager.
	 *
	 * @param string             $title Title.
	 * @param Fieldmanager_Field $fm    Fieldmanager field.
	 */
	public function __construct( $title, $fm = null ) {

		$this->title = $title;
		$this->fm    = $fm;

	}

	/**
	 * Renders fields.
	 *
	 * @param WP_Post   $post   Post.
	 * @param array     $values Values.
	 * @param bool|true $echo   Echo.
	 */
	public function render_fields( $post, $values = array(), $echo = true ) {
		$this->fm->data_type = 'post';
		$this->fm->data_id   = $post->ID;

		$this->render_field( array( 'data' => $values, 'echo' => $echo ) );

		// Check if any validation is required.
		$fm_validation = Fieldmanager_Util_Validation( 'post', 'post' );
		$fm_validation->add_field( $this->fm );
	}

	/**
	 * Renders a single field.
	 *
	 * @param array $args Args.
	 *
	 * @return string
	 */
	protected function render_field( $args = array() ) {
		$data = array_key_exists( 'data', $args ) ? $args['data'] : null;
		$echo = isset( $args['echo'] ) ? $args['echo'] : true;

		$field = $this->fm->element_markup( $data );

		if ( $echo ) {
			echo $field;

			return;
		}

		return $field;
	}

	/**
	 * Get post meta.
	 *
	 * @see get_post_meta().
	 *
	 * @param int        $post_id  Post ID.
	 * @param string     $meta_key Meta Key.
	 * @param bool|false $single   Single.
	 *
	 * @return mixed
	 */
	protected function get_data( $post_id, $meta_key, $single = false ) {
		return get_post_meta( $post_id, $meta_key, $single );
	}

	/**
	 * Add post meta.
	 *
	 * @see add_post_meta().
	 *
	 * @param int        $post_id    Post ID.
	 * @param string     $meta_key   Meta Key.
	 * @param mixed      $meta_value Meta Value.
	 * @param bool|false $unique     Unique.
	 *
	 * @return void
	 */
	protected function add_data( $post_id, $meta_key, $meta_value, $unique = false ) {
		return;
	}

	/**
	 * Update post meta.
	 *
	 * @see update_post_meta().
	 *
	 * @param int    $post_id         Post ID.
	 * @param string $meta_key        Meta Key.
	 * @param mixed  $meta_value      Meta Value.
	 * @param string $data_prev_value Data Prev Value.
	 *
	 * @return void
	 */
	protected function update_data( $post_id, $meta_key, $meta_value, $data_prev_value = '' ) {
		return;
	}

	/**
	 * Delete post meta.
	 *
	 * @see delete_post_meta().
	 *
	 * @param int    $post_id    Post ID.
	 * @param string $meta_key   Meta Key.
	 * @param string $meta_value Meta Value.
	 *
	 * @return void
	 */
	protected function delete_data( $post_id, $meta_key, $meta_value = '' ) {
		return;
	}
}

/**
 * Class Babble_Fieldmanager_TextField
 *
 * A Babble compatible version of Fieldmanager_TextField.
 */
class Babble_Fieldmanager_TextField extends Fieldmanager_TextField {
	/**
	 * Render a field.
	 *
	 * @param string  $name  Name.
	 * @param mixed   $value Value.
	 * @param string  $title Title.
	 * @param WP_Post $post  Post.
	 */
	public function render_field( $name, $value, $title, $post ) {
		$context = new Babble_Fieldmanager_Context( $title, $this );

		return $context->render_fields( $post, $value );
	}
}

/**
 * Class Babble_Fieldmanager_TextArea
 *
 * A Babble compatible version of Fieldmanager_TextArea.
 */
class Babble_Fieldmanager_TextArea extends Fieldmanager_TextArea {
	/**
	 * Render a field.
	 *
	 * @param string  $name  Name.
	 * @param mixed   $value Value.
	 * @param string  $title Title.
	 * @param WP_Post $post  Post.
	 */
	public function render_field( $name, $value, $title, $post ) {
		$context = new Babble_Fieldmanager_Context( $title, $this );

		return $context->render_fields( $post, $value );
	}
}

/**
 * Class Babble_Fieldmanager_Media
 *
 * A Babble compatible version of Fieldmanager_Media.
 */
class Babble_Fieldmanager_Media extends Fieldmanager_Media {
	/**
	 * Render a field.
	 *
	 * @param string  $name  Name.
	 * @param mixed   $value Value.
	 * @param string  $title Title.
	 * @param WP_Post $post  Post.
	 */
	public function render_field( $name, $value, $title, $post ) {
		$context = new Babble_Fieldmanager_Context( $title, $this );

		return $context->render_fields( $post, $value );
	}
}

/**
 * Class Babble_Fieldmanager_Group
 *
 * A Babble compatible version of Fieldmanager_Group.
 */
class Babble_Fieldmanager_Group extends Fieldmanager_Group {
	/**
	 * Render a field.
	 *
	 * @param string  $name  Name.
	 * @param mixed   $value Value.
	 * @param string  $title Title.
	 * @param WP_Post $post  Post.
	 */
	public function render_field( $name, $value, $title, $post ) {
		$context = new Babble_Fieldmanager_Context( $title, $this );

		return $context->render_fields( $post, $value );
	}
}

/**
 * Class Babble_Fieldmanager_RichTextarea
 *
 * A Babble compatible version of Fieldmanager_RichTextarea.
 */
class Babble_Fieldmanager_RichTextarea extends Fieldmanager_RichTextarea {

	/**
	 * A babble element id.
	 *
	 * @var null|mixed
	 */
	public $babble_element_id = null;

	/**
	 * Render a field.
	 *
	 * @param string  $name  Name.
	 * @param mixed   $value Value.
	 * @param string  $title Title.
	 * @param WP_Post $post  Post.
	 */
	public function render_field( $name, $value, $title, $post ) {
		$context = new Babble_Fieldmanager_Context( $title, $this );

		return $context->render_fields( $post, $value, true );
	}

	/**
	 * Get a element id.
	 *
	 * @return mixed|null
	 */
	public function get_element_id() {
		// Allows different id for Babble edit screen.
		if ( true == isset( $this->babble_element_id ) && false === empty( $this->babble_element_id ) ) {
			return $this->babble_element_id;
		}
		$el       = $this;
		$id_slugs = array();
		while ( $el ) {
			$slug = $el->is_proto ? 'proto' : $el->seq;
			array_unshift( $id_slugs, $el->name . '-' . $slug );
			$el = $el->parent;
		}

		// Wp_editor can not take ID containintg '[]'.
		return str_replace( array( '[', ']' ), '-', 'fm-' . implode( '-', $id_slugs ) );
	}
}

/**
 * Class Babble_Fieldmanager_Autocomplete
 *
 * A Babble compatible version of Fieldmanager_Autocomplete.
 */
class Babble_Fieldmanager_Autocomplete extends Fieldmanager_Autocomplete {
	/**
	 * Render a field.
	 *
	 * @param string  $name  Name.
	 * @param mixed   $value Value.
	 * @param string  $title Title.
	 * @param WP_Post $post  Post.
	 */
	public function render_field( $name, $value, $title, $post ) {
		$context = new Babble_Fieldmanager_Context( $title, $this );

		return $context->render_fields( $post, $value );
	}
}

/**
 * Class Babble_Fieldmanager_Link
 *
 * A Babble compatible version of Fieldmanager_Link.
 */
class Babble_Fieldmanager_Link extends Fieldmanager_Link {
	/**
	 * Render a field.
	 *
	 * @param string  $name  Name.
	 * @param mixed   $value Value.
	 * @param string  $title Title.
	 * @param WP_Post $post  Post.
	 */
	public function render_field( $name, $value, $title, $post ) {
		$context = new Babble_Fieldmanager_Context( $title, $this );

		return $context->render_fields( $post, $value );
	}
}

/**
 * Class Babble_Fieldmanager_Meta_Field
 *
 * Allows babble to display Fieldmanager's metaboxes inside Babble. It's not doing anything interesting except it's
 * renaming Fieldmanager's fields in order to match Babble's requirements and is creating new
 * Babble_{$fieldmanager_field} class. All those (Babble_{$fieldmanager_field}) classes has to be created in order to
 * make Babble play nice with Fieldmanager as I also created a new context for Babble (the topmost class
 * Babble_Fieldmanager_Context) which removes all actions and filters which else would be registered by Post context of
 * Fieldmanager (and we really don't need those).
 */
class Babble_Fieldmanager_Meta_Field extends Babble_Meta_Field {

	/**
	 * Base field.
	 *
	 * @var Fieldmanager_Field $fm
	 */
	public $fm;

	/**
	 * Name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Args.
	 *
	 * @var array
	 */
	public $args;

	/**
	 * Translation meta key.
	 *
	 * @var string
	 */
	public $translation_meta_key = 'bbl_translation[meta]';

	/**
	 * Constructor.
	 *
	 * @param WP_Post $post       Post.
	 * @param string  $meta_key   Meta Key.
	 * @param string  $meta_title Meta Title.
	 * @param array   $args       Args.
	 */
	public function __construct( WP_Post $post, $meta_key, $meta_title, array $args = array() ) {

		$this->post       = $post;
		$this->meta_key   = $meta_key;
		$this->meta_title = $meta_title;
		$this->meta_value = maybe_unserialize( get_post_meta( $this->post->ID, $this->meta_key, true ) );
		$this->args       = $args;

		$type    = $this->args['fm']['type'];
		$fm_args = $this->args['fm']['args'];

		$type = "Babble_{$type}";

		// Rename.
		$this->name      = "{$this->translation_meta_key}[{$meta_key}]";
		$fm_args['name'] = $this->name;

		$this->fm = new $type( $fm_args );

	}

	/**
	 * Get input.
	 *
	 * @param string $name  Name.
	 * @param mixed  $value Value.
	 */
	public function get_input( $name, $value ) {
		add_filter( 'wp_editor_settings', array( $this, 'wp_editor_css_styles' ) );
		ob_start();
		$this->fm->render_field( $name, $value, $this->meta_title, $this->post );
		$field = ob_get_clean();
		remove_filter( 'wp_editor_settings', array( $this, 'wp_editor_css_styles' ) );

		// Escape related-element for use in jQuery.
		$data_related_element_old = sprintf( 'data-related-element="%s"', esc_attr( $this->name ) );
		$escaped_name             = str_replace( '[', '\\[', $this->name );
		$escaped_name             = str_replace( ']', '\\]', $escaped_name );
		$data_related_element_new = sprintf( 'data-related-element="%s"', $escaped_name );
		$field                    = str_replace( $data_related_element_old, $data_related_element_new, $field );

		// Adjust array position for bbl_translation[meta][home-content-section] format.
		$field = preg_replace_callback( '/data\-fm\-array\-position\=\"(\d+)\"/', function ( $matches ) {
			$pos = intval( $matches[1] );
			if ( 0 !== $pos ) {
				$pos += 2;
			}

			return sprintf( 'data-fm-array-position="%d"', $pos );
		}, $field );

		// @todo might want to echo.
		echo $field;
	}

	/**
	 * Gets output.
	 */
	public function get_output() {
		$this->set_readonly_attribute();
		$this->maybe_update_ids();

		add_filter( 'tiny_mce_before_init', array( $this, 'readonly_for_tinymce' ) );
		add_filter( 'the_editor', array( $this, 'readonly_for_editor_textarea' ) );
		add_filter( 'wp_editor_settings', array( $this, 'wp_editor_css_styles' ) );

		ob_start();
		echo $this->fm->render_field( $this->name, $this->get_value(), $this->meta_title, $this->post );
		$field = ob_get_clean();

		remove_filter( 'tiny_mce_before_init', array( $this, 'readonly_for_tinymce' ) );
		remove_filter( 'the_editor', array( $this, 'readonly_for_editor_textarea' ) );
		remove_filter( 'wp_editor_settings', array( $this, 'wp_editor_css_styles' ) );

		$original_meta = 'bbl_translation_original[meta]';
		$field         = str_replace(
			sprintf( 'name="%s', $this->translation_meta_key ),
			sprintf( 'name="%s', $original_meta ),
			$field
		);

		// Echoing the field instead of properly returing it will preserve HTML.
		echo $field;
	}

	/**
	 * Set readonly attribute on TinyMCE.
	 *
	 * @param array $args Args.
	 *
	 * @return mixed
	 */
	public function readonly_for_tinymce( $args ) {
		$args['readonly'] = 1;

		return $args;
	}

	/**
	 * Set readonly attribute on textarea.
	 *
	 * @param string $the_editor The Editor.
	 *
	 * @return mixed
	 */
	public function readonly_for_editor_textarea( $the_editor ) {
		$the_editor = str_replace( '>%s</textarea></div>', ' readonly="readonly">%s</textarea></div>', $the_editor );

		return $the_editor;
	}

	/**
	 * Add styles to WP_Editor.
	 *
	 * @param array $settings Settings.
	 *
	 * @return mixed
	 */
	public function wp_editor_css_styles( $settings ) {
		// In case it does not exists.
		if ( false === array_key_exists( 'editor_css', $settings ) ) {
			$settings['editor_css'] = '';
		}
		$settings['editor_css'] .= '<style type="text/css">.fm-richtext textarea {color: #333 !important}</style>';

		return $settings;
	}

	/**
	 * Maybe update IDs.
	 *
	 * @param null $el Element.
	 */
	public function maybe_update_ids( $el = null ) {
		if ( null === $el ) {
			$el = $this->fm;
		}
		if ( $el instanceof Babble_Fieldmanager_RichTextarea ) {
			$el->babble_element_id = $el->get_element_id( $el ) . '-original';
		}
		if ( false === empty( $el->children ) ) {
			foreach ( $el->children as $child ) {
				$this->maybe_update_ids( $child );
			}
		}
	}

	/**
	 * Set readonly attribute.
	 *
	 * @param null $el Element.
	 */
	public function set_readonly_attribute( $el = null ) {
		if ( null === $el ) {
			$el = $this->fm;
		}
		$el->attributes = array_merge( $el->attributes, array( 'readonly' => 'readonly' ) );
		if ( false === empty( $el->children ) ) {
			foreach ( $el->children as $child ) {
				$this->set_readonly_attribute( $child );
			}
		}
	}

	/**
	 * Get title.
	 *
	 * @return mixed
	 */
	public function get_title() {
		return $this->meta_title;
	}

	/**
	 * Get value.
	 *
	 * @return mixed
	 */
	public function get_value() {
		return $this->meta_value;
	}

	/**
	 * Get key.
	 *
	 * @return mixed
	 */
	public function get_key() {
		return $this->meta_key;
	}

	/**
	 * Update.
	 *
	 * @param mixed   $value Value.
	 * @param WP_Post $job   Job.
	 *
	 * @return mixed
	 */
	public function update( $value, WP_Post $job ) {
		return $value;
	}
}

/**
 * Class Babble_Translatable_Fieldmanager
 *
 * Allows you to pass Fieldmanager's configuration with slight changes and it takes care of registering fields for
 * both, post/page edit screen and Babble translation screen. The registration for post/page edit screen is standard FM
 * - the Babble_Translatable_Fieldmanager class is really just a fancy wrapper and it's primary purpose is in
 * registering Babble related logic.
 */
class Babble_Translatable_Fieldmanager {

	/**
	 * Type.
	 *
	 * @var null
	 */
	public $type = null;

	/**
	 * Args.
	 *
	 * @var array
	 */
	public $args = array();

	/**
	 * Action args.
	 *
	 * @var array
	 */
	public $action_args = array();

	/**
	 * Babble meta.
	 *
	 * @var null
	 */
	public $babble_meta = null;

	/**
	 * Constructor.
	 *
	 * @param string $type    Type.
	 * @param array  $args    Args.
	 * @param array  $actions Actions.
	 */
	public function __construct( $type, $args, $actions ) {

		$this->type    = $type;
		$this->args    = $args;
		$this->actions = $actions;

		foreach ( $this->actions as $action => $action_args ) {
			switch ( $action ) {
				case 'add_meta_box' :
					list( , $cpts ) = $action_args;
					if ( ! $cpts ) {
						$cpts = array( 'post' );
					} else if ( ! is_array( $cpts ) ) {
						$cpts = array( $cpts );
					}
					foreach ( $cpts as $cpt ) {
						add_action( "fm_post_{$cpt}", array( $this, 'register_fieldmanager_meta' ), 10, 0 );
					}
					break;
			}
		}

		// Some fields need to register their scripts early.
		if ( 'Fieldmanager_Autocomplete' === $this->type ) {
			fm_add_script(
				'fm_autocomplete_js',
				'js/fieldmanager-autocomplete.js',
				array(
					'fieldmanager_script',
					'jquery-ui-autocomplete',
				),
				'1.0.5',
				false,
				'fm_search',
				array(
					'nonce' => wp_create_nonce(
						'fm_search_nonce'
					),
				)
			);
		}

		add_filter( 'bbl_translated_meta_fields', array( $this, 'translated_meta_fields' ), 10, 2 );
		add_filter( 'bbl_sync_meta_key', array( $this, 'do_not_sync' ), 10, 2 );
		add_filter( 'bbl_meta_before_save', array( $this, 'before_meta_save' ), 10, 5 );
	}

	/**
	 * Register Fieldmanager meta.
	 */
	public function register_fieldmanager_meta() {

		$fm = new $this->type( $this->args );

		foreach ( $this->actions as $action => $action_args ) {
			switch ( $action ) {
				case 'add_meta_box' :
					list( $title, $posts ) = $action_args;
					if ( true === isset( $action_args[2] ) ) {
						$context = $action_args[2];
					} else {
						$context = 'advanced';
					}
					if ( true === isset( $action_args[3] ) ) {
						$priority = $action_args[3];
					} else {
						$priority = 'default';
					}
					$fm->add_meta_box( $title, $posts, $context, $priority );
					break;
			}
		}
	}

	/**
	 * Translated meta fields.
	 *
	 * @param array   $fields Fields.
	 * @param WP_Post $post   Post.
	 *
	 * @return array
	 */
	public function translated_meta_fields( array $fields, WP_Post $post ) {

		$name  = $this->args['name'];
		$title = $this->actions['add_meta_box'][0];

		$this->babble_meta = new Babble_Fieldmanager_Meta_Field(
			$post,
			$name,
			$title,
			array(
				'fm' => array(
					'type' => $this->type,
					'args' => $this->args,
				),
			)
		);

		$fields[ $name ] = $this->babble_meta;

		// This is a filter! Don't forget to return $fields.
		return $fields;
	}

	/**
	 * Do not sync.
	 *
	 * @param bool   $synchronize Synchronize.
	 * @param string $meta_key    Meta Key.
	 *
	 * @return bool
	 */
	public function do_not_sync( $synchronize, $meta_key ) {
		if ( $meta_key === $this->args['name'] ) {
			$synchronize = false;
		}

		return $synchronize;
	}

	/**
	 * Before meta save.
	 *
	 * @param mixed   $value      Value.
	 * @param WP_Post $job        Job.
	 * @param string  $meta_key   Meta Key.
	 * @param string  $meta_field Meta Field.
	 * @param mixed   $meta_data  Meta Data.
	 *
	 * @return mixed
	 */
	public function before_meta_save( $value, $job, $meta_key, $meta_field, $meta_data ) {
		return $this->babble_meta->update( $value, $job );
	}
}
