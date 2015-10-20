<?php
/**
 * Created by PhpStorm.
 * User: bkoren
 * Date: 10/20/15
 * Time: 1:00 PM
 */

class LinkTransformer {

  /**
   * The two-character language code our transformed links should use
   * @var string
   */
  private $languageCode;

  /**
   * Constructor.
   *
   * @param string $languageCode  The two-character language code our transformed links should use
   */
  public function __construct($languageCode) {
    $this->languageCode = $languageCode;
  }

  /**
   * Transforms the provided link to point to the proper domain and language.
   *
   * @param string $url   The URL to be transformed
   * @return string
   */
  public function transform($url) {

    // @TODO: Transform the URL based on the given language

    return $url;
  }
}
