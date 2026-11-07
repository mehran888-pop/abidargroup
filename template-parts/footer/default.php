<?php
/**
 * Default footer.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

$about     = abidar_copy( 'footer_about' );
$copyright = abidar_option( 'footer_copyright', '' );
$copyright = $copyright ? $copyright : abidar_copy( 'copyright' );
$services  = abidar_services_data();
?>
<footer class="ab-footer" id="ab-footer">
	<?php if ( abidar_option( 'footer_show_newsletter', 1 ) ) : ?>
		<div class="ab-footer__newsletter">
			<div class="ab-container ab-footer__newsletter-inner">
				<div>
					<h3 class="ab-footer__newsletter-title"><?php echo esc_html( abidar_copy( 'footer_newsletter' ) ); ?></h3>
					<p><?php echo esc_html( abidar_copy( 'footer_newsletter_text' ) ); ?></p>
				</div>
				<form class="ab-newsletter" data-newsletter>
					<label class="screen-reader-text" for="ab-newsletter-email"><?php echo esc_html( abidar_copy( 'newsletter_placeholder' ) ); ?></label>
					<input id="ab-newsletter-email" type="email" required placeholder="<?php echo esc_attr( abidar_copy( 'newsletter_placeholder' ) ); ?>">
					<button class="ab-btn ab-btn--accent" type="submit"><?php echo esc_html( abidar_copy( 'newsletter_button' ) ); ?></button>
					<span class="ab-newsletter__msg" role="status" aria-live="polite"></span>
				</form>
			</div>
		</div>
	<?php endif; ?>

	<div class="ab-footer__main">
		<div class="ab-container ab-footer__grid">
			<div class="ab-footer__col ab-footer__col--brand">
				<?php abidar_logo( array( 'dark' => true ) ); ?>
				<p class="ab-footer__about"><?php echo esc_html( $about ); ?></p>
				<?php abidar_social_links(); ?>
			</div>

			<div class="ab-footer__col">
				<h4 class="ab-footer__title"><?php echo esc_html( abidar_copy( 'footer_links' ) ); ?></h4>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'ab-footer__list',
							'walker'         => new Abidar_Walker_Nav(),
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
				} else {
					echo '<ul class="ab-footer__list">';
					foreach ( array( 'about' => 'nav_about', 'projects' => 'nav_projects', 'team' => 'nav_team', 'blog' => 'nav_blog', 'contact' => 'nav_contact' ) as $slug => $key ) {
						printf(
							'<li><a href="%s">%s</a></li>',
							esc_url( abidar_page_url( $slug ) ),
							esc_html( abidar_copy( $key ) )
						);
					}
					echo '</ul>';
				}
				?>
			</div>

			<div class="ab-footer__col">
				<h4 class="ab-footer__title"><?php echo esc_html( abidar_copy( 'footer_services' ) ); ?></h4>
				<ul class="ab-footer__list">
					<?php foreach ( $services as $service ) : ?>
						<li><a href="<?php echo esc_url( abidar_page_url( 'services' ) ); ?>"><?php echo esc_html( abidar_localized( $service, 'title' ) ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="ab-footer__col">
				<h4 class="ab-footer__title"><?php echo esc_html( abidar_copy( 'footer_contact' ) ); ?></h4>
				<?php abidar_contact_rows(); ?>
			</div>
		</div>
	</div>

	<div class="ab-footer__bottom">
		<div class="ab-container ab-footer__bottom-inner">
			<p class="ab-footer__copy"><?php echo esc_html( $copyright ); ?></p>
			<ul class="ab-footer__legal">
				<li><a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>"><?php echo esc_html( abidar_copy( 'privacy' ) ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php echo esc_html( abidar_copy( 'terms' ) ); ?></a></li>
			</ul>
		</div>
	</div>
</footer>

<?php if ( abidar_option( 'scroll_top', 1 ) ) : ?>
	<button class="ab-to-top" type="button" aria-label="<?php esc_attr_e( 'Back to top', 'abidar' ); ?>" data-to-top>
		<?php abidar_icon( 'arrow-up', true ); ?>
	</button>
<?php endif; ?>
