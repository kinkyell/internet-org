<?php
/**
 * The template for displaying search results pages.
 *
 * @package Internet.org
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="narrative-section-bd">
				<header class="page-header">
					<h1 class="page-title"><?php printf( esc_html__( '%s', 'internetorg' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				</header><!-- .page-header -->

				<?php if ( have_posts() ) : ?>

					<div class="resultsList-hd">
						<div class="hdg hdg_5 mix-hdg_italic mix-hdg_gray"><?php printf( esc_html__( '%d Results Found', 'internetorg' ) , $wp_query->found_posts ); ?></div>
					</div>


					<?php /* Start the Loop */ ?>
					<div class="resultsList-list">
						<?php while ( have_posts() ) : the_post(); ?>

							<?php
							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */
							get_template_part( 'template-parts/content', 'search' );
							?>

						<?php endwhile; ?>
					</div>

					<?php the_posts_navigation(); ?>

				<?php else : ?>

					<?php get_template_part( 'template-parts/content', 'search-none' ); ?>

				<?php endif; ?>
			</div>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
