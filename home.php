<?php
/**
 * Blog index (page_for_posts).
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();

$page_for_posts = get_option( 'page_for_posts' );

abidar_page_hero(
	array(
		'title'    => $page_for_posts ? get_the_title( $page_for_posts ) : abidar_copy( 'latest_news' ),
		'subtitle' => abidar_copy( 'latest_news_text' ),
		'eyebrow'  => abidar_copy( 'latest_news' ),
		'bg'       => ABIDAR_URI . '/assets/img/pattern-dark.jpg',
	)
);
?>

<section class="ab-section">
	<div class="ab-container">
		<div class="ab-layout <?php echo abidar_option( 'blog_sidebar', 1 ) ? '' : 'ab-layout--full'; ?>">
			<div>
				<?php if ( have_posts() ) : ?>
					<div class="ab-posts-grid ab-stagger" style="grid-template-columns:repeat(<?php echo abidar_option( 'blog_sidebar', 1 ) ? '2' : '3'; ?>,minmax(0,1fr))">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/card/post' );
						endwhile;
						?>
					</div>
					<?php abidar_pagination(); ?>
				<?php else : ?>
					<p class="ab-muted"><?php echo esc_html( abidar_copy( 'no_results' ) ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( abidar_option( 'blog_sidebar', 1 ) ) : ?>
				<aside class="ab-sidebar">
					<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
						<?php dynamic_sidebar( 'sidebar-1' ); ?>
					<?php else : ?>
						<section class="widget">
							<h3 class="widget__title"><?php echo esc_html( abidar_copy( 'nav_search' ) ); ?></h3>
							<?php get_search_form(); ?>
						</section>
						<section class="widget">
							<h3 class="widget__title"><?php echo esc_html( abidar_copy( 'category' ) ); ?></h3>
							<ul>
								<?php wp_list_categories( array( 'title_li' => '', 'show_count' => true ) ); ?>
							</ul>
						</section>
						<section class="widget widget_tag_cloud">
							<h3 class="widget__title">Tags</h3>
							<?php wp_tag_cloud( array( 'smallest' => 12, 'largest' => 12, 'unit' => 'px' ) ); ?>
						</section>
					<?php endif; ?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
get_footer();
