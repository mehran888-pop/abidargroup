<?php
/**
 * Elementor-built header & footer — without Elementor Pro.
 *
 * Elementor **Free** has no Theme Builder, so the theme registers two small
 * template post types (`abidar_header`, `abidar_footer`). Each is edited with
 * the regular Elementor editor and then rendered by the theme in place of the
 * PHP header/footer. Elementor Pro users are unaffected: the Pro Theme Builder
 * location still wins if they build one.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the header/footer template post types.
 */
function abidar_register_part_post_types() {
	$parts = array(
		'header' => array(
			'singular' => __( 'Header template', 'abidar' ),
			'plural'   => __( 'Headers', 'abidar' ),
			'menu'     => __( 'Headers', 'abidar' ),
			'icon'     => 'dashicons-align-full-width',
		),
		'footer' => array(
			'singular' => __( 'Footer template', 'abidar' ),
			'plural'   => __( 'Footers', 'abidar' ),
			'menu'     => __( 'Footers', 'abidar' ),
			'icon'     => 'dashicons-align-center',
		),
	);

	foreach ( $parts as $slug => $labels ) {
		register_post_type(
			'abidar_' . $slug,
			array(
				'labels'          => array(
					'name'               => $labels['plural'],
					'singular_name'      => $labels['singular'],
					'menu_name'          => $labels['menu'],
					'add_new'            => __( 'Add new', 'abidar' ),
					'add_new_item'       => sprintf( /* translators: %s: template type */ __( 'Add new %s', 'abidar' ), strtolower( $labels['singular'] ) ),
					'edit_item'          => __( 'Edit template', 'abidar' ),
					'new_item'           => __( 'New template', 'abidar' ),
					'view_item'          => __( 'Preview template', 'abidar' ),
					'search_items'       => __( 'Search templates', 'abidar' ),
					'not_found'          => __( 'No templates yet. Add one and build it with Elementor.', 'abidar' ),
					'not_found_in_trash' => __( 'No templates in trash', 'abidar' ),
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => 'abidar-options',
				'show_in_rest'    => true,
				'menu_icon'       => $labels['icon'],
				'capability_type' => 'page',
				'map_meta_cap'    => true,
				'supports'        => array( 'title', 'editor', 'elementor' ),
				'rewrite'         => false,
				'query_var'       => false,
			)
		);
	}
}
add_action( 'init', 'abidar_register_part_post_types' );

/**
 * Elementor editor needs the "Elementor" editor switch on these post types.
 *
 * @param array $post_types Supported post types.
 * @return array
 */
function abidar_elementor_cpt_support( $post_types ) {
	$post_types[] = 'abidar_header';
	$post_types[] = 'abidar_footer';
	return array_values( array_unique( $post_types ) );
}
add_filter( 'elementor/utils/get_public_post_types', 'abidar_elementor_cpt_support' );

/**
 * Also register the template post types as "Elementor canvas" candidates so the
 * editor does not wrap the header template in the site header/footer.
 *
 * @param array $templates Templates.
 * @return array
 */
function abidar_elementor_part_templates( $templates ) {
	return $templates;
}
add_filter( 'theme_abidar_header_templates', 'abidar_elementor_part_templates' );

/**
 * When editing a header/footer template, force the Elementor canvas so the
 * editor preview shows the bare component.
 *
 * @param string $template Template path.
 * @return string
 */
function abidar_part_editor_canvas( $template ) {
	$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : (int) get_the_ID(); // phpcs:ignore WordPress.Security.NonceVerification

	if ( ! $post_id ) {
		return $template;
	}

	$type = get_post_type( $post_id );

	if ( ! in_array( $type, array( 'abidar_header', 'abidar_footer' ), true ) ) {
		return $template;
	}

	$canvas = locate_template( 'elementor_header_footer.php' );

	return $canvas ? $canvas : $template;
}
add_filter( 'template_include', 'abidar_part_editor_canvas', 99 );

/**
 * Admin notice explaining the free-Elementor workflow, shown once per screen.
 */
function abidar_headers_footers_notice() {
	$screen = get_current_screen();

	if ( ! $screen || ! in_array( $screen->post_type, array( 'abidar_header', 'abidar_footer' ), true ) ) {
		return;
	}

	if ( 'edit' !== $screen->base ) {
		return;
	}

	printf(
		'<div class="notice notice-info"><p><strong>%1$s</strong> %2$s</p><p>%3$s</p></div>',
		esc_html__( 'Build your header and footer with Elementor Free.', 'abidar' ),
		esc_html__( 'Add a template, click “Edit with Elementor”, then drag in the “Abidar header” or “Abidar footer” widgets. The published template automatically replaces the theme default.', 'abidar' ),
		esc_html__( 'Only one header and one footer template is used at a time: the first published one. Elementor Pro Theme Builder still takes priority if you use it.', 'abidar' )
	);
}
add_action( 'admin_notices', 'abidar_headers_footers_notice' );

/**
 * Seed a ready-made header and footer template the first time the theme runs.
 *
 * This is what makes the site look finished immediately: both templates are
 * pre-built from the theme widgets, and the client only edits them.
 */
function abidar_seed_elementor_parts() {
	if ( get_option( 'abidar_parts_seeded' ) === ABIDAR_VERSION ) {
		return;
	}

	// Elementor must be available; otherwise the templates would be empty.
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	$seed = array(
		'header' => array(
			'title'   => __( 'Abidar header (pre-built)', 'abidar' ),
			'widgets' => array(
				array( 'ab-topbar', array() ),
				array( 'ab-header', array() ),
			),
		),
		'footer' => array(
			'title'   => __( 'Abidar footer (pre-built)', 'abidar' ),
			'widgets' => array(
				array( 'ab-footer', array() ),
			),
		),
	);

	foreach ( $seed as $type => $config ) {
		$existing = get_posts(
			array(
				'post_type'      => 'abidar_' . $type,
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'post_status'    => 'any',
			)
		);

		if ( $existing ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => 'abidar_' . $type,
				'post_title'  => $config['title'],
				'post_status' => 'publish',
				'meta_input'  => array(
					'_elementor_edit_mode'     => 'builder',
					'_elementor_template_type' => 'wp-' . $type,
					'_elementor_version'       => defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0',
				),
			),
			true
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		// Build a two-section Elementor document holding our widgets.
		$elements = array();
		$column   = array();

		foreach ( $config['widgets'] as $index => $widget ) {
			$column[] = array(
				'id'         => substr( md5( $post_id . $type . $index ), 0, 7 ),
				'elType'     => 'widget',
				'settings'   => $widget[1],
				'elements'   => array(),
				'widgetType' => $widget[0],
			);
		}

		$elements[] = array(
			'id'       => substr( md5( $post_id . $type . 'sec' ), 0, 7 ),
			'elType'   => 'section',
			'settings' => array(
				'layout'       => 'full_width',
				'structure'    => '10',
				'gap'          => 'no',
				'padding'      => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ),
				'content_width' => array( 'unit' => 'px', 'size' => 1600 ),
			),
			'elements' => array(
				array(
					'id'       => substr( md5( $post_id . $type . 'col' ), 0, 7 ),
					'elType'   => 'column',
					'settings' => array( '_column_size' => 100, '_inline_size' => null ),
					'elements' => $column,
					'isInner'  => false,
				),
			),
			'isInner'  => false,
		);

		update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $elements ) ) );
		update_post_meta( $post_id, '_elementor_page_settings', array( 'hide_title' => 'yes' ) );
	}

	update_option( 'abidar_parts_seeded', ABIDAR_VERSION );
}
add_action( 'admin_init', 'abidar_seed_elementor_parts', 20 );
