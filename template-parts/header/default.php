<?php
/**
 * Default header — used when no Elementor header template exists.
 *
 * Emits the same markup and classes as the "Abidar site header" widget, so the
 * design is identical whether the header is built with Elementor or falls back
 * to PHP.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

$layout  = abidar_option( 'header_layout', 'classic' );
$lang    = Abidar_Multilingual::instance();
$cta     = abidar_copy( 'nav_quote' );
$cta_url = abidar_option( 'header_cta_url', '' );
$cta_url = $cta_url ? $cta_url : '#contact';
$phone   = abidar_option( 'topbar_phone', '' );
$email   = abidar_option( 'topbar_email', '' );
$items   = function_exists( 'abidar_menu_items' ) ? abidar_menu_items() : array();
$uid     = 'ab-drawer-default';

$wrap_class = 'ab-masthead-wrap';

if ( abidar_option( 'header_sticky', 1 ) ) {
	$wrap_class .= ' is-sticky-enabled';
}

if ( abidar_option( 'header_transparent', 0 ) ) {
	$wrap_class .= ' is-transparent';
}
?>
<a class="ab-skip" href="#main"><?php esc_html_e( 'Skip to content', 'abidar' ); ?></a>

<div class="<?php echo esc_attr( $wrap_class ); ?>" data-masthead>
	<?php if ( abidar_option( 'topbar_enabled', 1 ) ) : ?>
		<div class="ab-topbar">
			<div class="ab-container ab-topbar__inner">
				<div class="ab-topbar__left">
					<span class="ab-topbar__note">
						<?php abidar_icon( 'shield', true, array( 'width' => 14, 'height' => 14 ) ); ?>
						<?php echo esc_html( abidar_copy( 'topbar_note' ) ); ?>
					</span>
				</div>
				<div class="ab-topbar__right">
					<?php if ( $phone ) : ?>
						<a class="ab-topbar__link" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
							<?php abidar_icon( 'phone', true, array( 'width' => 14, 'height' => 14 ) ); ?>
							<span dir="ltr"><?php echo esc_html( $phone ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( $email ) : ?>
						<a class="ab-topbar__link" href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>">
							<?php abidar_icon( 'mail', true, array( 'width' => 14, 'height' => 14 ) ); ?>
							<span><?php echo esc_html( antispambot( $email ) ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( abidar_option( 'show_lang_switcher', 1 ) ) : ?>
						<span class="ab-topbar__lang">
							<?php echo $lang->switcher( array( 'style' => 'inline', 'class' => 'ab-topbar__switcher' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<header class="ab-masthead ab-masthead--<?php echo esc_attr( $layout ); ?>" data-masthead-bar>
		<div class="ab-container ab-masthead__inner">

			<?php if ( 'split' === $layout ) : ?>
				<nav class="ab-masthead__nav ab-masthead__nav--start" aria-label="<?php esc_attr_e( 'Primary menu', 'abidar' ); ?>">
					<?php abidar_masthead_menu( $items, 3 ); ?>
				</nav>
			<?php endif; ?>

			<div class="ab-masthead__brand">
				<?php abidar_brand_mark(); ?>
			</div>

			<?php if ( 'split' !== $layout && 'minimal' !== $layout ) : ?>
				<nav class="ab-masthead__nav" aria-label="<?php esc_attr_e( 'Primary menu', 'abidar' ); ?>">
					<?php abidar_masthead_menu( $items, 3 ); ?>
				</nav>
			<?php endif; ?>

			<div class="ab-masthead__actions">
				<?php if ( abidar_option( 'show_lang_switcher', 1 ) && ! abidar_option( 'topbar_enabled', 1 ) ) : ?>
					<span class="ab-masthead__lang">
						<?php echo $lang->switcher( array( 'style' => 'dropdown', 'class' => 'ab-header__lang' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</span>
				<?php endif; ?>

				<?php if ( abidar_option( 'show_search', 1 ) ) : ?>
					<button class="ab-masthead__iconbtn" type="button" aria-label="<?php esc_attr_e( 'Search', 'abidar' ); ?>" data-ab-search>
						<?php abidar_icon( 'search', true, array( 'width' => 18, 'height' => 18 ) ); ?>
					</button>
				<?php endif; ?>

				<?php if ( $cta ) : ?>
					<a class="ab-masthead__cta" href="<?php echo esc_url( $cta_url ); ?>">
						<span><?php echo esc_html( $cta ); ?></span>
						<?php abidar_icon( 'arrow-right', true, array( 'width' => 15, 'height' => 15 ) ); ?>
					</a>
				<?php endif; ?>

				<button class="ab-masthead__burger" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $uid ); ?>" data-ab-drawer>
					<?php abidar_icon( 'menu', true, array( 'width' => 22, 'height' => 22 ) ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'abidar' ); ?></span>
				</button>
			</div>
		</div>

		<div class="ab-search-panel" data-ab-search-panel hidden>
			<div class="ab-container">
				<?php get_search_form(); ?>
				<button class="ab-search-panel__close" type="button" data-ab-search aria-label="<?php esc_attr_e( 'Close', 'abidar' ); ?>">
					<?php abidar_icon( 'x', true, array( 'width' => 18, 'height' => 18 ) ); ?>
				</button>
			</div>
		</div>
	</header>

	<aside class="ab-masthead__drawer" id="<?php echo esc_attr( $uid ); ?>" hidden>
		<div class="ab-masthead__drawer-head">
			<?php abidar_brand_mark( array( 'size' => 128 ) ); ?>
			<button class="ab-masthead__drawer-close" type="button" data-ab-drawer aria-label="<?php esc_attr_e( 'Close', 'abidar' ); ?>">
				<?php abidar_icon( 'x', true, array( 'width' => 20, 'height' => 20 ) ); ?>
			</button>
		</div>

		<?php abidar_masthead_menu( $items, 3, 'ab-masthead__drawer-list' ); ?>

		<?php if ( $phone ) : ?>
			<a class="ab-masthead__drawer-phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
				<?php abidar_icon( 'phone', true, array( 'width' => 16, 'height' => 16 ) ); ?>
				<span dir="ltr"><?php echo esc_html( $phone ); ?></span>
			</a>
		<?php endif; ?>

		<?php echo $lang->switcher( array( 'style' => 'inline', 'class' => 'ab-masthead__drawer-lang' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</aside>
</div>
