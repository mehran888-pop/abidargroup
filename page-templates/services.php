<?php
/**
 * Template Name: Abidar Services
 * Template Post Type: page
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();

abidar_page_hero(
	array(
		'title'    => get_the_title(),
		'subtitle' => has_excerpt() ? get_the_excerpt() : abidar_copy( 'services_text' ),
		'eyebrow'  => abidar_copy( 'services_eyebrow' ),
		'bg'       => get_the_post_thumbnail_url( get_the_ID(), 'abidar-wide' ),
	)
);

ab_render_services(
	array(
		'style'    => 'image',
		'surface'  => false,
		'show_more'=> false,
	)
);

ab_render_process( array( 'surface' => true ) );
ab_render_accordion( array( 'surface' => false ) );
ab_render_cta();

get_footer();
