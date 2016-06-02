<?php

/**
 * Class for working with routes.
 */
class IORG_CEI_Route {

	/**
	 * Generate a Url to admin page, with or without actions.
	 * @param  string  $page
	 * @param  boolean $action
	 * @return string
	 */
	public static function action( $page, $action = false ) {
		$route = get_admin_url() . 'admin.php?page=' . rawurlencode( $page );

		if ( $action ) {
			$route .=  '&action=' . rawurlencode( $action );
		}

		return $route;
	}

}
