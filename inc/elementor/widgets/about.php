<?php
/**
 * Elementor widget: About split.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_About
 */
class Abidar_Widget_About extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-about';
	}

	public function get_title() {
		return esc_html__( 'About split', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-image-box';
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
				'eyebrow' => 'about_eyebrow',
				'title'   => 'about_title',
				'text'    => 'about_text',
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Image', 'abidar' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => ABIDAR_URI . '/assets/img/about.jpg' ),
			)
		);

		$this->add_control(
			'points',
			array(
				'label'       => esc_html__( 'Checklist', 'abidar' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'default'     => implode( "\n", array( abidar_copy( 'about_point1' ), abidar_copy( 'about_point2' ), abidar_copy( 'about_point3' ) ) ),
				'description' => esc_html__( 'One item per line.', 'abidar' ),
			)
		);

		$this->add_control(
			'badge_num',
			array(
				'label'     => esc_html__( 'Badge number', 'abidar' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => abidar_copy( 'about_badge_num' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'badge_text',
			array(
				'label'   => esc_html__( 'Badge text', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => abidar_copy( 'about_badge_txt' ),
			)
		);

		$this->add_control(
			'btn_text',
			array(
				'label'   => esc_html__( 'Button label', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => abidar_copy( 'learn_more' ),
			)
		);

		$this->add_control(
			'btn_url',
			array(
				'label' => esc_html__( 'Button link', 'abidar' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'sig_name',
			array(
				'label'   => esc_html__( 'Signature name', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => abidar_copy( 'about_sig' ),
			)
		);

		$this->add_control(
			'sig_role',
			array(
				'label'   => esc_html__( 'Signature role', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => abidar_copy( 'about_sig_role' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_image',
			array(
				'label' => esc_html__( 'Image', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'lead_typography',
				'label'    => esc_html__( 'Body typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-lead, {{WRAPPER}} .ab-checklist__text',
			)
		);

		$this->add_control(
			'sig_color',
			array(
				'label'     => esc_html__( 'Signature colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-signature__name' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sig_typography',
				'label'    => esc_html__( 'Signature typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-signature__name',
			)
		);

		$this->add_control(
			'image_radius',
			array(
				'label'      => esc_html__( 'Radius', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-about__media img' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'badge_bg',
			array(
				'label'     => esc_html__( 'Badge background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-badge' => 'background: {{VALUE}};' ),
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
		$s      = $this->localize_settings( $this->get_settings_for_display() );
		$points = array_filter( array_map( 'trim', explode( "\n", (string) $s['points'] ) ) );
		?>
		<section class="ab-section">
			<div class="ab-container">
				<div class="ab-about">
					<div class="ab-about__media ab-reveal">
						<?php if ( ! empty( $s['image']['url'] ) ) : ?>
							<img src="<?php echo esc_url( $s['image']['url'] ); ?>" alt="<?php echo esc_attr( $s['head_title'] ); ?>" loading="lazy">
						<?php endif; ?>
						<?php if ( $s['badge_num'] ) : ?>
							<div class="ab-badge">
								<span class="ab-badge__num"><?php echo esc_html( $s['badge_num'] ); ?></span>
								<span class="ab-badge__text"><?php echo esc_html( $s['badge_text'] ); ?></span>
							</div>
						<?php endif; ?>
					</div>

					<div class="ab-reveal">
						<?php if ( $s['head_eyebrow'] ) : ?>
							<span class="ab-eyebrow"><?php echo esc_html( $s['head_eyebrow'] ); ?></span>
						<?php endif; ?>
						<h2><?php echo esc_html( $s['head_title'] ); ?></h2>
						<p class="ab-lead"><?php echo esc_html( $s['head_text'] ); ?></p>

						<?php if ( $points ) : ?>
							<ul class="ab-checklist">
								<?php foreach ( $points as $point ) : ?>
									<li>
										<span class="ab-checklist__icon"><?php abidar_icon( 'check', true, array( 'width' => 13, 'height' => 13 ) ); ?></span>
										<span><?php echo esc_html( $point ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php if ( $s['btn_text'] ) : ?>
							<a class="ab-btn ab-btn--primary" href="<?php echo esc_url( $s['btn_url']['url'] ?? abidar_page_url( 'about' ) ); ?>">
								<span><?php echo esc_html( $s['btn_text'] ); ?></span>
								<?php abidar_icon( 'arrow-right', true, array( 'width' => 16, 'height' => 16 ) ); ?>
							</a>
						<?php endif; ?>

						<?php if ( $s['sig_name'] ) : ?>
							<div class="ab-signature">
								<span class="ab-quote__avatar">RA</span>
								<span>
									<span class="ab-signature__name"><?php echo esc_html( $s['sig_name'] ); ?></span><br>
									<span class="ab-signature__role"><?php echo esc_html( $s['sig_role'] ); ?></span>
								</span>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
