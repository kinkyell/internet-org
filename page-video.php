<?php
/**
 * The template for displaying the video test page
 *
 * Template Name: Video
 *
 * @package Internet.org
 */

get_header();

?>

<video controls="" style="width:640px;height:360px;" poster="http://www.html5rocks.com/en/tutorials/video/basics/poster.png">
    <source src="http://www.html5rocks.com/en/tutorials/video/basics/devstories.webm" type="video/webm;codecs=&quot;vp8, vorbis&quot;">
    <source src="http://www.html5rocks.com/en/tutorials/video/basics/devstories.mp4" type="video/mp4;codecs=&quot;avc1.42E01E, mp4a.40.2&quot;">
		<a href="http://www.w3schools.com/html/mov_bbb.mp4">Video</a>
</video>


<?php get_footer();
