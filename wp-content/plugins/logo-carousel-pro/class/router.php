<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Logo Carousel Pro - router class
 *
 * @since 3.3
 */
class SP_LCPRO_Router {

	/**
	 * Single instance of the class
	 *
	 * @var SP_LCPRO_Router single instance of the class
	 *
	 * @since 3.3
	 */
	protected static $_instance = null;


	/**
	 * Main SP_LCPRO_Router Instance
	 *
	 * @since 3.3
	 * @static
	 * @return self Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Include the required files
	 *
	 * @since 3.3
	 * @return void
	 */
	function includes() {
		include_once SP_LOGO_CAROUSEL_PRO_PATH . '/includes/loader.php';
	}

	/**
	 * Logo Carousel Pro function
	 *
	 * @since 1.0
	 * @return void
	 */
	function sp_lcpro_function() {
		include_once SP_LOGO_CAROUSEL_PRO_PATH . '/includes/functions.php';
	}

	/**
	 * LCPRO MeatBox
	 *
	 * @since 2.0
	 * @return void
	 */
	function sp_lcpro_metabox() {
		include_once SP_LOGO_CAROUSEL_PRO_PATH . '/admin/views/metabox/sp-framework.php';
	}

}
