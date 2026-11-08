<?php
/**
 * Archive (category / tag / date / author / custom post types).
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();

$title       = get_the_archive_title();
$title       = preg_replace( '/^[^:]+:\s*/', '', $title );
$description = get_the_archive_description();

abidar_page_hero(
	array(
		'title'    => $title,
		'subtitle' => wp_strip_all_tags( $description ),
		'eyebrow'  => is_post_type_archive() ? post_type_archive_title( '', false ) : abidar_copy( 'archive_eyebrow' ),
		'bg'       => ABIDAR_URI . '/assets/img/pattern-dark.jpg',
	)
);
?>

<section class="ab-section">
	<div class="ab-container">
		<?php if ( have_posts() ) : ?>
			<div class="ab-posts-grid ab-stagger">
				<?php
				while ( have_posts() ) :
					the_post();
					if ( 'post' === get_post_type() ) {
						get_template_part( 'template-parts/card/post' );
					} else {
						get_template_part( 'template-parts/card/media' );
					}
				endwhile;
				?>
			</div>

			<?php abidar_pagination(); ?>
		<?php else : ?>
			<div class="ab-center" style="padding-block:3rem">
				<h2><?php echo esc_html( abidar_copy( 'no_results' ) ); ?></h2>
				<p><a class="ab-btn ab-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( abidar_copy( 'back_home' ) ); ?></a></p>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
