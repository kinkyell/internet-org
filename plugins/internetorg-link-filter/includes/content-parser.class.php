<?php
/**
 * File ContentParser.class.php
 *
 * @package Internet.org
 */

/**
 * Class ContentParser.
 *
 * Walks through a given string of HTML content and transforms the appropriate links
 * into the proper language, utilizing LinkTransformer. Does NOT support HTML with
 * <head> elements as these are stripped out - see parseMarkup() for details.
 *
 * @author Ben Koren <bkoren@nerdery.com>
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
class ContentParser {

	/**
	 * Link transformer object.
	 *
	 * @var LinkTransformer
	 */
	private $linkTransformer;

	/**
	 * Constructor
	 *
	 * @param LinkTransformer $linkTransformer Link transformer object.
	 */
	public function __construct( LinkTransformer $linkTransformer ) {
		$this->linkTransformer = $linkTransformer;
	}

	/**
	 * Parses through the content output of WP and transforms links to correspond
	 * to the current language.
	 *
	 * @param string $content Large string containing all of the content to be rendered.
	 *                          Received from the_content filter from WP.
	 *
	 * @return string
	 */
	public function parse_markup( $content ) {

		if ( empty( $content ) ) {
			return $content;
		}

		/*
		 * DOMDocument::loadHTML() does not, by default, support UTF-8 characters. However, we can hint
		 * that our content needs to be processed as UTF-8 by adding the following meta element. The only
		 * caveat to that is that a <head><meta [...] /></head> structure gets automatically added to the
		 * DOM, so we need to remove that later - see below for the call to removeHeadNode().
		 */
		$content = '<meta http-equiv="content-type" content="text/html; charset=utf-8">' . $content;

		$dom = new DOMDocument();
		$dom->loadHTML( $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

		// If the content could not be parsed as HTML, do not filter.
		if ( ! $dom ) {
			return $content;
		}

		// Recurse through the content, transforming all applicable links to use the proper language.
		$this->traverse_dom( $dom );

		/*
		 * As mentioned above, we need to remove the <head> element and that was automatically added as a
		 * result of us hinting the charset for the DOM handler.
		 */
		$this->remove_head_node( $dom );

		$transformedContent = $dom->saveHTML();

		/*
		 * Removes the extra DOCTYPE, html, and body HTML wrappers that get added by old versions
		 * of PHP and/or LibXML. In new versions of PHP and LibXML, passing in the proper "LIBXML_*"
		 * flags to loadHTML() result in the undesirable parent elements from being added. However,
		 * the VIP environment is using old versions so we need to do this manually.
		 */
		$trimmedContent = $this->remove_extraneous_wrappers( $transformedContent );

		return $trimmedContent;
	}

	/**
	 * Recursive method used to traverse the DOM and filter all links. We loop through the current
	 * node's children rather than simply recurse over the children because in order to replace
	 * a child, DOMDocument needs to know the parent.
	 *
	 * @param DOMDocument $dom Document to parse.
	 */
	protected function traverse_dom( DOMDocument $dom ) {
		/**
		 * Processing tags.
		 *
		 * @var DomElement $item Anchor element.
		 */
		foreach ( $dom->getElementsByTagName( 'a' ) as $item ) {
			if ( ! $this->should_href_be_transformed( $item ) ) {
				$item->setAttribute( 'excluded', 'true' );
				continue;
			}

			$href = $item->getAttribute( 'href' );
			$href = $this->linkTransformer->transform( $href );

			$item->setAttribute( 'href', $href );
		}
	}

	/**
	 * Determines whether a given DOMElement/href should be transformed. We take the
	 * DOMElement in as a parameter because we intend on using HTML classes to manage
	 * exclusions.
	 *
	 * @param DOMElement $element The element containing the anchor in question.
	 *
	 * @return boolean
	 */
	protected function should_href_be_transformed( DOMElement $element ) {
		// @TODO: Account for exclusions here.
		return true;
	}

	/**
	 * Removes the extra DOCTYPE, html, and body HTML wrappers that get added by old versions
	 * of PHP and/or LibXML.
	 *
	 * @param string $content String containing HTML.
	 *
	 * @return string
	 */
	protected function remove_extraneous_wrappers( $content ) {
		return preg_replace( '/^<!DOCTYPE.+?>/', '', str_replace( array(
			'<html>',
			'</html>',
			'<body>',
			'</body>',
		), array( '', '', '', '' ), $content ) );
	}

	/**
	 * Removes the entire <head> node and its children from a given DOMDocument.
	 *
	 * @param DOMDocument $dom Document to parse.
	 */
	protected function remove_head_node( DOMDocument $dom ) {
		/**
		 * Processing head tags.
		 *
		 * @var DomElement $item head tag.
		 */
		foreach ( $dom->getElementsByTagName( 'head' ) as $item ) {
			$item->parentNode->removeChild( $item );
		}
	}
}
