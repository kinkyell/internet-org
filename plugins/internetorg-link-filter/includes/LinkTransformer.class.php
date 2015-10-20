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
   * @param string $url   The URL to be transformed. May or may not already contain a language code.
   * @return string
   */
  public function transform($url) {

    // We only transform internal URLs, not external (ex: google.com).
    if (!$this->isInternal($url)) {
      return $url;
    }

    $parsedUrl = parse_url($url);

    

    // @TODO: Transform the URL based on the given language and current domain. http://domain/langCode/path

    return $url;
  }

  /**
   * Determines whether the given URL is internal. This is used to determine whether the link
   * should be transformed/localized.
   *
   * @param string $url
   * @return bool
   */
  protected function isInternal($url) {
    // @TODO: confirm that this works as intended
    return internetorg_is_internal_url($url);
  }
}
