<?php
/**
 * Elementor widget: Testimonials.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Testimonials
 */
class Abidar_Widget_Testimonials extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-testimonials';
	}

	public function get_title() {
		return esc_html__( 'Testimonials', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
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
				'eyebrow' => 'testimonials_eyebrow',
				'title'   => 'testimonials_title',
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'     => esc_html__( 'Columns', 'abidar' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '3',
				'options'   => array( '1' => '1', '2' => '2', '3' => '3' ),
				'selectors' => array( '{{WRAPPER}} .ab-testimonials' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'quote',
			array(
				'label' => esc_html__( 'Quote', 'abidar' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 4,
			)
		);

		$repeater->add_control(
			'name',
			array(
				'label'   => esc_html__( 'Name', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Client', 'abidar' ),
			)
		);

		$repeater->add_control(
			'role',
			array(
				'label' => esc_html__( 'Role / company', 'abidar' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'avatar',
			array(
				'label' => esc_html__( 'Avatar', 'abidar' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'rating',
			array(
				'label'   => esc_html__( 'Rating', 'abidar' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 5,
				'default' => 5,
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => esc_html__( 'Testimonials', 'abidar' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $this->default_items(),
				'title_field' => '{{{ name }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_card',
			array(
				'label' => esc_html__( 'Card', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'quote_typography',
				'label'    => esc_html__( 'Quote typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-quote__text',
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => esc_html__( 'Name colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-quote__name' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'label'    => esc_html__( 'Name typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-quote__name',
			)
		);

		$this->add_control(
			'role_color',
			array(
				'label'     => esc_html__( 'Role colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-quote__role' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => esc_html__( 'Background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-quote' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'quote_color',
			array(
				'label'     => esc_html__( 'Quote colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-quote__text' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_quote_typography',
				'label'     => esc_html__( 'Card quote typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-quote__text',
			)
		);

		$this->add_control(
			'stars_color',
			array(
				'label'     => esc_html__( 'Stars colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-quote__stars' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'card_radius',
			array(
				'label'      => esc_html__( 'Radius', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 50 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-quote' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->add_heading_style_controls();
		$this->add_section_style_controls();
	}

	/**
	 * Default testimonials, localised for the active language.
	 *
	 * @return array
	 */
	protected function default_items() {
		$out = array();

		foreach ( abidar_testimonials_data() as $item ) {
			$out[] = array(
				'quote'  => abidar_localized( $item, 'quote' ),
				'name'   => abidar_localized( $item, 'name' ),
				'role'   => abidar_localized( $item, 'role' ),
				'rating' => 5,
			);
		}

		return $out;
	}

	/**
	 * Are the saved quotes still the untouched defaults?
	 *
	 * @param array $items Saved items.
	 * @return bool
	 */
	protected function items_are_default( $items ) {
		if ( ! is_array( $items ) || count( $items ) !== count( abidar_testimonials_data() ) ) {
			return false;
		}

		$known = array();
		foreach ( abidar_testimonials_data() as $item ) {
			foreach ( $item['quote'] as $translation ) {
				$known[] = $translation;
			}
		}

		foreach ( $items as $item ) {
			if ( ! in_array( $item['quote'], $known, true ) ) {
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

				<div class="ab-testimonials ab-stagger">
					<?php foreach ( (array) $s['items'] as $i => $item ) : ?>
						<figure class="ab-quote ab-reveal" style="--ab-i:<?php echo (int) $i; ?>">
							<span class="ab-quote__mark"><?php abidar_icon( 'quote', true, array( 'width' => 30, 'height' => 30 ) ); ?></span>
							<div class="ab-quote__stars">
								<?php for ( $star = 0; $star < (int) $item['rating']; $star++ ) : ?>
									<?php abidar_icon( 'star', true, array( 'width' => 14, 'height' => 14, 'fill' => 'currentColor' ) ); ?>
								<?php endfor; ?>
							</div>
							<blockquote class="ab-quote__text"><?php echo esc_html( $item['quote'] ); ?></blockquote>
							<figcaption class="ab-quote__author">
								<?php if ( ! empty( $item['avatar']['url'] ) ) : ?>
									<img class="ab-quote__avatar" style="object-fit:cover" src="<?php echo esc_url( $item['avatar']['url'] ); ?>" alt="">
								<?php else : ?>
									<span class="ab-quote__avatar"><?php echo esc_html( mb_substr( $item['name'], 0, 2 ) ); ?></span>
								<?php endif; ?>
								<span>
									<span class="ab-quote__name"><?php echo esc_html( $item['name'] ); ?></span><br>
									<span class="ab-quote__role"><?php echo esc_html( $item['role'] ); ?></span>
								</span>
							</figcaption>
						</figure>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
