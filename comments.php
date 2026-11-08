<?php
/**
 * Comments template.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="ab-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="ab-comments__title">
			<?php
			printf(
				/* translators: %s: comment count */
				esc_html( _n( '%s comment', '%s comments', get_comments_number(), 'abidar' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>

		<ol class="ab-comments__list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'avatar_size' => 48,
					'short_ping'  => true,
					'callback'    => 'abidar_comment',
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => esc_html( abidar_copy( 'prev' ) ),
				'next_text' => esc_html( abidar_copy( 'next' ) ),
			)
		);
		?>

		<?php if ( ! comments_open() ) : ?>
			<p class="ab-muted"><?php esc_html_e( 'Comments are closed.', 'abidar' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'          => abidar_copy( 'comment_form_title' ),
			'title_reply_before'   => '<h3 class="ab-comments__form-title">',
			'title_reply_after'    => '</h3>',
			'class_form'           => 'ab-form',
			'comment_notes_before' => '',
			'label_submit'         => abidar_copy( 'send' ),
			'class_submit'         => 'ab-btn ab-btn--primary',
			'comment_field'        => '<p class="ab-field"><label for="comment">' . esc_html( abidar_copy( 'form_message' ) ) . '</label><textarea id="comment" name="comment" required></textarea></p>',
			'fields'               => array(
				'author' => '<p class="ab-field"><label for="author">' . esc_html( abidar_copy( 'form_name' ) ) . '</label><input id="author" name="author" type="text" required></p>',
				'email'  => '<p class="ab-field"><label for="email">' . esc_html( abidar_copy( 'form_email' ) ) . '</label><input id="email" name="email" type="email" required></p>',
				'url'    => '<p class="ab-field"><label for="url">' . esc_html__( 'Website', 'abidar' ) . '</label><input id="url" name="url" type="url"></p>',
			),
		)
	);
	?>
</section>
