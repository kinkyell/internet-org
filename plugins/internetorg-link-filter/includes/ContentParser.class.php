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
 * into the proper language, utilizing LinkTransformer.
 *
 * @author Ben Koren <bkoren@nerdery.com>
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
class ContentParser {

  /**
   * @var LinkTransformer
   */
  private $linkTransformer;

  /**
   * Constructor
   *
   * @param LinkTransformer $linkTransformer
   */
  public function __construct(LinkTransformer $linkTransformer) {
    $this->linkTransformer = $linkTransformer;
  }

  /**
   * Parses through the content output of WP and transforms links to correspond
   * to the current language.
   *
   * @param string $content   Large string containing all of the content to be rendered.
   *                          Received from the_content filter from WP.
   * @return string
   */
  public function parseMarkup($content) {

    if (empty($content)) {
      return $content;
    }

    $dom = new DOMDocument();
    $dom->loadHTML($content);

    // If the content could not be parsed as HTML, do not filter.
    if (!$dom) {
      return $content;
    }

    // Recurse through the content, transforming all applicable links
    $this->traverseDom($dom);

    $transformedContent = $dom->saveHTML();

    return $transformedContent;
  }

  /**
   * Recursive method used to traverse the DOM and filter all links. We loop through the current
   * node's children rather than simply recurse over the children because in order to replace
   * a child, DOMDocument needs to know the parent.
   *
   * @param DOMDocument $dom
   */
  protected function traverseDom(DOMDocument $dom) {
    /** @var DomElement $item */
    foreach ($dom->getElementsByTagName('a') as $item) {
      if (!$this->shouldHrefBeTransformed($item)) {
        $item->setAttribute('excluded', 'true');
        continue;
      }

      $href = $item->getAttribute('href');
      $href = $this->linkTransformer->transform($href);

      $item->setAttribute('href', $href);
    }
  }

  /**
   * Determines whether a given DOMElement/href should be transformed. We take the
   * DOMElement in as a parameter because we intend on using HTML classes to manage
   * exclusions.
   *
   * @param DOMElement $element   The element containing the anchor in question
   * @return boolean
   */
  protected function shouldHrefBeTransformed(DOMElement $element) {
    // @TODO: Account for exclusions here.
    return true;
  }
}
