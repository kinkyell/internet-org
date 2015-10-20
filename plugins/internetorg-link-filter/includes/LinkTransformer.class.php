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
   * @param string $domain        The current domain
   */
  public function __construct($languageCode, $domain) {
    $this->languageCode = $languageCode;
  }

  /**
   * Transforms the provided link to point to the proper domain and language.
   *
   * @param string $url   The URL to be transformed
   * @return string
   */
  public function transform($url) {

    // @TODO: Transform the URL based on the given language and current domain

    return $url;
  }
}
