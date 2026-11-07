<?php
/**
 * Global helpers: options access, asset loading, misc utilities.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return a theme option value with a sane fallback.
 *
 * @param string $key     Option key.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function abidar_option( $key, $default = '' ) {
	$options = get_option( 'abidar_options', array() );

	if ( is_array( $options ) && array_key_exists( $key, $options ) && '' !== $options[ $key ] && null !== $options[ $key ] ) {
		return $options[ $key ];
	}

	$defaults = abidar_option_defaults();

	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : $default;
}

/**
 * Default values for every theme option.
 *
 * @return array
 */
function abidar_option_defaults() {
	$defaults = array(
		// Branding.
		'logo'                  => '',
		'logo_dark'             => '',
		'logo_width'            => 148,
		'favicon'               => '',
		// Colors (drives the CSS custom properties).
		'color_primary'         => '#0c6a5a',
		'color_primary_dark'    => '#084a40',
		'color_accent'          => '#c8912f',
		'color_ink'             => '#101b1c',
		'color_body'            => '#4a5758',
		'color_surface'         => '#f5f4f0',
		'color_dark'            => '#0b1516',
		// Typography.
		'font_body'             => 'vazirmatn',
		'font_heading'          => 'sora',
		'base_font_size'        => 16,
		// Header.
		'header_layout'         => 'classic',
		'header_sticky'         => 1,
		'header_transparent'    => 1,
		'header_cta_text'       => '',
		'header_cta_url'        => '',
		'topbar_enabled'        => 1,
		'topbar_phone'          => '+98 21 8800 1234',
		'topbar_email'          => 'info@abidargroup.com',
		'topbar_note'           => '',
		'show_search'           => 1,
		'show_lang_switcher'    => 1,
		// Footer.
		'footer_about'          => '',
		'footer_copyright'      => '',
		'footer_show_newsletter'=> 1,
		'social_linkedin'       => '#',
		'social_instagram'      => '#',
		'social_telegram'       => '#',
		'social_whatsapp'       => '#',
		'social_youtube'        => '',
		'address'               => '',
		'phone'                 => '',
		'email'                 => '',
		'working_hours'         => '',
		'map_embed'             => '',
		// Blog.
		'blog_excerpt_length'   => 24,
		'blog_sidebar'          => 1,
		// Misc.
		'preloader'             => 0,
		'scroll_top'            => 1,
		'custom_css'            => '',
		'default_language'      => 'fa',
	);
	$defaults = array_merge( $defaults, abidar_default_copy_options() );

	return apply_filters( 'abidar_option_defaults', $defaults );
}

/**
 * Language sensitive default copy for options that hold prose.
 * Keys are stored as copy_<key>_<lang>.
 *
 * @return array
 */
function abidar_default_copy_options() {
	$out = array();

	foreach ( array( 'fa', 'en', 'zh' ) as $lang ) {
		$strings = Abidar_Strings::all( $lang );

		foreach ( $strings as $key => $value ) {
			$out[ 'copy_' . $key . '_' . $lang ] = $value;
		}
	}

	return $out;
}

/**
 * Retrieve a translatable theme copy string.
 *
 * Looks for copy_<key>_<current-lang>, then copy_<key>_<default-lang>,
 * then the built-in dictionary.
 *
 * @param string $key     Copy key (e.g. `hero_title`).
 * @param string $lang    Optional language override.
 * @param string $default Optional fallback.
 * @return string
 */
function abidar_copy( $key, $lang = '', $default = '' ) {
	$lang = $lang ? $lang : Abidar_Multilingual::instance()->current_language();

	$value = abidar_option( 'copy_' . $key . '_' . $lang, '' );
	if ( '' === $value ) {
		$default_lang = abidar_option( 'default_language', 'fa' );
		$value        = abidar_option( 'copy_' . $key . '_' . $default_lang, '' );
	}

	if ( '' === $value ) {
		$value = Abidar_Strings::get( $key, $lang, $default );
	}

	// Last resort: the English entry, then the key's default argument.
	if ( '' === $value ) {
		$value = Abidar_Strings::get( $key, 'en', $default );
	}

	return apply_filters( 'abidar_copy', $value, $key, $lang );
}

/**
 * Inline CSS custom properties generated from the theme options.
 *
 * @return string
 */
function abidar_dynamic_css() {
	$primary   = abidar_option( 'color_primary', '#0c6a5a' );
	$accent    = abidar_option( 'color_accent', '#c8912f' );
	$ink       = abidar_option( 'color_ink', '#101b1c' );
	$body      = abidar_option( 'color_body', '#4a5758' );
	$surface   = abidar_option( 'color_surface', '#f5f4f0' );
	$dark      = abidar_option( 'color_dark', '#0b1516' );
	$p_dark    = abidar_option( 'color_primary_dark', '#084a40' );
	$base      = (int) abidar_option( 'base_font_size', 16 );
	$font_body = abidar_option( 'font_body', 'vazirmatn' );
	$font_head = abidar_option( 'font_heading', 'sora' );

	$stacks = array(
		'vazirmatn'   => "'Vazirmatn', 'Segoe UI', Tahoma, sans-serif",
		'sora'        => "'Sora', 'Vazirmatn', sans-serif",
		'system'      => "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
		'georgia'     => "Georgia, 'Times New Roman', serif",
		'inherit'     => 'inherit',
	);

	$body_stack = isset( $stacks[ $font_body ] ) ? $stacks[ $font_body ] : $stacks['vazirmatn'];
	$head_stack = isset( $stacks[ $font_head ] ) ? $stacks[ $font_head ] : $stacks['sora'];

	$css  = ':root{';
	$css .= '--ab-primary:' . esc_attr( $primary ) . ';';
	$css .= '--ab-primary-dark:' . esc_attr( $p_dark ) . ';';
	$css .= '--ab-accent:' . esc_attr( $accent ) . ';';
	$css .= '--ab-ink:' . esc_attr( $ink ) . ';';
	$css .= '--ab-body:' . esc_attr( $body ) . ';';
	$css .= '--ab-surface:' . esc_attr( $surface ) . ';';
	$css .= '--ab-dark:' . esc_attr( $dark ) . ';';
	$css .= '--ab-font-body:' . $body_stack . ';';
	$css .= '--ab-font-head:' . $head_stack . ';';
	$css .= '--ab-font-size:' . $base . 'px;';
	$css .= '--ab-logo-w:' . (int) abidar_option( 'logo_width', 148 ) . 'px;';
	$css .= '}';

	$css .= 'body{font-family:var(--ab-font-body);font-size:var(--ab-font-size);color:var(--ab-body);}';
	$css .= 'h1,h2,h3,h4,h5,h6,.ab-h{font-family:var(--ab-font-head);}';

	return apply_filters( 'abidar_dynamic_css', $css );
}

/**
 * Register a stylesheet with automatic RTL handling.
 *
 * @param string $handle Handle.
 * @param string $path   Relative path inside the theme.
 * @param array  $deps   Dependencies.
 */
function abidar_enqueue_style( $handle, $path, $deps = array() ) {
	$src = ABIDAR_URI . $path;
	if ( is_rtl() && file_exists( ABIDAR_DIR . '/' . preg_replace( '/\.css$/', '-rtl.css', $path ) ) ) {
		$src = ABIDAR_URI . preg_replace( '/\.css$/', '-rtl.css', $path );
	}
	wp_enqueue_style( $handle, $src, $deps, ABIDAR_VERSION );
}

/**
 * Front-end assets.
 */
function abidar_enqueue_assets() {
	abidar_enqueue_style( 'abidar-fonts', '/assets/css/fonts.css' );
	abidar_enqueue_style( 'abidar-main', '/assets/css/main.css', array( 'abidar-fonts' ) );
	abidar_enqueue_style( 'abidar-widgets', '/assets/css/widgets.css', array( 'abidar-main' ) );

	wp_add_inline_style( 'abidar-main', abidar_dynamic_css() );

	wp_enqueue_script( 'abidar-main', ABIDAR_URI . '/assets/js/main.js', array(), ABIDAR_VERSION, true );

	wp_localize_script(
		'abidar-main',
		'abidarData',
		array(
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce( 'abidar_nonce' ),
			'lang'          => Abidar_Multilingual::instance()->current_language(),
			'dir'           => is_rtl() ? 'rtl' : 'ltr',
			'stickyHeader'  => (bool) abidar_option( 'header_sticky', 1 ),
			'i18n'          => array(
				'loading' => abidar_copy( 'loading' ),
				'copied'  => abidar_copy( 'copied' ),
				'error'   => abidar_copy( 'error_generic' ),
			),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'abidar_enqueue_assets' );

/**
 * Editor styles so the block editor matches the front-end.
 */
function abidar_editor_assets() {
	wp_enqueue_style( 'abidar-editor-fonts', ABIDAR_URI . '/assets/css/fonts.css', array(), ABIDAR_VERSION );
	wp_enqueue_style( 'abidar-editor', ABIDAR_URI . '/assets/css/editor.css', array(), ABIDAR_VERSION );
	add_editor_style( ABIDAR_URI . '/assets/css/editor.css' );
}
add_action( 'enqueue_block_editor_assets', 'abidar_editor_assets' );

/**
 * Strip the default WordPress gallery/embed clutter for a cleaner canvas.
 */
function abidar_body_classes( $classes ) {
	$classes[] = 'ab-theme';
	$classes[] = 'ab-lang-' . Abidar_Multilingual::instance()->current_language();

	if ( abidar_option( 'header_sticky', 1 ) ) {
		$classes[] = 'ab-sticky-header';
	}
	if ( abidar_option( 'header_transparent', 1 ) && ( is_front_page() || is_singular( 'ab_project' ) ) ) {
		$classes[] = 'ab-transparent-header';
	}
	if ( abidar_is_elementor_built() ) {
		$classes[] = 'ab-elementor-page';
	}

	return $classes;
}
add_filter( 'body_class', 'abidar_body_classes' );

/**
 * Is the current page built with Elementor?
 *
 * @return bool
 */
function abidar_is_elementor_built() {
	if ( ! did_action( 'elementor/loaded' ) ) {
		return false;
	}

	$id = get_the_ID();
	return $id && class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->documents->get( $id ) && \Elementor\Plugin::$instance->documents->get( $id )->is_built_with_elementor();
}

/**
 * Excerpt length from theme options.
 *
 * @param int $length Default length.
 * @return int
 */
function abidar_excerpt_length( $length ) {
	if ( is_admin() ) {
		return $length;
	}
	return (int) abidar_option( 'blog_excerpt_length', 24 );
}
add_filter( 'excerpt_length', 'abidar_excerpt_length', 999 );

/**
 * Nicer excerpts.
 *
 * @param string $more Default more string.
 * @return string
 */
function abidar_excerpt_more( $more ) {
	return is_admin() ? $more : '&hellip;';
}
add_filter( 'excerpt_more', 'abidar_excerpt_more' );

/**
 * Output the schema graph for the organisation.
 */
function abidar_schema_org() {
	$name    = get_bloginfo( 'name' );
	$url     = home_url( '/' );
	$logo_id = abidar_option( 'logo', '' );
	$logo    = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';

	$data = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => $name,
		'url'      => $url,
		'email'    => abidar_option( 'email', '' ),
		'telephone' => abidar_option( 'phone', '' ),
		'address'  => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => abidar_copy( 'address' ),
			'addressCountry'  => 'IR',
		),
	);

	if ( $logo ) {
		$data['logo'] = $logo;
	}

	if ( $email = abidar_option( 'topbar_email', '' ) ) {
		$data['email'] = $email;
	}
	if ( $phone = abidar_option( 'topbar_phone', '' ) ) {
		$data['telephone'] = $phone;
	}

	echo '<script type="application/ld+json">' . wp_json_encode( array_filter( $data ) ) . '</script>';
}
add_action( 'wp_head', 'abidar_schema_org', 20 );

/**
 * Social sharing meta (Open Graph + Twitter).
 *
 * Skipped when a dedicated SEO plugin already handles it.
 */
function abidar_social_meta() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' ) ) {
		return;
	}

	$lang  = Abidar_Multilingual::instance()->current_language();
	$og    = array(
		'fa' => 'og-fa.jpg',
		'en' => 'og-en.jpg',
		'zh' => 'og-zh.jpg',
	);

	$image = ABIDAR_URI . '/assets/img/og/' . ( isset( $og[ $lang ] ) ? $og[ $lang ] : $og['fa'] );
	$title = wp_get_document_title();
	$desc  = '';

	if ( is_singular() ) {
		$desc = get_the_excerpt();

		if ( has_post_thumbnail() ) {
			$thumb = wp_get_attachment_image_url( get_post_thumbnail_id(), 'abidar-card' );
			if ( $thumb ) {
				$image = $thumb;
			}
		}
	} else {
		$desc = abidar_copy( 'hero_text' );
	}

	$desc = wp_strip_all_tags( $desc );
	$desc = wp_html_excerpt( $desc, 180, '…' );

	printf( '<meta property="og:type" content="%s">' . "\n", is_singular() && ! is_front_page() ? 'article' : 'website' );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( Abidar_Multilingual::instance()->language_url( $lang ) ) );
	printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	printf( '<meta property="og:image:width" content="1200">' . "\n" );
	printf( '<meta property="og:image:height" content="630">' . "\n" );
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( str_replace( '-', '_', get_locale() ) ) );
	printf( '<meta name="twitter:card" content="summary_large_image">' . "\n" );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $desc ) );
	printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
}
add_action( 'wp_head', 'abidar_social_meta', 6 );

/**
 * Add a pingback / generator meta and preload the primary fonts.
 */
function abidar_preload_fonts() {
	$fonts = array(
		'/assets/fonts/vazirmatn-arabic-400-normal.woff2',
		'/assets/fonts/vazirmatn-latin-400-normal.woff2',
	);
	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( ABIDAR_URI . $font )
		);
	}
}
add_action( 'wp_head', 'abidar_preload_fonts', 1 );
