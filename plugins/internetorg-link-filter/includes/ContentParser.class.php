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

    // @TODO: parse through DOM, for each link: $newLink = $this->linkTransformer($oldLink);

    return $content;
  }
}
