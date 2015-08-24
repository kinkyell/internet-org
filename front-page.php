<?php
/**
 * This is the home page template
 *
 * @package internetorg
 * @author arichard <arichard@nerdery.com>
 */

// Header
get_header();
$home_background_image_url = '';

// content
?>

<div class="viewWindow-panel isActive">
	<div class="viewWindow-panel-content">
		<div class="viewWindow-panel-content-inner viewWindow-panel-content-inner_home">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php
				if ( has_post_thumbnail() ) :
					$home_background_image_url = internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'full' );
				endif;
				?>

				<?php get_template_part( 'template-parts/content', 'home-page-entry-start' ); ?>
				<?php get_template_part( 'template-parts/content', 'home-page-entry-content' ); ?>
				<?php get_template_part( 'template-parts/content', 'page-home-blocks' ); ?>
				<?php get_template_part( 'template-parts/content', 'home-page-entry-end' ); ?>

			<?php endwhile; // End of the loop. ?>

		</div>
	</div>
</div>

<?php

/*
 * these next sections are only partially figured out by FE so until it's done
 * there's no real way of telling how we're going to have to structure things
 * on the BE
 *
 * @todo finish this when the styles are complete
 */

?>

<div class="viewWindow-panel viewWindow-panel_feature">
	<div class="viewWindow-panel-content">
		<div class="viewWindow-panel-content-inner" style="background-image: url('<?php esc_attr_e( $home_background_image_url ); ?>');"></div>
	</div>
</div>

<div class="viewWindow-panel viewWindow-panel_story">
	<div class="viewWindow-panel-content">
		<div class="viewWindow-panel-content-inner" style="background-color: #dddddd;">
			<div class="vr_x5 theme-approach">

				<div class="feature"> <!-- TEXT -->
					<div class="feature-hd">
						<div class="hdg hdg_2">Text Block</div>
					</div>
					<div class="feature-bd">
						<p class="bdcpy">
							Lorem ipsum dolor sit amet, con sectetur adipiscing elit. Sus pendisse est nulla, suscipit eu ante vel, vehicula finibus nisi. Integer ut felis purus. Etiam vehicula, ipsum non venenatis feugiat, lorem tellus euismod mi, eu bibendum ligula.
						</p>
					</div>
				</div>

				<div class="feature"> <!-- TEXT IMAGE -->
					<div class="feature-hd">
						<div class="hdg hdg_2">Text Block with Image</div>
					</div>
					<div class="feature-bd">
						<p class="bdcpy">
							Lorem ipsum dolor sit amet, con sectetur adipiscing elit. Sus pendisse est nulla, suscipit eu ante vel, vehicula finibus nisi.
						</p>
					</div>
					<div class="feature-media">
						<img src="//www.placehold.it/480x280" alt="FPO">
					</div>
				</div>

				<div class="feature"> <!-- IMAGE TEXT QUOTE -->
					<div class="feature-hd">
						<div class="hdg hdg_2">Image, Text and Quote</div>
					</div>
					<div class="feature-media">
						<img src="//www.placehold.it/480x280" alt="FPO">
					</div>
					<div class="feature-bd">
						<p class="bdcpy">
							Lorem ipsum dolor sit amet, con sectetur adipiscing elit. Sus pendisse est nulla, suscipit eu ante vel, vehicula finibus nisi.
						</p>
					</div>
					<blockquote class="quote">
						<div class="quote-statement">
							<p>This is a quote adipiscing elitasie sus pendisse est nulla, suscipit eante finibus nisipiscing lorem ipsum.</p>
						</div>
						<div class="quote-author">Jenny Johannesson</div>
					</blockquote>
				</div>

				<div class="feature"> <!-- VIDEO TEXT LINK -->
					<div class="feature-hd">
						<div class="hdg hdg_2">Video, Text and Link</div>
					</div>
					<div class="feature-media">
						<img src="//www.placehold.it/480x280" alt="FPO">
					</div>
					<div class="feature-bd">
						<p class="bdcpy">
							Lorem ipsum dolor sit amet, con sectetur adipiscing elit. Sus pendisse est nulla, suscipit eu ante vel, vehicula finibus nisi.
						</p>
					</div>
					<div class="feature-cta">
						<a class="link" href="">Link</a>
					</div>
				</div>

				<div class="feature"> <!-- IMAGE w/CAPTION -->
					<div class="feature-hd">
						<div class="hdg hdg_2">Image with Caption</div>
					</div>
					<div class="feature-media">
						<img src="//www.placehold.it/480x280" alt="FPO">
						<div class="feature-media-caption">
						   <p>Caption lorem ipsum dolor sit amet, consectetur adipiscing elit suspendisse. </p>
						</div>
					</div>
				</div>

				<div class="feature"> <!-- VIDEO TEXT LINK -->
					<div class="feature-hd">
						<div class="hdg hdg_2">Video, Text and Link</div>
					</div>
					<div class="feature-media">
						<div class="contentOnMedia">
							<img class="contentOnMedia-media" src="//www.placehold.it/480x280">
							<div class="contentOnMedia-details">
								<div class="contentOnMedia-details-title">Video Title</div>
								<div class="contentOnMedia-details-duration">00:00</div>
							</div>
							<a href="" class="contentOnMedia-link contentOnMedia-link_ct">
								<span class="circleBtn circleBtn_play"></span>
							</a>
						</div>
					</div>
					<div class="feature-bd">
						<p class="bdcpy">
							Lorem ipsum dolor sit amet, con sectetur adipiscing elit. Sus pendisse est nulla, suscipit eu ante vel, vehicula finibus nisi.
						</p>
					</div>
					<div class="feature-cta">
						<a class="link" href="">Link</a>
					</div>
				</div>

				<div class="feature"> <!-- GALLERY -->
					<div class="feature-hd">
						<div class="hdg hdg_2">Gallery <span class="hdg hdg_5 mix-hdg_gray">(TODO: Define Features)</span></div>
					</div>
					<div class="feature-media">
						<div class="gallery">
							<img src="//www.placehold.it/480x280" alt="FPO">
						</div>
					</div>

					<div class="feature-bd">
						<p class="bdcpy">
							Lorem ipsum dolor sit amet, con sectetur adipiscing elit. Sus pendisse est nulla, suscipit eu ante vel, vehicula finibus nisi.
						</p>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>



<?php

// Sidebar -- no sidebar on the front page
// get_sidebar();

// Footer
get_footer();
