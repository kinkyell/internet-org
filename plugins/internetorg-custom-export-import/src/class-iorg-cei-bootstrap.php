<?php

/**
 * Creates a new instance of each class that is necessary
 * for the plugin to fun.
 */
class IORG_CEI_Bootstrap {

	public function __construct() {

		new IORG_CEI_PO_Strings;
		new IORG_CEI_Menu;

	}
}
