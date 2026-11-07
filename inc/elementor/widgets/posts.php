<?php
/**
 * Elementor widget: Latest posts.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Posts
 */
class Abidar_Widget_Posts extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-posts';
	}

	public function get_title() {
		return esc_html__( 'Latest posts', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-post-list';
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
				'eyebrow' => 'latest_news',
				'title'   => 'latest_news',
				'text'    => 'latest_news_text',
			)
		);

		$this->add_control(
			'count',
			array(
				'label'   => esc_html__( 'Number of posts', 'abidar' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 9,
			)
		);

		$this->add_control(
			'category',
			array(
				'label'       => esc_html__( 'Category slug (optional)', 'abidar' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
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

		$this->add_control(
			'card_bg',
			array(
				'label'     => esc_html__( 'Background', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-post-card' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-post-card__title, {{WRAPPER}} .ab-post-card__title a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .ab-post-card__title',
			)
		);

		$this->add_control(
			'card_radius',
			array(
				'label'      => esc_html__( 'Radius', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-post-card' => 'border-radius: {{SIZE}}{{UNIT}};' ),
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

		$query_args = array(
			'post_type'           => 'post',
			'posts_per_page'      => (int) $s['count'],
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		);

		if ( $s['category'] ) {
			$query_args['category_name'] = sanitize_title( $s['category'] );
		}

		$query = new \WP_Query( $query_args );

		if ( ! $query->have_posts() ) {
			echo '<p class="ab-container ab-muted">' . esc_html( abidar_copy( 'no_results' ) ) . '</p>';
			return;
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
					<a class="ab-link-arrow" href="<?php echo esc_url( abidar_page_url( 'blog' ) ); ?>">
						<span><?php echo esc_html( abidar_copy( 'view_all' ) ); ?></span>
						<?php abidar_icon( 'arrow-right', true, array( 'width' => 16, 'height' => 16 ) ); ?>
					</a>
				</div>

				<div class="ab-posts-grid ab-stagger">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php get_template_part( 'template-parts/card/post' ); ?>
					<?php endwhile; ?>
				</div>
			</div>
		</section>
		<?php
		wp_reset_postdata();
	}
}
