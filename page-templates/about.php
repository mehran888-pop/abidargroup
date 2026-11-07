<?php
/**
 * Template Name: Abidar About
 * Template Post Type: page
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();

abidar_page_hero(
	array(
		'title'    => get_the_title(),
		'subtitle' => has_excerpt() ? get_the_excerpt() : abidar_copy( 'about_title' ),
		'eyebrow'  => abidar_copy( 'about_eyebrow' ),
		'bg'       => get_the_post_thumbnail_url( get_the_ID(), 'abidar-wide' ),
	)
);

while ( have_posts() ) :
	the_post();
	if ( trim( get_the_content() ) ) {
		echo '<section class="ab-section"><div class="ab-container"><div class="ab-prose">';
		the_content();
		echo '</div></div></section>';
	}
endwhile;

ab_render_about(
	array(
		'surface'  => false,
		'anchor'   => 'story',
		'title'    => abidar_copy( 'about_title' ),
	)
);
ab_render_counters();
ab_render_process( array( 'surface' => true ) );
ab_render_team();
ab_render_testimonials();
ab_render_cta();

get_footer();
