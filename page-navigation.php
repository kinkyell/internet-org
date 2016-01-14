<?php
/**
 * The template for displaying the navigation page (mostly used for Opera Mini)
 *
 * Template Name: Navigation
 *
 * @package Internet.org
 */

get_header();

?>

<?php while ( have_posts() ) : the_post(); ?>

  <div class="viewWindow isShifted js-viewWindow js-stateDefault" id="main-content" role="main" data-route="<?php the_permalink(); ?>" data-type="panel" data-title="<?php the_title(); ?>" data-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'panel-image' ) ); ?>" data-mobile-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'inline-image' ) ); ?>">
    <div class="mainMenu-center-container">
      <div class="mainMenu-panel-primary">
        <ul id="menu-main-menu" class="">
          <li>
            <div class="topicLink topicLink_theme js-menuView-slider"><a href="/mission" class="topicLink-link js-stateLink">Our Mission</a></div>
          </li>
          <li>
            <div class="topicLink topicLink_theme js-menuView-slider"><a href="/approach" class="topicLink-link js-stateLink">Our Approach</a></div>
          </li>
          <li>
            <div class="topicLink topicLink_theme js-menuView-slider"><a href="/impact" class="topicLink-link js-stateLink">Our Impact</a></div>
          </li>
        </ul>
      </div>
      <div class="mainMenu-panel-secondary">
        <ul id="menu-secondary-menu" class="borderBlocks borderBlocks_2up">
          <li><a class="auxLink js-stateLink" href="/press"><span>Press</span></a></li>
          <li><a class="auxLink js-stateLink" href="/story/platform" target="_blank"><span>Platform</span></a></li>
          <li><a class="auxLink js-stateLink" href="/story/mobile-operator-partnership-program/"><span>Operators</span></a></li>
          <li><a class="auxLink" href="https://fb.me/Internetdotorg" target="_blank"><span>Facebook Page</span></a></li>
          <li><a class="auxLink" href="https://www.facebook.com/careers/" target="_blank"><span>Careers</span></a></li>
          <li><a class="auxLink js-stateLink" href="/contact-us/"><span>Contact</span></a></li>
        </ul>
      </div>
    </div>
  </div>
  <video width="320" height="240" controls>
    <source src="http://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
    <source src="http://www.w3schools.com/html/mov_bbb.ogg" type="video/ogg">
    <p>Video not supported, download the video from here</p>
    <a href="http://google.com.uy">link</a>
  </video>
<?php endwhile; // End of the loop. ?>

<?php get_footer();
