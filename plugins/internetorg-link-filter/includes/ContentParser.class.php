<?php
/**
 * Created by PhpStorm.
 * User: bkoren
 * Date: 10/20/15
 * Time: 1:00 PM
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
   * Recursive method used to traverse the DOM and filter all links.
   *
   * @param DOMDocument $dom
   * @param DomNode $node
   */
  protected function traverseDom(DOMDocument $dom, DomNode &$node) {
    if ($node->childNodes) {
      for ($i = 0; $i < $node->childNodes->length; ++$i) {
        /**
         * @var DomElement
         */
        $childNode = $node->childNodes->item($i);
        if ($childNode->tagName == 'a') {
          $href = $childNode->getAttribute('href');j

          // @TODO: pass href to LinkTransformer for transformation.

//          $textReplacementNode = $dom->createTextNode($childNode->data);
//          $node->replaceChild(
//            $textReplacementNode,
//            $childNode
//          );
        }
        $this->traverseDom($dom, $node->childNodes->item($i));
      }
    }
  }
}
