<?php

class IORG_CEI_Shortcode_Parser {

	private $shortcodes_tags;

	public function __construct( $shortcodes = false ) {
		global $shortcode_tags;

		$this->shortcodes_tags = $shortcode_tags;
	}

	public function to_xml( $content ) {

		if ( false === strpos( $content, '[' ) ) {
			return $content;
		}

		if ( empty( $this->shortcodes_tags ) || !is_array( $this->shortcodes_tags ) )
			return $content;

		preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
		$tagnames = array_intersect( array_keys( $this->shortcodes_tags), $matches[1] );

		if ( empty( $tagnames ) ) {
			return $content;
		}

		$content = do_shortcodes_in_html_tags( $content, false, $tagnames );
		$pattern = get_shortcode_regex( $tagnames );
		$content = preg_replace_callback( "/$pattern/",  array( $this, 'convert_to_xml' ), $content );
		$content = unescape_invalid_shortcodes( $content );

		return $content;
	}

	private function convert_to_xml( $m ) {

		if ( $m[1] == '[' && $m[6] == ']' ) {
			return substr( $m[0], 1, -1 );
		}

		$tag  = 'wp-' . str_replace('_', '-', $m[2]);
		$attr = shortcode_parse_atts( $m[3] );
		$xml  = '<' . $tag . ' wp_type="shortcode">';

		foreach( $attr as $key => $value ) {
			$xml .= "<{$key}>{$value}</{$key}>";
		}

		if ( isset( $m[5] ) ) {
			$xml .= $this->to_xml( $m[5] );
		}

		$xml .= "</{$tag}>";

		return $xml;
	}

}
