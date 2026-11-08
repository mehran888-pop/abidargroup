<?php
/**
 * Abidar Group — theme bootstrap.
 *
 * @package Abidar
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

define( 'ABIDAR_VERSION', '1.0.0' );
define( 'ABIDAR_DIR', get_template_directory() );
define( 'ABIDAR_URI', get_template_directory_uri() );

/**
 * Core helpers (options, assets, template helpers).
 */
require ABIDAR_DIR . '/inc/helpers.php';
require ABIDAR_DIR . '/inc/sections.php';

/**
 * Theme setup: supports, menus, image sizes, widgets areas.
 */
require ABIDAR_DIR . '/inc/setup.php';

/**
 * Trilingual engine (Persian / English / Chinese).
 */
require ABIDAR_DIR . '/inc/class-abidar-multilingual.php';
require ABIDAR_DIR . '/inc/strings.php';

/**
 * Templates helpers, nav walker and meta boxes.
 */
require ABIDAR_DIR . '/inc/template-tags.php';
require ABIDAR_DIR . '/inc/class-abidar-walker-nav.php';
require ABIDAR_DIR . '/inc/meta-boxes.php';
require ABIDAR_DIR . '/inc/ajax.php';
require ABIDAR_DIR . '/inc/comments.php';

/**
 * Demo content used by the front page and the one-click importer.
 */
require ABIDAR_DIR . '/inc/demo-content.php';
require ABIDAR_DIR . '/inc/blocks.php';

/**
 * Custom post types (projects, services, team, testimonials).
 */
require ABIDAR_DIR . '/inc/post-types.php';

/**
 * Theme options panel.
 */
require ABIDAR_DIR . '/inc/admin/options.php';
require ABIDAR_DIR . '/inc/admin/demo-import.php';

/**
 * Elementor integration (widgets, header/footer locations).
 */
require ABIDAR_DIR . '/inc/elementor/helpers.php';
require ABIDAR_DIR . '/inc/elementor/parts.php';
require ABIDAR_DIR . '/inc/elementor/init.php';

/**
 * Boot the multilingual engine and register theme hooks.
 */
Abidar_Multilingual::instance()->boot();
