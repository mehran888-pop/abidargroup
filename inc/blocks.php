<?php
/**
 * Gutenberg bridge: exposes every theme section as a server-rendered block.
 *
 * A client can compose a page in the block editor using the very same sections
 * the Elementor widgets provide, without installing anything. Blocks are
 * registered server-side so they stay valid even if the JS build is missing.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Section slug => renderer callback.
 *
 * @return array
 */
function abidar_block_sections() {
	return array(
		'hero'         => 'ab_render_hero',
		'marquee'      => 'ab_render_marquee',
		'services'     => 'ab_render_services',
		'about'        => 'ab_render_about',
		'counters'     => 'ab_render_counters',
		'projects'     => 'ab_render_projects',
		'process'      => 'ab_render_process',
		'team'         => 'ab_render_team',
		'testimonials' => 'ab_render_testimonials',
		'posts'        => 'ab_render_posts',
		'accordion'    => 'ab_render_accordion',
		'cta'          => 'ab_render_cta',
		'contact'      => 'ab_render_contact',
	);
}

/**
 * Register the block.
 */
function abidar_register_blocks() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	register_block_type(
		'abidar/section',
		array(
			'api_version'     => 2,
			'title'           => __( 'Abidar section', 'abidar' ),
			'category'        => 'design',
			'icon'            => 'layout',
			'attributes'      => array(
				'section' => array(
					'type'    => 'string',
					'default' => 'services',
				),
				'surface' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'count'   => array(
					'type'    => 'number',
					'default' => 6,
				),
			),
			'render_callback' => 'abidar_render_section_block',
			'supports'        => array(
				'html'  => false,
				'align' => array( 'wide', 'full' ),
			),
		)
	);
}
add_action( 'init', 'abidar_register_blocks' );

/**
 * Render a section block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function abidar_render_section_block( $attributes ) {
	$sections = abidar_block_sections();
	$slug     = isset( $attributes['section'] ) ? sanitize_key( $attributes['section'] ) : '';

	if ( ! isset( $sections[ $slug ] ) ) {
		return '';
	}

	$args = array();

	if ( isset( $attributes['surface'] ) ) {
		$args['surface'] = (bool) $attributes['surface'];
	}
	if ( isset( $attributes['count'] ) && 'contact' !== $slug ) {
		$args['count'] = (int) $attributes['count'];
	}

	// Capture the renderer's output so we can return it as a string.
	ob_start();
	call_user_func( $sections[ $slug ], $args );
	return ob_get_clean();
}

/**
 * Tiny editor script: register the block in JS with a live preview.
 */
function abidar_block_editor_assets() {
	wp_register_script(
		'abidar-blocks',
		ABIDAR_URI . '/assets/js/blocks.js',
		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-server-side-render', 'wp-i18n' ),
		ABIDAR_VERSION,
		true
	);

	wp_localize_script(
		'abidar-blocks',
		'abidarBlocks',
		array(
			'sections' => array_map(
				static function ( $slug ) {
					return array(
						'value' => $slug,
						/* translators: %s: section slug */
						'label' => ucwords( str_replace( '-', ' ', $slug ) ),
					);
				},
				array_map( 'strval', array_keys( abidar_block_sections() ) )
			),
		)
	);

	wp_enqueue_script( 'abidar-blocks' );
}
add_action( 'enqueue_block_editor_assets', 'abidar_block_editor_assets' );
