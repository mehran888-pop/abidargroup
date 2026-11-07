<?php
/**
 * Elementor widget: Process steps.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Process
 */
class Abidar_Widget_Process extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-process';
	}

	public function get_title() {
		return esc_html__( 'Process steps', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-number-field';
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
				'eyebrow' => 'process_eyebrow',
				'title'   => 'process_title',
			)
		);

		$this->add_control(
			'dark',
			array(
				'label'   => esc_html__( 'Dark section', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Icon', 'abidar' ),
				'type'    => Controls_Manager::SELECT,
				'options' => abidar_icon_options(),
				'default' => 'search',
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Step', 'abidar' ),
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label' => esc_html__( 'Description', 'abidar' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 3,
			)
		);

		$this->add_control(
			'steps',
			array(
				'label'   => esc_html__( 'Steps', 'abidar' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array( 'icon' => 'search', 'title' => $this->copy_field( 'step1_title', 'process_step1' ), 'text' => $this->copy_field( 'step1_text', 'process_step1_t' ) ),
					array( 'icon' => 'ruler', 'title' => $this->copy_field( 'step2_title', 'process_step2' ), 'text' => $this->copy_field( 'step2_text', 'process_step2_t' ) ),
					array( 'icon' => 'excavator', 'title' => $this->copy_field( 'step3_title', 'process_step3' ), 'text' => $this->copy_field( 'step3_text', 'process_step3_t' ) ),
					array( 'icon' => 'handshake', 'title' => $this->copy_field( 'step4_title', 'process_step4' ), 'text' => $this->copy_field( 'step4_text', 'process_step4_t' ) ),
				),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->add_control(
			'show_numbers',
			array(
				'label'   => esc_html__( 'Show numbers instead of icons', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_steps',
			array(
				'label' => esc_html__( 'Steps', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'dot_bg',
			array(
				'label'     => esc_html__( 'Dot background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-step__dot' => 'background: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'dot_color',
			array(
				'label'     => esc_html__( 'Dot colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-step__dot' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-step__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-step__text' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'line_color',
			array(
				'label'     => esc_html__( 'Connector line', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-process::before' => 'background: {{VALUE}}; height: 1px;' ),
			)
		);

		$this->end_controls_section();

		$this->add_heading_style_controls();
		$this->add_section_style_controls();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$s = $this->localize_settings( $this->get_settings_for_display() );

		// Keep an untouched step list trilingual.
		if ( is_array( $s['steps'] ) && 4 === count( $s['steps'] ) ) {
			$untouched = true;
			foreach ( $s['steps'] as $i => $step ) {
				$number  = $i + 1;
				$titles  = array();
				foreach ( array( 'fa', 'en', 'zh' ) as $lang ) {
					$titles[] = Abidar_Strings::get( 'process_step' . $number, $lang );
				}
				if ( ! in_array( $step['title'], $titles, true ) ) {
					$untouched = false;
					break;
				}
			}

			if ( $untouched ) {
				foreach ( $s['steps'] as $i => $step ) {
					$number = $i + 1;
					$s['steps'][ $i ]['title'] = abidar_copy( 'process_step' . $number );
					$s['steps'][ $i ]['text']  = abidar_copy( 'process_step' . $number . '_t' );
				}
			}
		}
		?>
		<section class="ab-section <?php echo 'yes' === $s['dark'] ? 'ab-section--dark' : ''; ?>">
			<div class="ab-container">
				<div class="ab-section-head ab-section-head--center ab-reveal">
					<?php if ( $s['head_eyebrow'] ) : ?>
						<span class="ab-eyebrow"><?php echo esc_html( $s['head_eyebrow'] ); ?></span>
					<?php endif; ?>
					<h2><?php echo esc_html( $s['head_title'] ); ?></h2>
					<?php if ( $s['head_text'] ) : ?>
						<p><?php echo esc_html( $s['head_text'] ); ?></p>
					<?php endif; ?>
				</div>

				<div class="ab-process ab-stagger">
					<?php foreach ( (array) $s['steps'] as $i => $step ) : ?>
						<div class="ab-step ab-reveal" style="--ab-i:<?php echo (int) $i; ?>">
							<span class="ab-step__dot">
								<?php if ( 'yes' === $s['show_numbers'] ) : ?>
									<?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?>
								<?php else : ?>
									<?php abidar_icon( $step['icon'], true, array( 'width' => 22, 'height' => 22 ) ); ?>
								<?php endif; ?>
							</span>
							<h3 class="ab-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
							<p class="ab-step__text"><?php echo esc_html( $step['text'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
