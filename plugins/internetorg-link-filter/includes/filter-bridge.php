<?php
/**
 * File FilterBridge.php
 *
 * @package Internet.org
 */

/**
 * Class FilterBridge.
 *
 * Provides Wordpress-specific filter handling. This bridges the gap between our
 * parser/transformer and the WP filter system.
 *
 * @author Ben Koren <bkoren@nerdery.com>
 */
class FilterBridge {

	/**
	 * Parser object data member.
	 *
	 * @var ContentParser Object that does the parsing.
	 */
	protected $parser;

	/**
	 * Link transformer data member.
	 *
	 * @var LinkTransformer Link transformer worker object.
	 */
	protected $transformer;

	/**
	 * Constructor.
	 *
	 * @param ContentParser   $parser Object that will be used to parse the content.
	 * @param LinkTransformer $transformer Transformer object to use on URLs.
	 */
	public function __construct( ContentParser $parser, LinkTransformer $transformer ) {
		$this->parser      = $parser;
		$this->transformer = $transformer;
	}

	/**
	 * Filter the content.
	 *
	 * @param string $content the content to filter.
	 *
	 * @return string
	 */
	public function filter_content( $content ) {
		return $this->parser->parse_markup( $content );
	}

	/**
	 * Filters the passed in URL.
	 *
	 * @param string $url the URL to process.
	 *
	 * @return string
	 */
	public function filter_url( $url ) {
		$newUrl = $this->transformer->transform( $url );

		return $newUrl;
	}

	/**
	 * Handler for page_link filter, which is applied to the permalink URL for a
	 * post prior to returning the processed url by the function.
	 *
	 * @param string $link The permalink for the page.
	 * @param int    $pageId The ID for the page represented by this permalink.
	 *
	 * @return string
	 **/
	public function filter_page_link( $link, $pageId ) {
		$newUrl = $this->transformer->transform( $link );

		return $newUrl;
	}

	/**
	 * Handler for post_link filter, which is applied to the permalink URL for a
	 * post prior to returning the processed url by the function.
	 *
	 * @param string $url The post URL.
	 * @param Object $post The post object.
	 * @param bool   $leavename Whether to keep the post name or page name.
	 *
	 * @return string
	 */
	public function filter_post_link( $url, $post, $leavename ) {
		$newUrl = $this->transformer->transform( $url );

		return $newUrl;
	}
}
