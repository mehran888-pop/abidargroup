<?php
/**
 * Helpers shared by the Elementor header / footer widgets.
 *
 * These functions exist so the theme's header and footer can be rebuilt with
 * Elementor **Free** (not only Pro's Theme Builder):
 *
 *  - `abidar_menu_choices()`  – select options for every WP menu.
 *  - `abidar_menu_items()`    – normalised menu tree (label, url, icon, desc, children).
 *  - `abidar_mega_label()/url()/children()` – tolerant accessors for both the
 *    normalised menu tree and the widget's own repeater rows.
 *  - `abidar_header_layout_*` – the active Elementor header/footer template.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

// Guard against a second include (child theme or plugin re-requiring the file).
if ( defined( 'ABIDAR_ELEMENTOR_HELPERS' ) ) {
	return;
}
define( 'ABIDAR_ELEMENTOR_HELPERS', true );

/**
 * Every registered nav menu, as `id => name` pairs for a SELECT control.
 *
 * @return array
 */
function abidar_menu_choices() {
	$choices = array( '0' => __( '— Default menu —', 'abidar' ) );
	$menus   = wp_get_nav_menus();

	if ( is_array( $menus ) ) {
		foreach ( $menus as $menu ) {
			$choices[ (string) $menu->term_id ] = $menu->name;
		}
	}

	return $choices;
}

/**
 * Read a menu into a normalised tree.
 *
 * @param int $menu_id Menu term id; 0 falls back to the primary location.
 * @return array
 */
function abidar_menu_items( $menu_id = 0 ) {
	$locations = get_nav_menu_locations();
	$objects   = array();

	if ( ! $menu_id && ! empty( $locations['primary'] ) ) {
		$menu_id = (int) $locations['primary'];
	} elseif ( ! $menu_id && ! empty( $locations['menu-1'] ) ) {
		$menu_id = (int) $locations['menu-1'];
	}

	if ( ! $menu_id && ! empty( $locations ) ) {
		$first   = reset( $locations );
		$menu_id = (int) $first;
	}

	if ( $menu_id ) {
		$objects = wp_get_nav_menu_items( $menu_id );
	}

	if ( ! $objects ) {
		return array();
	}

	$by_parent = array();

	foreach ( $objects as $item ) {
		$parent                = (int) $item->menu_item_parent;
		$by_parent[ $parent ][] = $item;
	}

	/**
	 * Convert a flat list of menu items into our normalised shape.
	 *
	 * @param array $items    Menu items for one level.
	 * @param array $by_parent Items keyed by parent id.
	 * @param int   $depth    Current depth.
	 * @return array
	 */
	$build = static function ( $items, $by_parent, $depth = 0 ) use ( &$build ) {
		$out = array();

		foreach ( (array) $items as $item ) {
			$classes = (array) $item->classes;
			$icon    = '';

			// "ab-icon-layers" style classes let editors pick an icon per item.
			foreach ( $classes as $class ) {
				if ( 0 === strpos( $class, 'ab-icon-' ) ) {
					$icon = substr( $class, 8 );
					break;
				}
			}

			$children = isset( $by_parent[ (int) $item->ID ] )
				? $build( $by_parent[ (int) $item->ID ], $by_parent, $depth + 1 )
				: array();

			$out[] = array(
				'label'    => $item->title,
				'url'      => $item->url,
				'icon'     => $icon,
				// The "description" column gives editors the mega-menu blurb.
				'desc'     => isset( $item->description ) ? $item->description : '',
				'children' => $children,
				'target'   => isset( $item->target ) ? $item->target : '',
				'mega'     => in_array( 'ab-mega', $classes, true ) || $depth === 0 && in_array( 'mega-menu', $classes, true ),
			);
		}

		return $out;
	};

	return $build( isset( $by_parent[0] ) ? $by_parent[0] : array(), $by_parent );
}

/**
 * Label of a menu item or repeater row.
 *
 * @param array $item Item.
 * @return string
 */
function abidar_mega_label( $item ) {
	if ( is_object( $item ) ) {
		$item = (array) $item;
	}

	foreach ( array( 'label', 'title', 'text', 'name' ) as $key ) {
		if ( ! empty( $item[ $key ] ) ) {
			return (string) $item[ $key ];
		}
	}

	return '';
}

/**
 * URL of a menu item or repeater row (handles Elementor's URL control shape).
 *
 * @param array $item Item.
 * @return string
 */
function abidar_mega_url( $item ) {
	if ( is_object( $item ) ) {
		$item = (array) $item;
	}

	$url = '';

	if ( isset( $item['url'] ) ) {
		$url = is_array( $item['url'] ) ? ( $item['url']['url'] ?? '' ) : (string) $item['url'];
	} elseif ( isset( $item['link'] ) ) {
		$url = is_array( $item['link'] ) ? ( $item['link']['url'] ?? '' ) : (string) $item['link'];
	}

	return $url ? $url : '#';
}

/**
 * Children of a menu item or repeater row.
 *
 * Repeater rows store their children as `label|url` lines, so they are parsed
 * into the same shape as a real menu tree.
 *
 * @param array $item Item.
 * @return array
 */
function abidar_mega_children( $item ) {
	if ( is_object( $item ) ) {
		$item = (array) $item;
	}

	if ( ! empty( $item['children'] ) && is_array( $item['children'] ) ) {
		return $item['children'];
	}

	if ( empty( $item['children'] ) || ! is_string( $item['children'] ) ) {
		return array();
	}

	$children = array();

	foreach ( preg_split( '/\r\n|\r|\n/', $item['children'] ) as $line ) {
		$line = trim( $line );

		if ( '' === $line ) {
			continue;
		}

		$parts = array_map( 'trim', explode( '|', $line ) );

		$children[] = array(
			'label'    => $parts[0],
			'url'      => isset( $parts[1] ) ? $parts[1] : '#',
			'icon'     => isset( $parts[2] ) ? $parts[2] : '',
			'desc'     => isset( $parts[3] ) ? $parts[3] : '',
			'children' => array(),
		);
	}

	return $children;
}

/**
 * Logo / site title markup shared by the header and footer widgets.
 *
 * @param array $args Args: dark (bool), size (int), tagline (bool).
 */
function abidar_brand_mark( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'dark'    => false,
			'size'    => 0,
			'tagline' => false,
		)
	);

	$logo_id  = $args['dark'] ? abidar_option( 'logo_dark', '' ) : abidar_option( 'logo', '' );
	$logo_url = $logo_id ? wp_get_attachment_image_url( (int) $logo_id, 'full' ) : '';
	$width    = $args['size'] ? (int) $args['size'] : (int) abidar_option( 'logo_width', 148 );

	echo '<a class="ab-brand" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';

	if ( $logo_url ) {
		printf(
			'<img class="ab-brand__img" src="%s" alt="%s" width="%d" height="auto" loading="eager" decoding="async">',
			esc_url( $logo_url ),
			esc_attr( get_bloginfo( 'name' ) ),
			(int) $width
		);
	} else {
		echo '<span class="ab-brand__mark" aria-hidden="true">' . abidar_icon( 'mountain', false, array( 'width' => 22, 'height' => 22 ) ) . '</span>';
		echo '<span class="ab-brand__text">';
		echo '<span class="ab-brand__name">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
		if ( $args['tagline'] ) {
			echo '<span class="ab-brand__tagline">' . esc_html( abidar_copy( 'topbar_note' ) ) . '</span>';
		}
		echo '</span>';
	}

	echo '</a>';
}

/**
 * Social links repeater defaults, used by header and footer widgets.
 *
 * @return array
 */
function abidar_social_defaults() {
	return array(
		array( 'network' => 'linkedin', 'url' => array( 'url' => '#' ) ),
		array( 'network' => 'instagram', 'url' => array( 'url' => '#' ) ),
		array( 'network' => 'telegram', 'url' => array( 'url' => '#' ) ),
		array( 'network' => 'whatsapp', 'url' => array( 'url' => '#' ) ),
	);
}

/**
 * Which Elementor template (if any) should render the header / footer.
 *
 * Elementor Free has no Theme Builder, so the theme offers its own "Header" and
 * "Footer" template post types. This returns the published template of that
 * type, letting editors design the header/footer inside the free editor.
 *
 * @param string $type `header` or `footer`.
 * @return int Template post id, 0 when none.
 */
function abidar_elementor_part_id( $type = 'header' ) {
	static $cache = array();

	$type = 'footer' === $type ? 'footer' : 'header';

	if ( isset( $cache[ $type ] ) ) {
		return $cache[ $type ];
	}

	$parts = get_posts(
		array(
			'post_type'              => 'abidar_' . $type,
			'posts_per_page'         => 1,
			'post_status'            => 'publish',
			'orderby'                => 'menu_order date',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'fields'                 => 'ids',
		)
	);

	$cache[ $type ] = $parts ? (int) $parts[0] : 0;

	return $cache[ $type ];
}

/**
 * Render the Elementor-built header/footer, if the editor created one.
 *
 * Order of precedence:
 *   1. Elementor Pro Theme Builder location (unchanged, Pro users keep the win).
 *   2. The theme's own `abidar_header` / `abidar_footer` template post.
 *
 * @param string $type `header` or `footer`.
 * @return bool True when a template rendered.
 */
function abidar_render_elementor_part( $type = 'header' ) {
	$location = 'footer' === $type ? 'footer' : 'header';

	if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location ) ) {
		return true;
	}

	$id = abidar_elementor_part_id( $location );

	if ( ! $id ) {
		return false;
	}

	echo '<div class="ab-el-part ab-el-part--' . esc_attr( $location ) . '">';
	echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, true ); // phpcs:ignore WordPress.Security.EscapeOutput
	echo '</div>';

	return true;
}

/**
 * Render a normalised menu tree as a `<ul>`.
 *
 * @param array  $items Items.
 * @param int    $depth Current depth.
 * @param string $class UL class.
 */
function abidar_masthead_menu( $items, $depth = 3, $class = '' ) {
	if ( ! $items || $depth < 1 ) {
		return;
	}

	$class = $class ? $class : 'ab-masthead__list';
	$level = 4 - $depth;

	echo '<ul class="' . esc_attr( $class . ( $level ? ' ab-masthead__sub' : '' ) ) . '">';

	foreach ( $items as $item ) {
		$children = isset( $item['children'] ) && is_array( $item['children'] ) ? $item['children'] : array();
		$label    = abidar_mega_label( $item );
		$url      = abidar_mega_url( $item );

		echo '<li class="' . esc_attr( $children ? 'has-children' : '' ) . '">';
		printf( '<a href="%s">%s', esc_url( $url ), esc_html( $label ) );

		if ( $children ) {
			echo abidar_icon( 'chevron-down', false, array( 'width' => 13, 'height' => 13, 'class' => 'ab-icon ab-masthead__caret' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		echo '</a>';

		if ( $children ) {
			abidar_masthead_menu( $children, $depth - 1, $class );
		}

		echo '</li>';
	}

	echo '</ul>';
}
