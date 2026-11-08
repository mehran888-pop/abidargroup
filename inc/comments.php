<?php
/**
 * Comment callback.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Single comment markup.
 *
 * @param WP_Comment $comment Comment.
 * @param array      $args    Args.
 * @param int        $depth   Depth.
 */
function abidar_comment( $comment, $args, $depth ) {
	$tag = 'div' === $args['style'] ? 'div' : 'li';
	?>
	<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'ab-comment' ); ?>>
		<article class="ab-comment__body">
			<div class="ab-comment__head">
				<span class="ab-comment__avatar"><?php echo get_avatar( $comment, 48 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>

				<span class="ab-comment__meta">
					<span class="ab-comment__author"><?php echo esc_html( get_comment_author( $comment ) ); ?></span>
					<time class="ab-comment__date" datetime="<?php echo esc_attr( get_comment_date( 'c', $comment ) ); ?>">
						<?php echo esc_html( get_comment_date( '', $comment ) ); ?>
					</time>
				</span>

				<?php if ( '0' === $comment->comment_approved ) : ?>
					<span class="ab-comment__awaiting"><?php esc_html_e( 'Your comment is awaiting moderation.', 'abidar' ); ?></span>
				<?php endif; ?>
			</div>

			<div class="ab-comment__content">
				<?php comment_text( $comment ); ?>
			</div>

			<?php
			// Only regular comments get a reply link; pings and trackbacks do not.
			$is_ping = in_array( (string) $comment->comment_type, array( 'pingback', 'trackback' ), true );

			if ( ! $is_ping && comments_open() ) :
				?>
				<div class="ab-comment__reply">
					<?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'depth'      => $depth,
								'max_depth'  => $args['max_depth'],
								'reply_text' => esc_html__( 'Reply', 'abidar' ),
							)
						),
						$comment
					);
					?>
				</div>
			<?php endif; ?>
		</article>
	</<?php echo esc_attr( $tag ); ?>>
	<?php
}
