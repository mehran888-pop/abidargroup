<?php
/**
 * Shared base class for all Abidar Elementor widgets.
 *
 * It provides the boilerplate every widget needs — category, icon, a set of
 * reusable style controls (spacing, background, typography) and helper methods
 * for rendering repeatable items.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;

/**
 * Class Abidar_Widget_Base
 */
abstract class Abidar_Widget_Base extends Widget_Base {

	/**
	 * Setting id => theme copy key, used to keep untouched defaults trilingual.
	 *
	 * @var array
	 */
	protected $copy_map = array();

	/**
	 * Register a trilingual default and return it.
	 *
	 * Use it in place of abidar_copy() when defining a control default so the
	 * widget can re-translate the value at render time when the visitor
	 * switches language (Persian / English / Chinese).
	 *
	 * @param string $id  Setting id.
	 * @param string $key Theme copy key.
	 * @return string
	 */
	protected function copy_field( $id, $key ) {
		$this->copy_map[ $id ] = $key;
		return abidar_copy( $key );
	}

	/**
	 * Swap any untouched default copy for the active language.
	 *
	 * @param array $settings Widget settings.
	 * @return array
	 */
	protected function localize_settings( $settings ) {
		foreach ( $this->copy_map as $id => $key ) {
			if ( ! isset( $settings[ $id ] ) || ! is_string( $settings[ $id ] ) ) {
				continue;
			}

			$known = array();
			foreach ( array( 'fa', 'en', 'zh' ) as $lang ) {
				$known[] = Abidar_Strings::get( $key, $lang );
			}

			if ( in_array( $settings[ $id ], $known, true ) ) {
				$settings[ $id ] = abidar_copy( $key );
			}
		}

		return $settings;
	}

	/**
	 * Widget category.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'abidar' );
	}

	/**
	 * Script/style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'abidar-main', 'abidar-widgets' );
	}

	/**
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'abidar-main' );
	}

	/**
	 * Keywords for the widget search.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'abidar', 'corporate', 'mining', 'oil', 'stone', 'business' );
	}

	/**
	 * Convenience wrapper: localised copy with a fallback to the theme option.
	 *
	 * @param string $key Copy key.
	 * @return string
	 */
	protected function copy( $key ) {
		return abidar_copy( $key );
	}

	/**
	 * Add a "section heading" control group (eyebrow / title / text).
	 *
	 * @param string $prefix Control prefix, avoids collisions.
	 * @param array  $defaults Default values.
	 */
	protected function add_heading_controls( $prefix = 'head', $defaults = array(), $keys = array() ) {
		$defaults = wp_parse_args(
			$defaults,
			array(
				'eyebrow' => '',
				'title'   => '',
				'text'    => '',
			)
		);

		// Trilingual defaults: remember which copy key each control came from.
		$eyebrow_default = isset( $keys['eyebrow'] ) ? $this->copy_field( $prefix . '_eyebrow', $keys['eyebrow'] ) : $defaults['eyebrow'];
		$title_default   = isset( $keys['title'] ) ? $this->copy_field( $prefix . '_title', $keys['title'] ) : $defaults['title'];
		$text_default    = isset( $keys['text'] ) ? $this->copy_field( $prefix . '_text', $keys['text'] ) : $defaults['text'];

		$this->add_control(
			$prefix . '_eyebrow',
			array(
				'label'       => esc_html__( 'Eyebrow', 'abidar' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $eyebrow_default,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			$prefix . '_title',
			array(
				'label'       => esc_html__( 'Title', 'abidar' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $title_default,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		if ( ! empty( $defaults['text'] ) || isset( $defaults['text'] ) ) {
			$this->add_control(
				$prefix . '_text',
				array(
					'label'       => esc_html__( 'Description', 'abidar' ),
					'type'        => Controls_Manager::TEXTAREA,
					'default'     => $text_default,
					'rows'        => 3,
					'label_block' => true,
					'dynamic'     => array( 'active' => true ),
				)
			);
		}
	}

	/**
	 * Style controls for the section heading.
	 *
	 * @param string $selector CSS selector of the title.
	 * @param string $eyebrow_selector Eyebrow selector.
	 */
	protected function add_heading_style_controls( $selector = '{{WRAPPER}} .ab-section-head h2', $eyebrow_selector = '{{WRAPPER}} .ab-eyebrow' ) {
		$this->start_controls_section(
			'style_heading',
			array(
				'label' => esc_html__( 'Section heading', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'heading_align',
			array(
				'label'     => esc_html__( 'Alignment', 'abidar' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array( 'title' => esc_html__( 'Start', 'abidar' ), 'icon' => 'eicon-text-align-left' ),
					'center' => array( 'title' => esc_html__( 'Center', 'abidar' ), 'icon' => 'eicon-text-align-center' ),
					'end'    => array( 'title' => esc_html__( 'End', 'abidar' ), 'icon' => 'eicon-text-align-right' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ab-section-head' => 'text-align: {{VALUE}}; margin-inline: {{VALUE === "center" ? "auto" : "0"}};',
					'{{WRAPPER}} .ab-eyebrow'      => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'label'    => esc_html__( 'Title typography', 'abidar' ),
				'selector' => $selector,
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => esc_html__( 'Title colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $selector => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => esc_html__( 'Eyebrow colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $eyebrow_selector => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Background + spacing controls common to every section.
	 *
	 * @param string $wrapper Selector of the section wrapper.
	 */
	protected function add_section_style_controls( $wrapper = '{{WRAPPER}} .ab-section' ) {
		$this->start_controls_section(
			'style_section',
			array(
				'label' => esc_html__( 'Section', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'section_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => $wrapper,
			)
		);

		$this->add_responsive_control(
			'section_padding',
			array(
				'label'      => esc_html__( 'Padding', 'abidar' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%' ),
				'selectors'  => array( $wrapper => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'section_margin',
			array(
				'label'      => esc_html__( 'Margin', 'abidar' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%' ),
				'selectors'  => array( $wrapper => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'section_border',
				'selector' => $wrapper,
			)
		);

		$this->add_responsive_control(
			'section_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'abidar' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( $wrapper => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'section_shadow',
				'selector' => $wrapper,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register a repeater of "item" controls used by several widgets.
	 *
	 * @param string $name      Control name.
	 * @param array  $fields    Field definitions (key => [label, type, default]).
	 * @param array  $default   Default items.
	 * @param string $label     Control label.
	 */
	protected function add_items_repeater( $name, $fields, $default = array(), $label = '' ) {
		$repeater = new \Elementor\Repeater();

		foreach ( $fields as $key => $field ) {
			$type = isset( $field['type'] ) ? $field['type'] : Controls_Manager::TEXT;

			$args = array(
				'label'   => $field['label'],
				'type'    => $type,
				'default' => isset( $field['default'] ) ? $field['default'] : '',
			);

			if ( Controls_Manager::TEXTAREA === $type || Controls_Manager::WYSIWYG === $type ) {
				$args['rows'] = 3;
			}

			if ( isset( $field['options'] ) ) {
				$args['options'] = $field['options'];
			}

			if ( isset( $field['label_block'] ) ) {
				$args['label_block'] = $field['label_block'];
			}

			if ( isset( $field['selectors'] ) ) {
				$args['selectors'] = $field['selectors'];
			}

			if ( isset( $field['condition'] ) ) {
				$args['condition'] = $field['condition'];
			}

			$repeater->add_control( $key, $args );
		}

		$this->add_control(
			$name,
			array(
				'label'       => $label ? $label : esc_html__( 'Items', 'abidar' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $default,
				'title_field' => '{{{ ' . ( isset( $fields['title'] ) ? 'title' : array_key_first( $fields ) ) . ' }}}',
			)
		);
	}
}
