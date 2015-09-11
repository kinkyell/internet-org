<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Internet.org
 */
global $post;
?>

<div class="resultsList-list-item">
	<div class="feature feature_tight">
		<div class="feature-hd">
			<h2 class="hdg hdg_4"><?php the_title(); ?></h2>
		</div>
		<div class="feature-bd">
			<div class="bdcpy"><?php the_excerpt(); ?></div>
		</div>
		<div class="feature-cta">
			<a href="<?php the_permalink(); ?>" class="link mix-link_small js-stateLink"
			   data-title="<?php echo apply_filters( 'the_title', esc_attr( $post->post_title ) ); ?>"
			   <?php if($post->post_type === 'post'){ ?>
				   data-date="<?php echo esc_attr( get_the_date( '', $post->ID ) ); ?>"
				   data-social="true"
				   data-desc="<?php echo wp_kses_post( get_post_field( 'post_excerpt', get_the_ID() ) ); ?>"
			   <?php } ?>
			   data-type="titled">
				<?php esc_html_e( 'Read More', 'internetorg' ); ?>
			</a>
		</div>
	</div>
</div>
