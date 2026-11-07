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

ab_render_hero();
ab_render_marquee();
ab_render_services();
ab_render_about();
ab_render_counters();
ab_render_projects();
ab_render_process( array( 'surface' => true ) );
ab_render_team();
ab_render_testimonials();
ab_render_posts();
ab_render_accordion( array( 'surface' => true ) );
ab_render_cta();

get_footer();
