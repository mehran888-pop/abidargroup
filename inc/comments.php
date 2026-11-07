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
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'ab-comment' ); ?>>
		<article style="display:flex;gap:1rem;padding:1.25rem 0;border-bottom:1px solid var(--ab-line)">
			<?php echo get_avatar( $comment, 48, '', '', array( 'style' => 'border-radius:50%;flex:none' ) ); ?>

			<div style="flex:1">
				<div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap">
					<strong style="font-family:var(--ab-font-head);font-size:.92rem;color:var(--ab-ink)"><?php echo esc_html( get_comment_author( $comment ) ); ?></strong>
					<time style="font-size:.74rem;color:var(--ab-body);opacity:.75" datetime="<?php echo esc_attr( get_comment_date( 'c', $comment ) ); ?>">
						<?php echo esc_html( get_comment_date( '', $comment ) ); ?>
					</time>
				</div>

				<?php if ( '0' === $comment->comment_approved ) : ?>
					<p class="ab-muted" style="font-size:.8rem"><?php esc_html_e( 'Your comment is awaiting moderation.', 'abidar' ); ?></p>
				<?php endif; ?>

				<div style="font-size:.92rem;margin-top:.4rem"><?php comment_text( $comment ); ?></div>

				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'reply_text' => esc_html__( 'Reply', 'abidar' ),
							'before'    => '<span style="font-size:.78rem;font-weight:600">',
							'after'     => '</span>',
						)
					),
					$comment
				);
				?>
			</div>
		</article>
	<?php
}
