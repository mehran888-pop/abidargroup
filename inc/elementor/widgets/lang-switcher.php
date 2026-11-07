<?php
/**
 * Elementor widget: Language switcher (FA / EN / ZH).
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Lang_Switcher
 */
class Abidar_Widget_Lang_Switcher extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-lang-switcher';
	}

	public function get_title() {
		return esc_html__( 'Language switcher', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-globe';
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
			'label',
			array(
				'label'   => esc_html__( 'Caption', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => esc_html__( 'Style', 'abidar' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => array(
					'inline'   => esc_html__( 'Inline pills (FA / EN / ZH)', 'abidar' ),
					'dropdown' => esc_html__( 'Dropdown', 'abidar' ),
				),
			)
		);

		$this->add_control(
			'show_flags',
			array(
				'label'   => esc_html__( 'Show flags', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'abidar' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'center' => array( 'title' => esc_html__( 'Center', 'abidar' ), 'icon' => 'eicon-text-align-center' ),
					'start'  => array( 'title' => esc_html__( 'Start', 'abidar' ), 'icon' => 'eicon-text-align-left' ),
					'end'    => array( 'title' => esc_html__( 'End', 'abidar' ), 'icon' => 'eicon-text-align-right' ),
				),
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .ab-lang' => 'justify-content: {{VALUE}}; display:flex;' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_items',
			array(
				'label' => esc_html__( 'Items', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'item_color',
			array(
				'label'     => esc_html__( 'Text colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-lang__item a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'active_bg',
			array(
				'label'     => esc_html__( 'Active background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-lang__item.is-current a' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'active_color',
			array(
				'label'     => esc_html__( 'Active colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-lang__item.is-current a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_typography',
				'selector' => '{{WRAPPER}} .ab-lang__item a',
			)
		);

		$this->add_control(
			'item_radius',
			array(
				'label'      => esc_html__( 'Item radius', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-lang__item a' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->add_section_style_controls();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$s = $this->get_settings_for_display();

		echo '<div class="ab-section ab-section--tight">';
		ab_render_language_switcher(
			array(
				'title'  => $s['label'],
				'style'  => $s['style'],
				'flags'  => 'yes' === $s['show_flags'],
				'center' => 'center' === $s['align'],
			)
		);
		echo '</div>';
	}
}
