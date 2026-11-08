<?php
/**
 * Widget: Site header (topbar + navigation).
 *
 * A complete header in one free-Elementor widget: top contact bar, logo,
 * primary menu, language switcher, search, CTA and mobile drawer. Every part has
 * its own Style-tab section.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

/**
 * Class Abidar_Widget_Header
 */
class Abidar_Widget_Header extends Abidar_Widget_Base {

	/**
	 * Widget name.
	 */
	public function get_name() {
		return 'ab-header';
	}

	/**
	 * Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Abidar site header', 'abidar' );
	}

	/**
	 * Widget icon.
	 */
	public function get_icon() {
		return 'eicon-header';
	}

	/**
	 * Keywords.
	 */
	public function get_keywords() {
		return array( 'header', 'nav', 'menu', 'logo', 'topbar', 'navigation' );
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		/* ——— Content ——— */
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Header', 'abidar' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'abidar' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'classic',
				'options' => array(
					'classic'  => esc_html__( 'Classic (logo start, menu end)', 'abidar' ),
					'split'    => esc_html__( 'Split (menu – logo – menu)', 'abidar' ),
					'centered' => esc_html__( 'Centered logo', 'abidar' ),
					'minimal'  => esc_html__( 'Minimal (logo + burger)', 'abidar' ),
				),
			)
		);

		$this->add_control(
			'menu_id',
			array(
				'label'   => esc_html__( 'Menu', 'abidar' ),
				'type'    => Controls_Manager::SELECT,
				'options' => abidar_menu_choices(),
			)
		);

		$this->add_control(
			'logo_size',
			array(
				'label'      => esc_html__( 'Logo width', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 60, 'max' => 320 ) ),
				'default'    => array( 'size' => 148, 'unit' => 'px' ),
				'selectors'  => array( '{{WRAPPER}} .ab-brand__img' => 'width: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'show_topbar',
			array(
				'label'   => esc_html__( 'Show top bar', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'topbar_note',
			array(
				'label'     => esc_html__( 'Top bar note', 'abidar' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $this->copy_field( 'topbar_note', 'topbar_note' ),
				'condition' => array( 'show_topbar' => 'yes' ),
			)
		);

		$this->add_control(
			'show_phone',
			array(
				'label'     => esc_html__( 'Show phone', 'abidar' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array( 'show_topbar' => 'yes' ),
			)
		);

		$this->add_control(
			'show_email',
			array(
				'label'     => esc_html__( 'Show email', 'abidar' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array( 'show_topbar' => 'yes' ),
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
			'show_search',
			array(
				'label'   => esc_html__( 'Show search', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'cta_text',
			array(
				'label'   => esc_html__( 'CTA label', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $this->copy_field( 'cta_text', 'nav_quote' ),
			)
		);

		$this->add_control(
			'cta_url',
			array(
				'label'       => esc_html__( 'CTA link', 'abidar' ),
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '#contact' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'sticky',
			array(
				'label'       => esc_html__( 'Sticky header', 'abidar' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => esc_html__( 'Keeps the bar on screen while scrolling.', 'abidar' ),
			)
		);

		$this->add_control(
			'transparent',
			array(
				'label'       => esc_html__( 'Transparent over hero', 'abidar' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Only applies when the header sits above a hero section.', 'abidar' ),
			)
		);

		$this->end_controls_section();

		/* ——— Style: bar ——— */
		$this->start_controls_section(
			'style_bar',
			array(
				'label' => esc_html__( 'Bar', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bar_bg',
			array(
				'label'     => esc_html__( 'Background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-masthead' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'bar_height',
			array(
				'label'      => esc_html__( 'Minimum height', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 48, 'max' => 160 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-masthead__inner' => 'min-height: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'bar_padding',
			array(
				'label'      => esc_html__( 'Padding', 'abidar' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'rem' ),
				'selectors'  => array( '{{WRAPPER}} .ab-masthead' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'bar_line',
			array(
				'label'     => esc_html__( 'Bottom border', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ab-masthead' => 'border-bottom: 1px solid {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'bar_shadow',
				'label'    => esc_html__( 'Box shadow', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-masthead',
			)
		);

		$this->add_responsive_control(
			'bar_layout',
			array(
				'label'     => esc_html__( 'Alignment', 'abidar' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array( 'title' => esc_html__( 'Start', 'abidar' ), 'icon' => 'eicon-text-align-left' ),
					'center'     => array( 'title' => esc_html__( 'Center', 'abidar' ), 'icon' => 'eicon-text-align-center' ),
					'flex-end'   => array( 'title' => esc_html__( 'End', 'abidar' ), 'icon' => 'eicon-text-align-right' ),
				),
				'selectors' => array( '{{WRAPPER}} .ab-masthead__inner' => 'justify-content: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		/* ——— Style: topbar ——— */
		$this->start_controls_section(
			'style_topbar',
			array(
				'label'     => esc_html__( 'Top bar', 'abidar' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_topbar' => 'yes' ),
			)
		);

		$this->add_control(
			'topbar_bg',
			array(
				'label'     => esc_html__( 'Background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-topbar' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'topbar_color',
			array(
				'label'     => esc_html__( 'Text colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-topbar, {{WRAPPER}} .ab-topbar a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'topbar_typography',
				'label'    => esc_html__( 'Typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-topbar',
			)
		);

		$this->add_responsive_control(
			'topbar_padding',
			array(
				'label'      => esc_html__( 'Padding', 'abidar' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'rem' ),
				'selectors'  => array( '{{WRAPPER}} .ab-topbar__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		/* ——— Style: menu ——— */
		$this->start_controls_section(
			'style_menu',
			array(
				'label' => esc_html__( 'Menu', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'menu_color',
			array(
				'label'     => esc_html__( 'Link colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-masthead__nav a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'menu_hover_color',
			array(
				'label'     => esc_html__( 'Hover / active colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ab-masthead__nav a:hover, {{WRAPPER}} .ab-masthead__nav .current-menu-item > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'menu_typography',
				'label'    => esc_html__( 'Typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-masthead__nav a',
			)
		);

		$this->add_responsive_control(
			'menu_gap',
			array(
				'label'      => esc_html__( 'Gap', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-masthead__nav ul' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'dropdown_bg',
			array(
				'label'     => esc_html__( 'Dropdown background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ab-masthead__nav .sub-menu' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'dropdown_radius',
			array(
				'label'      => esc_html__( 'Dropdown radius', 'abidar' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .ab-masthead__nav .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		/* ——— Style: CTA & actions ——— */
		$this->start_controls_section(
			'style_cta',
			array(
				'label' => esc_html__( 'Button & actions', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'cta_bg',
			array(
				'label'     => esc_html__( 'Button background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-masthead__cta' => 'background: {{VALUE}}; border-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'cta_color',
			array(
				'label'     => esc_html__( 'Button text', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-masthead__cta' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cta_typography',
				'label'    => esc_html__( 'Button typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-masthead__cta',
			)
		);

		$this->add_responsive_control(
			'cta_radius',
			array(
				'label'      => esc_html__( 'Button radius', 'abidar' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .ab-masthead__cta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .ab-masthead__iconbtn, {{WRAPPER}} .ab-masthead__lang .ab-lang__toggle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/* ——— Style: mobile ——— */
		$this->start_controls_section(
			'style_mobile',
			array(
				'label' => esc_html__( 'Mobile drawer', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'drawer_bg',
			array(
				'label'     => esc_html__( 'Background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-masthead__drawer' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'drawer_color',
			array(
				'label'     => esc_html__( 'Link colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-masthead__drawer a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'drawer_typography',
				'label'    => esc_html__( 'Link typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-masthead__drawer a',
			)
		);

		$this->end_controls_section();

		$this->add_section_style_controls( '{{WRAPPER}} .ab-masthead-wrap' );
	}

	/**
	 * Render.
	 */
	protected function render() {
		$s          = $this->localize_settings( $this->get_settings_for_display() );
		$lang       = Abidar_Multilingual::instance();
		$layout     = sanitize_html_class( $s['layout'] );
		$menu_items = abidar_menu_items( (int) ( $s['menu_id'] ?? 0 ) );
		$phone      = abidar_option( 'topbar_phone', '' );
		$email      = abidar_option( 'topbar_email', '' );
		$uid        = 'ab-drawer-' . $this->get_id();

		$wrap_class = 'ab-masthead-wrap';

		if ( 'yes' === $s['sticky'] ) {
			$wrap_class .= ' is-sticky-enabled';
		}

		if ( 'yes' === $s['transparent'] ) {
			$wrap_class .= ' is-transparent';
		}
		?>
		<div class="<?php echo esc_attr( $wrap_class ); ?>" data-masthead>
			<?php if ( 'yes' === $s['show_topbar'] ) : ?>
				<div class="ab-topbar">
					<div class="ab-container ab-topbar__inner">
						<div class="ab-topbar__left">
							<?php if ( $s['topbar_note'] ) : ?>
								<span class="ab-topbar__note"><?php echo esc_html( $s['topbar_note'] ); ?></span>
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
						</div>
					</div>
				</div>
			<?php endif; ?>

			<header class="ab-masthead ab-masthead--<?php echo esc_attr( $layout ); ?>" data-masthead-bar>
				<div class="ab-container ab-masthead__inner">

					<?php if ( 'split' === $layout ) : ?>
						<nav class="ab-masthead__nav ab-masthead__nav--start" aria-label="<?php esc_attr_e( 'Primary menu', 'abidar' ); ?>">
							<?php abidar_masthead_menu( $menu_items, 3 ); ?>
						</nav>
					<?php endif; ?>

					<div class="ab-masthead__brand">
						<?php abidar_brand_mark( array( 'size' => (int) ( $s['logo_size']['size'] ?? 0 ) ) ); ?>
					</div>

					<?php if ( 'split' !== $layout && 'minimal' !== $layout ) : ?>
						<nav class="ab-masthead__nav" aria-label="<?php esc_attr_e( 'Primary menu', 'abidar' ); ?>">
							<?php abidar_masthead_menu( $menu_items, 3 ); ?>
						</nav>
					<?php endif; ?>

					<div class="ab-masthead__actions">
						<?php if ( 'yes' === $s['show_lang'] ) : ?>
							<span class="ab-masthead__lang">
								<?php echo $lang->switcher( array( 'style' => 'dropdown', 'class' => 'ab-header__lang' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</span>
						<?php endif; ?>

						<?php if ( 'yes' === $s['show_search'] ) : ?>
							<button class="ab-masthead__iconbtn" type="button" aria-label="<?php esc_attr_e( 'Search', 'abidar' ); ?>" data-ab-search>
								<?php abidar_icon( 'search', true, array( 'width' => 18, 'height' => 18 ) ); ?>
							</button>
						<?php endif; ?>

						<?php if ( $s['cta_text'] ) : ?>
							<a class="ab-masthead__cta" href="<?php echo esc_url( $s['cta_url']['url'] ?? '#contact' ); ?>">
								<span><?php echo esc_html( $s['cta_text'] ); ?></span>
								<?php abidar_icon( 'arrow-right', true, array( 'width' => 15, 'height' => 15 ) ); ?>
							</a>
						<?php endif; ?>

						<button class="ab-masthead__burger" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $uid ); ?>" data-ab-drawer>
							<?php abidar_icon( 'menu', true, array( 'width' => 22, 'height' => 22 ) ); ?>
							<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'abidar' ); ?></span>
						</button>
					</div>
				</div>

				<div class="ab-search-panel" data-ab-search-panel hidden>
					<div class="ab-container">
						<?php get_search_form(); ?>
						<button class="ab-search-panel__close" type="button" data-ab-search aria-label="<?php esc_attr_e( 'Close', 'abidar' ); ?>">
							<?php abidar_icon( 'x', true, array( 'width' => 18, 'height' => 18 ) ); ?>
						</button>
					</div>
				</div>
			</header>

			<aside class="ab-masthead__drawer" id="<?php echo esc_attr( $uid ); ?>" hidden>
				<div class="ab-masthead__drawer-head">
					<?php abidar_brand_mark( array( 'size' => 128 ) ); ?>
					<button class="ab-masthead__drawer-close" type="button" data-ab-drawer aria-label="<?php esc_attr_e( 'Close', 'abidar' ); ?>">
						<?php abidar_icon( 'x', true, array( 'width' => 20, 'height' => 20 ) ); ?>
					</button>
				</div>

				<?php abidar_masthead_menu( $menu_items, 3, 'ab-masthead__drawer-list' ); ?>

				<?php if ( $phone ) : ?>
					<a class="ab-masthead__drawer-phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
						<?php abidar_icon( 'phone', true, array( 'width' => 16, 'height' => 16 ) ); ?>
						<span dir="ltr"><?php echo esc_html( $phone ); ?></span>
					</a>
				<?php endif; ?>

				<?php echo $lang->switcher( array( 'style' => 'inline', 'class' => 'ab-masthead__drawer-lang' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</aside>
		</div>
		<?php
	}
}
