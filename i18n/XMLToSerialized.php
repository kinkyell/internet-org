<?php
/**
 * This is a helper class for Data Importing
 *
 * @package Internet_org
 * @author arichard <arichard@nerdery.com>
 */

require_once( './dom_debug/dom_debug.php' );

/**
 * Class Xml_To_Serialized - use to reverse SerializedToXML output
 *
 * @package Internet_org
 * @author arichard <arichard@nerdery.com>
 */
class Xml_To_Serialized {

	/**
	 * @var string $file filename of XML file to load
	 */
	public $file = '';

	/**
	 * @var string $xml_string xml string to load
	 */
	public $xml_string = '';

	/**
	 * @var DOMDocument $dom Dom Object based on $file or $xml_string
	 */
	protected $dom;

	/**
	 * @var bool $debug flag that determines if debug output will be displayed
	 */
	public $debug = false;

	/**
	 * @var string NameSpace URI for WordPress
	 */
	public $namespace_wp = 'http://wordpress.org/export/1.2/';

	/**
	 * Constructor.
	 *
	 * @param string $file name of file to load (optional)
	 */
	public function __construct( $file = '' ) {
		$this->dom = new DOMDocument();
		if ( ! empty( $file ) ) {
			$this->load_file( $file );
		}
	}

	/**
	 * Converts stored XML to serialized data where appropriate (data > item# elements)
	 */
	public function convert_data_xml_to_serialized()
	{
		// get all the meta value elements to process
		$metaList = $this->dom->getElementsByTagNameNS( $this->namespace_wp, 'meta_value' );

		for ( $j = 0, $mc = $metaList->length ; $j < $mc ; ++$j ) {
			$currentMeta = $metaList->item( $j );
			$domNodeList = $currentMeta->getElementsByTagName( 'data' );

			for ( $i = 0, $c = $domNodeList->length; $i < $c; ++$i ) {
				$domNode = $domNodeList->item( $i );

				if ( ! $domNode->hasChildNodes() ) {
					continue;
				}

				$resp = $this->process_node( $domNode );
				if ( ! is_array( $resp ) && ! empty( $resp ) ) {
					$resp['data'] = $resp;
				} elseif ( isset( $resp['data'] ) && empty( $resp['data'] ) ) {
					// if we don't have a data element in the response there was
					// nothing to process so bail out
					continue;
				}

				// wrap content in CDATA so we don't have to worry about crazy escaping logic
				$newNode = $this->dom->createCDATASection( serialize( $resp['data'] ) );

				// replace the node we processed with the serialized version
				$domNode->parentNode->replaceChild( $newNode, $domNode );
			}
		}
	}

	/**
	 * Process the specified node into a serializable array for our data model
	 *
	 * @param DomNode $node the current node to process
	 *
	 * @return mixed depending on the node you can get a string, null, or an array
	 */
	protected function process_node( DomNode $node ) {
		$response = null;

		// if this item has children, process them, otherwise we have a "text" type of node
		if ( $node->hasChildNodes() ) {
			$loopedResp = array();

			// recursively process all children
			foreach ( $node->childNodes as $child ) {
				$loopedResp[] = $this->process_node( $child );
			}

			$item = '';
			// flatten out our list of responses
			if ( 1 == count( $loopedResp ) ) {
				$item = $loopedResp[0];
			} else {
				// we have a list of children in a numerically index array so we need
				// to process them all, adding them as key value pairs (worst case
				// they are numerically indexed themselves)
				foreach ( $loopedResp as $r ) {
					// this moves the internal pointer to the beginning of the array and returns the first value
					$val = reset( $r );

					// this gets the first array key of the array
					$key = key( $r );

					$item[ $this->convert_item_key_to_numeric( $key ) ] = $val;
				}
			}

			if ( ! is_array( $item ) || ! $this->is_assoc( $item ) ) {
				$response[ $node->nodeName ] = $item;
				return $response;
			}

			// since we are still here we have more data to work with before returning
			// process this list as several sets of key value pairs
			foreach ( $item as $key => $val ) {
				$response[ $node->nodeName ][ $this->convert_item_key_to_numeric( $key ) ] = $val;
			}
		} else {
			// processing single level node/element
			switch ( $node->nodeType ) {
				case XML_TEXT_NODE:
				case XML_CDATA_SECTION_NODE: // don't add '<![CDATA[' and ']]>' because this data will be serialized
					// pulling text content of out xml node so it can be returned by itself
					$response = $node->textContent;
					break;
				case XML_ELEMENT_NODE:
					$response[ $node->nodeName ] = $node->textContent;
					break;
			}
		}

		return $response;
	}

	/**
	 * converts the array key into a numeric index if it's an item
	 *
	 * When we converted serialized values to XML we converted numeric indices to
	 * strings by appending "item" to the beginning of them, this method seeks
	 * to reverse that.
	 *
	 * @param string $key key to process
	 *
	 * @return mixed string if not an "item", int if it is
	 */
	protected function convert_item_key_to_numeric( $key ) {
		if ( 0 !== strpos( $key, 'item' ) ) {
			return $key;
		}

		$tmpKey = str_replace( 'item', '', $key );
		// is the new key numeric? if so, return it
		if ( is_numeric( $tmpKey ) ) {
			return $tmpKey;
		}

		// if we didn't have an item# key return the original
		return $key;
	}

	/**
	 * Check if array is associative or not
	 *
	 * @param array $array to be checked
	 *
	 * @return bool true is is associative array, false otherwise
	 */
	public function is_assoc( $array ) {
		return (bool) count( array_filter( array_keys( $array ), 'is_string' ) );
	}


	/**
	 * debug this thing - dumps a val to the screen if debug is enabled
	 *
	 * @param mixed $val item to dumped out for debug
	 * @param bool $force_var_dump force val to be var_dump'd instead of echo'd
	 */
	public function debug( $val, $force_var_dump = false ) {
		if ( ! $this->debug ) {
			return;
		}

		echo '<pre>';
		if ( $force_var_dump || ! is_scalar( $val ) ) {
			var_dump( $val );
		} else {
			echo htmlentities( $val, ENT_QUOTES ) . "\n";
		}
		echo '</pre>';
	}

	/**
	 * dump out the dom currently stored internally
	 *
	 * @return void
	 */
	public function dump_dom() {
		if ( ! $this->debug ) {
			return;
		}

		if ( function_exists( 'xmltree_dump' ) ) {
			xmltree_dump( $this->dom );
			return;
		}

		echo 'No way to dump Dom objects';
	}

	/**
	 * dump out the XML currently stored internally
	 *
	 * @return void
	 */
	public function dump_xml() {
		if ( ! $this->debug ) {
			return;
		}

		if ( empty( $this->xml_string ) ) {
			echo 'No XML to dump';
			return;
		}

		echo htmlentities( $this->xml_string, ENT_QUOTES );
	}

	/**
	 * Sets the internal debug flag to specified value
	 *
	 * @param bool $debug (default true) new value for internal debug flag
	 * @return void
	 */
	public function set_debug( $debug = true ) {
		$this->debug = $debug;
	}

	/**
	 * Load specified XML into this object and refresh the DOM object
	 *
	 * @param string $xml XML to load as DOMDocument
	 *
	 * @throws Exception if there is nothing to load into DOM object
	 *
	 * @return void
	 */
	public function load_xml_string( $xml ) {
		$this->xml_string = $xml;
		$this->file = '';
		$this->reload_dom();
	}

	/**
	 * Load specified file into DOM Object
	 *
	 * @param string $filename name of file to load into DOMDocument
	 *
	 * @throws Exception if there is nothing to load into DOM object
	 *
	 * @return void
	 */
	public function load_file( $filename ) {
		$this->file = $filename;
		$this->xml_string = '';
		$this->reload_dom();
	}

	/**
	 * Reload the DOMDocument from inter XML or File, File takes precedence
	 *
	 * @throws Exception if there is nothing to load into DOM object
	 *
	 * @return void
	 */
	private function reload_dom() {
		$this->serialized = '';

		if ( empty( $this->file ) && empty( $this->xml_string ) ) {
			throw new Exception( 'Nothing to load' );
		}

		// loads from file first if both file and xml are set
		if ( ! empty( $this->file ) ) {
			$this->dom->load( $this->file );
			return;
		}

		$this->dom->loadXML( $this->xml_string );
	}

	/**
	 * Saves current dom object to the path specified
	 *
	 * @param string $filepath path to save file
	 * @return int size of output written
	 */
	public function save_to_file( $filepath ) {
		return $this->dom->save( $filepath );
	}

}













