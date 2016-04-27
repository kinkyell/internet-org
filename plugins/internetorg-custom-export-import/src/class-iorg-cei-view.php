<?php

class IORG_CEI_View {

	public static function render( $template, $data = [] ) {
		$path = plugin_dir_path( __DIR__ ) . 'views/';
		extract( $data );
		ob_start();
			include $path . $template . '.php';
			$buffer = ob_get_contents();
		@ob_end_clean();
		echo $buffer;
	}

}
