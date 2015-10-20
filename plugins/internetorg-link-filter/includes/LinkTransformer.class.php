<?php
/**
 * File LinkTransformer.class.php
 *
 * @package Internet.org
 */

/**
 * Class LinkTransformer
 *
 * Transform existing link into normalized format specific to
 * the currently selected language
 *
 * @author Ben Koren <bkoren@nerdery.com>
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
class LinkTransformer {

  /**
   * The two-character language code our transformed links should use
   * @var string
   */
  private $languageCode;

  /**
   * Our domain name (ex: vip.local)
   * @var string
   */
  private $domain;

  /**
   * Constructor.
   *
   * @param string $languageCode  The two-character language code our transformed links should use
   * @param string $domain        The current domain
   */
  public function __construct($languageCode, $domain) {
    $this->languageCode = $languageCode;
    $this->domain = $domain;
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
    $pathParts = array_values(array_filter(explode('/', $parsedUrl['path'])));

    // stop at first potential language code
    foreach ($pathParts as $index => $part) {
      if (!$this->isLanguageCode($part)) {
        continue;
      }

      $pathParts[$index] = $this->languageCode;
      break;
    }

    $scheme = isset($pathParts['scheme']) ? $pathParts['scheme'] : 'http';
    $newPath = implode('/', $pathParts);

    return sprintf('%s://%s/%s', $scheme, $this->domain, $newPath);
  }

  /**
   * Test if the input string is a potential language code. This tests only that the
   * string is exactly 2 [a-z] case insensitive characters
   *
   * This could be made more accurate by fetching only the supported languages from babble and
   * testing against that subset, but is sufficient for current requirements.
   *
   * @param string $string
   * @return bool
   */
  protected function isLanguageCode($string) {
    return (bool) preg_match('/^[a-z]{2}/i', $string);
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
