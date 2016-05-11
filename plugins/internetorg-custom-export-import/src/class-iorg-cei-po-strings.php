<?php

/**
 * Class for handling the storing and reading of PO Strings
 */
class IORG_CEI_PO_Strings {

	const name = '_iorg_cei_po_strings';

	public function __construct()
	{
		add_site_option( self::name, '' );
	}

	public function get()
	{
		return get_site_option( self::name );
	}

	public function set( $value )
	{
		return update_site_option( self::name, $value );
	}
}
