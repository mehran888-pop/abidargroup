<?php
/**
 * Theme setup.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports, menus and image sizes.
 */
function abidar_setup() {
	load_theme_textdomain( 'abidar', ABIDAR_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 480,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Elementor: theme locations for header/footer built with Elementor.
	add_theme_support( 'elementor' );
	add_theme_support( 'elementor-pro' );
	add_theme_support( 'elementor-header-footer' );

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary menu', 'abidar' ),
			'topbar'  => esc_html__( 'Top bar menu', 'abidar' ),
			'footer'  => esc_html__( 'Footer menu', 'abidar' ),
			'legal'   => esc_html__( 'Footer legal menu', 'abidar' ),
			'lang'    => esc_html__( 'Language menu', 'abidar' ),
		)
	);

	add_image_size( 'abidar-card', 720, 540, true );
	add_image_size( 'abidar-wide', 1600, 900, true );
	add_image_size( 'abidar-square', 700, 700, true );
	add_image_size( 'abidar-team', 640, 800, true );

	// Editor colour palette mirrors the theme palette.
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => esc_html__( 'Deep Teal', 'abidar' ),
				'slug'  => 'ab-primary',
				'color' => abidar_option( 'color_primary', '#0c6a5a' ),
			),
			array(
				'name'  => esc_html__( 'Ochre', 'abidar' ),
				'slug'  => 'ab-accent',
				'color' => abidar_option( 'color_accent', '#c8912f' ),
			),
			array(
				'name'  => esc_html__( 'Ink', 'abidar' ),
				'slug'  => 'ab-ink',
				'color' => abidar_option( 'color_ink', '#101b1c' ),
			),
			array(
				'name'  => esc_html__( 'Sand', 'abidar' ),
				'slug'  => 'ab-surface',
				'color' => abidar_option( 'color_surface', '#f5f4f0' ),
			),
			array(
				'name'  => esc_html__( 'White', 'abidar' ),
				'slug'  => 'ab-white',
				'color' => '#ffffff',
			),
		)
	);
}
add_action( 'after_setup_theme', 'abidar_setup' );

/**
 * Content width.
 */
function abidar_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'abidar_content_width', 1240 );
}
add_action( 'after_setup_theme', 'abidar_content_width', 0 );

/**
 * Widget areas.
 */
function abidar_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Blog sidebar', 'abidar' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Shown next to the blog archive and single posts.', 'abidar' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget__title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer column 1', 'abidar' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'First footer widget column.', 'abidar' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="widget__title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer column 2', 'abidar' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Second footer widget column.', 'abidar' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="widget__title">',
			'after_title'   => '</h4>',
		)
	);

	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: shop column number */
				'name'          => sprintf( esc_html__( 'Shop sidebar %d', 'abidar' ), $i ),
				'id'            => 'shop-' . $i,
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget__title">',
				'after_title'   => '</h3>',
			)
		);
	}
}
add_action( 'widgets_init', 'abidar_widgets_init' );

/**
 * Register the theme's own widget: a compact "contact card".
 */
function abidar_register_custom_widgets() {
	require_once ABIDAR_DIR . '/inc/widgets/class-abidar-contact-widget.php';
	register_widget( 'Abidar_Contact_Widget' );
}
add_action( 'widgets_init', 'abidar_register_custom_widgets' );

/**
 * One-click starter content for the block editor (used by new installs).
 */
function abidar_starter_content() {
	add_theme_support(
		'starter-content',
		array(
			'widgets'  => array(
				'sidebar-1' => array( 'search', 'recent-posts', 'categories' ),
				'footer-1'  => array( 'text_about' ),
			),
			'posts'    => array(
				'home'  => array(
					'post_title' => esc_html__( 'Home', 'abidar' ),
				),
				'about' => array(
					'post_type'  => 'page',
					'post_title' => esc_html__( 'About us', 'abidar' ),
				),
				'services' => array(
					'post_type'  => 'page',
					'post_title' => esc_html__( 'Services', 'abidar' ),
				),
				'contact' => array(
					'post_type'  => 'page',
					'post_title' => esc_html__( 'Contact us', 'abidar' ),
				),
			),
			'options'  => array(
				'show_on_front'  => 'page',
				'page_on_front'  => '{{home}}',
				'page_for_posts' => '{{blog}}',
			),
		)
	);
}
add_action( 'after_setup_theme', 'abidar_starter_content' );
