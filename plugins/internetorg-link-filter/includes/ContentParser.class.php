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
    $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    // If the content could not be parsed as HTML, do not filter.
    if (!$dom) {
      return $content;
    }

    // Recurse through the content, transforming all applicable links
    $this->traverseDom($dom);

    $transformedContent = $dom->saveHTML();

    /* Here we are manually removing the DOCTYPE, html, and body tags from the output of saveHTML().
     * In new versions of PHP and LibXML, passing in the proper "LIBXML_*" flags to loadHTML() result
     * in the undesirable parent elements from being added. However, the VIP environment is using old
     * versions so we need to do this manually. */
    $trimmedContent = $this->removeExtraneousWrappers($transformedContent);

    return $trimmedContent;
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

  /**
   * Removes the extra DOCTYPE, html, and body HTML wrappers that get added by old versions
   * of PHP and/or LibXML.
   *
   * @param string $content   String containing HTML
   * @return string
   */
  protected function removeExtraneousWrappers($content) {
    return preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $content));
  }
}
