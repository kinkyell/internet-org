<?php
/**
 * This file is the search page
 *
 * Template Name: Search Page
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */


global $query_string;

$query_args   = explode( '&', $query_string );
$search_query = array();

foreach ( $query_args as $key => $string ) {
	$query_split                     = explode( '=', $string );
	$search_query[ $query_split[0] ] = urldecode( $query_split[1] );
} // foreach

$search = new WP_Query( $search_query );


get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="narrative-section-bd">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html__( 'Search Internet.org', 'internetorg' ); ?></h1>
				</header>
				<!-- .page-header -->
			</div>

			<?php get_search_form(); ?>

		</main>
	</section>

<?php get_sidebar(); ?>
<?php get_footer();