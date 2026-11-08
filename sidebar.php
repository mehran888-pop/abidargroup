<?php
/**
 * Blog sidebar.
 *
 * Falls back to a search box, recent posts, categories and a contact card when
 * no widgets have been added to the "Blog sidebar" widget area yet.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

if ( ! abidar_option( 'blog_sidebar', 1 ) ) {
	return;
}

$class = isset( $args['class'] ) ? $args['class'] : 'ab-sidebar';
?>

<aside class="<?php echo esc_attr( $class ); ?>" aria-label="<?php esc_attr_e( 'Blog sidebar', 'abidar' ); ?>">
	<?php
	if ( is_active_sidebar( 'sidebar-1' ) ) {
		dynamic_sidebar( 'sidebar-1' );
	} else {
		?>
		<section class="widget">
			<h3 class="widget__title"><?php echo esc_html( abidar_copy( 'nav_search' ) ); ?></h3>
			<?php get_search_form(); ?>
		</section>

		<section class="widget">
			<h3 class="widget__title"><?php echo esc_html( abidar_copy( 'latest_news' ) ); ?></h3>
			<ul class="ab-sidebar__list">
				<?php
				$ab_recent = new WP_Query(
					array(
						'posts_per_page'      => 5,
						'no_found_rows'       => true,
						'ignore_sticky_posts' => true,
						'post__not_in'        => is_singular() ? array( get_the_ID() ) : array(),
					)
				);

				while ( $ab_recent->have_posts() ) :
					$ab_recent->the_post();
					printf(
						'<li><a href="%s">%s</a><span class="ab-muted">%s</span></li>',
						esc_url( get_permalink() ),
						esc_html( get_the_title() ),
						esc_html( get_the_date() )
					);
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		</section>

		<?php
		$ab_cats = get_categories( array( 'hide_empty' => true ) );

		if ( $ab_cats ) :
			?>
			<section class="widget">
				<h3 class="widget__title"><?php echo esc_html( abidar_copy( 'categories' ) ); ?></h3>
				<ul class="ab-sidebar__list">
					<?php foreach ( $ab_cats as $ab_cat ) : ?>
						<li>
							<a href="<?php echo esc_url( get_category_link( $ab_cat->term_id ) ); ?>"><?php echo esc_html( $ab_cat->name ); ?></a>
							<span class="ab-muted"><?php echo esc_html( number_format_i18n( $ab_cat->count ) ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
			<?php
		endif;

		$ab_phone = abidar_option( 'topbar_phone' );
		$ab_email = abidar_option( 'topbar_email' );

		if ( $ab_phone || $ab_email ) :
			?>
			<section class="widget ab-sidebar__cta">
				<h3 class="widget__title"><?php echo esc_html( abidar_copy( 'need_help' ) ); ?></h3>
				<p class="ab-muted"><?php echo esc_html( abidar_copy( 'need_help_text' ) ); ?></p>
				<?php if ( $ab_phone ) : ?>
					<a class="ab-sidebar__contact" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $ab_phone ) ); ?>">
						<?php abidar_icon( 'phone', true, array( 'width' => 16, 'height' => 16 ) ); ?>
						<span><?php echo esc_html( $ab_phone ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( $ab_email ) : ?>
					<a class="ab-sidebar__contact" href="mailto:<?php echo esc_attr( $ab_email ); ?>">
						<?php abidar_icon( 'mail', true, array( 'width' => 16, 'height' => 16 ) ); ?>
						<span><?php echo esc_html( $ab_email ); ?></span>
					</a>
				<?php endif; ?>
			</section>
			<?php
		endif;
	}
	?>
</aside>
