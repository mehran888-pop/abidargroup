<?php
/**
 * Search results.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();

abidar_page_hero(
	array(
		'title'    => abidar_copy( 'search_title' ) . ' “' . get_search_query() . '”',
		'subtitle' => have_posts() ? sprintf( '%s %d', esc_html__( 'Results:', 'abidar' ), (int) $GLOBALS['wp_query']->found_posts ) : abidar_copy( 'no_results' ),
		'bg'       => ABIDAR_URI . '/assets/img/pattern-dark.jpg',
	)
);
?>

<section class="ab-section">
	<div class="ab-container">
		<div style="max-width:620px;margin-bottom:2.5rem">
			<?php get_search_form(); ?>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="ab-posts-grid ab-stagger">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card/media' );
				endwhile;
				?>
			</div>
			<?php abidar_pagination(); ?>
		<?php else : ?>
			<p class="ab-muted"><?php echo esc_html( abidar_copy( 'not_found_text' ) ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
