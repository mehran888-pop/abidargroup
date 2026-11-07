<?php
/**
 * AJAX endpoints: contact form + newsletter.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Contact form handler.
 */
function abidar_ajax_contact() {
	check_ajax_referer( 'abidar_nonce', 'nonce' );

	// Honeypot.
	if ( ! empty( $_POST['ab_website'] ) ) {
		wp_send_json_success( array( 'message' => abidar_copy( 'form_success' ) ) );
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$company = isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		wp_send_json_error( array( 'message' => abidar_copy( 'form_required' ) ), 400 );
	}

	// Simple rate limiting per IP (5 submissions / 10 minutes).
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$key = 'abidar_rl_' . md5( $ip );
	$hit = (int) get_transient( $key );

	if ( $hit >= 5 ) {
		wp_send_json_error( array( 'message' => abidar_copy( 'error_generic' ) ), 429 );
	}
	set_transient( $key, $hit + 1, 10 * MINUTE_IN_SECONDS );

	$to      = abidar_option( 'topbar_email', get_option( 'admin_email' ) );
	$mail_subject = sprintf(
		/* translators: 1: name, 2: site name */
		__( '[%2$s] New enquiry from %1$s', 'abidar' ),
		$name,
		get_bloginfo( 'name' )
	);

	$body  = __( 'New message from the website contact form', 'abidar' ) . "\n\n";
	$body .= __( 'Name', 'abidar' ) . ': ' . $name . "\n";
	$body .= __( 'Email', 'abidar' ) . ': ' . $email . "\n";
	$body .= __( 'Phone', 'abidar' ) . ': ' . $phone . "\n";
	$body .= __( 'Company', 'abidar' ) . ': ' . $company . "\n";
	$body .= __( 'Subject', 'abidar' ) . ': ' . $subject . "\n";
	$body .= __( 'Language', 'abidar' ) . ': ' . Abidar_Multilingual::instance()->current_language() . "\n\n";
	$body .= $message . "\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	$sent = wp_mail( $to, $mail_subject, $body, $headers );

	// Store as a private CPT entry so nothing is lost even if mail fails.
	if ( post_type_exists( 'ab_message' ) ) {
		wp_insert_post(
			array(
				'post_type'    => 'ab_message',
				'post_status'  => 'private',
				'post_title'   => $name . ' — ' . ( $subject ? $subject : $email ),
				'post_content' => $body,
			)
		);
	}

	do_action( 'abidar_contact_submitted', compact( 'name', 'email', 'phone', 'company', 'subject', 'message' ) );

	wp_send_json_success(
		array(
			'message' => abidar_copy( 'form_success' ),
			'mailed'  => $sent,
		)
	);
}
add_action( 'wp_ajax_abidar_contact', 'abidar_ajax_contact' );
add_action( 'wp_ajax_nopriv_abidar_contact', 'abidar_ajax_contact' );

/**
 * Newsletter handler.
 */
function abidar_ajax_newsletter() {
	check_ajax_referer( 'abidar_nonce', 'nonce' );

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => abidar_copy( 'form_email_invalid' ) ), 400 );
	}

	$list = get_option( 'abidar_newsletter', array() );
	$list = is_array( $list ) ? $list : array();

	if ( ! in_array( $email, $list, true ) ) {
		$list[] = $email;
		update_option( 'abidar_newsletter', $list, false );
	}

	wp_send_json_success( array( 'message' => abidar_copy( 'form_success' ) ) );
}
add_action( 'wp_ajax_abidar_newsletter', 'abidar_ajax_newsletter' );
add_action( 'wp_ajax_nopriv_abidar_newsletter', 'abidar_ajax_newsletter' );
