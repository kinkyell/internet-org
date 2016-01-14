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

<!-- Add asset shade BEFORE scripts are loaded -->
<script type="text/javascript">
	// Ext Execution of JavaScript
	function exit(e){function o(e){e.stopPropagation()}var t;window.addEventListener("error",function(e){e.preventDefault(),e.stopPropagation()},!1);var n=["copy","cut","paste","beforeunload","blur","change","click","contextmenu","dblclick","focus","keydown","keypress","keyup","mousedown","mousemove","mouseout","mouseover","mouseup","resize","scroll","DOMNodeInserted","DOMNodeRemoved","DOMNodeRemovedFromDocument","DOMNodeInsertedIntoDocument","DOMAttrModified","DOMCharacterDataModified","DOMElementNameChanged","DOMAttributeNameChanged","DOMActivate","DOMFocusIn","DOMFocusOut","online","offline","textInput","abort","close","dragdrop","load","paint","reset","select","submit","unload"];for(t=0;t<n.length;t++)window.addEventListener(n[t],function(e){o(e)},!0);throw window.stop&&window.stop(),""};
	exit( 'Opera 10 Detected' );
</script>
<video controls="" style="width:640px;height:360px;" poster="http://www.html5rocks.com/en/tutorials/video/basics/poster.png">
    <source src="http://www.html5rocks.com/en/tutorials/video/basics/devstories.webm" type="video/webm;codecs=&quot;vp8, vorbis&quot;">
    <source src="http://www.html5rocks.com/en/tutorials/video/basics/devstories.mp4" type="video/mp4;codecs=&quot;avc1.42E01E, mp4a.40.2&quot;">
		<a href="http://www.w3schools.com/html/mov_bbb.mp4">Video</a>
</video>


<?php get_footer();