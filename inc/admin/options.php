<?php
/**
 * Theme options panel ("Abidar" menu in the dashboard).
 *
 * A dependency-free options framework: registering a tab + fields is enough to
 * get validation, sanitisation, media uploads, colour pickers and live preview.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the admin menu.
 */
function abidar_admin_menu() {
	add_menu_page(
		__( 'Abidar theme', 'abidar' ),
		__( 'Abidar', 'abidar' ),
		'manage_options',
		'abidar-options',
		'abidar_render_options_page',
		'dashicons-admin-customizer',
		59
	);

	add_submenu_page(
		'abidar-options',
		__( 'Theme options', 'abidar' ),
		__( 'Theme options', 'abidar' ),
		'manage_options',
		'abidar-options',
		'abidar_render_options_page'
	);

	add_submenu_page(
		'abidar-options',
		__( 'Demo content', 'abidar' ),
		__( 'Demo content', 'abidar' ),
		'manage_options',
		'abidar-demo-import',
		'abidar_render_demo_page'
	);

	add_submenu_page(
		'abidar-options',
		__( 'Elementor widgets', 'abidar' ),
		__( 'Elementor widgets', 'abidar' ),
		'manage_options',
		'abidar-widgets-help',
		'abidar_render_widgets_help'
	);
}
add_action( 'admin_menu', 'abidar_admin_menu' );

/**
 * Admin assets.
 *
 * @param string $hook Current admin page.
 */
function abidar_admin_assets( $hook ) {
	if ( false === strpos( $hook, 'abidar' ) ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_style( 'abidar-admin', ABIDAR_URI . '/assets/css/admin.css', array( 'wp-color-picker' ), ABIDAR_VERSION );
	wp_enqueue_script( 'abidar-admin', ABIDAR_URI . '/assets/js/admin.js', array( 'jquery', 'wp-color-picker' ), ABIDAR_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'abidar_admin_assets' );

/**
 * Register the option + the settings sections.
 */
function abidar_register_settings() {
	register_setting(
		'abidar_options_group',
		'abidar_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'abidar_sanitize_options',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'abidar_register_settings' );

/**
 * Tabs definition. Each tab has fields, each field has a type.
 *
 * @return array
 */
function abidar_option_tabs() {
	$tabs = array(
		'branding'  => array(
			'title'  => __( 'Branding', 'abidar' ),
			'icon'   => 'award',
			'desc'   => __( 'Logo, favicon and brand mark.', 'abidar' ),
			'fields' => array(
				'logo'       => array( 'label' => __( 'Logo (light backgrounds)', 'abidar' ), 'type' => 'image' ),
				'logo_dark'  => array( 'label' => __( 'Logo (dark backgrounds)', 'abidar' ), 'type' => 'image' ),
				'logo_width' => array( 'label' => __( 'Logo width (px)', 'abidar' ), 'type' => 'number', 'min' => 60, 'max' => 400 ),
				'favicon'    => array( 'label' => __( 'Favicon', 'abidar' ), 'type' => 'image' ),
			),
		),
		'colors'    => array(
			'title'  => __( 'Colours', 'abidar' ),
			'desc'   => __( 'The palette is injected as CSS variables, so Elementor widgets follow it automatically.', 'abidar' ),
			'fields' => array(
				'color_primary'      => array( 'label' => __( 'Primary', 'abidar' ), 'type' => 'color', 'default' => '#0c6a5a' ),
				'color_primary_dark' => array( 'label' => __( 'Primary dark', 'abidar' ), 'type' => 'color', 'default' => '#084a40' ),
				'color_accent'       => array( 'label' => __( 'Accent (ochre)', 'abidar' ), 'type' => 'color', 'default' => '#c8912f' ),
				'color_ink'          => array( 'label' => __( 'Headings / ink', 'abidar' ), 'type' => 'color', 'default' => '#101b1c' ),
				'color_body'         => array( 'label' => __( 'Body text', 'abidar' ), 'type' => 'color', 'default' => '#4a5758' ),
				'color_surface'      => array( 'label' => __( 'Surface / sand', 'abidar' ), 'type' => 'color', 'default' => '#f5f4f0' ),
				'color_dark'         => array( 'label' => __( 'Dark section', 'abidar' ), 'type' => 'color', 'default' => '#0b1516' ),
			),
		),
		'typography' => array(
			'title'  => __( 'Typography', 'abidar' ),
			'fields' => array(
				'font_body'      => array(
					'label'   => __( 'Body font', 'abidar' ),
					'type'    => 'select',
					'options' => array(
						'vazirmatn' => 'Vazirmatn (FA + EN)',
						'sora'      => 'Sora (Latin)',
						'system'    => 'System UI',
						'georgia'   => 'Georgia (serif)',
					),
				),
				'font_heading'   => array(
					'label'   => __( 'Heading font', 'abidar' ),
					'type'    => 'select',
					'options' => array(
						'sora'      => 'Sora',
						'vazirmatn' => 'Vazirmatn',
						'system'    => 'System UI',
						'georgia'   => 'Georgia (serif)',
					),
				),
				'base_font_size' => array( 'label' => __( 'Base font size (px)', 'abidar' ), 'type' => 'number', 'min' => 13, 'max' => 22 ),
			),
		),
		'header'    => array(
			'title'  => __( 'Header', 'abidar' ),
			'desc'   => __( 'Prefer Elementor? Build a header in Templates → Theme Builder → Header and it will replace this one automatically.', 'abidar' ),
			'fields' => array(
				'header_layout'      => array(
					'label'   => __( 'Header layout', 'abidar' ),
					'type'    => 'select',
					'options' => array(
						'classic'  => __( 'Classic (logo left, menu right)', 'abidar' ),
						'split'    => __( 'Split (menu – logo – menu)', 'abidar' ),
						'centered' => __( 'Centered logo', 'abidar' ),
					),
				),
				'header_sticky'      => array( 'label' => __( 'Sticky header', 'abidar' ), 'type' => 'toggle' ),
				'header_transparent' => array( 'label' => __( 'Transparent header on hero pages', 'abidar' ), 'type' => 'toggle' ),
				'topbar_enabled'     => array( 'label' => __( 'Show top bar', 'abidar' ), 'type' => 'toggle' ),
				'topbar_phone'       => array( 'label' => __( 'Top bar phone', 'abidar' ), 'type' => 'text' ),
				'topbar_email'       => array( 'label' => __( 'Top bar email', 'abidar' ), 'type' => 'text' ),
				'topbar_note'        => array( 'label' => __( 'Top bar note', 'abidar' ), 'type' => 'text' ),
				'header_cta_text'    => array( 'label' => __( 'Header CTA label', 'abidar' ), 'type' => 'text' ),
				'header_cta_url'     => array( 'label' => __( 'Header CTA URL', 'abidar' ), 'type' => 'url' ),
				'show_search'        => array( 'label' => __( 'Show search', 'abidar' ), 'type' => 'toggle' ),
				'show_lang_switcher' => array( 'label' => __( 'Show language switcher', 'abidar' ), 'type' => 'toggle' ),
			),
		),
		'footer'    => array(
			'title'  => __( 'Footer', 'abidar' ),
			'fields' => array(
				'footer_about'           => array( 'label' => __( 'Footer about text', 'abidar' ), 'type' => 'textarea' ),
				'footer_copyright'       => array( 'label' => __( 'Copyright line', 'abidar' ), 'type' => 'text' ),
				'footer_show_newsletter' => array( 'label' => __( 'Show newsletter form', 'abidar' ), 'type' => 'toggle' ),
				'social_linkedin'        => array( 'label' => 'LinkedIn', 'type' => 'url' ),
				'social_instagram'       => array( 'label' => 'Instagram', 'type' => 'url' ),
				'social_telegram'        => array( 'label' => 'Telegram', 'type' => 'url' ),
				'social_whatsapp'        => array( 'label' => 'WhatsApp', 'type' => 'url' ),
				'social_youtube'         => array( 'label' => 'YouTube', 'type' => 'url' ),
			),
		),
		'contact'   => array(
			'title'  => __( 'Contact details', 'abidar' ),
			'fields' => array(
				'address'       => array( 'label' => __( 'Address', 'abidar' ), 'type' => 'text' ),
				'phone'         => array( 'label' => __( 'Phone', 'abidar' ), 'type' => 'text' ),
				'email'         => array( 'label' => __( 'Email', 'abidar' ), 'type' => 'text' ),
				'working_hours' => array( 'label' => __( 'Working hours', 'abidar' ), 'type' => 'text' ),
				'map_embed'     => array( 'label' => __( 'Google Maps embed URL', 'abidar' ), 'type' => 'textarea' ),
			),
		),
		'blog'      => array(
			'title'  => __( 'Blog & SEO', 'abidar' ),
			'fields' => array(
				'blog_excerpt_length' => array( 'label' => __( 'Excerpt length (words)', 'abidar' ), 'type' => 'number', 'min' => 10, 'max' => 80 ),
				'blog_sidebar'        => array( 'label' => __( 'Show blog sidebar', 'abidar' ), 'type' => 'toggle' ),
			),
		),
		'language'  => array(
			'title'  => __( 'Languages', 'abidar' ),
			'desc'   => __( 'Persian, English and Chinese ship with the theme. Edit the copy of each language below — the site switches instantly.', 'abidar' ),
			'fields' => array(
				'default_language'  => array(
					'label'   => __( 'Default language', 'abidar' ),
					'type'    => 'select',
					'options' => array( 'fa' => 'فارسی — Persian', 'en' => 'English', 'zh' => '中文 — Chinese' ),
				),
				'lang_editor'       => array( 'label' => __( 'Language copy editor', 'abidar' ), 'type' => 'language_editor' ),
			),
		),
		'advanced'  => array(
			'title'  => __( 'Advanced', 'abidar' ),
			'fields' => array(
				'preloader'  => array( 'label' => __( 'Enable preloader', 'abidar' ), 'type' => 'toggle' ),
				'scroll_top' => array( 'label' => __( 'Back-to-top button', 'abidar' ), 'type' => 'toggle' ),
				'custom_css' => array( 'label' => __( 'Custom CSS', 'abidar' ), 'type' => 'code' ),
			),
		),
	);

	return apply_filters( 'abidar_option_tabs', $tabs );
}

/**
 * Sanitize the whole options array.
 *
 * @param array $input Raw input.
 * @return array
 */
function abidar_sanitize_options( $input ) {
	$input  = is_array( $input ) ? $input : array();
	$output = get_option( 'abidar_options', array() );
	$output = is_array( $output ) ? $output : array();
	$tabs   = abidar_option_tabs();

	foreach ( $tabs as $tab ) {
		foreach ( $tab['fields'] as $key => $field ) {
			if ( ! array_key_exists( $key, $input ) ) {
				// Checkbox / toggle fields are absent when unchecked.
				if ( 'toggle' === $field['type'] ) {
					$output[ $key ] = 0;
				}
				// The language editor posts a nested array; skip when not submitted.
				continue;
			}

			$raw = $input[ $key ];

			switch ( $field['type'] ) {
				case 'toggle':
					$output[ $key ] = empty( $raw ) ? 0 : 1;
					break;
				case 'number':
					$output[ $key ] = (int) $raw;
					break;
				case 'color':
					$color = sanitize_hex_color( $raw );
					$output[ $key ] = $color ? $color : ( $field['default'] ?? '#000000' );
					break;
				case 'url':
					$output[ $key ] = $raw ? esc_url_raw( $raw ) : '';
					break;
				case 'image':
					$output[ $key ] = absint( $raw );
					break;
				case 'textarea':
					$output[ $key ] = wp_kses_post( $raw );
					break;
				case 'code':
					$output[ $key ] = wp_strip_all_tags( $raw );
					break;
				case 'language_editor':
				case 'copy':
					if ( is_array( $raw ) ) {
						foreach ( $raw as $copy_key => $langs ) {
							$copy_key = sanitize_key( $copy_key );
							if ( ! is_array( $langs ) ) {
								continue;
							}
							foreach ( $langs as $lang_code => $value ) {
								$lang_code = sanitize_key( $lang_code );
								if ( ! in_array( $lang_code, array( 'fa', 'en', 'zh' ), true ) ) {
									continue;
								}
								$output[ 'copy_' . $copy_key . '_' . $lang_code ] = wp_kses_post( $value );
							}
						}
					}
					break;
				case 'select':
					$options = isset( $field['options'] ) ? array_keys( $field['options'] ) : array();
					$output[ $key ] = in_array( $raw, $options, true ) ? $raw : ( $options[0] ?? '' );
					break;
				default:
					$output[ $key ] = sanitize_text_field( $raw );
			}
		}
	}

	return $output;
}

/**
 * Render the settings page.
 */
function abidar_render_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$tabs    = abidar_option_tabs();
	$active  = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'branding'; // phpcs:ignore WordPress.Security.NonceVerification
	$active  = isset( $tabs[ $active ] ) ? $active : 'branding';
	$options = get_option( 'abidar_options', array() );
	$options = is_array( $options ) ? $options : array();

	if ( isset( $_GET['settings-updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		add_settings_error( 'abidar_messages', 'abidar_message', __( 'Settings saved.', 'abidar' ), 'updated' );
	}
	settings_errors( 'abidar_messages' );
	?>
	<div class="wrap ab-admin">
		<header class="ab-admin__header">
			<div>
				<h1><?php esc_html_e( 'Abidar Group — Theme options', 'abidar' ); ?></h1>
				<p class="ab-admin__sub"><?php esc_html_e( 'Version', 'abidar' ); ?> <?php echo esc_html( ABIDAR_VERSION ); ?> · <?php esc_html_e( 'Mining · Oil & Gas · Natural stone', 'abidar' ); ?></p>
			</div>
			<div class="ab-admin__langs">
				<span class="ab-admin__pill">فارسی</span>
				<span class="ab-admin__pill">English</span>
				<span class="ab-admin__pill">中文</span>
			</div>
		</header>

		<div class="ab-admin__body">
			<nav class="ab-admin__tabs" aria-label="<?php esc_attr_e( 'Option tabs', 'abidar' ); ?>">
				<?php foreach ( $tabs as $slug => $tab ) : ?>
					<a class="ab-admin__tab<?php echo $slug === $active ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( admin_url( 'admin.php?page=abidar-options&tab=' . $slug ) ); ?>">
						<span class="dashicons dashicons-<?php echo esc_attr( $tab['icon'] ?? 'admin-generic' ); ?>"></span>
						<?php echo esc_html( $tab['title'] ); ?>
					</a>
				<?php endforeach; ?>
			</nav>

			<form class="ab-admin__form" method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
				<?php settings_fields( 'abidar_options_group' ); ?>
				<input type="hidden" name="abidar_options[_tab]" value="<?php echo esc_attr( $active ); ?>">

				<?php if ( ! empty( $tabs[ $active ]['desc'] ) ) : ?>
					<p class="ab-admin__desc"><?php echo wp_kses_post( $tabs[ $active ]['desc'] ); ?></p>
				<?php endif; ?>

				<?php if ( 'language_editor' === ( $tabs[ $active ]['fields']['lang_editor']['type'] ?? '' ) ) : ?>
					<?php abidar_render_language_editor( $options ); ?>
				<?php endif; ?>

				<table class="form-table ab-admin__table" role="presentation">
					<tbody>
					<?php foreach ( $tabs[ $active ]['fields'] as $key => $field ) : ?>
						<?php
						if ( 'language_editor' === $field['type'] ) {
							continue;
						}
						$value = isset( $options[ $key ] ) ? $options[ $key ] : ( $field['default'] ?? abidar_option_defaults()[ $key ] ?? '' );
						?>
						<tr>
							<th scope="row"><label for="abidar_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
							<td>
								<?php abidar_render_field( $key, $field, $value ); ?>
								<?php if ( ! empty( $field['desc'] ) ) : ?>
									<p class="description"><?php echo wp_kses_post( $field['desc'] ); ?></p>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

				<?php submit_button( __( 'Save changes', 'abidar' ) ); ?>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Render one field control.
 *
 * @param string $key   Option key.
 * @param array  $field Field definition.
 * @param mixed  $value Current value.
 */
function abidar_render_field( $key, $field, $value ) {
	$name = 'abidar_options[' . $key . ']';
	$id   = 'abidar_' . $key;

	switch ( $field['type'] ) {
		case 'toggle':
			$checked = empty( $value ) ? '' : ' checked';
			printf(
				'<label class="ab-switch"><input type="checkbox" id="%1$s" name="%2$s" value="1"%3$s><span class="ab-switch__track"></span></label>',
				esc_attr( $id ),
				esc_attr( $name ),
				$checked // phpcs:ignore WordPress.Security.EscapeOutput
			);
			break;

		case 'color':
			printf(
				'<input type="text" class="ab-color-field" id="%s" name="%s" value="%s" data-default-color="%s">',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $value ),
				esc_attr( $field['default'] ?? '#000000' )
			);
			break;

		case 'image':
			$src = $value ? wp_get_attachment_image_url( (int) $value, 'medium' ) : '';
			echo '<div class="ab-image-field">';
			printf(
				'<input type="hidden" id="%1$s" name="%2$s" value="%3$s" class="ab-image-field__input">',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $value )
			);
			printf(
				'<div class="ab-image-field__preview" data-empty="%s">%s</div>',
				esc_attr__( 'No image selected', 'abidar' ),
				$src ? '<img src="' . esc_url( $src ) . '" alt="">' : ''
			);
			echo '<button type="button" class="button ab-image-field__select">' . esc_html__( 'Select image', 'abidar' ) . '</button> ';
			echo '<button type="button" class="button-link ab-image-field__remove">' . esc_html__( 'Remove', 'abidar' ) . '</button>';
			echo '</div>';
			break;

		case 'textarea':
			printf(
				'<textarea id="%s" name="%s" rows="5" class="large-text code">%s</textarea>',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_textarea( $value )
			);
			break;

		case 'code':
			printf(
				'<textarea id="%s" name="%s" rows="10" class="large-text code" spellcheck="false" placeholder="%s">%s</textarea>',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr__( '/* your CSS here */', 'abidar' ),
				esc_textarea( $value )
			);
			break;

		case 'number':
			printf(
				'<input type="number" id="%s" name="%s" value="%s" min="%s" max="%s" class="small-text">',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $value ),
				esc_attr( $field['min'] ?? 0 ),
				esc_attr( $field['max'] ?? 9999 )
			);
			break;

		case 'select':
			printf( '<select id="%s" name="%s">', esc_attr( $id ), esc_attr( $name ) );
			foreach ( $field['options'] as $opt_key => $opt_label ) {
				printf(
					'<option value="%s"%s>%s</option>',
					esc_attr( $opt_key ),
					selected( $value, $opt_key, false ),
					esc_html( $opt_label )
				);
			}
			echo '</select>';
			break;

		case 'url':
			printf(
				'<input type="url" id="%s" name="%s" value="%s" class="regular-text">',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $value )
			);
			break;

		default:
			printf(
				'<input type="text" id="%s" name="%s" value="%s" class="regular-text">',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $value )
			);
	}
}

/**
 * The trilingual copy editor: one row per string, one column per language.
 *
 * @param array $options Saved options.
 */
function abidar_render_language_editor( $options ) {
	$dict     = Abidar_Strings::dictionary();
	$langs    = array(
		'fa' => 'فارسی',
		'en' => 'English',
		'zh' => '中文',
	);
	$groups   = array(
		'hero'    => __( 'Hero & homepage', 'abidar' ),
		'nav'     => __( 'Navigation', 'abidar' ),
		'service' => __( 'Services', 'abidar' ),
		'about'   => __( 'About', 'abidar' ),
		'project' => __( 'Projects', 'abidar' ),
		'process' => __( 'Process', 'abidar' ),
		'team'    => __( 'Team', 'abidar' ),
		'cta'     => __( 'Call to action', 'abidar' ),
		'contact' => __( 'Contact & forms', 'abidar' ),
		'footer'  => __( 'Footer', 'abidar' ),
		'blog'    => __( 'Blog', 'abidar' ),
		'faq'     => __( 'FAQ', 'abidar' ),
		'form'    => __( 'Form fields', 'abidar' ),
		'number'  => __( 'Counters', 'abidar' ),
		'docs'    => __( 'Documentation page', 'abidar' ),
		'f_tree'  => __( 'Documentation page', 'abidar' ),
		'opt_'    => __( 'Documentation page', 'abidar' ),
		'misc'    => __( 'General & meta', 'abidar' ),
	);

	// Longest prefix first so "form_email" wins over "footer...".
	$prefixes = array_keys( $groups );
	usort(
		$prefixes,
		static function ( $a, $b ) {
			return strlen( $b ) - strlen( $a );
		}
	);

	$grouped = array();
	foreach ( $dict as $key => $translations ) {
		$group = 'misc';
		foreach ( $prefixes as $prefix ) {
			if ( 0 === strpos( $key, $prefix ) ) {
				$group = $prefix;
				break;
			}
		}
		$grouped[ $group ][ $key ] = $translations;
	}
	?>
	<div class="ab-lang-editor">
		<div class="ab-lang-editor__head">
			<p><?php esc_html_e( 'Edit any string in all three languages. Empty fields fall back to the built-in translation.', 'abidar' ); ?></p>
			<div class="ab-lang-editor__search">
				<input type="search" id="ab-lang-search" placeholder="<?php esc_attr_e( 'Filter strings…', 'abidar' ); ?>">
			</div>
		</div>

		<?php foreach ( $groups as $group_key => $group_label ) : ?>
			<?php if ( empty( $grouped[ $group_key ] ) ) { continue; } ?>
			<details class="ab-lang-editor__group" open>
				<summary><?php echo esc_html( $group_label ); ?> <span class="ab-lang-editor__count"><?php echo count( $grouped[ $group_key ] ); ?></span></summary>
				<table class="widefat striped ab-lang-editor__table">
					<thead>
						<tr>
							<th style="width:18%"><?php esc_html_e( 'Key', 'abidar' ); ?></th>
							<?php foreach ( $langs as $code => $label ) : ?>
								<th style="width:27%"><?php echo esc_html( $label ); ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
					<?php foreach ( $grouped[ $group_key ] as $copy_key => $translations ) : ?>
						<tr class="ab-lang-row" data-key="<?php echo esc_attr( $copy_key ); ?>">
							<td><code><?php echo esc_html( $copy_key ); ?></code></td>
							<?php foreach ( array_keys( $langs ) as $code ) : ?>
								<?php
								$saved = $options[ 'copy_' . $copy_key . '_' . $code ] ?? '';
								$value = '' !== $saved ? $saved : ( $translations[ $code ] ?? '' );
								?>
								<td>
									<textarea rows="2" class="ab-lang-input" dir="<?php echo 'fa' === $code ? 'rtl' : 'ltr'; ?>"
										name="abidar_options[lang_editor][<?php echo esc_attr( $copy_key ); ?>][<?php echo esc_attr( $code ); ?>]"
									><?php echo esc_textarea( $value ); ?></textarea>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</details>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Widget documentation page.
 */
function abidar_render_widgets_help() {
	$widgets = array(
		array( 'ab-hero', __( 'Hero slider', 'abidar' ), __( 'Full-height hero with background image/video, eyebrow, headline, two buttons, stat strip and scroll cue. Every colour, size and spacing is an Elementor control.', 'abidar' ) ),
		array( 'ab-services', __( 'Services grid', 'abidar' ), __( 'Icon cards or image cards for services, with hover states, read-more links and optional category filter.', 'abidar' ) ),
		array( 'ab-about', __( 'About split', 'abidar' ), __( 'Image + text split section with checklist, signature and a floating "years of experience" badge.', 'abidar' ) ),
		array( 'ab-counters', __( 'Animated counters', 'abidar' ), __( 'Repeating stat items with icons, suffix, decimal support and scroll-triggered animation.', 'abidar' ) ),
		array( 'ab-projects', __( 'Projects showcase', 'abidar' ), __( 'Pulls the Projects custom post type, with filterable categories, masonry/grid layouts and hover reveal.', 'abidar' ) ),
		array( 'ab-process', __( 'Process steps', 'abidar' ), __( 'Numbered timeline of how you work, horizontal or vertical, with connecting line.', 'abidar' ) ),
		array( 'ab-team', __( 'Team grid', 'abidar' ), __( 'Team members from the Team post type or manual repeater, with social links and hover bio.', 'abidar' ) ),
		array( 'ab-testimonials', __( 'Testimonials slider', 'abidar' ), __( 'Quote slider/grid with rating stars, client name, role and avatar.', 'abidar' ) ),
		array( 'ab-cta', __( 'Call to action', 'abidar' ), __( 'Dark or accent CTA band with headline, text, button and phone link, plus optional background image.', 'abidar' ) ),
		array( 'ab-contact', __( 'Contact block', 'abidar' ), __( 'Contact cards + working contact form (AJAX, honeypot, localised labels).', 'abidar' ) ),
		array( 'ab-marquee', __( 'Client marquee', 'abidar' ), __( 'Infinite scrolling logo strip, with pause-on-hover and speed control.', 'abidar' ) ),
		array( 'ab-posts', __( 'Latest posts', 'abidar' ), __( 'Blog cards queried by category/count, with localised dates and read time.', 'abidar' ) ),
		array( 'ab-accordion', __( 'FAQ accordion', 'abidar' ), __( 'Schema-ready accordion with smooth expand animation and single-open mode.', 'abidar' ) ),
		array( 'ab-lang-switcher', __( 'Language switcher', 'abidar' ), __( 'FA / EN / ZH switcher styled as inline, dropdown or flags — usable in header, footer or any section.', 'abidar' ) ),
		array( 'ab-page-title', __( 'Page title & breadcrumb', 'abidar' ), __( 'Inner page hero with background image, overlay and breadcrumb trail.', 'abidar' ) ),
	);
	?>
	<div class="wrap ab-admin">
		<h1><?php esc_html_e( 'Elementor widgets in this theme', 'abidar' ); ?></h1>
		<p><?php esc_html_e( 'All widgets live in the “Abidar” category of the Elementor panel. They use the theme palette by default and expose full Style tab controls.', 'abidar' ); ?></p>
		<table class="widefat striped" style="max-width:1000px">
			<thead>
				<tr>
					<th style="width:180px"><?php esc_html_e( 'Widget', 'abidar' ); ?></th>
					<th style="width:200px"><?php esc_html_e( 'Name', 'abidar' ); ?></th>
					<th><?php esc_html_e( 'Description', 'abidar' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $widgets as $w ) : ?>
				<tr>
					<td><code><?php echo esc_html( $w[0] ); ?></code></td>
					<td><?php echo esc_html( $w[1] ); ?></td>
					<td><?php echo esc_html( $w[2] ); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<h2 style="margin-top:2rem"><?php esc_html_e( 'Header & footer with Elementor', 'abidar' ); ?></h2>
		<p><?php esc_html_e( 'Go to Templates → Theme Builder → Header (or Footer) → Add New, design it, and publish. The theme automatically replaces its default header/footer. The same works for single project and archive templates.', 'abidar' ); ?></p>
	</div>
	<?php
}
