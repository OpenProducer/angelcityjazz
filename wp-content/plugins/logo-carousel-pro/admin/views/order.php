<?php
/**
 * Logo Carousel Post Order.
 *
 * @package Logo carousel pro
 */

if ( file_exists( SP_LOGO_CAROUSEL_PRO_PATH . 'class/order.php' ) ) {
	require_once( SP_LOGO_CAROUSEL_PRO_PATH . 'class/order.php' );
}

add_action( 'plugins_loaded', 'sp_custom_order_class_load' );
/**
 * Custom order class.
 *
 * @return void
 */
function sp_custom_order_class_load() {

	global $SP_CUSTOM_SORT_CLASS;
	$SP_CUSTOM_SORT_CLASS = new SP_CUSTOM_SORT_CLASS();
}



add_action( 'wp_loaded', 'init_sp_custom_order' );
/**
 * Init custom order.
 *
 * @return void
 */
function init_sp_custom_order() {
	global $SP_CUSTOM_SORT_CLASS;
	if ( is_admin() ) {
			$SP_CUSTOM_SORT_CLASS->init();
	}
}
