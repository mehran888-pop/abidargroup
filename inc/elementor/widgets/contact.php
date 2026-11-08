<?php
/**
 * Elementor widget: Contact block.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Contact
 */
class Abidar_Widget_Contact extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-contact';
	}

	public function get_title() {
		return esc_html__( 'Contact block', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-mail';
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
				'eyebrow' => 'contact_eyebrow',
				'title'   => 'contact_title',
				'text'    => 'contact_text',
			)
		);

		$this->add_control(
			'address',
			array(
				'label'   => esc_html__( 'Address', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => abidar_option( 'address', '' ) ? abidar_option( 'address', '' ) : abidar_copy( 'address' ),
			)
		);

		$this->add_control(
			'phone',
			array(
				'label'   => esc_html__( 'Phone', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => abidar_option( 'topbar_phone', '+98 21 8800 1234' ),
			)
		);

		$this->add_control(
			'email',
			array(
				'label'   => esc_html__( 'Email', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => abidar_option( 'topbar_email', 'info@abidargroup.com' ),
			)
		);

		$this->add_control(
			'hours',
			array(
				'label'   => esc_html__( 'Working hours', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => abidar_copy( 'hours' ),
			)
		);

		$this->add_control(
			'show_form',
			array(
				'label'     => esc_html__( 'Show form', 'abidar' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_map',
			array(
				'label'   => esc_html__( 'Show map', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'map_url',
			array(
				'label'       => esc_html__( 'Map embed URL', 'abidar' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => 'https://www.openstreetmap.org/export/embed.html?bbox=51.38%2C35.72%2C51.44%2C35.76&layer=mapnik',
				'condition'   => array( 'show_map' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_form',
			array(
				'label' => esc_html__( 'Form', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => esc_html__( 'Label typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-contact-row__label',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typography',
				'label'    => esc_html__( 'Value typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-contact-row__value',
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-contact-row__icon' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'form_bg',
			array(
				'label'     => esc_html__( 'Form background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-form' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'input_bg',
			array(
				'label'     => esc_html__( 'Input background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-field input, {{WRAPPER}} .ab-field textarea' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'btn_bg',
			array(
				'label'     => esc_html__( 'Button background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-form .ab-btn' => 'background: {{VALUE}}; border-color: {{VALUE}};' ),
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
		?>
		<section class="ab-section">
			<div class="ab-container">
				<div class="ab-contact">
					<div class="ab-reveal">
						<?php if ( $s['head_eyebrow'] ) : ?>
							<span class="ab-eyebrow"><?php echo esc_html( $s['head_eyebrow'] ); ?></span>
						<?php endif; ?>
						<h2><?php echo esc_html( $s['head_title'] ); ?></h2>
						<?php if ( $s['head_text'] ) : ?>
							<p><?php echo esc_html( $s['head_text'] ); ?></p>
						<?php endif; ?>

						<div style="margin-top:2rem">
							<?php
							$rows = array(
								'pin'   => array( abidar_copy( 'address_label' ), $s['address'], '' ),
								'phone' => array( abidar_copy( 'phone_label' ), $s['phone'], 'tel:' . preg_replace( '/[^0-9+]/', '', $s['phone'] ) ),
								'mail'  => array( abidar_copy( 'email_label' ), $s['email'], 'mailto:' . sanitize_email( $s['email'] ) ),
								'clock' => array( abidar_copy( 'hours_label' ), $s['hours'], '' ),
							);
							foreach ( $rows as $icon => $row ) :
								if ( ! $row[1] ) {
									continue;
								}
								?>
								<div class="ab-contact-row">
									<span class="ab-contact-row__icon"><?php abidar_icon( $icon, true ); ?></span>
									<span class="ab-contact-row__body">
										<span class="ab-contact-row__label"><?php echo esc_html( $row[0] ); ?></span>
										<?php if ( $row[2] ) : ?>
											<a class="ab-contact-row__value" href="<?php echo esc_url( $row[2] ); ?>"><?php echo esc_html( $row[1] ); ?></a>
										<?php else : ?>
											<span class="ab-contact-row__value"><?php echo esc_html( $row[1] ); ?></span>
										<?php endif; ?>
									</span>
								</div>
							<?php endforeach; ?>
						</div>

						<?php if ( 'yes' === $s['show_map'] && $s['map_url'] ) : ?>
							<div class="ab-map" style="margin-top:2rem">
								<iframe title="<?php esc_attr_e( 'Office location', 'abidar' ); ?>" loading="lazy" src="<?php echo esc_url( $s['map_url'] ); ?>"></iframe>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( 'yes' === $s['show_form'] ) : ?>
						<div class="ab-reveal">
							<?php ab_render_contact_form(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
