<?php
/**
 * Template Name: Abidar Home (sections)
 * Template Post Type: page
 *
 * A PHP-composed homepage. Every section maps 1:1 to an Elementor widget, so a
 * client can later rebuild the page in Elementor widget by widget.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();

// If the page was built in Elementor (or the editor wrote real content), that
// wins over the pre-built section stack — the client's work is never hidden.
if ( function_exists( 'abidar_is_elementor_built' ) && abidar_is_elementor_built() ) {
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	get_footer();
	return;
}

abidar_page_content();

ab_render_hero();
ab_render_marquee();
ab_render_sectors();
ab_render_services();
ab_render_capabilities();
ab_render_counters();
ab_render_featured();
ab_render_projects();
ab_render_process( array( 'surface' => true ) );
ab_render_about();
ab_render_team();
ab_render_testimonials();
ab_render_posts();
ab_render_accordion( array( 'surface' => true ) );
ab_render_cta();

get_footer();

