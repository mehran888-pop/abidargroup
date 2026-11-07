<?php
/**
 * Elementor widget: Services grid.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

/**
 * Class Abidar_Widget_Services
 */
class Abidar_Widget_Services extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-services';
	}

	public function get_title() {
		return esc_html__( 'Services grid', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-icon-box';
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
				'eyebrow' => 'services_eyebrow',
				'title'   => 'services_title',
				'text'    => 'services_text',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Card style', 'abidar' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'card',
				'options' => array(
					'card'  => esc_html__( 'Icon card', 'abidar' ),
					'image' => esc_html__( 'Image card', 'abidar' ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'abidar' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '4',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
				'selectors'      => array(
					'{{WRAPPER}} .ab-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				),
			)
		);

		$this->add_control(
			'show_list',
			array(
				'label'   => esc_html__( 'Show feature list', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_more',
			array(
				'label'   => esc_html__( 'Show “learn more” link', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Icon', 'abidar' ),
				'type'    => Controls_Manager::SELECT,
				'options' => abidar_icon_options(),
				'default' => 'mountain',
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label' => esc_html__( 'Image (image cards)', 'abidar' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Service', 'abidar' ),
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

		$repeater->add_control(
			'link',
			array(
				'label' => esc_html__( 'Link', 'abidar' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'items',
			array(
				'label'   => esc_html__( 'Services', 'abidar' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => $this->default_items(),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->end_controls_section();

		/* ——— Card style ——— */
		$this->start_controls_section(
			'style_card',
			array(
				'label' => esc_html__( 'Card', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => esc_html__( 'Background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-service' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Padding', 'abidar' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array( '{{WRAPPER}} .ab-service' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'abidar' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .ab-service' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .ab-service',
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .ab-service__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ab-service__icon svg' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_bg',
			array(
				'label'     => esc_html__( 'Icon background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-service__icon' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ab-service__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .ab-service__title',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Description colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-service__text' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		$this->add_heading_style_controls();
		$this->add_section_style_controls();
	}

	/**
	 * Default repeater items, localised from the theme dictionary.
	 *
	 * @return array
	 */
	protected function default_items() {
		$out = array();

		foreach ( abidar_services_data() as $service ) {
			$out[] = array(
				'icon'  => $service['icon'],
				'title' => abidar_localized( $service, 'title' ),
				'text'  => abidar_localized( $service, 'text' ),
				'image' => array( 'url' => ABIDAR_URI . '/assets/img/' . $service['image'] ),
			);
		}

		return $out;
	}

	/**
	 * Has the user left the repeater untouched?
	 *
	 * Compares titles only, so a saved-but-untranslated item still counts as
	 * default and keeps following the language switcher.
	 *
	 * @param array $items   Saved items.
	 * @param array $defaults Fresh defaults.
	 * @return bool
	 */
	protected function items_are_default( $items, $defaults ) {
		if ( ! is_array( $items ) || count( $items ) !== count( $defaults ) ) {
			return false;
		}

		// Collect every default title across the three languages.
		$known = array();
		foreach ( abidar_services_data() as $service ) {
			foreach ( $service['title'] as $translation ) {
				$known[] = $translation;
			}
		}

		foreach ( $items as $item ) {
			if ( ! in_array( $item['title'], $known, true ) ) {
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

		// Untouched widgets follow the active language (Persian / English / Chinese).
		if ( $this->items_are_default( $s['items'], $this->default_items() ) ) {
			$s['items'] = $this->default_items();
		}
		?>
		<section class="ab-section">
			<div class="ab-container">
				<div class="ab-section-head ab-section-head--split ab-reveal">
					<div>
						<?php if ( $s['head_eyebrow'] ) : ?>
							<span class="ab-eyebrow"><?php echo esc_html( $s['head_eyebrow'] ); ?></span>
						<?php endif; ?>
						<h2><?php echo esc_html( $s['head_title'] ); ?></h2>
						<?php if ( $s['head_text'] ) : ?>
							<p><?php echo esc_html( $s['head_text'] ); ?></p>
						<?php endif; ?>
					</div>
					<a class="ab-link-arrow" href="<?php echo esc_url( abidar_page_url( 'services' ) ); ?>">
						<span><?php echo esc_html( abidar_copy( 'view_all' ) ); ?></span>
						<?php abidar_icon( 'arrow-right', true, array( 'width' => 16, 'height' => 16 ) ); ?>
					</a>
				</div>

				<div class="ab-grid ab-stagger">
					<?php foreach ( (array) $s['items'] as $i => $item ) : ?>
						<article class="ab-card ab-service <?php echo 'image' === $s['layout'] ? 'ab-service--image' : ''; ?> ab-reveal" style="--ab-i:<?php echo (int) $i; ?>">
							<?php if ( 'image' === $s['layout'] && ! empty( $item['image']['url'] ) ) : ?>
								<div class="ab-service__media">
									<img src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy">
									<span class="ab-service__icon"><?php abidar_icon( $item['icon'], true ); ?></span>
								</div>
							<?php else : ?>
								<span class="ab-service__icon"><?php abidar_icon( $item['icon'], true ); ?></span>
							<?php endif; ?>

							<div class="<?php echo 'image' === $s['layout'] ? 'ab-service__body' : ''; ?>">
								<?php if ( 'card' === $s['layout'] ) : ?>
									<span class="ab-service__num"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
								<?php endif; ?>
								<h3 class="ab-service__title"><?php echo esc_html( $item['title'] ); ?></h3>
								<p class="ab-service__text"><?php echo esc_html( $item['text'] ); ?></p>

								<?php if ( 'yes' === $s['show_more'] ) : ?>
									<a class="ab-service__more" href="<?php echo esc_url( $item['link']['url'] ?? abidar_page_url( 'services' ) ); ?>">
										<span><?php echo esc_html( abidar_copy( 'learn_more' ) ); ?></span>
										<?php abidar_icon( 'arrow-right', true, array( 'width' => 15, 'height' => 15 ) ); ?>
									</a>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
