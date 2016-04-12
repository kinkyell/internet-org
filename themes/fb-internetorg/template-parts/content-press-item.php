<?php
/**
 * The template part for displaying single post summaries in home.php (press page).
 *
 * @package Internet.org
 */

?>

<div class="resultsList-list-item">

	<?php if ( has_post_thumbnail() ) : ?>
	<div class="media media_inline"><?php 
		if ( internetorg_get_post_thumbnail( get_the_ID(), 'listing-image' ) ){ ?>
			<div class="media-figure">
				<img src="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'listing-image' ) ); ?>" alt="" />
			</div><?php 
		}?>
		<div class="media-bd">
			<?php endif; ?>

			<div class="feature feature_tight">
				<div class="feature-hd">
					<a
						href="<?php the_permalink(); ?>"
						class="<?php echo esc_attr( internetorg_english_lang_notification_class() ); ?>"
					>
					<h3 class="hdg hdg_4">
						<?php the_title(); ?>
					</h3>
					</a>
				</div>
				<div class="feature-date">
					<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray">
						<?php internetorg_posted_on_date(); ?>
					</div>
				</div>

				<?php internetorg_media_embed(); ?>

				<div class="feature-bd">
					<p class="bdcpy">
						<?php echo wp_kses_post( get_the_excerpt() ); ?>
					</p>
				</div>
				<div class="feature-cta"> <!-- REMOVED: js-stateLink  -->
					<a
						class="link <?php echo esc_attr( internetorg_english_lang_notification_class( ['or' => 'js-stateLink'] ) ); ?>"
						href="<?php echo esc_url( get_the_permalink() ); ?>"
						data-title="<?php echo esc_attr( get_the_title() ); ?>"
						data-social="true"
						data-date="<?php echo esc_attr( get_the_date() ); ?>"
						data-type="titled"
					>
						<?php esc_html_e( 'Read More', 'internetorg' ); ?>
					</a>
				</div>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
		</div><!-- media-bd -->
	</div><!-- media_inline -->
<?php endif; ?>

</div><!-- resultsList-list-item -->
