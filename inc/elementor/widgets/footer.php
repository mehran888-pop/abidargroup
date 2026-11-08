<?php
/**
 * Widget: Site footer.
 *
 * Brand column, menu columns, newsletter, social bar and legal row — one widget
 * that rebuilds the whole footer in free Elementor, with a Style section per
 * part.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Footer
 */
class Abidar_Widget_Footer extends Abidar_Widget_Base {

	/**
	 * Widget name.
	 */
	public function get_name() {
		return 'ab-footer';
	}

	/**
	 * Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Abidar site footer', 'abidar' );
	}

	/**
	 * Widget icon.
	 */
	public function get_icon() {
		return 'eicon-footer';
	}

	/**
	 * Keywords.
	 */
	public function get_keywords() {
		return array( 'footer', 'bottom', 'copyright', 'newsletter', 'social' );
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		/* ——— Content: brand column ——— */
		$this->start_controls_section(
			'section_brand',
			array(
				'label' => esc_html__( 'Brand column', 'abidar' ),
			)
		);

		$this->add_control(
			'about_text',
			array(
				'label'   => esc_html__( 'About text', 'abidar' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $this->copy_field( 'about_text', 'footer_about' ),
				'rows'    => 4,
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

		$this->end_controls_section();

		/* ——— Content: columns ——— */
		$this->start_controls_section(
			'section_columns',
			array(
				'label' => esc_html__( 'Link columns', 'abidar' ),
			)
		);

		$this->add_control(
			'col1_menu',
			array(
				'label'   => esc_html__( 'Column 1 menu', 'abidar' ),
				'type'    => Controls_Manager::SELECT,
				'options' => abidar_menu_choices(),
			)
		);

		$this->add_control(
			'col1_title',
			array(
				'label'   => esc_html__( 'Column 1 title', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $this->copy_field( 'col1_title', 'footer_col1' ),
			)
		);

		$this->add_control(
			'col2_menu',
			array(
				'label'     => esc_html__( 'Column 2 menu', 'abidar' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => abidar_menu_choices(),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'col2_title',
			array(
				'label'   => esc_html__( 'Column 2 title', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $this->copy_field( 'col2_title', 'footer_col2' ),
			)
		);

		$this->end_controls_section();

		/* ——— Content: contact + newsletter ——— */
		$this->start_controls_section(
			'section_contact',
			array(
				'label' => esc_html__( 'Contact & newsletter', 'abidar' ),
			)
		);

		$this->add_control(
			'show_contact',
			array(
				'label'   => esc_html__( 'Show contact block', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_newsletter',
			array(
				'label'   => esc_html__( 'Show newsletter', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'newsletter_title',
			array(
				'label'     => esc_html__( 'Newsletter title', 'abidar' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $this->copy_field( 'newsletter_title', 'footer_newsletter' ),
				'condition' => array( 'show_newsletter' => 'yes' ),
			)
		);

		$this->add_control(
			'copyright',
			array(
				'label'       => esc_html__( 'Copyright line', 'abidar' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => $this->copy_field( 'copyright', 'footer_copy' ),
				'separator'   => 'before',
			)
		);

		$this->end_controls_section();

		/* ——— Style: shell ——— */
		$this->start_controls_section(
			'style_shell',
			array(
				'label' => esc_html__( 'Footer shell', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'footer_bg',
			array(
				'label'     => esc_html__( 'Background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-foot' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'footer_color',
			array(
				'label'     => esc_html__( 'Base text colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-foot' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'footer_padding',
			array(
				'label'      => esc_html__( 'Padding', 'abidar' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'rem' ),
				'selectors'  => array( '{{WRAPPER}} .ab-foot__main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'footer_pattern',
			array(
				'label'     => esc_html__( 'Show pattern overlay', 'abidar' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'selectors' => array( '{{WRAPPER}} .ab-foot' => '--ab-foot-pattern: {{VALUE}};' ),
				'selectors_dictionary' => array(
					'yes' => "url('" . ABIDAR_URI . "/assets/img/pattern-dark.jpg')",
					''    => 'none',
				),
			)
		);

		$this->end_controls_section();

		/* ——— Style: headings & links ——— */
		$this->start_controls_section(
			'style_text',
			array(
				'label' => esc_html__( 'Headings & links', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => esc_html__( 'Column title colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-foot__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'label'    => esc_html__( 'Column title typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-foot__title',
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Link colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ab-foot__list a, {{WRAPPER}} .ab-foot__contact a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => esc_html__( 'Link hover colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-foot__list a:hover, {{WRAPPER}} .ab-foot__contact a:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'link_typography',
				'label'    => esc_html__( 'Link typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-foot__list a, {{WRAPPER}} .ab-foot__contact a',
			)
		);

		$this->end_controls_section();

		/* ——— Style: social ——— */
		$this->start_controls_section(
			'style_social',
			array(
				'label'     => esc_html__( 'Social icons', 'abidar' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_social' => 'yes' ),
			)
		);

		$this->add_control(
			'social_color',
			array(
				'label'     => esc_html__( 'Icon colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-foot__social a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'social_bg',
			array(
				'label'     => esc_html__( 'Icon background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-foot__social a' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'social_hover',
			array(
				'label'     => esc_html__( 'Hover colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-foot__social a:hover' => 'color: {{VALUE}}; background: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'social_size',
			array(
				'label'      => esc_html__( 'Icon size', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 24, 'max' => 64 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ab-foot__social a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ——— Style: newsletter ——— */
		$this->start_controls_section(
			'style_newsletter',
			array(
				'label'     => esc_html__( 'Newsletter', 'abidar' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_newsletter' => 'yes' ),
			)
		);

		$this->add_control(
			'newsletter_bg',
			array(
				'label'     => esc_html__( 'Field background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-foot__newsletter input' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'newsletter_btn',
			array(
				'label'     => esc_html__( 'Button background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-foot__newsletter button' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'newsletter_radius',
			array(
				'label'      => esc_html__( 'Radius', 'abidar' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ab-foot__newsletter input, {{WRAPPER}} .ab-foot__newsletter button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ——— Style: bottom bar ——— */
		$this->start_controls_section(
			'style_bottom',
			array(
				'label' => esc_html__( 'Bottom bar', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bottom_color',
			array(
				'label'     => esc_html__( 'Text colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-foot__bottom' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'bottom_typography',
				'label'    => esc_html__( 'Typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-foot__bottom',
			)
		);

		$this->add_control(
			'bottom_line',
			array(
				'label'     => esc_html__( 'Top border colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-foot__bottom' => 'border-top: 1px solid {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		$this->add_section_style_controls( '{{WRAPPER}} .ab-foot' );
	}

	/**
	 * Render.
	 */
	protected function render() {
		$s        = $this->localize_settings( $this->get_settings_for_display() );
		$phone    = abidar_option( 'topbar_phone', '' );
		$email    = abidar_option( 'topbar_email', '' );
		$address  = abidar_option( 'address', '' );
		$hours    = abidar_option( 'hours', '' );
		$year     = date_i18n( 'Y' );

		$copyright = $s['copyright'];
		if ( ! $copyright ) {
			$copyright = sprintf(
				/* translators: 1: year, 2: site name */
				abidar_copy( 'footer_copy' ),
				$year,
				get_bloginfo( 'name' )
			);
		}

		$col1 = abidar_menu_items( (int) ( $s['col1_menu'] ?? 0 ) );
		$col2 = abidar_menu_items( (int) ( $s['col2_menu'] ?? 0 ) );
		?>
		<footer class="ab-foot" role="contentinfo">
			<div class="ab-foot__main">
				<div class="ab-container ab-foot__grid">

					<div class="ab-foot__col ab-foot__col--brand">
						<?php abidar_brand_mark( array( 'dark' => true, 'tagline' => true ) ); ?>

						<?php if ( $s['about_text'] ) : ?>
							<p class="ab-foot__about"><?php echo esc_html( $s['about_text'] ); ?></p>
						<?php endif; ?>

						<?php if ( 'yes' === $s['show_social'] ) : ?>
							<div class="ab-foot__social">
								<?php abidar_social_links(); ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $col1 ) : ?>
						<div class="ab-foot__col">
							<?php if ( $s['col1_title'] ) : ?>
								<h2 class="ab-foot__title"><?php echo esc_html( $s['col1_title'] ); ?></h2>
							<?php endif; ?>
							<?php abidar_masthead_menu( $col1, 1, 'ab-foot__list' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $col2 ) : ?>
						<div class="ab-foot__col">
							<?php if ( $s['col2_title'] ) : ?>
								<h2 class="ab-foot__title"><?php echo esc_html( $s['col2_title'] ); ?></h2>
							<?php endif; ?>
							<?php abidar_masthead_menu( $col2, 1, 'ab-foot__list' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $s['show_contact'] ) : ?>
						<div class="ab-foot__col ab-foot__contact">
							<h2 class="ab-foot__title"><?php echo esc_html( abidar_copy( 'contact_title' ) ); ?></h2>
							<ul class="ab-foot__list">
								<?php if ( $address ) : ?>
									<li>
										<?php abidar_icon( 'pin', true, array( 'width' => 15, 'height' => 15 ) ); ?>
										<span><?php echo esc_html( $address ); ?></span>
									</li>
								<?php endif; ?>
								<?php if ( $phone ) : ?>
									<li>
										<?php abidar_icon( 'phone', true, array( 'width' => 15, 'height' => 15 ) ); ?>
										<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" dir="ltr"><?php echo esc_html( $phone ); ?></a>
									</li>
								<?php endif; ?>
								<?php if ( $email ) : ?>
									<li>
										<?php abidar_icon( 'mail', true, array( 'width' => 15, 'height' => 15 ) ); ?>
										<a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a>
									</li>
								<?php endif; ?>
								<?php if ( $hours ) : ?>
									<li>
										<?php abidar_icon( 'clock', true, array( 'width' => 15, 'height' => 15 ) ); ?>
										<span><?php echo esc_html( $hours ); ?></span>
									</li>
								<?php endif; ?>
							</ul>

							<?php if ( 'yes' === $s['show_newsletter'] ) : ?>
								<form class="ab-foot__newsletter" data-newsletter>
									<?php if ( $s['newsletter_title'] ) : ?>
										<label class="ab-foot__newsletter-title" for="ab-news-<?php echo esc_attr( $this->get_id() ); ?>">
											<?php echo esc_html( $s['newsletter_title'] ); ?>
										</label>
									<?php endif; ?>
									<div class="ab-foot__newsletter-row">
										<input type="email" id="ab-news-<?php echo esc_attr( $this->get_id() ); ?>" name="email" required
											placeholder="<?php esc_attr_e( 'Email address', 'abidar' ); ?>">
										<button type="submit" aria-label="<?php esc_attr_e( 'Subscribe', 'abidar' ); ?>">
											<?php abidar_icon( 'send', true, array( 'width' => 17, 'height' => 17 ) ); ?>
										</button>
									</div>
								</form>
							<?php endif; ?>
						</div>
					<?php endif; ?>

				</div>
			</div>

			<div class="ab-foot__bottom">
				<div class="ab-container ab-foot__bottom-inner">
					<p class="ab-foot__copy"><?php echo wp_kses_post( $copyright ); ?></p>

					<?php if ( has_nav_menu( 'footer-legal' ) ) : ?>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer-legal',
								'container'      => false,
								'menu_class'     => 'ab-foot__legal',
								'depth'          => 1,
							)
						);
						?>
					<?php endif; ?>
				</div>
			</div>
		</footer>
		<?php
	}
}
