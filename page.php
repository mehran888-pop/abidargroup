<?php
/**
 * Default page template.
 *
 * Pages built with Elementor render full-bleed; classic pages get the page hero
 * plus a readable prose column.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$is_elementor = abidar_is_elementor_built();

	if ( $is_elementor ) {
		the_content();
	} else {
		abidar_page_hero(
			array(
				'title'    => get_the_title(),
				'subtitle' => has_excerpt() ? get_the_excerpt() : '',
				'bg'       => get_the_post_thumbnail_url( get_the_ID(), 'abidar-wide' ),
			)
		);
		?>
		<article class="ab-section">
			<div class="ab-container">
				<div class="ab-prose">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<nav class="ab-pagination"><div class="nav-links">',
							'after'  => '</div></nav>',
						)
					);
					?>
				</div>
			</div>
		</article>
		<?php
	}

	if ( comments_open() || get_comments_number() ) {
		echo '<div class="ab-section ab-section--tight"><div class="ab-container ab-container--narrow">';
		comments_template();
		echo '</div></div>';
	}

endwhile;

get_footer();
