<?php
/**
 * Template Name: Abidar Team
 * Template Post Type: page
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();

abidar_page_hero(
	array(
		'title'    => get_the_title(),
		'subtitle' => has_excerpt() ? get_the_excerpt() : abidar_copy( 'team_text' ),
		'eyebrow'  => abidar_copy( 'team_eyebrow' ),
		'bg'       => get_the_post_thumbnail_url( get_the_ID(), 'abidar-wide' ),
	)
);

abidar_page_content();

ab_render_team(
	array(
		'count'  => 8,
		'source' => post_type_exists( 'ab_team' ) && wp_count_posts( 'ab_team' )->publish > 0 ? 'cpt' : 'demo',
	)
);

ab_render_testimonials( array( 'surface' => true ) );
ab_render_cta();

get_footer();
