<?php

class IORG_CEI_Route {

	public static function action( $page, $action = false ) {
		$route = get_admin_url() . 'admin.php?page=' . $page;

		if ( $action ) {
			$route .=  '&action=' . $action;
		}

		return $route;
	}

}
