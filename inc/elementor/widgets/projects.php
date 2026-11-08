<?php
/**
 * Elementor widget: Projects showcase.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Projects
 */
class Abidar_Widget_Projects extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-projects';
	}

	public function get_title() {
		return esc_html__( 'Projects showcase', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
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
				'eyebrow' => 'projects_eyebrow',
				'title'   => 'projects_title',
				'text'    => 'projects_text',
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => esc_html__( 'Source', 'abidar' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'demo',
				'options' => array(
					'demo' => esc_html__( 'Theme demo projects', 'abidar' ),
					'cpt'  => esc_html__( 'Projects post type', 'abidar' ),
				),
			)
		);

		$this->add_control(
			'count',
			array(
				'label'   => esc_html__( 'Number of projects', 'abidar' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 12,
			)
		);

		$this->add_control(
			'show_filter',
			array(
				'label'   => esc_html__( 'Show category filter', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'feature',
			array(
				'label'   => esc_html__( 'Feature first project (wide)', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'surface',
			array(
				'label'   => esc_html__( 'Surface background', 'abidar' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_card',
			array(
				'label' => esc_html__( 'Project card', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_radius',
			array(
				'label'      => esc_html__( 'Radius', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 50 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-project' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'card_min_height',
			array(
				'label'      => esc_html__( 'Minimum height', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 200, 'max' => 700 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-project' => 'min-height: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ab-project__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'     => esc_html__( 'Title typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-project__title',
			)
		);

		$this->add_control(
			'cat_bg',
			array(
				'label'     => esc_html__( 'Category chip background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-project__cat' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'overlay',
			array(
				'label'     => esc_html__( 'Overlay strength', 'abidar' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'     => array( '%' => array( 'min' => 0, 'max' => 100 ) ),
				'default'   => array( 'unit' => '%', 'size' => 94 ),
				'selectors' => array( '{{WRAPPER}} .ab-project::after' => 'opacity: {{SIZE}}%;' ),
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
		$s    = $this->localize_settings( $this->get_settings_for_display() );
		$lang = Abidar_Multilingual::instance()->current_language();

		$projects = array();

		if ( 'cpt' === $s['source'] && post_type_exists( 'ab_project' ) ) {
			$query = new \WP_Query(
				array(
					'post_type'      => 'ab_project',
					'posts_per_page' => (int) $s['count'],
					'no_found_rows'  => true,
				)
			);
			while ( $query->have_posts() ) {
				$query->the_post();
				$terms      = get_the_terms( get_the_ID(), 'ab_project_cat' );
				$projects[] = array(
					'image'    => get_the_post_thumbnail_url( get_the_ID(), 'abidar-card' ),
					'title'    => get_the_title(),
					'category' => $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '',
					'excerpt'  => get_the_excerpt(),
					'url'      => get_permalink(),
				);
			}
			wp_reset_postdata();
		}

		if ( ! $projects ) {
			foreach ( array_slice( abidar_projects_data(), 0, (int) $s['count'] ) as $project ) {
				$projects[] = array(
					'image'    => ABIDAR_URI . '/assets/img/' . $project['image'],
					'title'    => $project['title'][ $lang ] ?? $project['title']['en'],
					'category' => $project['category'][ $lang ] ?? $project['category']['en'],
					'excerpt'  => $project['excerpt'][ $lang ] ?? $project['excerpt']['en'],
					'url'      => abidar_page_url( 'projects' ),
				);
			}
		}

		$cats = array();
		foreach ( $projects as $project ) {
			$key = sanitize_title( $project['category'] );
			if ( $key ) {
				$cats[ $key ] = $project['category'];
			}
		}
		?>
		<section class="ab-section <?php echo 'yes' === $s['surface'] ? 'ab-section--surface' : ''; ?>">
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
					<a class="ab-link-arrow" href="<?php echo esc_url( abidar_page_url( 'projects' ) ); ?>">
						<span><?php echo esc_html( abidar_copy( 'view_all' ) ); ?></span>
						<?php abidar_icon( 'arrow-right', true, array( 'width' => 16, 'height' => 16 ) ); ?>
					</a>
				</div>

				<?php if ( 'yes' === $s['show_filter'] && count( $cats ) > 1 ) : ?>
					<div class="ab-filters" data-filter-group data-filter-target="#ab-projects-grid-<?php echo esc_attr( $this->get_id() ); ?>">
						<button class="ab-filter is-active" type="button" data-filter="*"><?php echo esc_html( abidar_copy( 'all' ) ); ?></button>
						<?php foreach ( $cats as $key => $label ) : ?>
							<button class="ab-filter" type="button" data-filter="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="ab-projects-grid" id="ab-projects-grid-<?php echo esc_attr( $this->get_id() ); ?>">
					<?php foreach ( $projects as $i => $project ) : ?>
						<a class="ab-project ab-reveal <?php echo ( 'yes' === $s['feature'] && 0 === $i ) ? 'ab-project--feature' : ''; ?>"
							href="<?php echo esc_url( $project['url'] ); ?>"
							data-cat="<?php echo esc_attr( sanitize_title( $project['category'] ) ); ?>">
							<?php if ( $project['image'] ) : ?>
								<img src="<?php echo esc_url( $project['image'] ); ?>" alt="<?php echo esc_attr( $project['title'] ); ?>" loading="lazy">
							<?php endif; ?>
							<span class="ab-project__arrow"><?php abidar_icon( 'arrow-up-right', true, array( 'width' => 18, 'height' => 18 ) ); ?></span>
							<div class="ab-project__body">
								<?php if ( $project['category'] ) : ?>
									<span class="ab-project__cat"><?php echo esc_html( $project['category'] ); ?></span>
								<?php endif; ?>
								<h3 class="ab-project__title"><?php echo esc_html( $project['title'] ); ?></h3>
								<p class="ab-project__excerpt"><?php echo esc_html( $project['excerpt'] ); ?></p>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
