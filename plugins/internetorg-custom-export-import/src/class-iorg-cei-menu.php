<?php

class IORG_CEI_Menu {

	private $export_route;
	private $import_route;

	public function __construct() {

		$this->export_route = 'iorg-cei-export';
		$this->import_route = 'iorg-cei-import';

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_date_picker' ) );
		add_action( 'admin_menu', array( $this, 'add_menus_to_dashboard' ) );
		add_action( 'load-toplevel_page_iorg-cei-export', array( $this, 'generate_export_file' ) );

	}

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
			array( $this, 'render_dashboard_import_page' )
		);
	}

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

	public function generate_export_file() {

		if (  $this->get_current_action() != 'process' || current_user_can( 'manage_options' ) == false ) {
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

	public function render_dashboard_import_page() {

		IORG_CEI_View::render( 'import' );
	}

	public function enqueue_date_picker() {
		wp_enqueue_script( 'jquery-ui-datepicker', false, array( 'jquery-ui-core', 'jquery-ui-datepicker' ) );
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'jquery-ui-datepicker', IORG_CEI_Assets::url( '/css/datepicker.css' ) );
	}

	public function get_current_action() {
		if ( isset( $_REQUEST['action'] ) )  {
			return $_REQUEST['action'];
		}
		return false;
	}
}
