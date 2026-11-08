<?php
/**
 * Front page.
 *
 * When the page is built with Elementor (or a page template is assigned) that
 * content wins. Otherwise the theme renders its own composed homepage so the
 * site looks complete the moment the theme is activated.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();

$page_id  = get_option( 'page_on_front' );
$use_page = $page_id && 'page' === get_option( 'show_on_front' );

if ( $use_page && abidar_is_elementor_built() ) {

	// Elementor owns the whole layout.
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;

} elseif ( $use_page && get_page_template_slug( $page_id ) && 'default' !== get_page_template_slug( $page_id ) ) {

	// A named page template (e.g. "Abidar Home (sections)") takes over.
	$template = locate_template( get_page_template_slug( $page_id ) );
	if ( $template ) {
		include $template;
	} else {
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
	}

} elseif ( $use_page && trim( get_post_field( 'post_content', $page_id ) ) ) {

	// A hand-built page with block content.
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;

} else {

	// Composed theme homepage: works the moment the theme is activated.
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
}

get_footer();
