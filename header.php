<?php
/**
 * Document head + opening body.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php
	if ( $favicon = abidar_option( 'favicon', '' ) ) {
		printf( '<link rel="icon" href="%s">', esc_url( wp_get_attachment_image_url( (int) $favicon, 'full' ) ) );
	}
	wp_head();
	?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( abidar_option( 'preloader', 0 ) ) : ?>
	<div class="ab-preloader" aria-hidden="true"><span class="ab-preloader__spinner"></span></div>
<?php endif; ?>

<div id="page" class="ab-site">
	<?php abidar_header(); ?>

	<main id="main" class="ab-main">
