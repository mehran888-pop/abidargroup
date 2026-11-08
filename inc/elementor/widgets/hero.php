<?php
/**
 * Elementor widget: Hero.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

/**
 * Class Abidar_Widget_Hero
 */
class Abidar_Widget_Hero extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-hero';
	}

	public function get_title() {
		return esc_html__( 'Hero slider', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-slider-full-screen';
	}

	public function get_categories() {
		return array( 'abidar' );
	}

	public function get_keywords() {
		return array( 'hero', 'banner', 'slider', 'header', 'abidar' );
	}

	public function get_style_depends() {
		return array( 'abidar-main', 'abidar-widgets' );
	}

	public function get_script_depends() {
		return array( 'abidar-main' );
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		/* ——— Content ——— */
		$this->start_controls_section(
			'section_content',
			array( 'label' => esc_html__( 'Content', 'abidar' ) )
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => esc_html__( 'Eyebrow', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $this->copy_field( 'eyebrow', 'hero_eyebrow' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Headline', 'abidar' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => $this->copy_field( 'title', 'hero_title' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => esc_html__( 'Description', 'abidar' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => $this->copy_field( 'text', 'hero_text' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'btn1_text',
			array(
				'label'   => esc_html__( 'Primary button', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $this->copy_field( 'btn1_text', 'hero_cta1' ),
			)
		);

		$this->add_control(
			'btn1_url',
			array(
				'label'       => esc_html__( 'Primary button link', 'abidar' ),
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '#' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'btn2_text',
			array(
				'label'     => esc_html__( 'Secondary button', 'abidar' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $this->copy_field( 'btn2_text', 'hero_cta2' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'btn2_url',
			array(
				'label'       => esc_html__( 'Secondary button link', 'abidar' ),
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '#contact' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_scroll',
			array(
				'label'     => esc_html__( 'Show scroll cue', 'abidar' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		/* ——— Background ——— */
		$this->start_controls_section(
			'section_background',
			array( 'label' => esc_html__( 'Background', 'abidar' ) )
		);

		$this->add_control(
			'bg_image',
			array(
				'label'       => esc_html__( 'Background image', 'abidar' ),
				'type'        => Controls_Manager::MEDIA,
				'default'     => array( 'url' => ABIDAR_URI . '/assets/img/hero-mine.jpg' ),
				'show_label'  => true,
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'     => esc_html__( 'Overlay gradient start', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(9,18,19,0.82)',
				'selectors' => array( '{{WRAPPER}} .ab-hero__bg::after' => '--ab-overlay: {{VALUE}}; background-image: linear-gradient(180deg, {{VALUE}}, transparent 45%, {{VALUE}});' ),
			)
		);

		$this->add_control(
			'min_height',
			array(
				'label'      => esc_html__( 'Minimum height', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'vh', 'px' ),
				'range'      => array(
					'vh' => array( 'min' => 40, 'max' => 100 ),
					'px' => array( 'min' => 380, 'max' => 1000 ),
				),
				'default'    => array( 'unit' => 'vh', 'size' => 82 ),
				'selectors'  => array( '{{WRAPPER}} .ab-hero' => 'min-height: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'kenburns',
			array(
				'label'     => esc_html__( 'Ken Burns zoom', 'abidar' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'selectors' => array( '{{WRAPPER}} .ab-hero__bg img' => 'animation: {{VALUE === "yes" ? "ab-kenburns 22s ease-in-out infinite alternate" : "none"}};' ),
			)
		);

		$this->end_controls_section();

		/* ——— Stats ——— */
		$this->start_controls_section(
			'section_stats',
			array( 'label' => esc_html__( 'Statistics strip', 'abidar' ) )
		);

		$this->add_control(
			'show_stats',
			array(
				'label'   => esc_html__( 'Show stats', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'number',
			array(
				'label'   => esc_html__( 'Number', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '248',
			)
		);

		$repeater->add_control(
			'suffix',
			array(
				'label'   => esc_html__( 'Suffix', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '+',
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => esc_html__( 'Label', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Successful projects', 'abidar' ),
			)
		);

		$this->add_control(
			'stats',
			array(
				'label'       => esc_html__( 'Stats', 'abidar' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $this->default_stats(),
				'title_field' => '{{{ number }}}{{{ suffix }}} — {{{ label }}}',
				'condition'   => array( 'show_stats' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ——— Style: headline ——— */
		$this->start_controls_section(
			'style_title',
			array(
				'label' => esc_html__( 'Headline', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'     => esc_html__( 'Title typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-hero__title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-hero__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'max_width',
			array(
				'label'      => esc_html__( 'Content max width', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 400, 'max' => 1100 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 810 ),
				'selectors'  => array( '{{WRAPPER}} .ab-hero__content' => 'max-width: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		/* ——— Style: text & buttons ——— */
		$this->start_controls_section(
			'style_buttons',
			array(
				'label' => esc_html__( 'Buttons', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'btn_primary_bg',
			array(
				'label'     => esc_html__( 'Primary background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-hero__actions .ab-btn--accent' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'btn_primary_color',
			array(
				'label'     => esc_html__( 'Primary text', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-hero__actions .ab-btn--accent' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'btn_radius',
			array(
				'label'      => esc_html__( 'Button radius', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-hero__actions .ab-btn' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		/* ——— Style: stats ——— */
		$this->start_controls_section(
			'style_stats',
			array(
				'label'     => esc_html__( 'Statistics', 'abidar' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_stats' => 'yes' ),
			)
		);

		$this->add_control(
			'stats_bg',
			array(
				'label'     => esc_html__( 'Cell background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-hero__stat' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'stats_num_color',
			array(
				'label'     => esc_html__( 'Number colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-hero__stat-num' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'stats_label_color',
			array(
				'label'     => esc_html__( 'Label colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-hero__stat-label' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		// Same background / spacing / border / shadow group as every other section.
		/* ——— Style: description, eyebrow, stats ——— */
		$this->start_controls_section(
			'style_text',
			array(
				'label' => esc_html__( 'Description & stats', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => esc_html__( 'Eyebrow colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-eyebrow' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eyebrow_typography',
				'label'    => esc_html__( 'Eyebrow typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-eyebrow',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Description colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ab-hero__text' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'label'    => esc_html__( 'Description typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-hero__text',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stat_number_typography',
				'label'    => esc_html__( 'Stat number typography', 'abidar' ),
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .ab-hero__stat-num',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stat_label_typography',
				'label'    => esc_html__( 'Stat label typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-hero__stat-label',
			)
		);

		$this->add_control(
			'stat_number_color',
			array(
				'label'     => esc_html__( 'Number colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-hero__stat-num' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'stat_label_color',
			array(
				'label'     => esc_html__( 'Label colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-hero__stat-label' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'stat_cell_bg',
			array(
				'label'     => esc_html__( 'Cell background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-hero__stat' => 'background: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		$this->add_section_style_controls( '{{WRAPPER}} .ab-hero' );
	}

	/**
	 * Default stat strip, localised for the active language.
	 *
	 * @return array
	 */
	protected function default_stats() {
		return array(
			array( 'number' => '248', 'suffix' => '+', 'label' => $this->copy_field( 'stat1_label', 'hero_stat1' ) ),
			array( 'number' => '22', 'suffix' => '', 'label' => $this->copy_field( 'stat2_label', 'hero_stat2' ) ),
			array( 'number' => '412', 'suffix' => '', 'label' => $this->copy_field( 'stat3_label', 'hero_stat3' ) ),
			array( 'number' => '9', 'suffix' => '', 'label' => $this->copy_field( 'stat4_label', 'hero_stat4' ) ),
		);
	}

	/**
	 * Keep untouched copy in sync with the active language.
	 *
	 * Any field the editor has not customised is swapped for the current
	 * language string, so a header/footer built once is fully trilingual.
	 *
	 * @param array $settings Widget settings.
	 * @return array
	 */
	protected function localize_defaults( $settings ) {
		$settings = $this->localize_settings( $settings );

		// Stat labels.
		$known = array();
		for ( $i = 1; $i <= 4; $i++ ) {
			foreach ( array( 'fa', 'en', 'zh' ) as $lang ) {
				$known[] = Abidar_Strings::get( 'hero_stat' . $i, $lang );
			}
		}

		if ( is_array( $settings['stats'] ) ) {
			$untouched = true;
			foreach ( $settings['stats'] as $stat ) {
				if ( ! in_array( $stat['label'], $known, true ) ) {
					$untouched = false;
					break;
				}
			}
			if ( $untouched && 4 === count( $settings['stats'] ) ) {
				$fresh = $this->default_stats();
				foreach ( $settings['stats'] as $i => $stat ) {
					$settings['stats'][ $i ]['label'] = $fresh[ $i ]['label'];
				}
			}
		}

		return $settings;
	}

	/**
	 * Render.
	 */
	protected function render() {
		$s = $this->localize_defaults( $this->get_settings_for_display() );

		$stats = array();
		foreach ( (array) $s['stats'] as $stat ) {
			$stats[] = array( $stat['number'], $stat['suffix'], $stat['label'] );
		}

		if ( 'yes' !== $s['show_stats'] ) {
			$stats = array();
		}

		$bg = isset( $s['bg_image']['url'] ) ? $s['bg_image']['url'] : '';
		?>
		<section class="ab-hero" id="top">
			<div class="ab-hero__bg">
				<?php if ( $bg ) : ?>
					<img src="<?php echo esc_url( $bg ); ?>" alt="" fetchpriority="high" decoding="async">
				<?php endif; ?>
			</div>

			<div class="ab-container ab-hero__inner">
				<div class="ab-hero__content">
					<?php if ( $s['eyebrow'] ) : ?>
						<span class="ab-eyebrow ab-eyebrow--light ab-reveal"><?php echo esc_html( $s['eyebrow'] ); ?></span>
					<?php endif; ?>

					<h1 class="ab-hero__title ab-reveal"><?php echo esc_html( $s['title'] ); ?></h1>

					<?php if ( $s['text'] ) : ?>
						<p class="ab-hero__text ab-reveal"><?php echo esc_html( $s['text'] ); ?></p>
					<?php endif; ?>

					<div class="ab-hero__actions ab-reveal">
						<?php if ( $s['btn1_text'] ) : ?>
							<a class="ab-btn ab-btn--accent ab-btn--lg" href="<?php echo esc_url( $s['btn1_url']['url'] ?? '#' ); ?>">
								<span><?php echo esc_html( $s['btn1_text'] ); ?></span>
								<?php abidar_icon( 'arrow-right', true, array( 'width' => 17, 'height' => 17 ) ); ?>
							</a>
						<?php endif; ?>
						<?php if ( $s['btn2_text'] ) : ?>
							<a class="ab-btn ab-btn--light ab-btn--lg" href="<?php echo esc_url( $s['btn2_url']['url'] ?? '#' ); ?>">
								<?php abidar_icon( 'phone', true, array( 'width' => 16, 'height' => 16 ) ); ?>
								<span><?php echo esc_html( $s['btn2_text'] ); ?></span>
							</a>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( $stats ) : ?>
					<div class="ab-hero__stats ab-reveal">
						<?php foreach ( $stats as $stat ) : ?>
							<div class="ab-hero__stat">
								<span class="ab-hero__stat-num"><?php echo esc_html( $stat[0] . $stat[1] ); ?></span>
								<span class="ab-hero__stat-label"><?php echo esc_html( $stat[2] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( 'yes' === $s['show_scroll'] ) : ?>
				<span class="ab-hero__scroll" aria-hidden="true"><?php echo esc_html( abidar_copy( 'scroll' ) ); ?></span>
			<?php endif; ?>
		</section>
		<?php
	}
}
