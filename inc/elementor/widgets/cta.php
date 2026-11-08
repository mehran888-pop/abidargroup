<?php
/**
 * Elementor widget: Call to action.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Cta
 */
class Abidar_Widget_Cta extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-cta';
	}

	public function get_title() {
		return esc_html__( 'Call to action', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
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
			'eyebrow',
			array(
				'label'   => esc_html__( 'Eyebrow', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $this->copy_field( 'eyebrow', 'cta_eyebrow' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'abidar' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => $this->copy_field( 'title', 'cta_title' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => esc_html__( 'Description', 'abidar' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => $this->copy_field( 'text', 'cta_text' ),
			)
		);

		$this->add_control(
			'btn_text',
			array(
				'label'   => esc_html__( 'Button label', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $this->copy_field( 'btn_text', 'cta_button' ),
			)
		);

		$this->add_control(
			'btn_url',
			array(
				'label'   => esc_html__( 'Button link', 'abidar' ),
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '#contact' ),
			)
		);

		$this->add_control(
			'phone',
			array(
				'label'   => esc_html__( 'Phone number', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => abidar_option( 'topbar_phone', '+98 21 8800 1234' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Appearance', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bg_image',
			array(
				'label' => esc_html__( 'Background image', 'abidar' ),
				'type'  => Controls_Manager::MEDIA,
				'default' => array( 'url' => ABIDAR_URI . '/assets/img/cta.jpg' ),
			)
		);

		$this->add_control(
			'overlay_from',
			array(
				'label'     => esc_html__( 'Overlay colour (start)', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(8,32,29,0.96)',
				'selectors' => array( '{{WRAPPER}} .ab-cta::after' => 'background: linear-gradient(100deg, {{VALUE}} 10%, rgba(8,32,29,0.66) 62%, rgba(200,145,47,0.32) 100%);' ),
			)
		);

		$this->add_control(
			'padding',
			array(
				'label'      => esc_html__( 'Inner padding', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array( 'px' => array( 'min' => 20, 'max' => 140 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-cta' => 'padding: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'radius',
			array(
				'label'      => esc_html__( 'Radius', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-cta' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'     => esc_html__( 'Title typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-cta__title',
			)
		);

		$this->end_controls_section();

		$this->add_section_style_controls();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$s = $this->localize_settings( $this->get_settings_for_display() );
		?>
		<section class="ab-section">
			<div class="ab-container">
				<div class="ab-cta ab-reveal">
					<?php if ( ! empty( $s['bg_image']['url'] ) ) : ?>
						<div class="ab-cta__bg">
							<img src="<?php echo esc_url( $s['bg_image']['url'] ); ?>" alt="" loading="lazy">
						</div>
					<?php endif; ?>

					<div class="ab-cta__inner">
						<div>
							<?php if ( $s['eyebrow'] ) : ?>
								<span class="ab-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
							<?php endif; ?>
							<h2 class="ab-cta__title"><?php echo esc_html( $s['title'] ); ?></h2>
							<?php if ( $s['text'] ) : ?>
								<p class="ab-cta__text"><?php echo esc_html( $s['text'] ); ?></p>
							<?php endif; ?>
						</div>
						<div class="ab-cta__actions">
							<a class="ab-btn ab-btn--accent ab-btn--lg" href="<?php echo esc_url( $s['btn_url']['url'] ?? '#contact' ); ?>">
								<span><?php echo esc_html( $s['btn_text'] ); ?></span>
								<?php abidar_icon( 'arrow-right', true, array( 'width' => 17, 'height' => 17 ) ); ?>
							</a>
							<?php if ( $s['phone'] ) : ?>
								<a class="ab-cta__phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $s['phone'] ) ); ?>">
									<?php abidar_icon( 'phone', true, array( 'width' => 15, 'height' => 15 ) ); ?>
									<span><?php echo esc_html( abidar_copy( 'cta_phone' ) ); ?></span>
									<strong dir="ltr"><?php echo esc_html( $s['phone'] ); ?></strong>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
