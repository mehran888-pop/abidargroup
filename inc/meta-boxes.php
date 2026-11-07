<?php
/**
 * Meta boxes for projects, services and team members.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field definitions per post type.
 *
 * @return array
 */
function abidar_meta_fields() {
	return array(
		'ab_project' => array(
			'client'   => array( 'label' => __( 'Client', 'abidar' ), 'type' => 'text' ),
			'location' => array( 'label' => __( 'Location', 'abidar' ), 'type' => 'text' ),
			'year'     => array( 'label' => __( 'Year', 'abidar' ), 'type' => 'text' ),
			'duration' => array( 'label' => __( 'Duration', 'abidar' ), 'type' => 'text' ),
			'status'   => array( 'label' => __( 'Status', 'abidar' ), 'type' => 'select', 'options' => array( 'completed' => __( 'Completed', 'abidar' ), 'ongoing' => __( 'Ongoing', 'abidar' ) ) ),
			'surface'  => array( 'label' => __( 'Surface / area (e.g. 12,400 m²)', 'abidar' ), 'type' => 'text' ),
			'gallery'  => array( 'label' => __( 'Gallery image IDs (comma separated)', 'abidar' ), 'type' => 'text' ),
		),
		'ab_service' => array(
			'icon'  => array( 'label' => __( 'Icon key (see inc/icons.php)', 'abidar' ), 'type' => 'text' ),
			'price' => array( 'label' => __( 'Starting price label (optional)', 'abidar' ), 'type' => 'text' ),
		),
		'ab_team'    => array(
			'role'     => array( 'label' => __( 'Role / position', 'abidar' ), 'type' => 'text' ),
			'email'    => array( 'label' => __( 'Email', 'abidar' ), 'type' => 'text' ),
			'phone'    => array( 'label' => __( 'Phone', 'abidar' ), 'type' => 'text' ),
			'linkedin' => array( 'label' => __( 'LinkedIn URL', 'abidar' ), 'type' => 'text' ),
		),
		'ab_testimonial' => array(
			'role' => array( 'label' => __( 'Role / company', 'abidar' ), 'type' => 'text' ),
			'rating' => array( 'label' => __( 'Rating (1–5)', 'abidar' ), 'type' => 'number' ),
		),
	);
}

/**
 * Register the meta boxes.
 */
function abidar_add_meta_boxes() {
	foreach ( array_keys( abidar_meta_fields() ) as $post_type ) {
		add_meta_box(
			'abidar-details',
			__( 'Details', 'abidar' ),
			'abidar_render_meta_box',
			$post_type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'abidar_add_meta_boxes' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Post.
 */
function abidar_render_meta_box( $post ) {
	$fields = abidar_meta_fields();
	$fields = isset( $fields[ $post->post_type ] ) ? $fields[ $post->post_type ] : array();

	wp_nonce_field( 'abidar_save_meta', 'abidar_meta_nonce' );

	echo '<div class="abidar-metabox">';
	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, '_abidar_' . $key, true );
		echo '<p class="abidar-metabox__row">';
		echo '<label for="abidar_' . esc_attr( $key ) . '"><strong>' . esc_html( $field['label'] ) . '</strong></label><br>';

		if ( 'select' === $field['type'] ) {
			echo '<select id="abidar_' . esc_attr( $key ) . '" name="abidar_' . esc_attr( $key ) . '" style="min-width:240px">';
			foreach ( $field['options'] as $opt_key => $opt_label ) {
				printf(
					'<option value="%s"%s>%s</option>',
					esc_attr( $opt_key ),
					selected( $value, $opt_key, false ),
					esc_html( $opt_label )
				);
			}
			echo '</select>';
		} else {
			printf(
				'<input type="%s" id="abidar_%s" name="abidar_%s" value="%s" class="widefat" style="max-width:520px">',
				esc_attr( $field['type'] ),
				esc_attr( $key ),
				esc_attr( $key ),
				esc_attr( $value )
			);
		}

		echo '</p>';
	}
	echo '</div>';
}

/**
 * Save the meta box values.
 *
 * @param int $post_id Post ID.
 */
function abidar_save_meta( $post_id ) {
	if ( ! isset( $_POST['abidar_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['abidar_meta_nonce'] ) ), 'abidar_save_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = abidar_meta_fields();
	$type   = get_post_type( $post_id );

	if ( ! isset( $fields[ $type ] ) ) {
		return;
	}

	foreach ( $fields[ $type ] as $key => $field ) {
		$name = 'abidar_' . $key;
		if ( ! isset( $_POST[ $name ] ) ) {
			continue;
		}

		$raw = wp_unslash( $_POST[ $name ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$val = 'text' === $field['type'] || 'number' === $field['type'] ? sanitize_text_field( $raw ) : sanitize_text_field( $raw );

		if ( '' === $val ) {
			delete_post_meta( $post_id, '_abidar_' . $key );
		} else {
			update_post_meta( $post_id, '_abidar_' . $key, $val );
		}
	}
}
add_action( 'save_post', 'abidar_save_meta' );

/**
 * Helper to read a project meta value.
 *
 * @param string $key     Field key.
 * @param int    $post_id Post ID.
 * @return string
 */
function abidar_meta( $key, $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return (string) get_post_meta( $post_id, '_abidar_' . $key, true );
}
