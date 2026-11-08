<?php
/**
 * Search form.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;
?>
<form role="search" method="get" class="ab-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="ab-search-input"><?php echo esc_html( abidar_copy( 'search_placeholder' ) ); ?></label>
	<input id="ab-search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( abidar_copy( 'search_placeholder' ) ); ?>">
	<button type="submit" aria-label="<?php echo esc_attr( abidar_copy( 'nav_search' ) ); ?>">
		<?php abidar_icon( 'search', true, array( 'width' => 18, 'height' => 18 ) ); ?>
	</button>
</form>
