<?php
/**
 * Elementor integration.
 *
 * - Registers the "Abidar" widget category.
 * - Registers the theme's widgets.
 * - Registers Elementor theme locations (header / footer / single / archive).
 * - Syncs the theme palette with Elementor's global colours + kit defaults.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme locations so Elementor Pro Theme Builder can take over.
 *
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $manager Manager.
 */
function abidar_register_elementor_locations( $manager ) {
	$manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'abidar_register_elementor_locations' );

/**
 * Widget category.
 *
 * @param \Elementor\Elements_Manager $manager Manager.
 */
function abidar_elementor_widget_categories( $manager ) {
	$manager->add_category(
		'abidar',
		array(
			'title' => esc_html__( 'Abidar', 'abidar' ),
			'icon'  => 'eicon-blockquote',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'abidar_elementor_widget_categories' );

/**
 * Register the widgets once Elementor is ready.
 *
 * @param \Elementor\Widgets_Manager $manager Manager.
 */
function abidar_register_elementor_widgets( $manager ) {
	require_once ABIDAR_DIR . '/inc/elementor/helpers.php';
	require_once ABIDAR_DIR . '/inc/elementor/class-abidar-widget-base.php';

	$widgets = array(
		'topbar',
		'header',
		'footer',
		'hero',
		'services',
		'about',
		'counters',
		'projects',
		'process',
		'team',
		'testimonials',
		'cta',
		'contact',
		'marquee',
		'posts',
		'accordion',
		'lang-switcher',
		'page-title',
	);

	foreach ( $widgets as $widget ) {
		$file = ABIDAR_DIR . '/inc/elementor/widgets/' . $widget . '.php';
		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
}
add_action( 'elementor/widgets/register', 'abidar_register_elementor_widgets' );

/**
 * Add Elementor page-template support hints in the editor.
 *
 * @param array $templates Available templates.
 * @return array
 */
function abidar_elementor_page_templates( $templates ) {
	$templates['elementor_header_footer'] = array(
		'name'     => __( 'Abidar Canvas (no header/footer)', 'abidar' ),
		'filename' => 'elementor_header_footer',
	);
	return $templates;
}
add_filter( 'theme_page_templates', 'abidar_elementor_page_templates' );

/**
 * Add the theme palette to the Elementor global colours, once.
 *
 * Runs on admin_init (Elementor is loaded by then) and only when a kit exists,
 * so it is safe on every request and never overwrites the client's own swatches.
 */
function abidar_sync_elementor_palette() {
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return;
	}

	if ( get_option( 'abidar_palette_synced' ) === ABIDAR_VERSION ) {
		return;
	}

	$kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit();

	if ( ! $kit ) {
		return;
	}

	$existing = (array) $kit->get_settings( 'custom_colors' );
	$keyed    = array();

	foreach ( $existing as $color ) {
		if ( isset( $color['_id'] ) ) {
			$keyed[ $color['_id'] ] = true;
		}
	}

	$palette = array(
		'abprimary' => array( '_id' => 'abprimary', 'title' => __( 'Abidar Primary', 'abidar' ), 'color' => abidar_option( 'color_primary', '#0c6a5a' ) ),
		'abaccent'  => array( '_id' => 'abaccent', 'title' => __( 'Abidar Accent', 'abidar' ), 'color' => abidar_option( 'color_accent', '#c8912f' ) ),
		'abink'     => array( '_id' => 'abink', 'title' => __( 'Abidar Ink', 'abidar' ), 'color' => abidar_option( 'color_ink', '#101b1c' ) ),
		'absand'    => array( '_id' => 'absand', 'title' => __( 'Abidar Sand', 'abidar' ), 'color' => abidar_option( 'color_surface', '#f5f4f0' ) ),
	);

	$changed = false;

	foreach ( $palette as $id => $color ) {
		if ( ! isset( $keyed[ $id ] ) ) {
			$existing[] = $color;
			$changed    = true;
		}
	}

	if ( $changed ) {
		$kit->update_settings( array( 'custom_colors' => $existing ) );
	}

	update_option( 'abidar_palette_synced', ABIDAR_VERSION );
}
add_action( 'admin_init', 'abidar_sync_elementor_palette', 20 );

/**
 * Let Elementor know the content width for full-width widgets.
 *
 * @return int
 */
function abidar_elementor_content_width() {
	return 1280;
}
add_filter( 'elementor/frontend/content_width', 'abidar_elementor_content_width' );

/**
 * Enqueue the widget stylesheet only when Elementor renders widgets.
 */
function abidar_elementor_styles() {
	wp_enqueue_style( 'abidar-widgets', ABIDAR_URI . '/assets/css/widgets.css', array(), ABIDAR_VERSION );
}
add_action( 'elementor/frontend/after_enqueue_styles', 'abidar_elementor_styles' );

/**
 * Notify the admin when Elementor is missing (the theme still works standalone).
 */
function abidar_elementor_missing_notice() {
	if ( did_action( 'elementor/loaded' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$dismissed = get_option( 'abidar_elementor_notice_dismissed' );
	if ( $dismissed ) {
		return;
	}

	$install = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

	echo '<div class="notice notice-info"><p><strong>' . esc_html__( 'Abidar Group', 'abidar' ) . '</strong> — ';
	esc_html_e( 'install Elementor to unlock the 15 dedicated widgets and the header/footer builder.', 'abidar' );
	echo ' <a class="button button-primary" href="' . esc_url( $install ) . '">' . esc_html__( 'Install Elementor', 'abidar' ) . '</a>';
	echo '</p></div>';
}
add_action( 'admin_notices', 'abidar_elementor_missing_notice' );
