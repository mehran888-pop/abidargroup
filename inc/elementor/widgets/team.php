<?php
/**
 * Elementor widget: Team grid.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Abidar_Widget_Team
 */
class Abidar_Widget_Team extends Abidar_Widget_Base {

	public function get_name() {
		return 'ab-team';
	}

	public function get_title() {
		return esc_html__( 'Team grid', 'abidar' );
	}

	public function get_icon() {
		return 'eicon-person';
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
				'eyebrow' => 'team_eyebrow',
				'title'   => 'team_title',
				'text'    => 'team_text',
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => esc_html__( 'Source', 'abidar' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'demo',
				'options' => array(
					'demo' => esc_html__( 'Theme demo team', 'abidar' ),
					'cpt'  => esc_html__( 'Team post type', 'abidar' ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'     => esc_html__( 'Columns', 'abidar' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'options'   => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5' ),
				'selectors' => array( '{{WRAPPER}} .ab-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			array(
				'label' => esc_html__( 'Photo', 'abidar' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'name',
			array(
				'label'   => esc_html__( 'Name', 'abidar' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Team member', 'abidar' ),
			)
		);

		$repeater->add_control(
			'role',
			array(
				'label' => esc_html__( 'Role', 'abidar' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'email',
			array(
				'label' => esc_html__( 'Email', 'abidar' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'linkedin',
			array(
				'label' => esc_html__( 'LinkedIn URL', 'abidar' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'members',
			array(
				'label'       => esc_html__( 'Team members', 'abidar' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $this->default_members(),
				'title_field' => '{{{ name }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_card',
			array(
				'label' => esc_html__( 'Member card', 'abidar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'photo_radius',
			array(
				'label'      => esc_html__( 'Photo radius', 'abidar' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ab-team-card__media' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'ratio',
			array(
				'label'     => esc_html__( 'Photo aspect ratio', 'abidar' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4 / 5',
				'options'   => array(
					'1 / 1'  => esc_html__( 'Square', 'abidar' ),
					'4 / 5'  => esc_html__( 'Portrait', 'abidar' ),
					'3 / 4'  => esc_html__( 'Tall portrait', 'abidar' ),
					'16 / 10' => esc_html__( 'Landscape', 'abidar' ),
				),
				'selectors' => array( '{{WRAPPER}} .ab-team-card__media' => 'aspect-ratio: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'label'    => esc_html__( 'Name typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-team-card__name',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'role_typography',
				'label'    => esc_html__( 'Role typography', 'abidar' ),
				'selector' => '{{WRAPPER}} .ab-team-card__role',
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => esc_html__( 'Name colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-team-card__name' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'role_color',
			array(
				'label'     => esc_html__( 'Role colour', 'abidar' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ab-team-card__role' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'     => esc_html__( 'Text alignment', 'abidar' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'center' => array( 'title' => esc_html__( 'Center', 'abidar' ), 'icon' => 'eicon-text-align-center' ),
					'start'  => array( 'title' => esc_html__( 'Start', 'abidar' ), 'icon' => 'eicon-text-align-left' ),
				),
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .ab-team-card' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		$this->add_heading_style_controls();
		$this->add_section_style_controls();
	}

	/**
	 * Default members, localised for the active language.
	 *
	 * @return array
	 */
	protected function default_members() {
		$out = array();

		foreach ( abidar_team_data() as $member ) {
			$out[] = array(
				'image' => array( 'url' => ABIDAR_URI . '/assets/img/' . $member['image'] ),
				'name'  => abidar_localized( $member, 'name' ),
				'role'  => abidar_localized( $member, 'role' ),
				'email' => $member['email'],
			);
		}

		return $out;
	}

	/**
	 * Are the saved members still the untouched defaults?
	 *
	 * @param array $members Saved members.
	 * @return bool
	 */
	protected function members_are_default( $members ) {
		if ( ! is_array( $members ) || count( $members ) !== count( abidar_team_data() ) ) {
			return false;
		}

		$known = array();
		$mails = array();

		foreach ( abidar_team_data() as $member ) {
			foreach ( $member['name'] as $translation ) {
				$known[] = $translation;
			}
			$mails[] = $member['email'];
		}

		foreach ( $members as $member ) {
			if ( ! in_array( $member['name'], $known, true ) || ! in_array( $member['email'], $mails, true ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Render.
	 */
	protected function render() {
		$s    = $this->localize_settings( $this->get_settings_for_display() );
		$team = array();

		if ( 'demo' === $s['source'] && $this->members_are_default( $s['members'] ) ) {
			$s['members'] = $this->default_members();
		}

		if ( 'cpt' === $s['source'] && post_type_exists( 'ab_team' ) ) {
			$query = new \WP_Query(
				array(
					'post_type'      => 'ab_team',
					'posts_per_page' => 8,
					'no_found_rows'  => true,
				)
			);
			while ( $query->have_posts() ) {
				$query->the_post();
				$team[] = array(
					'image' => array( 'url' => get_the_post_thumbnail_url( get_the_ID(), 'abidar-team' ) ),
					'name'  => get_the_title(),
					'role'  => abidar_meta( 'role' ),
					'email' => abidar_meta( 'email' ),
				);
			}
			wp_reset_postdata();
		}

		if ( ! $team ) {
			$team = (array) $s['members'];
		}
		?>
		<section class="ab-section">
			<div class="ab-container">
				<div class="ab-section-head ab-section-head--center ab-reveal">
					<?php if ( $s['head_eyebrow'] ) : ?>
						<span class="ab-eyebrow"><?php echo esc_html( $s['head_eyebrow'] ); ?></span>
					<?php endif; ?>
					<h2><?php echo esc_html( $s['head_title'] ); ?></h2>
					<?php if ( $s['head_text'] ) : ?>
						<p><?php echo esc_html( $s['head_text'] ); ?></p>
					<?php endif; ?>
				</div>

				<div class="ab-grid ab-stagger">
					<?php foreach ( $team as $i => $member ) : ?>
						<article class="ab-team-card ab-reveal" style="--ab-i:<?php echo (int) $i; ?>">
							<div class="ab-team-card__media">
								<?php if ( ! empty( $member['image']['url'] ) ) : ?>
									<img src="<?php echo esc_url( $member['image']['url'] ); ?>" alt="<?php echo esc_attr( $member['name'] ); ?>" loading="lazy">
								<?php else : ?>
									<span class="ab-team-card__initials"><?php echo esc_html( mb_substr( $member['name'], 0, 2 ) ); ?></span>
								<?php endif; ?>
								<div class="ab-team-card__social">
									<?php if ( ! empty( $member['linkedin']['url'] ) ) : ?>
										<a href="<?php echo esc_url( $member['linkedin']['url'] ); ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><?php abidar_icon( 'linkedin', true, array( 'width' => 15, 'height' => 15 ) ); ?></a>
									<?php endif; ?>
									<?php if ( ! empty( $member['email'] ) ) : ?>
										<a href="mailto:<?php echo esc_attr( antispambot( $member['email'] ) ); ?>" aria-label="Email"><?php abidar_icon( 'mail', true, array( 'width' => 15, 'height' => 15 ) ); ?></a>
									<?php endif; ?>
								</div>
							</div>
							<div class="ab-team-card__body">
								<h3 class="ab-team-card__name"><?php echo esc_html( $member['name'] ); ?></h3>
								<p class="ab-team-card__role"><?php echo esc_html( $member['role'] ); ?></p>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
