<?php

class IORG_CEI_Assets {

	public static function url( $asset ) {
		return plugin_dir_url( __DIR__ ) . 'assets/' . $asset;
	}

}
