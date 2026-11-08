<?php
/**
 * Elementor widget: Page title & breadcrumb.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Page_Title
 */
class Abidar_Widget_Page_Title extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-page-title';
	}

	public function get_title() {
		return esc_html__( 'Page title & breadcrumb', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-page-title';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => esc_html__( 'Content', 'abidar' ) )
		);

		$this->add_control(
			'use_page_title',
			array(
				'label'   => esc_html__( 'Use the current page title', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'custom_title',
			array(
				'label'     => esc_html__( 'Custom title', 'abidar' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'use_page_title' => '' ),
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label' => esc_html__( 'Subtitle', 'abidar' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 2,
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label' => esc_html__( 'Eyebrow', 'abidar' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'show_breadcrumb',
			array(
				'label'   => esc_html__( 'Show breadcrumb', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'bg',
			array(
				'label'   => esc_html__( 'Background image', 'abidar' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => ABIDAR_URI . '/assets/img/pattern-dark.jpg' ),
			)
		);

		$this->add_control(
			'height',
			array(
				'label'      => esc_html__( 'Minimum height', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array( 'px' => array( 'min' => 180, 'max' => 700 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-page-hero' => 'min-height: {{SIZE}}{{UNIT}}; display:flex; align-items:center;' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_title',
			array(
				'label' => esc_html__( 'Title', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-page-hero__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-page-hero__title',
			)
		);

		$this->add_control(
			'sub_color',
			array(
				'label'     => esc_html__( 'Description colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ab-page-hero__sub' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_typography',
				'label'    => esc_html__( 'Description typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-page-hero__sub',
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => esc_html__( 'Eyebrow colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ab-eyebrow' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'breadcrumb_color',
			array(
				'label'     => esc_html__( 'Breadcrumb colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ab-breadcrumb li, {{WRAPPER}} .ab-breadcrumb a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'breadcrumb_typography',
				'label'    => esc_html__( 'Breadcrumb typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-breadcrumb ol',
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'     => esc_html__( 'Overlay colour (start)', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .ab-page-hero' => 'background-image: linear-gradient(105deg, {{VALUE}} 18%, rgba(8,22,21,0.74) 60%, rgba(8,22,21,0.5)), var(--ab-hero-bg, none);',
				),
			)
		);

		$this->add_control(
			'overlay_strength',
			array(
				'label'      => esc_html__( 'Overlay strength', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array( '%' => array( 'min' => 0, 'max' => 100 ) ),
				'default'    => array( 'size' => 40, 'unit' => '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ab-page-hero::after' => 'opacity: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->add_section_style_controls( '{{WRAPPER}} .ab-page-hero' );
	}

	/**
	 * Render.
	 */
	protected function render() {
		$s = $this->localize_settings( $this->get_settings_for_display() );

		$title = 'yes' === $s['use_page_title'] ? get_the_title() : $s['custom_title'];
		$title = $title ? $title : get_bloginfo( 'name' );

		abidar_page_hero(
			array(
				'title'      => $title,
				'subtitle'   => $s['subtitle'],
				'eyebrow'    => $s['eyebrow'],
				'breadcrumb' => 'yes' === $s['show_breadcrumb'],
				'bg'         => ! empty( $s['bg']['url'] ) ? $s['bg']['url'] : '',
			)
		);
	}
}
