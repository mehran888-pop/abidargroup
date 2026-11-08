<?php
/**
 * Widget: Announcement / contact top bar.
 *
 * A thin bar above the header: announcement text, phone, email, working hours,
 * social icons and the language switcher. Useful on its own inside an Elementor
 * header template.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

/**
 * Class Abidar_Widget_Topbar
 */
class Abidar_Widget_Topbar extends Abidar_Widget_Base {

	/**
	 * Widget name.
	 */
	public function get_name() {
		return 'ab-topbar';
	}

	/**
	 * Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Abidar top bar', 'abidar' );
	}

	/**
	 * Widget icon.
	 */
	public function get_icon() {
		return 'eicon-bar';
	}

	/**
	 * Keywords.
	 */
	public function get_keywords() {
		return array( 'topbar', 'top bar', 'announcement', 'contact', 'notice' );
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Top bar', 'abidar' ),
			)
		);

		$this->add_control(
			'note',
			array(
				'label'   => esc_html__( 'Note', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $this->copy_field( 'note', 'topbar_note' ),
			)
		);

		$this->add_control(
			'note_icon',
			array(
				'label'   => esc_html__( 'Note icon', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'shield',
			)
		);

		$this->add_control(
			'show_phone',
			array(
				'label'     => esc_html__( 'Show phone', 'abidar' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_email',
			array(
				'label'   => esc_html__( 'Show email', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_hours',
			array(
				'label'   => esc_html__( 'Show working hours', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_social',
			array(
				'label'   => esc_html__( 'Show social icons', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_lang',
			array(
				'label'   => esc_html__( 'Show language switcher', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'dismissible',
			array(
				'label'       => esc_html__( 'Dismissible', 'abidar' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Visitors can close the bar for the session.', 'abidar' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_bar',
			array(
				'label' => esc_html__( 'Bar', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bg',
			array(
				'label'     => esc_html__( 'Background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-topbar' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => esc_html__( 'Text colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-topbar' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Link colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-topbar a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'accent_color',
			array(
				'label'     => esc_html__( 'Icon colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-topbar svg' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'label'    => esc_html__( 'Typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-topbar',
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => esc_html__( 'Padding', 'abidar' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'rem' ),
				'selectors'  => array( '{{WRAPPER}} .ab-topbar__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-topbar__right' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Content alignment', 'abidar' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'space-between' => array( 'title' => esc_html__( 'Space between', 'abidar' ), 'icon' => 'eicon-justify-space-between-h' ),
					'center'        => array( 'title' => esc_html__( 'Center', 'abidar' ), 'icon' => 'eicon-text-align-center' ),
					'flex-start'    => array( 'title' => esc_html__( 'Start', 'abidar' ), 'icon' => 'eicon-text-align-left' ),
				),
				'selectors' => array( '{{WRAPPER}} .ab-topbar__inner' => 'justify-content: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'shadow',
				'label'    => esc_html__( 'Box shadow', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-topbar',
			)
		);

		$this->end_controls_section();

		$this->add_section_style_controls( '{{WRAPPER}} .ab-topbar-wrap' );
	}

	/**
	 * Render.
	 */
	protected function render() {
		$s       = $this->localize_settings( $this->get_settings_for_display() );
		$lang    = Abidar_Multilingual::instance();
		$phone   = abidar_option( 'topbar_phone', '' );
		$email   = abidar_option( 'topbar_email', '' );
		$hours   = abidar_option( 'hours', '' );
		$classes = 'ab-topbar';
		?>
		<div class="ab-topbar-wrap"<?php echo $s['dismissible'] ? ' data-dismissible' : ''; ?>>
			<div class="<?php echo esc_attr( $classes ); ?>">
				<div class="ab-container ab-topbar__inner">
					<div class="ab-topbar__left">
						<?php if ( $s['note'] ) : ?>
							<span class="ab-topbar__note">
								<?php
								if ( $s['note_icon'] ) {
									abidar_icon( $s['note_icon'], true, array( 'width' => 14, 'height' => 14 ) );
								}
								echo esc_html( $s['note'] );
								?>
							</span>
						<?php endif; ?>
					</div>

					<div class="ab-topbar__right">
						<?php if ( 'yes' === $s['show_phone'] && $phone ) : ?>
							<a class="ab-topbar__link" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
								<?php abidar_icon( 'phone', true, array( 'width' => 14, 'height' => 14 ) ); ?>
								<span dir="ltr"><?php echo esc_html( $phone ); ?></span>
							</a>
						<?php endif; ?>

						<?php if ( 'yes' === $s['show_email'] && $email ) : ?>
							<a class="ab-topbar__link" href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>">
								<?php abidar_icon( 'mail', true, array( 'width' => 14, 'height' => 14 ) ); ?>
								<span><?php echo esc_html( antispambot( $email ) ); ?></span>
							</a>
						<?php endif; ?>

						<?php if ( 'yes' === $s['show_hours'] && $hours ) : ?>
							<span class="ab-topbar__link">
								<?php abidar_icon( 'clock', true, array( 'width' => 14, 'height' => 14 ) ); ?>
								<span><?php echo esc_html( $hours ); ?></span>
							</span>
						<?php endif; ?>

						<?php if ( 'yes' === $s['show_social'] ) : ?>
							<span class="ab-topbar__social"><?php abidar_social_links( 'ab-social--bare' ); ?></span>
						<?php endif; ?>

						<?php if ( 'yes' === $s['show_lang'] ) : ?>
							<span class="ab-topbar__lang">
								<?php echo $lang->switcher( array( 'style' => 'inline', 'class' => 'ab-topbar__switcher' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</span>
						<?php endif; ?>

						<?php if ( $s['dismissible'] ) : ?>
							<button class="ab-topbar__close" type="button" data-ab-dismiss aria-label="<?php esc_attr_e( 'Close', 'abidar' ); ?>">
								<?php abidar_icon( 'x', true, array( 'width' => 14, 'height' => 14 ) ); ?>
							</button>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
