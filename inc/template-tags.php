<?php
/**
 * Template tags used across the theme and the Elementor widgets.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

require_once ABIDAR_DIR . '/inc/icons.php';

/**
 * Load a template part with data, keeping the global scope clean.
 *
 * @param string $slug Template slug.
 * @param array  $args Data.
 */
function abidar_part( $slug, $args = array() ) {
	$file = ABIDAR_DIR . '/template-parts/' . $slug . '.php';

	if ( ! file_exists( $file ) ) {
		return;
	}

	$data = $args; // phpcs:ignore
	include $file;
}

/**
 * The logo (custom logo, theme option or wordmark fallback).
 *
 * @param array $args Args: class, dark.
 */
function abidar_logo( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'class' => '',
			'dark'  => false,
			'url'   => home_url( '/' ),
		)
	);

	$logo_id = $args['dark'] ? abidar_option( 'logo_dark', '' ) : abidar_option( 'logo', '' );

	if ( ! $logo_id && has_custom_logo() ) {
		$logo_id = get_theme_mod( 'custom_logo' );
	}

	echo '<a class="ab-logo ' . esc_attr( $args['class'] ) . '" href="' . esc_url( $args['url'] ) . '" rel="home" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '">';

	if ( $logo_id ) {
		$src = wp_get_attachment_image_url( $logo_id, 'full' );
		if ( $src ) {
			printf(
				'<img class="ab-logo__img" src="%s" alt="%s" width="%d" loading="eager" decoding="async">',
				esc_url( $src ),
				esc_attr( get_bloginfo( 'name' ) ),
				(int) abidar_option( 'logo_width', 148 )
			);
		}
	} else {
		echo '<span class="ab-logo__mark" aria-hidden="true">' . abidar_icon( 'mountain', false, array( 'width' => 28, 'height' => 28 ) ) . '</span>';
		echo '<span class="ab-logo__text">';
		echo '<span class="ab-logo__name">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
		echo '<span class="ab-logo__tag">' . esc_html( get_bloginfo( 'description' ) ) . '</span>';
		echo '</span>';
	}

	echo '</a>';
}

/**
 * Elementor location aware header.
 */
function abidar_header() {
	if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
		return;
	}
	get_template_part( 'template-parts/header/default' );
}

/**
 * Elementor location aware footer.
 */
function abidar_footer() {
	if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
		return;
	}
	get_template_part( 'template-parts/footer/default' );
}

/**
 * Page title bar / hero for inner pages.
 *
 * @param array $args Args: title, subtitle, breadcrumb (bool), bg.
 */
function abidar_page_hero( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'title'      => get_the_title(),
			'subtitle'   => '',
			'breadcrumb' => true,
			'bg'         => '',
			'eyebrow'    => '',
		)
	);

	$bg = $args['bg'] ? $args['bg'] : ABIDAR_URI . '/assets/img/pattern-dark.jpg';
	?>
	<section class="ab-page-hero" style="--ab-hero-bg:url('<?php echo esc_url( $bg ); ?>')">
		<div class="ab-container">
			<?php if ( $args['eyebrow'] ) : ?>
				<span class="ab-eyebrow ab-eyebrow--light"><?php echo esc_html( $args['eyebrow'] ); ?></span>
			<?php endif; ?>
			<h1 class="ab-page-hero__title"><?php echo esc_html( $args['title'] ); ?></h1>
			<?php if ( $args['subtitle'] ) : ?>
				<p class="ab-page-hero__sub"><?php echo esc_html( $args['subtitle'] ); ?></p>
			<?php endif; ?>
			<?php if ( $args['breadcrumb'] ) : ?>
				<?php abidar_breadcrumb(); ?>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Breadcrumb trail.
 */
function abidar_breadcrumb() {
	echo '<nav class="ab-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'abidar' ) . '"><ol>';

	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( abidar_copy( 'nav_home' ) ) . '</a></li>';

	if ( is_singular() ) {
		$post_type = get_post_type();

		if ( 'post' === $post_type ) {
			$cats = get_the_category();
			if ( $cats ) {
				echo '<li><a href="' . esc_url( get_category_link( $cats[0] ) ) . '">' . esc_html( $cats[0]->name ) . '</a></li>';
			}
			echo '<li aria-current="page">' . esc_html( get_the_title() ) . '</li>';
		} elseif ( 'ab_project' === $post_type || 'ab_service' === $post_type ) {
			$archive = get_post_type_archive_link( $post_type );
			echo '<li><a href="' . esc_url( $archive ) . '">' . esc_html( post_type_archive_title( '', false ) ) . '</a></li>';
			echo '<li aria-current="page">' . esc_html( get_the_title() ) . '</li>';
		} else {
			echo '<li aria-current="page">' . esc_html( get_the_title() ) . '</li>';
		}
	} elseif ( is_archive() ) {
		echo '<li aria-current="page">' . esc_html( get_the_archive_title() ) . '</li>';
	} elseif ( is_search() ) {
		echo '<li aria-current="page">' . esc_html( abidar_copy( 'search_title' ) ) . ' “' . esc_html( get_search_query() ) . '”</li>';
	} elseif ( is_404() ) {
		echo '<li aria-current="page">404</li>';
	} elseif ( is_front_page() ) {
		echo '<li aria-current="page">' . esc_html( abidar_copy( 'nav_home' ) ) . '</li>';
	}

	echo '</ol></nav>';
}

/**
 * Meta line for blog cards (date, category, read time).
 *
 * @param bool $show_author Author.
 */
function abidar_post_meta( $show_author = true ) {
	$cats = get_the_category();

	echo '<div class="ab-post-meta">';

	if ( $cats ) {
		printf(
			'<a class="ab-post-meta__cat" href="%s">%s</a>',
			esc_url( get_category_link( $cats[0] ) ),
			esc_html( $cats[0]->name )
		);
	}

	printf(
		'<time class="ab-post-meta__date" datetime="%s">%s</time>',
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	if ( $show_author ) {
		printf(
			'<span class="ab-post-meta__author">%s %s</span>',
			esc_html( abidar_copy( 'by' ) ),
			esc_html( get_the_author() )
		);
	}

	echo '</div>';
}

/**
 * Reading time in the active language.
 *
 * @param int|null $post_id Post ID.
 * @return string
 */
function abidar_reading_time( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) );
	$content = preg_replace( '/\s+/u', ' ', $content );

	$lang = Abidar_Multilingual::instance()->current_language();

	if ( 'zh' === $lang ) {
		// Chinese has no spaces: count characters, ~400 per minute.
		$chars = preg_match_all( '/[\x{4E00}-\x{9FFF}]/u', $content );
		$units = $chars ? $chars : max( 1, (int) preg_match_all( '/\S/u', $content ) );
		$wpm   = 400;
	} else {
		// str_word_count() is latin-only, so split on whitespace instead.
		$units = max( 1, count( preg_split( '/\s+/u', trim( $content ) ) ) );
		$wpm   = 'fa' === $lang ? 190 : 220;
	}

	$mins = max( 1, (int) ceil( $units / $wpm ) );

	return $mins . ' ' . abidar_copy( 'min_read' );
}

/**
 * Render a paginated navigation with localised labels.
 *
 * @param WP_Query|null $query Query.
 */
function abidar_pagination( $query = null ) {
	$total = $query ? $query->max_num_pages : 0;

	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => abidar_icon( 'arrow-left', false ) . '<span>' . esc_html( abidar_copy( 'prev' ) ) . '</span>',
			'next_text' => '<span>' . esc_html( abidar_copy( 'next' ) ) . '</span>' . abidar_icon( 'arrow-right', false ),
			'class'     => 'ab-pagination',
		)
	);
}

/**
 * Social profiles from the theme options.
 *
 * @param string $class Extra class.
 */
function abidar_social_links( $class = '' ) {
	$networks = array(
		'linkedin'  => abidar_icon( 'linkedin' ),
		'instagram' => abidar_icon( 'instagram' ),
		'telegram'  => abidar_icon( 'telegram' ),
		'whatsapp'  => abidar_icon( 'whatsapp' ),
		'youtube'   => abidar_icon( 'youtube' ),
	);

	$links = array();
	foreach ( $networks as $key => $icon ) {
		$url = abidar_option( 'social_' . $key, '' );
		if ( $url && '#' !== $url ) {
			$links[ $key ] = array( $url, $icon );
		} elseif ( '#' === $url ) {
			$links[ $key ] = array( '#', $icon );
		}
	}

	if ( ! $links ) {
		return;
	}

	echo '<ul class="ab-social ' . esc_attr( $class ) . '">';
	foreach ( $links as $key => $data ) {
		printf(
			'<li><a href="%1$s" class="ab-social__link" target="_blank" rel="noopener noreferrer nofollow" aria-label="%2$s">%3$s</a></li>',
			esc_url( $data[0] ),
			esc_attr( ucfirst( $key ) ),
			$data[1] // phpcs:ignore WordPress.Security.EscapeOutput
		);
	}
	echo '</ul>';
}

/**
 * Contact rows (address/phone/email/hours) from theme options.
 */
function abidar_contact_rows() {
	$rows = array(
		'pin'   => array( 'address', abidar_copy( 'address_label' ) ),
		'phone' => array( 'topbar_phone', abidar_copy( 'phone_label' ) ),
		'mail'  => array( 'topbar_email', abidar_copy( 'email_label' ) ),
		'clock' => array( 'working_hours', abidar_copy( 'hours_label' ) ),
	);

	foreach ( $rows as $icon => $row ) {
		$value = abidar_option( $row[0], '' );

		if ( 'address' === $row[0] ) {
			$value = $value ? $value : abidar_copy( 'address' );
		}
		if ( 'working_hours' === $row[0] ) {
			$value = $value ? $value : abidar_copy( 'hours' );
		}
		if ( ! $value ) {
			continue;
		}

		$href = '';
		if ( 'phone' === $row[0] ) {
			$href = 'tel:' . preg_replace( '/[^0-9+]/', '', $value );
		} elseif ( 'mail' === $row[0] ) {
			$href = 'mailto:' . sanitize_email( $value );
		}

		echo '<div class="ab-contact-row">';
		echo '<span class="ab-contact-row__icon">' . abidar_icon( $icon ) . '</span>';
		echo '<span class="ab-contact-row__body"><span class="ab-contact-row__label">' . esc_html( $row[1] ) . '</span>';
		if ( $href ) {
			echo '<a class="ab-contact-row__value" href="' . esc_url( $href ) . '">' . esc_html( $value ) . '</a>';
		} else {
			echo '<span class="ab-contact-row__value">' . esc_html( $value ) . '</span>';
		}
		echo '</span></div>';
	}
}

/**
 * Post thumbnail with graceful placeholder.
 *
 * @param string $size Image size.
 * @param string $class Classes.
 */
function abidar_thumbnail( $size = 'abidar-card', $class = '' ) {
	if ( has_post_thumbnail() ) {
		the_post_thumbnail(
			$size,
			array(
				'class'    => 'ab-thumb ' . $class,
				'loading'  => 'lazy',
				'decoding' => 'async',
			)
		);
	} else {
		echo '<div class="ab-thumb ab-thumb--placeholder ' . esc_attr( $class ) . '" aria-hidden="true">' . abidar_icon( 'mountain', false, array( 'width' => 48, 'height' => 48 ) ) . '</div>';
	}
}

/**
 * Demo data helpers: services / team / testimonials / stats.
 *
 * @return array
 */
function abidar_services_data() {
	return require ABIDAR_DIR . '/inc/data/services.php';
}

/**
 * @return array
 */
function abidar_stats_data() {
	return require ABIDAR_DIR . '/inc/data/stats.php';
}

/**
 * @return array
 */
function abidar_team_data() {
	return require ABIDAR_DIR . '/inc/data/team.php';
}

/**
 * @return array
 */
function abidar_testimonials_data() {
	return require ABIDAR_DIR . '/inc/data/testimonials.php';
}

/**
 * @return array
 */
function abidar_projects_data() {
	return require ABIDAR_DIR . '/inc/data/projects.php';
}

/**
 * Localised value out of a demo data array.
 *
 * @param array  $row  Demo row.
 * @param string $key  Key.
 * @param string $lang Language.
 * @return string
 */
function abidar_localized( $row, $key, $lang = '' ) {
	$lang = $lang ? $lang : Abidar_Multilingual::instance()->current_language();

	if ( isset( $row[ $key ][ $lang ] ) ) {
		return $row[ $key ][ $lang ];
	}

	if ( isset( $row[ $key ]['en'] ) ) {
		return $row[ $key ]['en'];
	}

	return is_string( $row[ $key ] ?? null ) ? $row[ $key ] : '';
}

/**
 * Add a "preview id" anchor to widgets for the static preview.
 *
 * @param string $id Anchor slug.
 */
function abidar_anchor( $id ) {
	echo ' id="' . esc_attr( $id ) . '"';
}

/**
 * Menu fallback when no menu is assigned: the key pages, localised.
 *
 * @param array $args wp_nav_menu args.
 */
function abidar_menu_fallback( $args = array() ) {
	$classes = 'menu_class';
	$class   = isset( $args[ $classes ] ) ? $args[ $classes ] : 'ab-nav__list';

	$items = array(
		array( abidar_copy( 'nav_home' ), home_url( '/' ) ),
		array( abidar_copy( 'nav_about' ), abidar_page_url( 'about' ) ),
		array( abidar_copy( 'nav_services' ), abidar_page_url( 'services' ) ),
		array( abidar_copy( 'nav_projects' ), abidar_page_url( 'projects' ) ),
		array( abidar_copy( 'nav_team' ), abidar_page_url( 'team' ) ),
		array( abidar_copy( 'nav_blog' ), abidar_page_url( 'blog' ) ),
		array( abidar_copy( 'nav_contact' ), abidar_page_url( 'contact' ) ),
	);

	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $items as $item ) {
		printf(
			'<li class="menu-item ab-nav__item"><a class="ab-nav__link" href="%s"><span>%s</span></a></li>',
			esc_url( $item[1] ),
			esc_html( $item[0] )
		);
	}
	echo '</ul>';
}

/**
 * URL of a demo/standard page by slug, with a sensible fallback.
 *
 * @param string $slug Page slug.
 * @return string
 */
function abidar_page_url( $slug ) {
	$page = get_page_by_path( $slug );

	if ( $page ) {
		return get_permalink( $page );
	}

	// Fall back to the custom post type archive when it exists.
	$archives = array(
		'services' => 'ab_service',
		'projects' => 'ab_project',
		'team'     => 'ab_team',
	);
	if ( isset( $archives[ $slug ] ) ) {
		$link = get_post_type_archive_link( $archives[ $slug ] );
		if ( $link ) {
			return $link;
		}
	}

	return home_url( '/' . $slug . '/' );
}

/**
 * Elementor widget defaults for the copy strings.
 *
 * @param string $key Copy key.
 * @return string
 */
function abidar_default_text( $key ) {
	return Abidar_Strings::get( $key, 'en', $key );
}
