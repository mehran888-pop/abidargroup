<?php
/**
 * Generic result card (search results, custom post type archives).
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

$post_type = get_post_type();
$type_obj  = get_post_type_object( $post_type );
$label     = $type_obj ? $type_obj->labels->singular_name : '';
?>
<article <?php post_class( 'ab-card ab-post-card ab-reveal' ); ?>>
	<a class="ab-post-card__media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
		<?php abidar_thumbnail( 'abidar-card' ); ?>
	</a>

	<div class="ab-post-card__body">
		<span class="ab-post-meta">
			<span class="ab-post-meta__cat"><?php echo esc_html( $label ); ?></span>
		</span>

		<h3 class="ab-post-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<p class="ab-post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>

		<div class="ab-post-card__foot">
			<a class="ab-link-arrow" href="<?php the_permalink(); ?>">
				<span><?php echo esc_html( abidar_copy( 'read_more' ) ); ?></span>
				<?php abidar_icon( 'arrow-right', true, array( 'width' => 15, 'height' => 15 ) ); ?>
			</a>
		</div>
	</div>
</article>
