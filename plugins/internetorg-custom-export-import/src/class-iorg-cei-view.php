<?php

/**
 * Class for rendering a view.
 */
class IORG_CEI_View {

	/**
	 * Loads a PHP file with variables and outputs the result.
	 * @param  string $template
	 * @param  array  $data
	 */
	public static function render( $template, $data = [] ) {
		$path = plugin_dir_path( __DIR__ ) . 'views/';
		$file = $path . $template . '.php';

		if ( validate_file( $file ) === 0 ) {
			extract( $data );
			ob_start();
				include $file;
				$buffer = ob_get_contents();
			@ob_end_clean();
			echo $buffer;
		}
	}

}
