<?php
/**
 * Elementor widget: Animated counters.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Counters
 */
class Abidar_Widget_Counters extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-counters';
	}

	public function get_title() {
		return esc_html__( 'Animated counters', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-counter';
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
				'eyebrow' => 'numbers_eyebrow',
				'title'   => 'numbers_title',
			)
		);

		$this->add_control(
			'dark',
			array(
				'label'   => esc_html__( 'Dark section', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'bg_image',
			array(
				'label'     => esc_html__( 'Background texture', 'abidar' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array( 'url' => ABIDAR_URI . '/assets/img/pattern-dark.jpg' ),
				'condition' => array( 'dark' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'     => esc_html__( 'Columns', 'abidar' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'options'   => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
				'selectors' => array( '{{WRAPPER}} .ab-counters' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Icon', 'abidar' ),
				'type'    => Controls_Manager::SELECT,
				'options' => abidar_icon_options(),
				'default' => 'award',
			)
		);

		$repeater->add_control(
			'number',
			array(
				'label'   => esc_html__( 'Number', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '248',
			)
		);

		$repeater->add_control(
			'suffix',
			array(
				'label'   => esc_html__( 'Suffix', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '+',
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => esc_html__( 'Label', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Successful projects', 'abidar' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => esc_html__( 'Counters', 'abidar' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $this->default_items(),
				'title_field' => '{{{ number }}}{{{ suffix }}} — {{{ label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_number',
			array(
				'label' => esc_html__( 'Numbers', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'number_typography',
				'label'     => esc_html__( 'Number typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-counter__num',
			)
		);

		$this->add_control(
			'number_color',
			array(
				'label'     => esc_html__( 'Number colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-counter__num' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Label colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-counter__label' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-counter__icon' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'cell_bg',
			array(
				'label'     => esc_html__( 'Cell background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-counter' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'cell_radius',
			array(
				'label'      => esc_html__( 'Cell radius', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-counter' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->add_heading_style_controls();
		$this->add_section_style_controls();
	}

	/**
	 * Default counters, localised for the active language.
	 *
	 * @return array
	 */
	protected function default_items() {
		$out = array();

		foreach ( abidar_stats_data() as $stat ) {
			$out[] = array(
				'icon'   => $stat['icon'],
				'number' => $stat['number'],
				'suffix' => $stat['suffix'],
				'label'  => abidar_localized( $stat, 'label' ),
			);
		}

		return $out;
	}

	/**
	 * Are the saved counters still the untouched defaults?
	 *
	 * @param array $items Saved items.
	 * @return bool
	 */
	protected function items_are_default( $items ) {
		if ( ! is_array( $items ) || count( $items ) !== count( abidar_stats_data() ) ) {
			return false;
		}

		$known = array();
		foreach ( abidar_stats_data() as $stat ) {
			foreach ( $stat['label'] as $translation ) {
				$known[] = $translation;
			}
		}

		foreach ( $items as $item ) {
			if ( ! in_array( $item['label'], $known, true ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Render.
	 */
	protected function render() {
		$s    = $this->localize_settings( $this->get_settings_for_display() );

		if ( $this->items_are_default( $s['items'] ) ) {
			$s['items'] = $this->default_items();
		}

		$dark = 'yes' === $s['dark'];
		$bg   = ! empty( $s['bg_image']['url'] ) ? $s['bg_image']['url'] : ABIDAR_URI . '/assets/img/pattern-dark.jpg';
		?>
		<section class="ab-section <?php echo $dark ? 'ab-section--dark ab-section--pattern' : ''; ?>" style="--ab-pattern:url('<?php echo esc_url( $bg ); ?>')">
			<div class="ab-container">
				<div class="ab-section-head ab-section-head--split ab-reveal">
					<div>
						<?php if ( $s['head_eyebrow'] ) : ?>
							<span class="ab-eyebrow"><?php echo esc_html( $s['head_eyebrow'] ); ?></span>
						<?php endif; ?>
						<h2><?php echo esc_html( $s['head_title'] ); ?></h2>
					</div>
				</div>

				<div class="ab-counters <?php echo $dark ? '' : 'ab-counters--light'; ?> ab-stagger">
					<?php foreach ( (array) $s['items'] as $i => $item ) : ?>
						<div class="ab-counter ab-reveal" style="--ab-i:<?php echo (int) $i; ?>">
							<span class="ab-counter__icon"><?php abidar_icon( $item['icon'], true, array( 'width' => 26, 'height' => 26 ) ); ?></span>
							<span class="ab-counter__num">
								<span data-counter="<?php echo esc_attr( $item['number'] ); ?>"><?php echo esc_html( $item['number'] ); ?></span>
								<span><?php echo esc_html( $item['suffix'] ); ?></span>
							</span>
							<span class="ab-counter__label"><?php echo esc_html( $item['label'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
