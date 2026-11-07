<?php
/**
 * Primary navigation walker: adds dropdown carets and accessible markup.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Abidar_Walker_Nav
 */
class Abidar_Walker_Nav extends Walker_Nav_Menu {

	/**
	 * Start sub level.
	 *
	 * @param string   $output Output.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}

	/**
	 * Start element.
	 *
	 * @param string   $output Output.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 * @param int      $id     ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = 'ab-nav__item';
		$classes[] = 'ab-nav__item--depth-' . $depth;

		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$classes[] = 'has-sub';
		}

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$output     .= '<li class="' . esc_attr( $class_names ) . '">';

		$atts = array(
			'href'   => ! empty( $item->url ) ? $item->url : '',
			'class'  => 'ab-nav__link',
			'target' => ! empty( $item->target ) ? $item->target : '',
		);

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( $value ) {
				$attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
			}
		}

		if ( $item->current || in_array( 'current-menu-item', $classes, true ) ) {
			$attributes .= ' aria-current="page"';
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$output .= '<a' . $attributes . '><span>' . esc_html( $title ) . '</span>';

		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$output .= '<span class="ab-nav__caret" aria-hidden="true">' . abidar_icon( 'chevron-down', false, array( 'width' => 14, 'height' => 14 ) ) . '</span>';
		}

		$output .= '</a>';
	}

	/**
	 * End element.
	 *
	 * @param string   $output Output.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}
