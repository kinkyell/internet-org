<?php

/**
 * Add the menus for the plugin. This includes handling some basic
 * logic before calling the Exporter and Importer classes.
 */
class IORG_CEI_Menu {

	/**
	 * Slug for the export page
	 * @var string
	 */
	private $export_route;

	/**
	 * Slug for the import page
	 * @var string
	 */
	private $import_route;

	/**
	 * Set up the hooks for adding menus.
	 */
	public function __construct() {

		$this->export_route = 'iorg-cei-export';
		$this->import_route = 'iorg-cei-import';

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_date_picker' ) );
		add_action( 'admin_menu', array( $this, 'add_menus_to_dashboard' ) );
		add_action( 'load-toplevel_page_iorg-cei-export', array( $this, 'generate_export_file' ) );
	}

	/**
	 * Add the Import and Export under CEI in the dashboard.
	 */
	public function add_menus_to_dashboard() {

		add_menu_page(
			'CEI',
			'CEI',
			'manage_options',
			$this->export_route,
			array( $this, 'render_export_page' ),
			'dashicons-chart-pie',
			10
		);

		add_submenu_page(
			'iorg-cei-export',
			'Export',
			'Export',
			'manage_options',
			$this->export_route,
			array( $this, 'render_export_page' )
		);

		add_submenu_page(
			'iorg-cei-export',
			'Import',
			'Import',
			'manage_options',
			$this->import_route,
			array( $this, 'render_import_page' )
		);

	}

	/**
	 * Renders the export page, allowing the user to select what they would
	 * like to export.
	 */
	public function render_export_page() {
		global $shortcode_tags;

		$sites = wp_get_sites();
		unset($sites[0]);

		$po_strings = new IORG_CEI_PO_Strings;

		IORG_CEI_View::render( 'export', array(
			'types'    	  => get_post_types(),
			'statuses' 	  => array( 'all', 'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash' ),
			'users'    	  => get_users(),
			'sites'	 	  => $sites,
			'shortcodes'  => json_encode( $shortcode_tags ),
			'form_action' => IORG_CEI_Route::action( $this->export_route, 'process' ),
			'po_strings'  => $po_strings->get(),
		) );

	}

	/**
	 * Calls the Exporter class and returns data as file type for the
	 * user to download.
	 */
	public function generate_export_file() {

		$is_valid_nonce = ( isset( $_POST[ 'nonce' ] ) && wp_verify_nonce( $_POST[ 'nonce' ], 'iorg_cei_export' ) );

		if ( $is_valid_nonce != true && $this->get_current_action() != 'process' || current_user_can( 'manage_options' ) == false ) {
			return false;
		}

		$timestamp = time();
        $file_name = "export_{$timestamp}.xml";

        header( 'Pragma: public' );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header( 'Last-Modified: '.gmdate ( 'D, d M Y H:i:s', $timestamp ) . ' GMT' );
        header( 'Cache-Control: private', false );
        header( 'Content-Type: application/txt' );
        header( 'Content-Disposition: attachment; filename="'. basename($file_name) . '"' );
        header( 'Content-Transfer-Encoding: binary' );
        header( 'Connection: close' );

        $exporter = new IORG_CEI_Exporter;
        $exporter->run( $_POST );

		exit;
	}

	/**
	 * Renders the import page, allowing the user to upload a file.
	 * If the action is set to 'process' we call 'process_import_file'
	 */
	public function render_import_page() {

		if ( $this->get_current_action() == 'process' ) {

			IORG_CEI_View::render( 'importing', array(
				'output' => $this->process_import_file(),
			) );

		} else {

			$sites = wp_get_sites();
			unset($sites[0]);

			IORG_CEI_View::render( 'import', array(
				'sites'	 	  => $sites,
				'form_action' => IORG_CEI_Route::action( $this->import_route, 'process' ),
			) );
		}

	}

	/**
	 * Calls the Importer class to import the data from the users file.
	 */
	public function process_import_file() {

		$is_valid_nonce = ( isset( $_POST[ 'nonce' ] ) && wp_verify_nonce( $_POST[ 'nonce' ], 'iorg_cei_import' ) );

		if ( $is_valid_nonce != true && $this->get_current_action() != 'process' || current_user_can( 'manage_options' ) == false ) {
			return false;
		}
		if ( !isset( $_FILES['import-file'] ) ) {
			return false;
		}

		$contents = file_get_contents( $_FILES['import-file']['tmp_name'] );

        $importer = new IORG_CEI_Importer;

        return $importer->run( $contents, $_POST );
	}

	/**
	 * Enqueues the Jquery and CSS needed for the menus
	 */
	public function enqueue_date_picker() {
		wp_enqueue_script( 'jquery-ui-datepicker', false, array( 'jquery-ui-core', 'jquery-ui-datepicker' ) );
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'jquery-ui-datepicker', IORG_CEI_Assets::url( '/css/datepicker.css' ) );
		wp_enqueue_style( 'iorg-cei-dashboard', IORG_CEI_Assets::url( '/css/dashboard.css' ) );
	}

	/**
	 * Return the current action, e.g. process
	 * @return string|bool
	 */
	public function get_current_action() {
		if ( isset( $_REQUEST['action'] ) )  {
			return $_REQUEST['action'];
		}
		return false;
	}
}
