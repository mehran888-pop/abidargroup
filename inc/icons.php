<?php
/**
 * Inline SVG icon set (24×24, stroke based).
 *
 * Shipping the icons as a PHP array keeps the theme dependency free while
 * letting Elementor widgets pick any icon from the same library.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icon paths.
 *
 * @return array
 */
function abidar_icon_paths() {
	return array(
		'globe'        => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 2.5 15.4 0 18M12 3c-2.5 2.6-2.5 15.4 0 18"/>',
		'chevron-down' => '<path d="M6 9l6 6 6-6"/>',
		'chevron-left' => '<path d="M15 18l-6-6 6-6"/>',
		'chevron-right'=> '<path d="M9 18l6-6-6-6"/>',
		'arrow-right'  => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'arrow-left'   => '<path d="M19 12H5M11 18l-6-6 6-6"/>',
		'arrow-up-right' => '<path d="M7 17L17 7M9 7h8v8"/>',
		'arrow-up'     => '<path d="M12 19V5M6 11l6-6 6 6"/>',
		'plus'         => '<path d="M12 5v14M5 12h14"/>',
		'minus'        => '<path d="M5 12h14"/>',
		'check'        => '<path d="M20 6L9 17l-5-5"/>',
		'x'            => '<path d="M18 6L6 18M6 6l12 12"/>',
		'menu'         => '<path d="M3 6h18M3 12h18M3 18h18"/>',
		'search'       => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
		'phone'        => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.2-1.2a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2z"/>',
		'mail'         => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 6L2 7"/>',
		'pin'          => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
		'clock'        => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'calendar'     => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
		'user'         => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
		'send'         => '<path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>',
		'linkedin'     => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>',
		'instagram'    => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/>',
		'telegram'     => '<path d="M21.5 3.5L2.8 10.9c-.9.3-.9 1.6 0 1.9l4.4 1.4 1.7 5.4c.3.9 1.5 1 2 .2l2.4-3.2 4.6 3.4c.7.5 1.7.1 1.9-.7l3-13.4c.2-1-.8-1.8-1.8-1.4z"/><path d="M7.2 14.2l10-6.4-7.6 7.6"/>',
		'whatsapp'     => '<path d="M21 11.5a8.6 8.6 0 0 1-12.8 7.6L3 21l1.9-5.1A8.6 8.6 0 1 1 21 11.5z"/><path d="M8.6 8.4c.3-.6.6-.6.9-.6h.6c.2 0 .5 0 .7.5l.8 1.9c.1.3 0 .5-.1.7l-.5.6c-.2.2-.3.4-.1.7a7 7 0 0 0 3 2.6c.3.1.5.1.7-.1l.7-.8c.2-.2.4-.2.7-.1l1.8.9c.3.2.5.3.5.5v.6c0 .6-.5 1.4-1.1 1.6-1.9.7-4.6-.3-6.6-2.3s-3-4.7-2.3-6.6z"/>',
		'youtube'      => '<rect x="2" y="5" width="20" height="14" rx="4"/><path d="M10 9l5 3-5 3z"/>',
		'x-social'     => '<path d="M4 3l16 18M20 3L4 21"/>',
		'facebook'     => '<path d="M15 3h-3a4 4 0 0 0-4 4v3H5v4h3v7h4v-7h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
		// Industry icons.
		'mining'       => '<path d="M3 21l6-6M3 21h8"/><path d="M14.5 3.5l6 6-3 3-6-6z"/><path d="M11.5 6.5l6 6"/><path d="M9 9l-6 6"/>',
		'pickaxe'      => '<path d="M2 22l9-9"/><path d="M14 3c3 0 5.5 1.5 7 4-2-1-4-1.5-6-1"/><path d="M21 3c0 3-1.5 5.5-4 7 1-2 1.5-4 1-6"/><path d="M11 9l4 4"/>',
		'oil'          => '<path d="M4 21V9l7-4 7 4v12"/><path d="M4 15h18"/><path d="M9 21v-4h4v4"/><path d="M14 5V2"/>',
		'drop'         => '<path d="M12 2.7l5.3 5.9a7 7 0 1 1-10.6 0z"/>',
		'flame'        => '<path d="M12 2c1 4 5 5 5 10a5 5 0 0 1-10 0c0-2 1-3 2-4 0 2 1 3 2 3 0-3-1-6 1-9z"/>',
		'layers'       => '<path d="M12 3l9 5-9 5-9-5 9-5z"/><path d="M3 13l9 5 9-5"/>',
		'mountain'     => '<path d="M2 20l6.5-11 5 8 3-4.5L22 20z"/><path d="M8.5 9l2 3.2"/>',
		'cube'         => '<path d="M12 2l9 5v10l-9 5-9-5V7z"/><path d="M3 7l9 5 9-5M12 12v10"/>',
		'truck'        => '<path d="M2 6h11v11H2z"/><path d="M13 10h4l4 4v3h-8z"/><circle cx="6.5" cy="18.5" r="1.8"/><circle cx="17" cy="18.5" r="1.8"/>',
		'excavator'    => '<path d="M3 20h10"/><circle cx="6" cy="17" r="2"/><circle cx="11" cy="17" r="2"/><path d="M3 15V9h6l3 4"/><path d="M9 9l4-5 5 2"/><path d="M18 6l2 8-4 1"/>',
		'factory'      => '<path d="M3 21V10l5 3V10l5 3V9l5 3V6h3v15z"/><path d="M3 21h18"/><path d="M7 21v-4M12 21v-4M17 21v-4"/>',
		'hardhat'      => '<path d="M3 15a9 9 0 0 1 18 0"/><path d="M2 15h20v3H2z"/><path d="M9 15V7a3 3 0 0 1 6 0v8"/>',
		'shield'       => '<path d="M12 2l8 3v7c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V5z"/><path d="M8.5 12l2.5 2.5 4.5-5"/>',
		'leaf'         => '<path d="M11 20A7 7 0 0 1 4 13c0-6 8-11 16-11 0 8-5 16-11 16z"/><path d="M4 21c2-6 6-9 11-11"/>',
		'chart'        => '<path d="M3 3v18h18"/><path d="M7 15l4-4 3 3 5-6"/>',
		'gear'         => '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.2 4.2l2 2M17.8 17.8l2 2M2 12h3M19 12h3M4.2 19.8l2-2M17.8 6.2l2-2"/>',
		'settings'     => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.6 1.6 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.6 1.6 0 0 0-1.8-.3 1.6 1.6 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1A1.6 1.6 0 0 0 9 19.4a1.6 1.6 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.6 1.6 0 0 0 .3-1.8 1.6 1.6 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1A1.6 1.6 0 0 0 4.6 9a1.6 1.6 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.6 1.6 0 0 0 1.8.3H9a1.6 1.6 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.6 1.6 0 0 0 1 1.5 1.6 1.6 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.6 1.6 0 0 0-.3 1.8V9a1.6 1.6 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.6 1.6 0 0 0-1.5 1z"/>',
		'quote'        => '<path d="M9 7H5a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h2v2a2 2 0 0 1-2 2"/><path d="M20 7h-4a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h2v2a2 2 0 0 1-2 2"/>',
		'star'         => '<path d="M12 3l2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3 6.4 20.2l1.1-6.2L3 9.6l6.2-.9z"/>',
		'play'         => '<circle cx="12" cy="12" r="9"/><path d="M10 8.5l6 3.5-6 3.5z"/>',
		'download'     => '<path d="M12 3v12M7 11l5 5 5-5"/><path d="M4 21h16"/>',
		'file'         => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/>',
		'award'        => '<circle cx="12" cy="9" r="6"/><path d="M8.5 14.5L7 22l5-2.5L17 22l-1.5-7.5"/>',
		'handshake'    => '<path d="M3 11l4-4 5 5 5-5 4 4-5 5-4 4-4-4z"/>',
		'package'      => '<path d="M12 2l9 5v10l-9 5-9-5V7z"/><path d="M3 7l9 5 9-5M12 12v10"/>',
		'battery'      => '<rect x="2" y="7" width="18" height="10" rx="2"/><path d="M22 11v2"/><path d="M6 11v2M10 11v2"/>',
		'droplet-off'  => '<path d="M12 2.7l5.3 5.9a7 7 0 0 1-1.4 9.6"/><path d="M8 6a7 7 0 0 0 2.6 15c1 0 2-.2 2.8-.6"/><path d="M3 3l18 18"/>',
		'ruler'        => '<path d="M3 15L15 3l6 6L9 21z"/><path d="M7 11l2 2M11 7l2 2M15 15l2 2"/>',
	);
}

/**
 * Build an inline SVG icon.
 *
 * @param string $name  Icon name.
 * @param bool   $echo  Print or return.
 * @param array  $attrs Extra attributes (class, width, height).
 * @return string
 */
function abidar_icon( $name, $echo = false, $attrs = array() ) {
	$paths = abidar_icon_paths();

	if ( ! isset( $paths[ $name ] ) ) {
		$name = 'check';
	}

	// Icons that imply reading direction are mirrored in RTL stylesheets.
	$directional = array( 'arrow-right', 'arrow-left', 'arrow-up-right', 'chevron-right', 'chevron-left', 'login', 'logout' );

	$attrs = wp_parse_args(
		$attrs,
		array(
			'class'  => 'ab-icon',
			'width'  => 24,
			'height' => 24,
		)
	);

	if ( in_array( $name, $directional, true ) && false === strpos( $attrs['class'], 'ab-icon--directional' ) ) {
		$attrs['class'] .= ' ab-icon--directional';
		$attrs['class']  = trim( preg_replace( '/\s+/', ' ', $attrs['class'] ) );
	}

	$attr_string = '';
	foreach ( $attrs as $attr => $value ) {
		$attr_string .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
	}

	$svg = '<svg' . $attr_string . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';

	if ( $echo ) {
		echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput
	}

	return $svg;
}

/**
 * Flat list for Elementor select controls.
 *
 * @return array
 */
function abidar_icon_options() {
	$out = array();
	foreach ( array_keys( abidar_icon_paths() ) as $name ) {
		$out[ $name ] = ucwords( str_replace( '-', ' ', $name ) );
	}
	return $out;
}
