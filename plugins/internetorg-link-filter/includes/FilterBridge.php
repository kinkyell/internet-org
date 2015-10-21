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

  /** @var ContentParser */
  protected $parser;

  /** @var LinkTransformer */
  protected $transformer;

  /**
   * Constructor.
   *
   * @param ContentParser $parser
   * @param LinkTransformer $transformer
   */
  public function __construct(ContentParser $parser, LinkTransformer $transformer) {
    $this->parser = $parser;
    $this->transformer = $transformer;
  }

  /**
   * @param string $content
   * @return string
   */
  public function filterContent($content) {
    return $this->parser->parseMarkup($content);
  }

  /**
   * @param string $url
   * @return string
   */
  public function filterUrl($url) {
    $newUrl = $this->transformer->transform($url);
    return $newUrl;
  }

  /**
   * Handler for page_link filter, which is applied to the permalink URL for a
   * post prior to returning the processed url by the function.
   *
   * @param string $link  The permalink for the page
   * @param int $pageId   The ID for the page represented by this permalink
   * @return string
   **/
  public function filterPageLink($link, $pageId) {
    $newUrl = $this->transformer->transform($link);
    return $newUrl;
  }

  /**
   * Handler for post_link filter, which is applied to the permalink URL for a
   * post prior to returning the processed url by the function.
   *
   * @param string $url     The post URL
   * @param Object $post    The post object
   * @param bool $leavename Whether to keep the post name or page name.
   * @return string
   */
  public function filterPostLink($url, $post, $leavename) {
    $newUrl = $this->transformer->transform($url);
    return $newUrl;
  }

} 