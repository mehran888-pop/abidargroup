<?php
/**
 * Post card.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'ab-card ab-post-card ab-reveal' ); ?>>
	<a class="ab-post-card__media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
		<?php abidar_thumbnail( 'abidar-card' ); ?>
		<time class="ab-post-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
	</a>

	<div class="ab-post-card__body">
		<?php abidar_post_meta( false ); ?>

		<h3 class="ab-post-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<p class="ab-post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), (int) abidar_option( 'blog_excerpt_length', 24 ) ) ); ?></p>

		<div class="ab-post-card__foot">
			<a class="ab-link-arrow" href="<?php the_permalink(); ?>">
				<span><?php echo esc_html( abidar_copy( 'read_more' ) ); ?></span>
				<?php abidar_icon( 'arrow-right', true, array( 'width' => 15, 'height' => 15 ) ); ?>
			</a>
			<span><?php echo esc_html( abidar_reading_time() ); ?></span>
		</div>
	</div>
</article>
