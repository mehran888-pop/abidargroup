<?php
/**
 * Fallback template.
 *
 * WordPress requires index.php in every theme. Reading it means no more
 * specific template matched, so we fall back to the blog index layout.
 *
 * @package Abidar
 */

get_header();
?>

<main id="main" class="ab-main">

	<?php abidar_page_hero(); ?>

	<div class="ab-section">
		<div class="ab-container">
			<div class="ab-layout<?php echo abidar_option( 'blog_sidebar', 1 ) ? '' : ' ab-layout--full'; ?>">

				<div class="ab-layout__main">
					<?php if ( have_posts() ) : ?>

						<div class="ab-posts-grid ab-stagger">
							<?php
							while ( have_posts() ) :
								the_post();
								get_template_part( 'template-parts/card/post' );
							endwhile;
							?>
						</div>

						<?php abidar_pagination(); ?>

					<?php else : ?>

						<div class="ab-empty ab-center">
							<h2><?php echo esc_html( abidar_copy( 'nothing_found' ) ); ?></h2>
							<p class="ab-muted"><?php echo esc_html( abidar_copy( 'nothing_found_text' ) ); ?></p>
							<?php get_search_form(); ?>
						</div>

					<?php endif; ?>
				</div>

				<?php get_sidebar(); ?>

			</div>
		</div>
	</div>

</main>

<?php
get_footer();
