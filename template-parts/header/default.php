<?php
/**
 * Default header.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

$layout     = abidar_option( 'header_layout', 'classic' );
$lang       = Abidar_Multilingual::instance();
$cta_text   = abidar_copy( 'nav_quote' );
$cta_url    = abidar_option( 'header_cta_url', '' );
$cta_url    = $cta_url ? $cta_url : '#contact';
$phone      = abidar_option( 'topbar_phone', '' );
?>
<a class="ab-skip" href="#main"><?php esc_html_e( 'Skip to content', 'abidar' ); ?></a>

<header id="ab-header" class="ab-header ab-header--<?php echo esc_attr( $layout ); ?>" data-header>
	<?php if ( abidar_option( 'topbar_enabled', 1 ) ) : ?>
		<div class="ab-topbar">
			<div class="ab-container ab-topbar__inner">
				<div class="ab-topbar__left">
					<span class="ab-topbar__note"><?php echo esc_html( abidar_copy( 'topbar_note' ) ); ?></span>
				</div>
				<div class="ab-topbar__right">
					<?php if ( $phone ) : ?>
						<a class="ab-topbar__link" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
							<?php abidar_icon( 'phone', true, array( 'width' => 14, 'height' => 14 ) ); ?>
							<span dir="ltr"><?php echo esc_html( $phone ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( $email = abidar_option( 'topbar_email', '' ) ) : ?>
						<a class="ab-topbar__link" href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>">
							<?php abidar_icon( 'mail', true, array( 'width' => 14, 'height' => 14 ) ); ?>
							<span><?php echo esc_html( antispambot( $email ) ); ?></span>
						</a>
					<?php endif; ?>

					<?php abidar_social_links( 'ab-social--mini' ); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="ab-header__bar">
		<div class="ab-container ab-header__inner">
			<?php if ( 'split' === $layout ) : ?>
				<nav class="ab-nav ab-nav--split ab-nav--left" aria-label="<?php esc_attr_e( 'Primary menu', 'abidar' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'ab-nav__list',
							'walker'         => new Abidar_Walker_Nav(),
							'fallback_cb'    => 'abidar_menu_fallback',
							'depth'          => 2,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<div class="ab-header__brand">
				<?php abidar_logo(); ?>
			</div>

			<?php if ( 'split' !== $layout ) : ?>
				<nav class="ab-nav" aria-label="<?php esc_attr_e( 'Primary menu', 'abidar' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'ab-nav__list',
							'walker'         => new Abidar_Walker_Nav(),
							'fallback_cb'    => 'abidar_menu_fallback',
							'depth'          => 2,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<div class="ab-header__actions">
				<?php if ( abidar_option( 'show_lang_switcher', 1 ) ) : ?>
					<?php echo $lang->switcher( array( 'style' => 'dropdown', 'class' => 'ab-header__lang' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php endif; ?>

				<?php if ( abidar_option( 'show_search', 1 ) ) : ?>
					<button class="ab-icon-btn ab-search-toggle" type="button" aria-label="<?php esc_attr_e( 'Search', 'abidar' ); ?>" data-search-toggle>
						<?php abidar_icon( 'search', true ); ?>
					</button>
				<?php endif; ?>

				<?php if ( $cta_text ) : ?>
					<a class="ab-btn ab-btn--primary ab-header__cta" href="<?php echo esc_url( $cta_url ); ?>">
						<span><?php echo esc_html( $cta_text ); ?></span>
						<?php abidar_icon( 'arrow-right', true, array( 'width' => 16, 'height' => 16 ) ); ?>
					</a>
				<?php endif; ?>

				<button class="ab-burger" type="button" aria-label="<?php esc_attr_e( 'Menu', 'abidar' ); ?>" aria-expanded="false" data-menu-toggle>
					<span></span><span></span><span></span>
				</button>
			</div>
		</div>
	</div>

	<div class="ab-search-panel" data-search-panel hidden>
		<div class="ab-container">
			<?php get_search_form(); ?>
			<button class="ab-search-panel__close" type="button" data-search-toggle aria-label="<?php esc_attr_e( 'Close', 'abidar' ); ?>">
				<?php abidar_icon( 'x', true ); ?>
			</button>
		</div>
	</div>
</header>

<div class="ab-mobile-nav" data-mobile-nav hidden>
	<div class="ab-mobile-nav__head">
		<?php abidar_logo(); ?>
		<button class="ab-mobile-nav__close" type="button" data-menu-toggle aria-label="<?php esc_attr_e( 'Close', 'abidar' ); ?>">
			<?php abidar_icon( 'x', true ); ?>
		</button>
	</div>

	<nav aria-label="<?php esc_attr_e( 'Mobile menu', 'abidar' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'ab-mobile-nav__list',
				'walker'         => new Abidar_Walker_Nav(),
				'fallback_cb'    => 'abidar_menu_fallback',
				'depth'          => 2,
			)
		);
		?>
	</nav>

	<div class="ab-mobile-nav__footer">
		<?php echo $lang->switcher( array( 'style' => 'inline', 'class' => 'ab-mobile-nav__lang' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>

		<?php if ( $phone ) : ?>
			<a class="ab-btn ab-btn--primary ab-btn--block" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
				<?php abidar_icon( 'phone', true, array( 'width' => 16, 'height' => 16 ) ); ?>
				<span dir="ltr"><?php echo esc_html( $phone ); ?></span>
			</a>
		<?php endif; ?>

		<?php abidar_social_links(); ?>
	</div>
</div>
