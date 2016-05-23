<?php

/**
 * Class for working with assets.
 */
class IORG_CEI_Assets {

	/**
	 * Returns Url for asset
	 * @param  string $asset
	 * @return string
	 */
	public static function url( $asset ) {
		return plugin_dir_url( __DIR__ ) . 'assets/' . $asset;
	}

}
