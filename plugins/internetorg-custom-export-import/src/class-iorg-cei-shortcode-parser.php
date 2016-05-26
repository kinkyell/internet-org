<?php

/**
 * Class for converting Shortcodes to and from an XML format.
 */
class IORG_CEI_Shortcode_Parser {

	/**
	 * Stores a list of avaible shortcodes
	 * @var array
	 */
	private $shortcodes_tags;

	/**
	 * Gets current list of shortcodes
	 */
	public function __construct() {
		global $shortcode_tags;

		$this->shortcodes_tags = $shortcode_tags;
	}

	/**
	 * Finds shortcodes in a string and converts them to XML by passing them to 'convert_to_xml':
	 * [name atr="value" /] becomes <name><atr>value</atr></name>
	 * @param  string $content
	 * @return string
	 */
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

	/**
	 * Converts a single shortcode to XML.
	 * [name atr="value" /] becomes <name><atr>value</atr></name>
	 * @param  string $m
	 * @return string
	 */
	private function convert_to_xml( $m ) {

		if ( $m[1] == '[' && $m[6] == ']' ) {
			return substr( $m[0], 1, -1 );
		}

		$tag  = 'wp-' . $m[2];
		$attr = shortcode_parse_atts( $m[3] );
		$xml  = '<' . $tag . ' wp_type="shortcode">';

		foreach( $attr as $key => $value ) {
			$key  = str_replace( '_', '-', $key );
			$xml .= "<{$key}>{$value}</{$key}>";
		}

		if ( isset( $m[5] ) ) {
			$xml .= $this->to_xml( $m[5] );
		}

		$xml .= "</{$tag}>";

		return $xml;
	}

	/**
	 * Finds XML shortcodes in a string and converts them to shortcodes by passing them to 'convert_to_shortcode':
	 * <name><atr>value</atr></name> becomes [name atr="value" /]
	 * @param  string $content
	 * @return string
	 */
	public function from_xml( $content ) {

		$content_xml = simplexml_load_string( '<xml>' . $content . '</xml>' );
		$shortcodes  = $this->get_shortcodes_from_xml( $content_xml );

		foreach ( $shortcodes as $shortcode ) {
			$find 	 = $shortcode->asXML();
			$replace = $this->convert_to_shortcode( $shortcode );
			$content = str_replace( $find, $replace, $content );
		}

		return $content;
	}

	/**
	 * Converts a single XML shortcode to standard shortcode.
	 * <name><atr>value</atr></name> becomes [name atr="value" /]
	 * @param  SimpleXMLElement $shortcode
	 * @return string
	 */
	private function convert_to_shortcode( $shortcode ) {

		$shortcode_name    = str_replace( 'wp-', '', $shortcode->getName() );
		$convert_shortcode = '[' . $shortcode_name . ' ';
		$has_children      = false;

		foreach ( $shortcode->children() as $child ) {
			if ( $child['wp_type'] == 'shortcode' ) {
				$has_children = true;
			} else {
				$convert_shortcode .= str_replace( '-', '_', $child->getName() ) . '="' . $this->xml_to_string_or_html( $child ) . '" ';
			}
		}

		if ( $has_children ) {

			$convert_shortcode .= ' ]';

			foreach ( $shortcode->children() as $child ) {
				if ( $child['wp_type'] == 'shortcode' ) {
					$convert_shortcode .= $this->convert_to_shortcode( $child );
				}
			}

			$convert_shortcode .= '[/' . $shortcode_name . ']';
		} else {
			$convert_shortcode .= ' /]';
		}

		return $convert_shortcode;
	}

	/**
	 * Finds XML shortcodes in a string by doing an
	 * XPath looking for type="shortcode"
	 * @param  SimpleXMLElement $xml
	 * @return array
	 */
	private function get_shortcodes_from_xml( $xml ) {

		$all 		= $xml->xpath('//*[@wp_type="shortcode"]');
		$sub 		= $xml->xpath('//*[@wp_type="shortcode"]//*[@wp_type="shortcode"]');
		$shortcodes = array();

		foreach ( $all as $parent ) {
			$is_parent = true;
			foreach ( $sub as $child ) {
				if ( $parent->asXML() == $child->asXML() ) {
					$is_parent = false;
				}
			}
			if ( $is_parent ) {
				$shortcodes[] = $parent;
			}
		}

		return $shortcodes;
	}

	/**
	 * Gets a string from XML Element even if it contains HTML
	 * @param  SimpleXMLElement $xml
	 * @return string
	 */
	private function xml_to_string_or_html( $xml ) {
		return $this->strip_xml_tag( $xml->asXML(), $xml->getName() );
	}

	/**
	 * Strips opening and closing tag of a string.
	 * @param  string $string
	 * @param  string $tag_name
	 * @return string
	 */
	private function strip_xml_tag( $string, $tag_name ) {
		$string = preg_replace( '/<('. $tag_name .') [^>]*>/', '', $string );
		$string = str_replace( '<'. $tag_name .'>', '', $string );
		$string = str_replace( '</'. $tag_name .'>', '', $string );
		return $string;
	}
}
