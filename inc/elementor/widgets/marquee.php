<?php
/**
 * Elementor widget: Client marquee.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Marquee
 */
class Abidar_Widget_Marquee extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-marquee';
	}

	public function get_title() {
		return esc_html__( 'Client marquee', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-carousel';
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
			'label',
			array(
				'label'   => esc_html__( 'Caption', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $this->copy_field( 'label', 'clients_title' ),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'   => esc_html__( 'Speed (seconds per loop)', 'abidar' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array( 'px' => array( 'min' => 8, 'max' => 80 ) ),
				'default' => array( 'size' => 38 ),
			)
		);

		$this->add_control(
			'dark',
			array(
				'label'   => esc_html__( 'Dark background', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'logo',
			array(
				'label' => esc_html__( 'Logo image', 'abidar' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'name',
			array(
				'label'   => esc_html__( 'Name', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Partner', 'abidar' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'   => esc_html__( 'Partners', 'abidar' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array( 'name' => 'Sarvestan Cement' ),
					array( 'name' => 'National Gas Co.' ),
					array( 'name' => 'Zagros Minerals' ),
					array( 'name' => 'Shanghai Stone Co.' ),
					array( 'name' => 'Petrop Pars' ),
					array( 'name' => 'Alborz Kansar' ),
				),
				'title_field' => '{{{ name }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_items',
			array(
				'label' => esc_html__( 'Items', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_typography',
				'label'    => esc_html__( 'Item typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-marquee__item',
			)
		);

		$this->add_control(
			'item_color',
			array(
				'label'     => esc_html__( 'Text colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-marquee__item' => 'color: {{VALUE}}; opacity: 0.6;' ),
			)
		);

		$this->add_control(
			'item_opacity',
			array(
				'label'      => esc_html__( 'Opacity', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array( '%' => array( 'min' => 10, 'max' => 100 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-marquee__item' => 'opacity: calc({{SIZE}} / 100);' ),
			)
		);

		$this->add_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 8, 'max' => 140 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-marquee__track' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'logo_typography',
				'label'     => esc_html__( 'Logo typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-marquee__item',
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
		<div class="ab-section ab-section--tight <?php echo 'yes' === $s['dark'] ? 'ab-section--dark' : ''; ?>">
			<?php if ( $s['label'] ) : ?>
				<div class="ab-container">
					<p class="ab-center ab-muted" style="font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;margin-bottom:1.25rem"><?php echo esc_html( $s['label'] ); ?></p>
				</div>
			<?php endif; ?>

			<div class="ab-marquee">
				<div class="ab-marquee__track" style="--ab-marquee-duration:<?php echo (int) ( $s['speed']['size'] ?? 38 ); ?>s">
					<?php foreach ( (array) $s['items'] as $item ) : ?>
						<span class="ab-marquee__item">
							<?php if ( ! empty( $item['logo']['url'] ) ) : ?>
								<img src="<?php echo esc_url( $item['logo']['url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>" style="max-height:38px;width:auto">
							<?php else : ?>
								<?php abidar_icon( 'mountain', true, array( 'width' => 20, 'height' => 20 ) ); ?>
								<?php echo esc_html( $item['name'] ); ?>
							<?php endif; ?>
						</span>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
