<?php
/**
 * Single post.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'ab-single' ); ?>>
		<header class="ab-page-hero" style="--ab-hero-bg:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'abidar-wide' ) ); ?>')">
			<div class="ab-container">
				<?php abidar_breadcrumb(); ?>
				<h1 class="ab-page-hero__title" style="margin-top:1rem"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="ab-page-hero__sub"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				<div class="ab-post-meta" style="margin-top:1.25rem">
					<span class="ab-post-meta__date"><?php echo esc_html( get_the_date() ); ?></span>
					<span><?php echo esc_html( abidar_reading_time() ); ?></span>
				</div>
			</div>
		</header>

		<div class="ab-section">
			<div class="ab-container">
				<div class="ab-layout <?php echo abidar_option( 'blog_sidebar', 1 ) ? '' : 'ab-layout--full'; ?>">
					<div class="ab-prose" style="margin-inline:0">
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<nav class="ab-pagination"><div class="nav-links">',
								'after'  => '</div></nav>',
							)
						);

						$tags = get_the_tags();
						if ( $tags ) {
							echo '<div class="ab-tags">';
							foreach ( $tags as $tag ) {
								printf( '<a href="%s">#%s</a>', esc_url( get_tag_link( $tag ) ), esc_html( $tag->name ) );
							}
							echo '</div>';
						}
						?>
						<div class="ab-share">
							<strong style="font-size:.8rem"><?php echo esc_html( abidar_copy( 'share' ) ); ?></strong>
							<a href="<?php echo esc_url( 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( get_permalink() ) ); ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><?php abidar_icon( 'linkedin', true, array( 'width' => 15, 'height' => 15 ) ); ?></a>
							<a href="<?php echo esc_url( 'https://t.me/share/url?url=' . rawurlencode( get_permalink() ) . '&text=' . rawurlencode( get_the_title() ) ); ?>" target="_blank" rel="noopener" aria-label="Telegram"><?php abidar_icon( 'telegram', true, array( 'width' => 15, 'height' => 15 ) ); ?></a>
							<a href="<?php echo esc_url( 'https://api.whatsapp.com/send?text=' . rawurlencode( get_the_title() . ' ' . get_permalink() ) ); ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><?php abidar_icon( 'whatsapp', true, array( 'width' => 15, 'height' => 15 ) ); ?></a>
						</div>

						<?php
						the_post_navigation(
							array(
								'prev_text' => '<span class="ab-muted" style="display:block;font-size:.75rem">' . esc_html( abidar_copy( 'prev' ) ) . '</span><strong>%title</strong>',
								'next_text' => '<span class="ab-muted" style="display:block;font-size:.75rem">' . esc_html( abidar_copy( 'next' ) ) . '</span><strong>%title</strong>',
								'class'     => 'ab-post-nav',
							)
						);

						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
						?>
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
									<h3 class="widget__title"><?php echo esc_html( abidar_copy( 'latest_news' ) ); ?></h3>
									<ul>
										<?php
										$recent = new WP_Query( array( 'posts_per_page' => 5, 'no_found_rows' => true, 'post__not_in' => array( get_the_ID() ) ) );
										while ( $recent->have_posts() ) : $recent->the_post();
											printf( '<li><a href="%s">%s</a></li>', esc_url( get_permalink() ), esc_html( get_the_title() ) );
										endwhile;
										wp_reset_postdata();
										?>
									</ul>
								</section>
							<?php endif; ?>
						</aside>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</article>

	<?php
	$related = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 3,
			'post__not_in'   => array( get_the_ID() ),
			'no_found_rows'  => true,
		)
	);

	if ( $related->have_posts() ) :
		?>
		<section class="ab-section ab-section--surface">
			<div class="ab-container">
				<div class="ab-section-head ab-section-head--split">
					<div><h2><?php echo esc_html( abidar_copy( 'related' ) ); ?></h2></div>
				</div>
				<div class="ab-posts-grid">
					<?php
					while ( $related->have_posts() ) :
						$related->the_post();
						get_template_part( 'template-parts/card/post' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	endif;

endwhile;

get_footer();
