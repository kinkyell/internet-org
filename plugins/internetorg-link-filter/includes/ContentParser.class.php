<?php
/**
 * Created by PhpStorm.
 * User: bkoren
 * Date: 10/20/15
 * Time: 1:00 PM
 */

/**
 * Class ContentParser. Walks through a given string of HTML content and transforms
 * the appropriate links into the proper language, utilizing LinkTransformer.
 */
class ContentParser {

  /**
   * @var LinkTransformer
   */
  private $linkTransformer;


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
  public function parseLinks($content) {

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
    $this->traverseDom($dom, $dom);

    $transformedContent = $dom->saveHTML();

    return $transformedContent;
  }

  /**
   * Recursive method used to traverse the DOM and filter all links. We loop through the current
   * node's children rather than simply recurse over the children because in order to replace
   * a child, DOMDocument needs to know the parent.
   *
   * @param DOMDocument $dom
   * @param DomNode $node
   */
  protected function traverseDom(DOMDocument $dom, DomNode &$node) {
    if ($node->childNodes) {
      for ($i = 0; $i < $node->childNodes->length; ++$i) {
        /** @var DomElement */
        $childNode = $node->childNodes->item($i);
        if ($childNode->tagName == 'a') {
          $href = $childNode->getAttribute('href');

          if ($this->shouldHrefBeTransformed($href)) {
            $transformedHref = $this->linkTransformer->transform($href);

            // @TODO: Update the node. Not sure if we can just set the attribute or if we have to replace the node.

//          $textReplacementNode = $dom->createTextNode($childNode->data);
//          $node->replaceChild(
//            $textReplacementNode,
//            $childNode
//          );
          }
        }
        $this->traverseDom($dom, $node->childNodes->item($i));
      }
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
