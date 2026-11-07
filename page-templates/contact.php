<?php
/**
 * Template Name: Abidar Contact
 * Template Post Type: page
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();

abidar_page_hero(
	array(
		'title'    => get_the_title(),
		'subtitle' => has_excerpt() ? get_the_excerpt() : abidar_copy( 'contact_text' ),
		'eyebrow'  => abidar_copy( 'contact_eyebrow' ),
		'bg'       => get_the_post_thumbnail_url( get_the_ID(), 'abidar-wide' ),
	)
);

while ( have_posts() ) :
	the_post();
	if ( trim( get_the_content() ) ) {
		echo '<section class="ab-section ab-section--tight"><div class="ab-container"><div class="ab-prose">';
		the_content();
		echo '</div></div></section>';
	}
endwhile;

ab_render_contact( array( 'show_form' => true, 'show_map' => true ) );
ab_render_accordion( array( 'surface' => true ) );

get_footer();
