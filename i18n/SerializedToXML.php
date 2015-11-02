<?php
/**
 * SerializedToXML class definition
 *
 * @package FiInternetOrg
 */

/**
 * SerializedToXML
 *
 * The job of this class is to convert a serialized object into XML
 *
 * @package FiInternetOrg
 * @author Jansen Price <jprice@nerdery.com>
 * @version $Id$
 */
class SerializedToXML
{
    /**
     * Unserialize the PHP serialized array into XML
     *
     * @param string $serialized
     * @param string $rootName
     * @return string
     */
    public function unserialize($serialized, $rootName = 'data')
    {
        $unserialized = $this->maybe_unserialize($serialized);

        // SimpleXMLElement will be your XML to append to
        $xml = new SimpleXMLElementExtended("<$rootName></$rootName>");

        $this->array_to_xml($unserialized, $xml);

        return $xml->asXML();
    }

    /**
     * Convert a plain PHP array into XML
     *
     * @param array $data PHP array
     * @param SimpleXMLElement $xml_data SimpleXML object to append to
     * @return void
     */
    private function array_to_xml($data, &$xml_data)
    {
        foreach ($data as $key => $value) {
            $key = is_numeric($key) ? "item$key" : $key;

            if (is_array($value)) {
                $subnode = $xml_data->addChild($key);
                $this->array_to_xml($value, $subnode);
            } else {
                if (false !== strpos($value, '<')) {
                    $xml_data->addChildWithCDATA("$key", trim("$value"));
                } else {
                    $xml_data->addChild("$key", "$value");
                }
            }
        }
    }

    /**
     * Unserialize a string IF it is a PHP serialized string
     *
     * @param string $original
     * @return mixed
     */
    private function maybe_unserialize( $original )
    {
        if ( $this->is_serialized( $original ) ) {
            // don't attempt to unserialize data that wasn't serialized going in
            return @unserialize( $original );
        }

        return $original;
    }

    /**
     * Detect whether a string contains serialized data
     *
     * @see https://developer.wordpress.org/reference/functions/is_serialized/
     * @param mixed $data
     * @param bool $strict
     * @access public
     * @return void
     */
    private function is_serialized( $data, $strict = true )
    {
        // if it isn't a string, it isn't serialized.
        if ( ! is_string( $data ) ) {
            return false;
        }
        $data = trim( $data );
        if ( 'N;' == $data ) {
            return true;
        }
        if ( strlen( $data ) < 4 ) {
            return false;
        }
        if ( ':' !== $data[1] ) {
            return false;
        }
        if ( $strict ) {
            $lastc = substr( $data, -1 );
            if ( ';' !== $lastc && '}' !== $lastc ) {
                return false;
            }
        } else {
            $semicolon = strpos( $data, ';' );
            $brace     = strpos( $data, '}' );
            // Either ; or } must exist.
            if ( false === $semicolon && false === $brace )
                return false;
            // But neither must be in the first X characters.
            if ( false !== $semicolon && $semicolon < 3 )
                return false;
            if ( false !== $brace && $brace < 4 )
                return false;
        }
        $token = $data[0];
        switch ( $token ) {
            case 's' :
                if ( $strict ) {
                    if ( '"' !== substr( $data, -2, 1 ) ) {
                        return false;
                    }
                } elseif ( false === strpos( $data, '"' ) ) {
                    return false;
                }
                // or else fall through
            case 'a' :
            case 'O' :
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b' :
            case 'i' :
            case 'd' :
                $end = $strict ? '$' : '';
                return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
        }
        return false;
    }
}
