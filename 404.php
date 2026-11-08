<?php
/**
 * 404.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="ab-hero" style="min-height:70vh">
	<div class="ab-hero__bg">
		<img src="<?php echo esc_url( ABIDAR_URI . '/assets/img/pattern-dark.jpg' ); ?>" alt="">
	</div>
	<div class="ab-container ab-hero__inner">
		<div class="ab-hero__content">
			<span class="ab-eyebrow ab-eyebrow--light">404</span>
			<h1 class="ab-hero__title"><?php echo esc_html( abidar_copy( 'not_found_title' ) ); ?></h1>
			<p class="ab-hero__text"><?php echo esc_html( abidar_copy( 'not_found_text' ) ); ?></p>

			<div style="max-width:520px;margin-bottom:1.5rem"><?php get_search_form(); ?></div>

			<div class="ab-hero__actions">
				<a class="ab-btn ab-btn--accent ab-btn--lg" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<span><?php echo esc_html( abidar_copy( 'back_home' ) ); ?></span>
					<?php abidar_icon( 'arrow-right', true, array( 'width' => 17, 'height' => 17 ) ); ?>
				</a>
				<a class="ab-btn ab-btn--light ab-btn--lg" href="<?php echo esc_url( abidar_page_url( 'contact' ) ); ?>">
					<span><?php echo esc_html( abidar_copy( 'nav_contact' ) ); ?></span>
				</a>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
