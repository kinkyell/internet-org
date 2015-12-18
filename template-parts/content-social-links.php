<?php
/**
 * Content Social Links template part.
 *
 * @package Internet.org
 */

?>

<div class="introBlock-ft introBlock-ft_rule introBlock-ft_social">
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/<?php echo esc_js( bbl_get_current_lang()->code ); ?>/all.js#xfbml=1&amp;version=v2.3";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
  <div class="fb-like"
      data-layout="standard"
      data-action="like"
      data-show-faces="true"
      data-share="true">
  </div>
</div>
