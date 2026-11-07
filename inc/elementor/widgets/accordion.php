<?php
/**
 * Elementor widget: FAQ accordion.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Accordion
 */
class Abidar_Widget_Accordion extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-accordion';
	}

	public function get_title() {
		return esc_html__( 'FAQ accordion', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-accordion';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => esc_html__( 'Content', 'abidar' ) )
		);

		$this->add_heading_controls(
			'head',
			array(),
			array(
				'eyebrow' => 'faq_eyebrow',
				'title'   => 'faq_title',
			)
		);

		$this->add_control(
			'single',
			array(
				'label'   => esc_html__( 'Only one open at a time', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'question',
			array(
				'label'   => esc_html__( 'Question', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Question', 'abidar' ),
			)
		);

		$repeater->add_control(
			'answer',
			array(
				'label' => esc_html__( 'Answer', 'abidar' ),
				'type'  => Controls_Manager::WYSIWYG,
				'rows'  => 4,
			)
		);

		$this->add_control(
			'items',
			array(
				'label'   => esc_html__( 'Questions', 'abidar' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default'     => $this->default_items(),
				'title_field' => '{{{ question }}}',
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
			'item_bg',
			array(
				'label'     => esc_html__( 'Item background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-accordion__item' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'q_color',
			array(
				'label'     => esc_html__( 'Question colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-accordion__btn' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'a_color',
			array(
				'label'     => esc_html__( 'Answer colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-accordion__panel-inner' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'q_typography',
				'label'    => esc_html__( 'Question typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-accordion__btn',
			)
		);

		$this->add_control(
			'item_radius',
			array(
				'label'      => esc_html__( 'Radius', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-accordion__item' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->add_heading_style_controls();
		$this->add_section_style_controls();
	}

	/**
	 * Default FAQ items, localised for the active language.
	 *
	 * @return array
	 */
	protected function default_items() {
		$out = array();

		for ( $i = 1; $i <= 4; $i++ ) {
			$out[] = array(
				'question' => abidar_copy( 'faq_q' . $i ),
				'answer'   => abidar_copy( 'faq_a' . $i ),
			);
		}

		return $out;
	}

	/**
	 * Are the saved questions still the untouched defaults?
	 *
	 * @param array $items Saved items.
	 * @return bool
	 */
	protected function items_are_default( $items ) {
		if ( ! is_array( $items ) || 4 !== count( $items ) ) {
			return false;
		}

		$known = array();
		for ( $i = 1; $i <= 4; $i++ ) {
			foreach ( Abidar_Strings::dictionary()[ 'faq_q' . $i ] as $translation ) {
				$known[] = $translation;
			}
		}

		foreach ( $items as $item ) {
			if ( ! in_array( $item['question'], $known, true ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Render.
	 */
	protected function render() {
		$s = $this->localize_settings( $this->get_settings_for_display() );

		if ( $this->items_are_default( $s['items'] ) ) {
			$s['items'] = $this->default_items();
		}
		?>
		<section class="ab-section ab-section--surface">
			<div class="ab-container">
				<div class="ab-section-head ab-section-head--center ab-reveal">
					<?php if ( $s['head_eyebrow'] ) : ?>
						<span class="ab-eyebrow"><?php echo esc_html( $s['head_eyebrow'] ); ?></span>
					<?php endif; ?>
					<h2><?php echo esc_html( $s['head_title'] ); ?></h2>
				</div>

				<div class="ab-accordion" data-single="<?php echo 'yes' === $s['single'] ? 'true' : 'false'; ?>">
					<?php foreach ( (array) $s['items'] as $i => $item ) : ?>
						<div class="ab-accordion__item <?php echo 0 === $i ? 'is-open' : ''; ?>">
							<button class="ab-accordion__btn" type="button" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>">
								<span><?php echo esc_html( $item['question'] ); ?></span>
								<span class="ab-accordion__icon"><?php abidar_icon( 'plus', true, array( 'width' => 14, 'height' => 14 ) ); ?></span>
							</button>
							<div class="ab-accordion__panel" <?php echo 0 === $i ? 'style="max-height:400px"' : ''; ?>>
								<div class="ab-accordion__panel-inner"><?php echo wp_kses_post( $item['answer'] ); ?></div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
