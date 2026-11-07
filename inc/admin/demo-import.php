<?php
/**
 * One-click demo importer.
 *
 * Creates the pages (Home / About / Services / Projects / Team / Contact / Blog),
 * the primary navigation, the demo projects, services and team members, copies
 * the bundled images into the media library and sets the front page.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Page slugs created by the importer.
 *
 * @return array
 */
function abidar_demo_pages() {
	return array(
		'home'     => array(
			'title'    => array( 'fa' => 'خانه', 'en' => 'Home', 'zh' => '首页' ),
			'template' => 'page-templates/home.php',
			'content'  => '',
		),
		'about'    => array(
			'title'   => array( 'fa' => 'درباره ما', 'en' => 'About us', 'zh' => '关于我们' ),
			'content' => abidar_demo_page_content( 'about' ),
		),
		'services' => array(
			'title'   => array( 'fa' => 'خدمات', 'en' => 'Services', 'zh' => '服务' ),
			'content' => abidar_demo_page_content( 'services' ),
		),
		'projects' => array(
			'title'   => array( 'fa' => 'پروژه‌ها', 'en' => 'Projects', 'zh' => '项目' ),
			'content' => abidar_demo_page_content( 'projects' ),
		),
		'team'     => array(
			'title'   => array( 'fa' => 'تیم ما', 'en' => 'Our team', 'zh' => '我们的团队' ),
			'content' => abidar_demo_page_content( 'team' ),
		),
		'contact'  => array(
			'title'   => array( 'fa' => 'تماس با ما', 'en' => 'Contact us', 'zh' => '联系我们' ),
			'content' => abidar_demo_page_content( 'contact' ),
		),
		'blog'     => array(
			'title'   => array( 'fa' => 'اخبار', 'en' => 'News', 'zh' => '新闻' ),
			'content' => '',
		),
	);
}

/**
 * The demo importer page.
 */
function abidar_render_demo_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$done = isset( $_GET['imported'] ) ? sanitize_key( wp_unslash( $_GET['imported'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	?>
	<div class="wrap ab-admin">
		<h1><?php esc_html_e( 'Demo content', 'abidar' ); ?></h1>

		<?php if ( 'ok' === $done ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Demo content installed. Your site is ready to customise.', 'abidar' ); ?></p></div>
		<?php endif; ?>

		<div class="ab-demo">
			<div class="ab-demo__card">
				<h2><?php esc_html_e( 'What gets installed', 'abidar' ); ?></h2>
				<ul class="ab-demo__list">
					<li><?php esc_html_e( '7 pages: Home, About, Services, Projects, Team, Contact, News', 'abidar' ); ?></li>
					<li><?php esc_html_e( 'Primary menu and footer menu', 'abidar' ); ?></li>
					<li><?php esc_html_e( '6 demo projects with images and details', 'abidar' ); ?></li>
					<li><?php esc_html_e( '4 services and 4 team members', 'abidar' ); ?></li>
					<li><?php esc_html_e( '3 testimonials and 3 news posts', 'abidar' ); ?></li>
					<li><?php esc_html_e( 'Theme palette, contact details and statutory pages', 'abidar' ); ?></li>
				</ul>
				<p class="description"><?php esc_html_e( 'Existing content is never deleted. Importing twice creates duplicates, so run it once.', 'abidar' ); ?></p>

				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'abidar_import_demo', 'abidar_demo_nonce' ); ?>
					<input type="hidden" name="action" value="abidar_import_demo">
					<p>
						<button type="submit" class="button button-primary button-hero"><?php esc_html_e( 'Install demo content', 'abidar' ); ?></button>
					</p>
				</form>
			</div>

			<div class="ab-demo__card ab-demo__card--muted">
				<h2><?php esc_html_e( 'Build with Elementor', 'abidar' ); ?></h2>
				<ol class="ab-demo__list">
					<li><?php esc_html_e( 'Edit the Home page with Elementor.', 'abidar' ); ?></li>
					<li><?php esc_html_e( 'Search “Abidar” in the widget panel and drag any section in.', 'abidar' ); ?></li>
					<li><?php esc_html_e( 'Use Templates → Theme Builder for a custom header/footer.', 'abidar' ); ?></li>
				</ol>
				<p>
					<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=abidar-widgets-help' ) ); ?>"><?php esc_html_e( 'See all widgets', 'abidar' ); ?></a>
				</p>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Handle the demo import.
 */
function abidar_handle_demo_import() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do that.', 'abidar' ) );
	}

	check_admin_referer( 'abidar_import_demo', 'abidar_demo_nonce' );

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$images = abidar_import_demo_images();
	$pages  = array();

	// Pages.
	foreach ( abidar_demo_pages() as $slug => $page ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$pages[ $slug ] = $existing->ID;
			continue;
		}

		$page_id = wp_insert_post(
			array(
				'post_title'   => $page['title']['en'],
				'post_name'    => $slug,
				'post_content' => $page['content'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
			)
		);

		if ( is_wp_error( $page_id ) ) {
			continue;
		}

		$pages[ $slug ] = $page_id;

		if ( ! empty( $page['template'] ) ) {
			update_post_meta( $page_id, '_wp_page_template', $page['template'] );
		}

		// Featured image best-effort.
		$image_map = array(
			'home'     => 'hero-mine',
			'about'    => 'about',
			'services' => 'oil-gas',
			'projects' => 'stone',
			'team'     => 'team1',
			'contact'  => 'cta',
		);
		if ( isset( $image_map[ $slug ], $images[ $image_map[ $slug ] ] ) ) {
			set_post_thumbnail( $page_id, $images[ $image_map[ $slug ] ] );
		}
	}

	// Front page + blog page.
	if ( isset( $pages['home'], $pages['blog'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $pages['home'] );
		update_option( 'page_for_posts', $pages['blog'] );
	}

	// Projects & services & team.
	abidar_import_demo_projects( $images );
	abidar_import_demo_services( $images );
	abidar_import_demo_team( $images );
	abidar_import_demo_testimonials();
	abidar_import_demo_posts( $images );

	// Menus.
	abidar_import_demo_menus( $pages );

	// Options: brand copy + contact.
	abidar_import_demo_options();

	flush_rewrite_rules();

	wp_safe_redirect( admin_url( 'admin.php?page=abidar-demo-import&imported=ok' ) );
	exit;
}
add_action( 'admin_post_abidar_import_demo', 'abidar_handle_demo_import' );

/**
 * Copy the bundled demo images into the media library.
 *
 * @return array slug => attachment ID.
 */
function abidar_import_demo_images() {
	$slugs = array( 'hero-mine', 'oil-gas', 'stone', 'about', 'cta', 'project1', 'project2', 'project3', 'team1', 'team2', 'team3', 'team4', 'pattern-dark' );
	$out   = array();

	$dir = ABIDAR_DIR . '/assets/img/';

	if ( ! is_dir( $dir ) ) {
		return $out;
	}

	foreach ( $slugs as $slug ) {
		$file = $dir . $slug . '.jpg';
		if ( ! file_exists( $file ) ) {
			continue;
		}

		// Reuse an existing attachment with the same file name.
		$existing = get_posts(
			array(
				'post_type'      => 'attachment',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'     => '_wp_attached_file',
						'value'   => $slug,
						'compare' => 'LIKE',
					),
				),
			)
		);

		if ( $existing ) {
			$out[ $slug ] = (int) $existing[0];
			continue;
		}

		$tmp = wp_tempnam( $slug . '.jpg' );
		copy( $file, $tmp );

		$attachment_id = media_handle_sideload(
			array(
				'name'     => $slug . '.jpg',
				'tmp_name' => $tmp,
			),
			0,
			$slug
		);

		if ( ! is_wp_error( $attachment_id ) ) {
			$out[ $slug ] = $attachment_id;
		}
	}

	return $out;
}

/**
 * Insert projects.
 *
 * @param array $images Attachment IDs.
 */
function abidar_import_demo_projects( $images ) {
	$projects = abidar_projects_data();

	foreach ( $projects as $project ) {
		if ( get_page_by_title( $project['title']['en'], OBJECT, 'ab_project' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'ab_project',
				'post_status'  => 'publish',
				'post_title'   => $project['title']['en'],
				'post_excerpt' => $project['excerpt']['en'],
				'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $project['excerpt']['en'] ) . '</p><!-- /wp:paragraph -->',
			)
		);

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		update_post_meta( $post_id, '_abidar_client', $project['client'] );
		update_post_meta( $post_id, '_abidar_location', $project['location']['en'] );
		update_post_meta( $post_id, '_abidar_year', $project['year'] );
		update_post_meta( $post_id, '_abidar_duration', $project['duration']['en'] );
		update_post_meta( $post_id, '_abidar_status', abidar_project_status_key( $project['status']['en'] ) );

		$image_key = str_replace( array( 'hero-mine.jpg' ), array( 'hero-mine' ), $project['image'] );
		$image_key = preg_replace( '/\.jpg$/', '', $project['image'] );
		if ( isset( $images[ $image_key ] ) ) {
			set_post_thumbnail( $post_id, $images[ $image_key ] );
		}

		$term = wp_insert_term( $project['sector']['en'], 'ab_project_cat', array( 'slug' => sanitize_title( $project['sector']['en'] ) ) );
		if ( ! is_wp_error( $term ) ) {
			wp_set_object_terms( $post_id, (int) $term['term_id'], 'ab_project_cat' );
		}
	}
}

/**
 * Map a status label to its key.
 *
 * @param string $label Label.
 * @return string
 */
function abidar_project_status_key( $label ) {
	$map = array(
		'Completed' => 'completed',
		'Ongoing'   => 'ongoing',
	);
	return isset( $map[ $label ] ) ? $map[ $label ] : 'completed';
}

/**
 * Insert services.
 *
 * @param array $images Attachment IDs.
 */
function abidar_import_demo_services( $images ) {
	foreach ( abidar_services_data() as $service ) {
		if ( get_page_by_title( $service['title']['en'], OBJECT, 'ab_service' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'ab_service',
				'post_status'  => 'publish',
				'post_title'   => $service['title']['en'],
				'post_excerpt' => $service['text']['en'],
				'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $service['text']['en'] ) . '</p><!-- /wp:paragraph -->',
			)
		);

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		update_post_meta( $post_id, '_abidar_icon', $service['icon'] );

		$image_key = preg_replace( '/\.jpg$/', '', $service['image'] );
		if ( isset( $images[ $image_key ] ) ) {
			set_post_thumbnail( $post_id, $images[ $image_key ] );
		}
	}
}

/**
 * Insert team members.
 *
 * @param array $images Attachment IDs.
 */
function abidar_import_demo_team( $images ) {
	foreach ( abidar_team_data() as $member ) {
		if ( get_page_by_title( $member['name']['en'], OBJECT, 'ab_team' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'ab_team',
				'post_status'  => 'publish',
				'post_title'   => $member['name']['en'],
				'post_excerpt' => $member['bio']['en'],
				'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $member['bio']['en'] ) . '</p><!-- /wp:paragraph -->',
			)
		);

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		update_post_meta( $post_id, '_abidar_role', $member['role']['en'] );
		update_post_meta( $post_id, '_abidar_email', $member['email'] );
		update_post_meta( $post_id, '_abidar_linkedin', $member['linkedin'] );

		$image_key = preg_replace( '/\.jpg$/', '', $member['image'] );
		if ( isset( $images[ $image_key ] ) ) {
			set_post_thumbnail( $post_id, $images[ $image_key ] );
		}
	}
}

/**
 * Insert testimonials.
 */
function abidar_import_demo_testimonials() {
	foreach ( abidar_testimonials_data() as $item ) {
		if ( get_page_by_title( $item['name']['en'], OBJECT, 'ab_testimonial' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'ab_testimonial',
				'post_status'  => 'publish',
				'post_title'   => $item['name']['en'],
				'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $item['quote']['en'] ) . '</p><!-- /wp:paragraph -->',
			)
		);

		if ( ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, '_abidar_role', $item['role']['en'] );
			update_post_meta( $post_id, '_abidar_rating', 5 );
		}
	}
}

/**
 * Insert demo blog posts.
 *
 * @param array $images Attachment IDs.
 */
function abidar_import_demo_posts( $images ) {
	$posts = abidar_demo_posts();

	foreach ( $posts as $post ) {
		if ( get_page_by_title( $post['title']['en'], OBJECT, 'post' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'post',
				'post_status'  => 'publish',
				'post_title'   => $post['title']['en'],
				'post_excerpt' => $post['excerpt']['en'],
				'post_content' => $post['content'],
				'post_date'    => $post['date'],
			)
		);

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		if ( isset( $images[ $post['image'] ] ) ) {
			set_post_thumbnail( $post_id, $images[ $post['image'] ] );
		}

		$term = wp_insert_term( $post['category']['en'], 'category' );
		if ( ! is_wp_error( $term ) ) {
			wp_set_object_terms( $post_id, (int) $term['term_id'], 'category' );
		}
	}
}

/**
 * Create the demo menus.
 *
 * @param array $pages Page IDs keyed by slug.
 */
function abidar_import_demo_menus( $pages ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );

	$primary_items = array(
		'home'     => abidar_copy( 'nav_home', 'en' ),
		'about'    => abidar_copy( 'nav_about', 'en' ),
		'services' => abidar_copy( 'nav_services', 'en' ),
		'projects' => abidar_copy( 'nav_projects', 'en' ),
		'team'     => abidar_copy( 'nav_team', 'en' ),
		'blog'     => abidar_copy( 'nav_blog', 'en' ),
		'contact'  => abidar_copy( 'nav_contact', 'en' ),
	);

	$menu_name = __( 'Primary menu', 'abidar' );
	$menu      = wp_get_nav_menu_object( $menu_name );

	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
	} else {
		$menu_id = $menu->term_id;
	}

	if ( ! is_wp_error( $menu_id ) ) {
		$order = 0;
		foreach ( $primary_items as $slug => $label ) {
			if ( empty( $pages[ $slug ] ) ) {
				continue;
			}
			$order++;
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'     => $label,
					'menu-item-object'    => 'page',
					'menu-item-object-id' => $pages[ $slug ],
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
					'menu-item-position'  => $order,
				)
			);
		}

		$locations['primary'] = $menu_id;
	}

	// Footer menu with the legal pages.
	$footer_name = __( 'Footer menu', 'abidar' );
	$footer      = wp_get_nav_menu_object( $footer_name );
	$footer_id   = $footer ? $footer->term_id : wp_create_nav_menu( $footer_name );

	if ( ! is_wp_error( $footer_id ) ) {
		$order = 0;
		foreach ( array( 'about', 'services', 'projects', 'contact' ) as $slug ) {
			if ( empty( $pages[ $slug ] ) ) {
				continue;
			}
			$order++;
			wp_update_nav_menu_item(
				$footer_id,
				0,
				array(
					'menu-item-title'     => get_the_title( $pages[ $slug ] ),
					'menu-item-object'    => 'page',
					'menu-item-object-id' => $pages[ $slug ],
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
					'menu-item-position'  => $order,
				)
			);
		}
		$locations['footer'] = $footer_id;
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Seed the theme options with brand copy.
 */
function abidar_import_demo_options() {
	$options = get_option( 'abidar_options', array() );
	$options = is_array( $options ) ? $options : array();

	foreach ( array( 'fa', 'en', 'zh' ) as $lang ) {
		$strings = Abidar_Strings::all( $lang );
		foreach ( $strings as $key => $value ) {
			$options[ 'copy_' . $key . '_' . $lang ] = $value;
		}
	}

	$options['topbar_phone'] = '+98 21 8800 1234';
	$options['topbar_email'] = 'info@abidargroup.com';
	$options['phone']        = '+98 21 8800 1234';
	$options['email']        = 'info@abidargroup.com';
	$options['address']      = abidar_copy( 'address', 'en' );

	update_option( 'abidar_options', $options );
	update_option( 'abidar_demo_imported', time() );
}
