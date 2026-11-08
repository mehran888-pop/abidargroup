<?php
/**
 * Section renderers shared by the Elementor widgets and the PHP templates.
 *
 * Every function returns markup for one section. Passing different args is what
 * lets both the theme templates (defaults) and the Elementor widgets (controls)
 * render identical output — one implementation, two entry points.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Normalise args with defaults.
 *
 * @param array $args     Given args.
 * @param array $defaults Defaults.
 * @return array
 */
function ab_section_args( $args, $defaults ) {
	return wp_parse_args( is_array( $args ) ? $args : array(), $defaults );
}

/**
 * Wrapper attributes: id/class plus optional Elementor edit attributes.
 *
 * @param string $class Section class.
 * @param string $id    Section id.
 * @param array  $args  Args.
 * @return string
 */
function ab_section_attrs( $class, $id = '', $args = array() ) {
	$attrs = ' class="' . esc_attr( $class ) . '"';
	if ( $id ) {
		$attrs .= ' id="' . esc_attr( $id ) . '"';
	}
	if ( ! empty( $args['anchor'] ) ) {
		$attrs .= ' id="' . esc_attr( $args['anchor'] ) . '"';
	}
	return $attrs;
}

/* ═══════════════════════════════════════════════════
   HERO
   ═══════════════════════════════════════════════════ */
/**
 * Render the hero section.
 *
 * @param array $args Args.
 */
function ab_render_hero( $args = array() ) {
	$defaults = array(
		'eyebrow'    => abidar_copy( 'hero_eyebrow' ),
		'title'      => abidar_copy( 'hero_title' ),
		'title_em'   => '',
		'text'       => abidar_copy( 'hero_text' ),
		'btn1_text'  => abidar_copy( 'hero_cta1' ),
		'btn1_url'   => abidar_page_url( 'projects' ),
		'btn2_text'  => abidar_copy( 'hero_cta2' ),
		'btn2_url'   => '#contact',
		'image'      => ABIDAR_URI . '/assets/img/hero-mine.jpg',
		'show_stats' => true,
		'show_scroll'=> true,
		'stat1_num'  => '248',
		'stat1_suf'  => '+',
		'stat1_lbl'  => abidar_copy( 'hero_stat1' ),
		'stat2_num'  => '22',
		'stat2_suf'  => '',
		'stat2_lbl'  => abidar_copy( 'hero_stat2' ),
		'stat3_num'  => '412',
		'stat3_suf'  => '',
		'stat3_lbl'  => abidar_copy( 'hero_stat3' ),
		'stat4_num'  => '9',
		'stat4_suf'  => '',
		'stat4_lbl'  => abidar_copy( 'hero_stat4' ),
		'class'      => '',
		'anchor'     => 'top',
	);

	$args  = ab_section_args( $args, $defaults );
	$stats = array(
		array( $args['stat1_num'], $args['stat1_suf'], $args['stat1_lbl'] ),
		array( $args['stat2_num'], $args['stat2_suf'], $args['stat2_lbl'] ),
		array( $args['stat3_num'], $args['stat3_suf'], $args['stat3_lbl'] ),
		array( $args['stat4_num'], $args['stat4_suf'], $args['stat4_lbl'] ),
	);
	?>
	<section<?php echo ab_section_attrs( 'ab-hero ' . $args['class'], '', $args ); // phpcs:ignore ?>>

		<div class="ab-hero__bg">
			<?php if ( $args['image'] ) : ?>
				<img src="<?php echo esc_url( $args['image'] ); ?>" alt="" fetchpriority="high" decoding="async">
			<?php endif; ?>
		</div>

		<div class="ab-container ab-hero__inner">
			<div class="ab-hero__content">
				<?php if ( $args['eyebrow'] ) : ?>
					<span class="ab-eyebrow ab-eyebrow--light ab-reveal"><?php echo esc_html( $args['eyebrow'] ); ?></span>
				<?php endif; ?>

				<h1 class="ab-hero__title ab-reveal">
					<?php
					if ( $args['title_em'] ) {
						echo wp_kses( str_replace( $args['title_em'], '<em>' . $args['title_em'] . '</em>', esc_html( $args['title'] ) ), array( 'em' => array() ) );
					} else {
						echo esc_html( $args['title'] );
					}
					?>
				</h1>

				<?php if ( $args['text'] ) : ?>
					<p class="ab-hero__text ab-reveal"><?php echo esc_html( $args['text'] ); ?></p>
				<?php endif; ?>

				<div class="ab-hero__actions ab-reveal">
					<?php if ( $args['btn1_text'] ) : ?>
						<a class="ab-btn ab-btn--accent ab-btn--lg" href="<?php echo esc_url( $args['btn1_url'] ); ?>">
							<span><?php echo esc_html( $args['btn1_text'] ); ?></span>
							<?php abidar_icon( 'arrow-right', true, array( 'width' => 17, 'height' => 17 ) ); ?>
						</a>
					<?php endif; ?>
					<?php if ( $args['btn2_text'] ) : ?>
						<a class="ab-btn ab-btn--light ab-btn--lg" href="<?php echo esc_url( $args['btn2_url'] ); ?>">
							<?php abidar_icon( 'phone', true, array( 'width' => 16, 'height' => 16 ) ); ?>
							<span><?php echo esc_html( $args['btn2_text'] ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $args['show_stats'] ) : ?>
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

		<?php if ( $args['show_scroll'] ) : ?>
			<span class="ab-hero__scroll" aria-hidden="true"><?php echo esc_html( abidar_copy( 'scroll' ) ); ?></span>
		<?php endif; ?>
	</section>
	<?php
}

/* ═══════════════════════════════════════════════════
   MARQUEE
   ═══════════════════════════════════════════════════ */
/**
 * Render a client logo marquee.
 *
 * @param array $args Args.
 */
function ab_render_marquee( $args = array() ) {
	$defaults = array(
		'title'    => abidar_copy( 'clients_title' ),
		'items'    => array( 'Sarvestan Cement', 'National Gas Co.', 'Zagros Minerals', 'Shanghai Stone Co.', 'Petrop Pars', 'Alborz Kansar', 'Persian Gulf Terminal', 'Abidar Stone' ),
		'dark'     => false,
		'speed'    => 38,
		'class'    => '',
		'show_title' => true,
	);

	$args = ab_section_args( $args, $defaults );
	?>
	<div class="ab-section ab-section--tight <?php echo $args['dark'] ? 'ab-section--dark' : ''; ?> <?php echo esc_attr( $args['class'] ); ?>">
		<?php if ( $args['show_title'] && $args['title'] ) : ?>
			<div class="ab-container">
				<p class="ab-center ab-muted" style="font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;margin-bottom:1.25rem">
					<?php echo esc_html( $args['title'] ); ?>
				</p>
			</div>
		<?php endif; ?>

		<div class="ab-marquee">
			<div class="ab-marquee__track" style="--ab-marquee-duration:<?php echo (int) $args['speed']; ?>s">
				<?php foreach ( $args['items'] as $item ) : ?>
					<span class="ab-marquee__item">
						<?php abidar_icon( 'mountain', true, array( 'width' => 20, 'height' => 20 ) ); ?>
						<?php echo esc_html( $item ); ?>
					</span>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
}

/* ═══════════════════════════════════════════════════
   SERVICES
   ═══════════════════════════════════════════════════ */
/**
 * Render the services grid.
 *
 * @param array $args Args.
 */
function ab_render_services( $args = array() ) {
	$defaults = array(
		'eyebrow'   => abidar_copy( 'services_eyebrow' ),
		'title'     => abidar_copy( 'services_title' ),
		'text'      => abidar_copy( 'services_text' ),
		'style'     => 'card', // card | image
		'columns'   => 4,
		'show_list' => true,
		'show_more' => true,
		'surface'   => true,
		'items'     => abidar_services_data(),
		'class'     => '',
		'anchor'    => 'services',
		'link_page' => true,
	);

	$args = ab_section_args( $args, $defaults );
	?>
	<section<?php echo ab_section_attrs( 'ab-section ' . ( $args['surface'] ? 'ab-section--surface ' : '' ) . $args['class'], '', $args ); // phpcs:ignore ?>>
		<div class="ab-container">
			<div class="ab-section-head ab-section-head--split ab-reveal">
				<div>
					<?php if ( $args['eyebrow'] ) : ?>
						<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
					<?php endif; ?>
					<h2><?php echo esc_html( $args['title'] ); ?></h2>
					<?php if ( $args['text'] ) : ?>
						<p><?php echo esc_html( $args['text'] ); ?></p>
					<?php endif; ?>
				</div>
				<a class="ab-link-arrow" href="<?php echo esc_url( abidar_page_url( 'services' ) ); ?>">
					<span><?php echo esc_html( abidar_copy( 'view_all' ) ); ?></span>
					<?php abidar_icon( 'arrow-right', true, array( 'width' => 16, 'height' => 16 ) ); ?>
				</a>
			</div>

			<div class="ab-grid ab-grid--<?php echo (int) $args['columns']; ?> ab-stagger">
				<?php
				foreach ( $args['items'] as $i => $service ) :
					$title = abidar_localized( $service, 'title' );
					$text  = abidar_localized( $service, 'text' );
					$icon  = isset( $service['icon'] ) ? $service['icon'] : 'gear';
					?>
					<article class="ab-card ab-service <?php echo 'image' === $args['style'] ? 'ab-service--image' : ''; ?> ab-reveal" style="--ab-i:<?php echo (int) $i; ?>">
						<?php if ( 'image' === $args['style'] && ! empty( $service['image'] ) ) : ?>
							<div class="ab-service__media">
								<img src="<?php echo esc_url( ABIDAR_URI . '/assets/img/' . $service['image'] ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" decoding="async">
								<span class="ab-service__icon"><?php abidar_icon( $icon, true ); ?></span>
							</div>
						<?php else : ?>
							<span class="ab-service__icon"><?php abidar_icon( $icon, true ); ?></span>
						<?php endif; ?>

						<div class="<?php echo 'image' === $args['style'] ? 'ab-service__body' : ''; ?>" <?php echo 'image' === $args['style'] ? 'style="padding-top:2rem"' : ''; ?>>
							<?php if ( 'card' === $args['style'] ) : ?>
								<span class="ab-service__num"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
							<?php endif; ?>

							<h3 class="ab-service__title"><?php echo esc_html( $title ); ?></h3>
							<p class="ab-service__text"><?php echo esc_html( $text ); ?></p>

							<?php if ( $args['show_list'] && ! empty( $service['items'] ) ) : ?>
								<ul class="ab-service__list">
									<?php foreach ( $service['items'] as $feature ) : ?>
										<li>
											<?php abidar_icon( 'check', true, array( 'width' => 15, 'height' => 15 ) ); ?>
											<span><?php echo esc_html( abidar_localized( array( 'v' => $feature ), 'v' ) ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( $args['show_more'] ) : ?>
								<a class="ab-service__more" href="<?php echo esc_url( abidar_page_url( 'services' ) ); ?>">
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

/* ═══════════════════════════════════════════════════
   ABOUT
   ═══════════════════════════════════════════════════ */
/**
 * Render the about split section.
 *
 * @param array $args Args.
 */
function ab_render_about( $args = array() ) {
	$defaults = array(
		'eyebrow'  => abidar_copy( 'about_eyebrow' ),
		'title'    => abidar_copy( 'about_title' ),
		'text'     => abidar_copy( 'about_text' ),
		'points'   => array( abidar_copy( 'about_point1' ), abidar_copy( 'about_point2' ), abidar_copy( 'about_point3' ) ),
		'image'    => ABIDAR_URI . '/assets/img/about.jpg',
		'badge_num'=> abidar_copy( 'about_badge_num' ),
		'badge_txt'=> abidar_copy( 'about_badge_txt' ),
		'sig_name' => abidar_copy( 'about_sig' ),
		'sig_role' => abidar_copy( 'about_sig_role' ),
		'btn_text' => abidar_copy( 'learn_more' ),
		'btn_url'  => '',
		'surface'  => false,
		'class'    => '',
		'anchor'   => 'about',
	);

	$args   = ab_section_args( $args, $defaults );
	$btn    = $args['btn_url'] ? $args['btn_url'] : abidar_page_url( 'about' );
	?>
	<section<?php echo ab_section_attrs( 'ab-section ' . ( $args['surface'] ? 'ab-section--surface ' : '' ) . $args['class'], '', $args ); // phpcs:ignore ?>>
		<div class="ab-container">
			<div class="ab-about">
				<div class="ab-about__media ab-reveal">
					<img src="<?php echo esc_url( $args['image'] ); ?>" alt="<?php echo esc_attr( $args['title'] ); ?>" loading="lazy" decoding="async">
					<?php if ( $args['badge_num'] ) : ?>
						<div class="ab-badge">
							<span class="ab-badge__num"><?php echo esc_html( $args['badge_num'] ); ?></span>
							<span class="ab-badge__text"><?php echo esc_html( $args['badge_txt'] ); ?></span>
						</div>
					<?php endif; ?>
				</div>

				<div class="ab-reveal">
					<?php if ( $args['eyebrow'] ) : ?>
						<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
					<?php endif; ?>
					<h2><?php echo esc_html( $args['title'] ); ?></h2>
					<p class="ab-lead"><?php echo esc_html( $args['text'] ); ?></p>

					<?php if ( $args['points'] ) : ?>
						<ul class="ab-checklist">
							<?php foreach ( $args['points'] as $point ) : ?>
								<li>
									<span class="ab-checklist__icon"><?php abidar_icon( 'check', true, array( 'width' => 13, 'height' => 13 ) ); ?></span>
									<span><?php echo esc_html( $point ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( $args['btn_text'] ) : ?>
						<a class="ab-btn ab-btn--primary" href="<?php echo esc_url( $btn ); ?>">
							<span><?php echo esc_html( $args['btn_text'] ); ?></span>
							<?php abidar_icon( 'arrow-right', true, array( 'width' => 16, 'height' => 16 ) ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $args['sig_name'] ) : ?>
						<div class="ab-signature">
							<span class="ab-quote__avatar"><?php echo esc_html( 'RA' ); ?></span>
							<span>
								<span class="ab-signature__name"><?php echo esc_html( $args['sig_name'] ); ?></span><br>
								<span class="ab-signature__role"><?php echo esc_html( $args['sig_role'] ); ?></span>
							</span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/* ═══════════════════════════════════════════════════
   COUNTERS
   ═══════════════════════════════════════════════════ */
/**
 * Render animated counters.
 *
 * @param array $args Args.
 */
function ab_render_counters( $args = array() ) {
	$defaults = array(
		'eyebrow' => abidar_copy( 'numbers_eyebrow' ),
		'title'   => abidar_copy( 'numbers_title' ),
		'text'    => '',
		'items'   => abidar_stats_data(),
		'dark'    => true,
		'columns' => 4,
		'bg'      => '',
		'class'   => '',
		'anchor'  => 'numbers',
	);

	$args = ab_section_args( $args, $defaults );
	$bg   = $args['bg'] ? $args['bg'] : ABIDAR_URI . '/assets/img/pattern-dark.jpg';
	?>
	<section<?php echo ab_section_attrs( 'ab-section ' . ( $args['dark'] ? 'ab-section--dark ab-section--pattern' : '' ) . ' ' . $args['class'], '', $args ); // phpcs:ignore ?>
		style="--ab-pattern:url('<?php echo esc_url( $bg ); ?>')">
		<div class="ab-container">
			<?php if ( $args['title'] ) : ?>
				<div class="ab-section-head ab-section-head--split ab-reveal">
					<div>
						<?php if ( $args['eyebrow'] ) : ?>
							<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
						<?php endif; ?>
						<h2><?php echo esc_html( $args['title'] ); ?></h2>
					</div>
					<?php if ( $args['text'] ) : ?>
						<p class="ab-muted" style="max-width:38ch"><?php echo esc_html( $args['text'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="ab-counters <?php echo $args['dark'] ? '' : 'ab-counters--light'; ?> ab-stagger">
				<?php foreach ( $args['items'] as $i => $item ) : ?>
					<div class="ab-counter ab-reveal" style="--ab-i:<?php echo (int) $i; ?>">
						<span class="ab-counter__icon"><?php abidar_icon( $item['icon'] ?? 'award', true, array( 'width' => 26, 'height' => 26 ) ); ?></span>
						<span class="ab-counter__num">
							<span data-counter="<?php echo esc_attr( $item['number'] ); ?>"><?php echo esc_html( $item['number'] ); ?></span>
							<span><?php echo esc_html( $item['suffix'] ?? '' ); ?></span>
						</span>
						<span class="ab-counter__label"><?php echo esc_html( abidar_localized( $item, 'label' ) ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/* ═══════════════════════════════════════════════════
   PROJECTS
   ═══════════════════════════════════════════════════ */
/**
 * Render the project showcase.
 *
 * @param array $args Args.
 */
function ab_render_projects( $args = array() ) {
	$defaults = array(
		'eyebrow'   => abidar_copy( 'projects_eyebrow' ),
		'title'     => abidar_copy( 'projects_title' ),
		'text'      => abidar_copy( 'projects_text' ),
		'count'     => 6,
		'columns'   => 3,
		'feature'   => true,
		'show_filter'=> true,
		'source'    => 'demo', // demo | cpt
		'surface'   => true,
		'class'     => '',
		'anchor'    => 'projects',
	);

	$args = ab_section_args( $args, $defaults );

	$projects = array();

	if ( 'cpt' === $args['source'] && post_type_exists( 'ab_project' ) ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'ab_project',
				'posts_per_page' => (int) $args['count'],
				'no_found_rows'  => true,
			)
		);
		while ( $query->have_posts() ) {
			$query->the_post();
			$terms = get_the_terms( get_the_ID(), 'ab_project_cat' );
			$projects[] = array(
				'image'    => get_the_post_thumbnail_url( get_the_ID(), 'abidar-card' ),
				'title'    => get_the_title(),
				'category' => array( 'en' => $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '' ),
				'excerpt'  => get_the_excerpt(),
				'url'      => get_permalink(),
			);
		}
		wp_reset_postdata();
	}

	if ( ! $projects ) {
		$lang = Abidar_Multilingual::instance()->current_language();
		foreach ( array_slice( abidar_projects_data(), 0, (int) $args['count'] ) as $project ) {
			$projects[] = array(
				'image'    => ABIDAR_URI . '/assets/img/' . $project['image'],
				'title'    => $project['title'][ $lang ] ?? $project['title']['en'],
				'category' => $project['category'][ $lang ] ?? $project['category']['en'],
				'excerpt'  => $project['excerpt'][ $lang ] ?? $project['excerpt']['en'],
				'url'      => abidar_page_url( 'projects' ) . '#' . sanitize_title( $project['title']['en'] ),
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
	<section<?php echo ab_section_attrs( 'ab-section ' . ( $args['surface'] ? 'ab-section--surface ' : '' ) . $args['class'], '', $args ); // phpcs:ignore ?>>
		<div class="ab-container">
			<div class="ab-section-head ab-section-head--split ab-reveal">
				<div>
					<?php if ( $args['eyebrow'] ) : ?>
						<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
					<?php endif; ?>
					<h2><?php echo esc_html( $args['title'] ); ?></h2>
					<?php if ( $args['text'] ) : ?>
						<p><?php echo esc_html( $args['text'] ); ?></p>
					<?php endif; ?>
				</div>
				<a class="ab-link-arrow" href="<?php echo esc_url( abidar_page_url( 'projects' ) ); ?>">
					<span><?php echo esc_html( abidar_copy( 'view_all' ) ); ?></span>
					<?php abidar_icon( 'arrow-right', true, array( 'width' => 16, 'height' => 16 ) ); ?>
				</a>
			</div>

			<?php if ( $args['show_filter'] && count( $cats ) > 1 ) : ?>
				<div class="ab-filters" data-filter-group data-filter-target="#ab-projects-grid">
					<button class="ab-filter is-active" type="button" data-filter="*"><?php echo esc_html( abidar_copy( 'all' ) ); ?></button>
					<?php foreach ( $cats as $key => $label ) : ?>
						<button class="ab-filter" type="button" data-filter="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="ab-projects-grid" id="ab-projects-grid" style="--ab-cols:<?php echo (int) $args['columns']; ?>">
				<?php foreach ( $projects as $i => $project ) : ?>
					<a class="ab-project ab-reveal <?php echo ( $args['feature'] && 0 === $i ) ? 'ab-project--feature' : ''; ?>"
						href="<?php echo esc_url( $project['url'] ); ?>"
						data-cat="<?php echo esc_attr( sanitize_title( $project['category'] ) ); ?>">
						<?php if ( $project['image'] ) : ?>
							<img src="<?php echo esc_url( $project['image'] ); ?>" alt="<?php echo esc_attr( $project['title'] ); ?>" loading="lazy" decoding="async">
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

/* ═══════════════════════════════════════════════════
   PROCESS
   ═══════════════════════════════════════════════════ */
/**
 * Render the process steps.
 *
 * @param array $args Args.
 */
function ab_render_process( $args = array() ) {
	$defaults = array(
		'eyebrow' => abidar_copy( 'process_eyebrow' ),
		'title'   => abidar_copy( 'process_title' ),
		'text'    => '',
		'steps'   => array(
			array( 'title' => abidar_copy( 'process_step1' ), 'text' => abidar_copy( 'process_step1_t' ) ),
			array( 'title' => abidar_copy( 'process_step2' ), 'text' => abidar_copy( 'process_step2_t' ) ),
			array( 'title' => abidar_copy( 'process_step3' ), 'text' => abidar_copy( 'process_step3_t' ) ),
			array( 'title' => abidar_copy( 'process_step4' ), 'text' => abidar_copy( 'process_step4_t' ) ),
		),
		'dark'    => false,
		'surface' => false,
		'class'   => '',
		'anchor'  => 'process',
	);

	$args = ab_section_args( $args, $defaults );
	?>
	<section<?php echo ab_section_attrs( 'ab-section ' . ( $args['dark'] ? 'ab-section--dark ' : '' ) . ( $args['surface'] ? 'ab-section--surface ' : '' ) . $args['class'], '', $args ); // phpcs:ignore ?>>
		<div class="ab-container">
			<div class="ab-section-head ab-section-head--center ab-reveal">
				<?php if ( $args['eyebrow'] ) : ?>
					<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
				<?php endif; ?>
				<h2><?php echo esc_html( $args['title'] ); ?></h2>
				<?php if ( $args['text'] ) : ?>
					<p><?php echo esc_html( $args['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="ab-process ab-stagger">
				<?php foreach ( $args['steps'] as $i => $step ) : ?>
					<div class="ab-step ab-reveal" style="--ab-i:<?php echo (int) $i; ?>">
						<span class="ab-step__dot"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
						<h3 class="ab-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
						<p class="ab-step__text"><?php echo esc_html( $step['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/* ═══════════════════════════════════════════════════
   TEAM
   ═══════════════════════════════════════════════════ */
/**
 * Render the team grid.
 *
 * @param array $args Args.
 */
function ab_render_team( $args = array() ) {
	$defaults = array(
		'eyebrow' => abidar_copy( 'team_eyebrow' ),
		'title'   => abidar_copy( 'team_title' ),
		'text'    => abidar_copy( 'team_text' ),
		'columns' => 4,
		'count'   => 4,
		'source'  => 'demo',
		'surface' => false,
		'class'   => '',
		'anchor'  => 'team',
	);

	$args  = ab_section_args( $args, $defaults );
	$lang  = Abidar_Multilingual::instance()->current_language();
	$team  = array();

	if ( 'cpt' === $args['source'] && post_type_exists( 'ab_team' ) ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'ab_team',
				'posts_per_page' => (int) $args['count'],
				'no_found_rows'  => true,
			)
		);
		while ( $query->have_posts() ) {
			$query->the_post();
			$team[] = array(
				'image' => get_the_post_thumbnail_url( get_the_ID(), 'abidar-team' ),
				'name'  => get_the_title(),
				'role'  => abidar_meta( 'role' ),
				'email' => abidar_meta( 'email' ),
			);
		}
		wp_reset_postdata();
	}

	if ( ! $team ) {
		foreach ( array_slice( abidar_team_data(), 0, (int) $args['count'] ) as $member ) {
			$team[] = array(
				'image' => ABIDAR_URI . '/assets/img/' . $member['image'],
				'name'  => $member['name'][ $lang ] ?? $member['name']['en'],
				'role'  => $member['role'][ $lang ] ?? $member['role']['en'],
				'email' => $member['email'],
				'initials' => $member['initials'],
			);
		}
	}
	?>
	<section<?php echo ab_section_attrs( 'ab-section ' . ( $args['surface'] ? 'ab-section--surface ' : '' ) . $args['class'], '', $args ); // phpcs:ignore ?>>
		<div class="ab-container">
			<div class="ab-section-head ab-section-head--center ab-reveal">
				<?php if ( $args['eyebrow'] ) : ?>
					<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
				<?php endif; ?>
				<h2><?php echo esc_html( $args['title'] ); ?></h2>
				<?php if ( $args['text'] ) : ?>
					<p><?php echo esc_html( $args['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="ab-grid ab-grid--<?php echo (int) $args['columns']; ?> ab-stagger">
				<?php foreach ( $team as $i => $member ) : ?>
					<article class="ab-team-card ab-reveal" style="--ab-i:<?php echo (int) $i; ?>">
						<div class="ab-team-card__media">
							<?php if ( ! empty( $member['image'] ) ) : ?>
								<img src="<?php echo esc_url( $member['image'] ); ?>" alt="<?php echo esc_attr( $member['name'] ); ?>" loading="lazy" decoding="async">
							<?php else : ?>
								<span class="ab-team-card__initials"><?php echo esc_html( $member['initials'] ?? mb_substr( $member['name'], 0, 2 ) ); ?></span>
							<?php endif; ?>
							<div class="ab-team-card__social">
								<a href="#" aria-label="LinkedIn"><?php abidar_icon( 'linkedin', true, array( 'width' => 15, 'height' => 15 ) ); ?></a>
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

/* ═══════════════════════════════════════════════════
   TESTIMONIALS
   ═══════════════════════════════════════════════════ */
/**
 * Render testimonials.
 *
 * @param array $args Args.
 */
function ab_render_testimonials( $args = array() ) {
	$defaults = array(
		'eyebrow' => abidar_copy( 'testimonials_eyebrow' ),
		'title'   => abidar_copy( 'testimonials_title' ),
		'items'   => abidar_testimonials_data(),
		'surface' => true,
		'class'   => '',
		'anchor'  => 'testimonials',
	);

	$args = ab_section_args( $args, $defaults );
	$lang = Abidar_Multilingual::instance()->current_language();
	?>
	<section<?php echo ab_section_attrs( 'ab-section ' . ( $args['surface'] ? 'ab-section--surface ' : '' ) . $args['class'], '', $args ); // phpcs:ignore ?>>
		<div class="ab-container">
			<div class="ab-section-head ab-section-head--center ab-reveal">
				<?php if ( $args['eyebrow'] ) : ?>
					<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
				<?php endif; ?>
				<h2><?php echo esc_html( $args['title'] ); ?></h2>
			</div>

			<div class="ab-testimonials ab-stagger">
				<?php foreach ( $args['items'] as $i => $item ) : ?>
					<figure class="ab-quote ab-reveal" style="--ab-i:<?php echo (int) $i; ?>">
						<span class="ab-quote__mark"><?php abidar_icon( 'quote', true, array( 'width' => 30, 'height' => 30 ) ); ?></span>
						<div class="ab-quote__stars">
							<?php for ( $s = 0; $s < 5; $s++ ) { abidar_icon( 'star', true, array( 'width' => 14, 'height' => 14, 'fill' => 'currentColor' ) ); } ?>
						</div>
						<blockquote class="ab-quote__text"><?php echo esc_html( $item['quote'][ $lang ] ?? $item['quote']['en'] ); ?></blockquote>
						<figcaption class="ab-quote__author">
							<span class="ab-quote__avatar"><?php echo esc_html( $item['initials'] ?? '' ); ?></span>
							<span>
								<span class="ab-quote__name"><?php echo esc_html( $item['name'][ $lang ] ?? $item['name']['en'] ); ?></span><br>
								<span class="ab-quote__role"><?php echo esc_html( $item['role'][ $lang ] ?? $item['role']['en'] ); ?></span>
							</span>
						</figcaption>
					</figure>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/* ═══════════════════════════════════════════════════
   CTA
   ═══════════════════════════════════════════════════ */
/**
 * Render the CTA band.
 *
 * @param array $args Args.
 */
function ab_render_cta( $args = array() ) {
	$defaults = array(
		'eyebrow'  => abidar_copy( 'cta_eyebrow' ),
		'title'    => abidar_copy( 'cta_title' ),
		'text'     => abidar_copy( 'cta_text' ),
		'btn_text' => abidar_copy( 'cta_button' ),
		'btn_url'  => '#contact',
		'phone'    => abidar_option( 'topbar_phone', '+98 21 8800 1234' ),
		'image'    => ABIDAR_URI . '/assets/img/cta.jpg',
		'class'    => '',
		'anchor'   => 'cta',
	);

	$args = ab_section_args( $args, $defaults );
	?>
	<section<?php echo ab_section_attrs( 'ab-section ' . $args['class'], '', $args ); // phpcs:ignore ?>>
		<div class="ab-container">
			<div class="ab-cta ab-reveal">
				<?php if ( $args['image'] ) : ?>
					<div class="ab-cta__bg">
						<img src="<?php echo esc_url( $args['image'] ); ?>" alt="" loading="lazy" decoding="async">
					</div>
				<?php endif; ?>

				<div class="ab-cta__inner">
					<div>
						<?php if ( $args['eyebrow'] ) : ?>
							<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
						<?php endif; ?>
						<h2 class="ab-cta__title"><?php echo esc_html( $args['title'] ); ?></h2>
						<?php if ( $args['text'] ) : ?>
							<p class="ab-cta__text"><?php echo esc_html( $args['text'] ); ?></p>
						<?php endif; ?>
					</div>

					<div class="ab-cta__actions">
						<a class="ab-btn ab-btn--accent ab-btn--lg" href="<?php echo esc_url( $args['btn_url'] ); ?>">
							<span><?php echo esc_html( $args['btn_text'] ); ?></span>
							<?php abidar_icon( 'arrow-right', true, array( 'width' => 17, 'height' => 17 ) ); ?>
						</a>
						<?php if ( $args['phone'] ) : ?>
							<a class="ab-cta__phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $args['phone'] ) ); ?>">
								<?php abidar_icon( 'phone', true, array( 'width' => 15, 'height' => 15 ) ); ?>
								<span><?php echo esc_html( abidar_copy( 'cta_phone' ) ); ?></span>
								<strong dir="ltr"><?php echo esc_html( $args['phone'] ); ?></strong>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/* ═══════════════════════════════════════════════════
   CONTACT
   ═══════════════════════════════════════════════════ */
/**
 * Render the contact section with a working form.
 *
 * @param array $args Args.
 */
function ab_render_contact( $args = array() ) {
	$defaults = array(
		'eyebrow'  => abidar_copy( 'contact_eyebrow' ),
		'title'    => abidar_copy( 'contact_title' ),
		'text'     => abidar_copy( 'contact_text' ),
		'show_form'=> true,
		'show_map' => false,
		'surface'  => false,
		'dark'     => false,
		'class'    => '',
		'anchor'   => 'contact',
	);

	$args = ab_section_args( $args, $defaults );
	?>
	<section<?php echo ab_section_attrs( 'ab-section ' . ( $args['surface'] ? 'ab-section--surface ' : '' ) . ( $args['dark'] ? 'ab-section--dark ' : '' ) . $args['class'], '', $args ); // phpcs:ignore ?>>
		<div class="ab-container">
			<div class="ab-contact">
				<div class="ab-reveal">
					<?php if ( $args['eyebrow'] ) : ?>
						<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
					<?php endif; ?>
					<h2><?php echo esc_html( $args['title'] ); ?></h2>
					<?php if ( $args['text'] ) : ?>
						<p><?php echo esc_html( $args['text'] ); ?></p>
					<?php endif; ?>

					<div style="margin-top:2rem">
						<?php abidar_contact_rows(); ?>
					</div>

					<?php if ( $args['show_map'] ) : ?>
						<div class="ab-map" style="margin-top:2rem">
							<?php
							$map = abidar_option( 'map_embed', '' );
							if ( $map ) {
								echo wp_kses( $map, array( 'iframe' => array( 'src' => true, 'width' => true, 'height' => true, 'style' => true, 'loading' => true, 'title' => true, 'allowfullscreen' => true, 'referrerpolicy' => true ) ) );
							} else {
								echo '<iframe title="Abidar Group location" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox=51.38%2C35.72%2C51.44%2C35.76&amp;layer=mapnik"></iframe>';
							}
							?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( $args['show_form'] ) : ?>
					<div class="ab-reveal">
						<?php ab_render_contact_form(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * The contact form markup.
 *
 * @param array $args Args (class, dark).
 */
function ab_render_contact_form( $args = array() ) {
	$args = ab_section_args(
		$args,
		array(
			'class' => '',
		)
	);
	?>
	<form class="ab-form ab-card <?php echo esc_attr( $args['class'] ); ?>" style="padding:clamp(1.5rem,3vw,2.5rem)" data-ab-form novalidate>
		<div class="ab-form__msg" role="status" aria-live="polite"></div>

		<div class="ab-form__row">
			<div class="ab-field">
				<label for="ab-name"><?php echo esc_html( abidar_copy( 'form_name' ) ); ?></label>
				<input id="ab-name" name="name" type="text" required autocomplete="name">
				<span class="ab-field__error" style="display:none"><?php echo esc_html( abidar_copy( 'form_required' ) ); ?></span>
			</div>
			<div class="ab-field">
				<label for="ab-email"><?php echo esc_html( abidar_copy( 'form_email' ) ); ?></label>
				<input id="ab-email" name="email" type="email" required autocomplete="email">
				<span class="ab-field__error" style="display:none"><?php echo esc_html( abidar_copy( 'form_email_invalid' ) ); ?></span>
			</div>
		</div>

		<div class="ab-form__row">
			<div class="ab-field">
				<label for="ab-phone"><?php echo esc_html( abidar_copy( 'form_phone' ) ); ?></label>
				<input id="ab-phone" name="phone" type="tel" autocomplete="tel">
			</div>
			<div class="ab-field">
				<label for="ab-company"><?php echo esc_html( abidar_copy( 'form_company' ) ); ?></label>
				<input id="ab-company" name="company" type="text" autocomplete="organization">
			</div>
		</div>

		<div class="ab-field">
			<label for="ab-subject"><?php echo esc_html( abidar_copy( 'form_subject' ) ); ?></label>
			<input id="ab-subject" name="subject" type="text">
		</div>

		<div class="ab-field">
			<label for="ab-message"><?php echo esc_html( abidar_copy( 'form_message' ) ); ?></label>
			<textarea id="ab-message" name="message" required></textarea>
			<span class="ab-field__error" style="display:none"><?php echo esc_html( abidar_copy( 'form_required' ) ); ?></span>
		</div>

		<div class="ab-honeypot" aria-hidden="true">
			<label for="ab-website">Website</label>
			<input id="ab-website" name="ab_website" type="text" tabindex="-1" autocomplete="off">
		</div>

		<div class="ab-form__foot">
			<label class="ab-check">
				<input type="checkbox" name="consent" required>
				<span><?php echo esc_html( abidar_copy( 'form_consent' ) ); ?></span>
			</label>
			<button class="ab-btn ab-btn--primary" type="submit">
				<span><?php echo esc_html( abidar_copy( 'form_submit' ) ); ?></span>
				<?php abidar_icon( 'send', true, array( 'width' => 16, 'height' => 16 ) ); ?>
			</button>
		</div>
	</form>
	<?php
}

/* ═══════════════════════════════════════════════════
   ACCORDION / FAQ
   ═══════════════════════════════════════════════════ */
/**
 * Render the FAQ accordion.
 *
 * @param array $args Args.
 */
function ab_render_accordion( $args = array() ) {
	$defaults = array(
		'eyebrow' => abidar_copy( 'faq_eyebrow' ),
		'title'   => abidar_copy( 'faq_title' ),
		'text'    => '',
		'items'   => array(
			array( 'q' => abidar_copy( 'faq_q1' ), 'a' => abidar_copy( 'faq_a1' ) ),
			array( 'q' => abidar_copy( 'faq_q2' ), 'a' => abidar_copy( 'faq_a2' ) ),
			array( 'q' => abidar_copy( 'faq_q3' ), 'a' => abidar_copy( 'faq_a3' ) ),
			array( 'q' => abidar_copy( 'faq_q4' ), 'a' => abidar_copy( 'faq_a4' ) ),
		),
		'single'  => true,
		'surface' => true,
		'class'   => '',
		'anchor'  => 'faq',
	);

	$args = ab_section_args( $args, $defaults );
	?>
	<section<?php echo ab_section_attrs( 'ab-section ' . ( $args['surface'] ? 'ab-section--surface ' : '' ) . $args['class'], '', $args ); // phpcs:ignore ?>>
		<div class="ab-container">
			<div class="ab-section-head ab-section-head--center ab-reveal">
				<?php if ( $args['eyebrow'] ) : ?>
					<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
				<?php endif; ?>
				<h2><?php echo esc_html( $args['title'] ); ?></h2>
				<?php if ( $args['text'] ) : ?>
					<p><?php echo esc_html( $args['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="ab-accordion" data-single="<?php echo $args['single'] ? 'true' : 'false'; ?>">
				<?php foreach ( $args['items'] as $i => $item ) : ?>
					<div class="ab-accordion__item <?php echo 0 === $i ? 'is-open' : ''; ?>">
						<button class="ab-accordion__btn" type="button" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>">
							<span><?php echo esc_html( $item['q'] ); ?></span>
							<span class="ab-accordion__icon"><?php abidar_icon( 'plus', true, array( 'width' => 14, 'height' => 14 ) ); ?></span>
						</button>
						<div class="ab-accordion__panel" <?php echo 0 === $i ? 'style="max-height:400px"' : ''; ?>>
							<div class="ab-accordion__panel-inner"><?php echo wp_kses_post( $item['a'] ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/* ═══════════════════════════════════════════════════
   POSTS
   ═══════════════════════════════════════════════════ */
/**
 * Render the latest posts.
 *
 * @param array $args Args.
 */
function ab_render_posts( $args = array() ) {
	$defaults = array(
		'eyebrow' => abidar_copy( 'latest_news' ),
		'title'   => abidar_copy( 'latest_news' ),
		'text'    => abidar_copy( 'latest_news_text' ),
		'count'   => 3,
		'columns' => 3,
		'surface' => false,
		'class'   => '',
		'anchor'  => 'news',
	);

	$args  = ab_section_args( $args, $defaults );
	$query = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => (int) $args['count'],
			'no_found_rows'  => true,
			'ignore_sticky_posts' => true,
		)
	);

	if ( ! $query->have_posts() ) {
		return;
	}
	?>
	<section<?php echo ab_section_attrs( 'ab-section ' . ( $args['surface'] ? 'ab-section--surface ' : '' ) . $args['class'], '', $args ); // phpcs:ignore ?>>
		<div class="ab-container">
			<div class="ab-section-head ab-section-head--split ab-reveal">
				<div>
					<?php if ( $args['eyebrow'] ) : ?>
						<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
					<?php endif; ?>
					<h2><?php echo esc_html( $args['title'] ); ?></h2>
					<?php if ( $args['text'] ) : ?>
						<p><?php echo esc_html( $args['text'] ); ?></p>
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

/* ═══════════════════════════════════════════════════
   LANGUAGE SWITCHER
   ═══════════════════════════════════════════════════ */
/**
 * Render the language switcher block.
 *
 * @param array $args Args.
 */
function ab_render_language_switcher( $args = array() ) {
	$args = ab_section_args(
		$args,
		array(
			'title'  => '',
			'style'  => 'inline',
			'flags'  => true,
			'center' => true,
		)
	);

	echo '<div class="ab-container ' . ( $args['center'] ? 'ab-center' : '' ) . '">';
	if ( $args['title'] ) {
		echo '<p class="ab-muted" style="font-size:.8rem;letter-spacing:.12em;text-transform:uppercase;margin-bottom:.75rem">' . esc_html( $args['title'] ) . '</p>';
	}
	echo Abidar_Multilingual::instance()->switcher( // phpcs:ignore WordPress.Security.EscapeOutput
		array(
			'style'      => $args['style'],
			'show_flags' => (bool) $args['flags'],
			'class'      => $args['center'] ? 'ab-lang--center' : '',
		)
	);
	echo '</div>';
}

/* ═══════════════════════════════════════════════════
   PAGE TITLE
   ═══════════════════════════════════════════════════ */
/**
 * Render the inner page title.
 *
 * @param array $args Args.
 */
function ab_render_page_title( $args = array() ) {
	$args = ab_section_args(
		$args,
		array(
			'title'      => get_the_title(),
			'subtitle'   => '',
			'breadcrumb' => true,
			'eyebrow'    => '',
			'bg'         => '',
		)
	);

	abidar_page_hero( $args );
}

/* ═══════════════════════════════════════════════════
   SECTOR SPLIT (award-style interactive list)
   ═══════════════════════════════════════════════════ */
/**
 * Render the four sectors as an expanding image list.
 *
 * Each row grows on hover and reveals its photograph — the "editorial list"
 * pattern used by studios and engineering firms, built with no JS.
 *
 * @param array $args Args.
 */
function ab_render_sectors( $args = array() ) {
	$defaults = array(
		'eyebrow' => abidar_copy( 'sectors_eyebrow' ),
		'title'   => abidar_copy( 'sectors_title' ),
		'text'    => abidar_copy( 'sectors_text' ),
		'items'   => array(
			array(
				'num'   => '01',
				'title' => abidar_copy( 'service_mining' ),
				'text'  => abidar_copy( 'service_mining_text' ),
				'image' => ABIDAR_URI . '/assets/img/project1.jpg',
				'tag'   => abidar_copy( 'sector_tag_mining' ),
			),
			array(
				'num'   => '02',
				'title' => abidar_copy( 'service_oil' ),
				'text'  => abidar_copy( 'service_oil_text' ),
				'image' => ABIDAR_URI . '/assets/img/project2.jpg',
				'tag'   => abidar_copy( 'sector_tag_oil' ),
			),
			array(
				'num'   => '03',
				'title' => abidar_copy( 'service_stone' ),
				'text'  => abidar_copy( 'service_stone_text' ),
				'image' => ABIDAR_URI . '/assets/img/stone.jpg',
				'tag'   => abidar_copy( 'sector_tag_stone' ),
			),
			array(
				'num'   => '04',
				'title' => abidar_copy( 'service_engineering' ),
				'text'  => abidar_copy( 'service_engineering_text' ),
				'image' => ABIDAR_URI . '/assets/img/about.jpg',
				'tag'   => abidar_copy( 'sector_tag_engineering' ),
			),
		),
		'surface' => false,
		'class'   => '',
	);

	$args = ab_section_args( $args, $defaults );
	?>
	<section class="ab-section ab-sectors<?php echo $args['surface'] ? ' ab-section--surface' : ''; ?> <?php echo esc_attr( $args['class'] ); ?>">
		<div class="ab-container">
			<div class="ab-section-head ab-section-head--split ab-reveal">
				<div>
					<?php if ( $args['eyebrow'] ) : ?>
						<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
					<?php endif; ?>
					<h2><?php echo esc_html( $args['title'] ); ?></h2>
				</div>
				<?php if ( $args['text'] ) : ?>
					<p><?php echo esc_html( $args['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="ab-sectors__list ab-stagger">
				<?php foreach ( $args['items'] as $item ) : ?>
					<a class="ab-sector" href="<?php echo esc_url( abidar_page_url( 'services' ) ); ?>">
						<span class="ab-sector__media">
							<img src="<?php echo esc_url( $item['image'] ); ?>" alt="" loading="lazy" decoding="async">
						</span>
						<span class="ab-sector__body">
							<span class="ab-sector__num"><?php echo esc_html( $item['num'] ); ?></span>
							<span class="ab-sector__text">
								<span class="ab-sector__title"><?php echo esc_html( $item['title'] ); ?></span>
								<span class="ab-sector__desc"><?php echo esc_html( $item['text'] ); ?></span>
							</span>
							<span class="ab-sector__tag"><?php echo esc_html( $item['tag'] ); ?></span>
							<span class="ab-sector__arrow"><?php abidar_icon( 'arrow-up-right', true, array( 'width' => 18, 'height' => 18 ) ); ?></span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/* ═══════════════════════════════════════════════════
   CAPABILITY / PROCESS HORIZONTAL SCROLL
   ═══════════════════════════════════════════════════ */
/**
 * Render a sticky-stacked capability showcase.
 *
 * @param array $args Args.
 */
function ab_render_capabilities( $args = array() ) {
	$defaults = array(
		'eyebrow' => abidar_copy( 'cap_eyebrow' ),
		'title'   => abidar_copy( 'cap_title' ),
		'items'   => array(
			array( 'value' => '12', 'unit' => '', 'title' => abidar_copy( 'cap_1' ), 'text' => abidar_copy( 'cap_1_text' ), 'icon' => 'excavator' ),
			array( 'value' => '1.8', 'unit' => 'M', 'title' => abidar_copy( 'cap_2' ), 'text' => abidar_copy( 'cap_2_text' ), 'icon' => 'package' ),
			array( 'value' => '0.5', 'unit' => '', 'title' => abidar_copy( 'cap_3' ), 'text' => abidar_copy( 'cap_3_text' ), 'icon' => 'shield' ),
			array( 'value' => '24', 'unit' => '/7', 'title' => abidar_copy( 'cap_4' ), 'text' => abidar_copy( 'cap_4_text' ), 'icon' => 'clock' ),
		),
		'image'   => ABIDAR_URI . '/assets/img/hero-mine.jpg',
		'class'   => '',
	);
	$args = ab_section_args( $args, $defaults );
	?>
	<section class="ab-section ab-section--dark ab-caps <?php echo esc_attr( $args['class'] ); ?>">
		<div class="ab-caps__bg" aria-hidden="true">
			<img src="<?php echo esc_url( $args['image'] ); ?>" alt="" loading="lazy" decoding="async">
		</div>
		<div class="ab-container">
			<div class="ab-caps__head ab-reveal">
				<?php if ( $args['eyebrow'] ) : ?>
					<span class="ab-eyebrow ab-eyebrow--light"><?php echo esc_html( $args['eyebrow'] ); ?></span>
				<?php endif; ?>
				<h2><?php echo esc_html( $args['title'] ); ?></h2>
			</div>

			<div class="ab-caps__grid ab-stagger">
				<?php foreach ( $args['items'] as $item ) : ?>
					<div class="ab-cap">
						<span class="ab-cap__icon"><?php abidar_icon( $item['icon'], true, array( 'width' => 24, 'height' => 24 ) ); ?></span>
						<span class="ab-cap__value">
							<?php echo esc_html( $item['value'] ); ?><span class="ab-cap__unit"><?php echo esc_html( $item['unit'] ); ?></span>
						</span>
						<span class="ab-cap__title"><?php echo esc_html( $item['title'] ); ?></span>
						<span class="ab-cap__text"><?php echo esc_html( $item['text'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/* ═══════════════════════════════════════════════════
   FEATURED PROJECT (editorial full-bleed)
   ═══════════════════════════════════════════════════ */
/**
 * Render one project as a full-bleed editorial block with sticky facts.
 *
 * @param array $args Args.
 */
function ab_render_featured( $args = array() ) {
	$defaults = array(
		'eyebrow' => abidar_copy( 'featured_eyebrow' ),
		'title'   => abidar_copy( 'featured_title' ),
		'text'    => abidar_copy( 'featured_text' ),
		'image'   => ABIDAR_URI . '/assets/img/project1.jpg',
		'facts'   => array(
			array( 'label' => abidar_copy( 'client' ), 'value' => abidar_copy( 'featured_client' ) ),
			array( 'label' => abidar_copy( 'location' ), 'value' => abidar_copy( 'featured_location' ) ),
			array( 'label' => abidar_copy( 'duration' ), 'value' => abidar_copy( 'featured_duration' ) ),
			array( 'label' => abidar_copy( 'sector' ), 'value' => abidar_copy( 'service_mining' ) ),
		),
		'url'     => '',
		'class'   => '',
	);
	$args = ab_section_args( $args, $defaults );
	$url  = $args['url'] ? $args['url'] : abidar_page_url( 'projects' );
	?>
	<section class="ab-section ab-featured <?php echo esc_attr( $args['class'] ); ?>">
		<div class="ab-container">
			<div class="ab-featured__grid">
				<div class="ab-featured__media ab-reveal">
					<img src="<?php echo esc_url( $args['image'] ); ?>" alt="" loading="lazy" decoding="async">
					<a class="ab-featured__play" href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( abidar_copy( 'view_project' ) ); ?>">
						<?php abidar_icon( 'arrow-up-right', true, array( 'width' => 22, 'height' => 22 ) ); ?>
					</a>
				</div>

				<div class="ab-featured__body">
					<?php if ( $args['eyebrow'] ) : ?>
						<span class="ab-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
					<?php endif; ?>
					<h2 class="ab-featured__title"><?php echo esc_html( $args['title'] ); ?></h2>
					<p class="ab-lead"><?php echo esc_html( $args['text'] ); ?></p>

					<dl class="ab-featured__facts">
						<?php foreach ( $args['facts'] as $fact ) : ?>
							<div class="ab-fact">
								<dt><?php echo esc_html( $fact['label'] ); ?></dt>
								<dd><?php echo esc_html( $fact['value'] ); ?></dd>
							</div>
						<?php endforeach; ?>
					</dl>

					<a class="ab-btn ab-btn--primary" href="<?php echo esc_url( $url ); ?>">
						<span><?php echo esc_html( abidar_copy( 'view_project' ) ); ?></span>
						<?php abidar_icon( 'arrow-right', true, array( 'width' => 16, 'height' => 16 ) ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>
	<?php
}
