<?php
/**
 * Custom post types: projects, services, team members and testimonials.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the post types.
 */
function abidar_register_post_types() {
	$projects_labels = array(
		'name'               => _x( 'Projects', 'post type general name', 'abidar' ),
		'singular_name'      => _x( 'Project', 'post type singular name', 'abidar' ),
		'menu_name'          => _x( 'Projects', 'admin menu', 'abidar' ),
		'add_new'            => _x( 'Add project', 'project', 'abidar' ),
		'add_new_item'       => __( 'Add new project', 'abidar' ),
		'edit_item'          => __( 'Edit project', 'abidar' ),
		'new_item'           => __( 'New project', 'abidar' ),
		'view_item'          => __( 'View project', 'abidar' ),
		'search_items'       => __( 'Search projects', 'abidar' ),
		'not_found'          => __( 'No projects found', 'abidar' ),
		'not_found_in_trash' => __( 'No projects in trash', 'abidar' ),
	);

	register_post_type(
		'ab_project',
		array(
			'labels'       => $projects_labels,
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-analytics',
			'rewrite'      => array( 'slug' => 'projects', 'with_front' => false ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'elementor' ),
			'show_in_rest' => true,
		)
	);

	register_taxonomy(
		'ab_project_cat',
		'ab_project',
		array(
			'labels'            => array(
				'name'          => __( 'Project sectors', 'abidar' ),
				'singular_name' => __( 'Sector', 'abidar' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'project-sector' ),
		)
	);

	register_post_type(
		'ab_service',
		array(
			'labels'       => array(
				'name'          => __( 'Services', 'abidar' ),
				'singular_name' => __( 'Service', 'abidar' ),
				'add_new_item'  => __( 'Add new service', 'abidar' ),
				'edit_item'     => __( 'Edit service', 'abidar' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-hammer',
			'rewrite'      => array( 'slug' => 'services', 'with_front' => false ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'elementor' ),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'ab_team',
		array(
			'labels'       => array(
				'name'          => __( 'Team', 'abidar' ),
				'singular_name' => __( 'Team member', 'abidar' ),
				'add_new_item'  => __( 'Add team member', 'abidar' ),
				'edit_item'     => __( 'Edit team member', 'abidar' ),
			),
			'public'       => true,
			'menu_icon'    => 'dashicons-groups',
			'rewrite'      => array( 'slug' => 'team', 'with_front' => false ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'ab_testimonial',
		array(
			'labels'       => array(
				'name'          => __( 'Testimonials', 'abidar' ),
				'singular_name' => __( 'Testimonial', 'abidar' ),
				'add_new_item'  => __( 'Add testimonial', 'abidar' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-format-quote',
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'ab_message',
		array(
			'labels'    => array(
				'name'          => __( 'Enquiries', 'abidar' ),
				'singular_name' => __( 'Enquiry', 'abidar' ),
				'menu_name'     => __( 'Enquiries', 'abidar' ),
				'edit_item'     => __( 'View enquiry', 'abidar' ),
			),
			'public'    => false,
			'show_ui'   => true,
			'menu_icon' => 'dashicons-email-alt',
			'supports'  => array( 'title', 'editor' ),
			'capabilities' => array(
				'create_posts' => 'do_not_allow',
			),
			'map_meta_cap' => true,
		)
	);
}
add_action( 'init', 'abidar_register_post_types' );

/**
 * Flush rewrite rules once after activation.
 */
function abidar_flush_rewrites() {
	abidar_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'abidar_flush_rewrites' );

/**
 * In the admin, show the demo importer as a submenu of the theme options.
 */
function abidar_admin_notice_welcome() {
	if ( ! current_user_can( 'manage_options' ) || get_option( 'abidar_welcome_dismissed' ) ) {
		return;
	}

	// Only nudge once the theme has been active for a moment, never on the
	// theme options pages (they already explain everything).
	if ( isset( $_GET['page'] ) && 0 === strpos( sanitize_key( wp_unslash( $_GET['page'] ) ), 'abidar' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}

	if ( ! isset( $_GET['page'] ) || 'abidar-options' !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification
		?>
		<div class="notice notice-info is-dismissible">
			<p>
				<strong><?php esc_html_e( 'Welcome to Abidar Group.', 'abidar' ); ?></strong>
				<?php esc_html_e( 'Customise colours, fonts and copy in the theme options panel:', 'abidar' ); ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=abidar-options' ) ); ?>"><?php esc_html_e( 'Abidar → Theme options', 'abidar' ); ?></a>
			</p>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=abidar-demo-import' ) ); ?>"><?php esc_html_e( 'Install demo content', 'abidar' ); ?></a>
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'abidar_admin_notice_welcome' );
