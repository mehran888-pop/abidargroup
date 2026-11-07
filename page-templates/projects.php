<?php
/**
 * Template Name: Abidar Projects
 * Template Post Type: page
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();

abidar_page_hero(
	array(
		'title'    => get_the_title(),
		'subtitle' => has_excerpt() ? get_the_excerpt() : abidar_copy( 'projects_text' ),
		'eyebrow'  => abidar_copy( 'projects_eyebrow' ),
		'bg'       => get_the_post_thumbnail_url( get_the_ID(), 'abidar-wide' ),
	)
);

ab_render_projects(
	array(
		'count'   => 9,
		'surface' => false,
		'source'  => post_type_exists( 'ab_project' ) && wp_count_posts( 'ab_project' )->publish > 0 ? 'cpt' : 'demo',
	)
);

ab_render_counters();
ab_render_cta();

get_footer();
