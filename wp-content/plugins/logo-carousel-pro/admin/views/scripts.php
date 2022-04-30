<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

/**
 * Scripts and styles
 */
class SP_LCPRO_Admin_Scripts {

	/**
	 * Single instance of the class
	 *
	 * @var null
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * @return SP_LCPRO_Admin_Scripts
	 * @since 1.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Plugin Scripts and Styles
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'lcpro-admin-style', SP_LOGO_CAROUSEL_PRO_URL . 'admin/assets/css/admin-style.css', array(), SP_LOGO_CAROUSEL_PRO_VERSION );

		$screen = get_current_screen();
		if ( $screen->id == 'sp_logo_carousel_page_lcpro_help' ) {
			wp_enqueue_style( 'lcpro-font-awesome', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/css/font-awesome.min.css', array(), SP_LOGO_CAROUSEL_PRO_VERSION );
			wp_enqueue_style( 'lcpro-help-style', SP_LOGO_CAROUSEL_PRO_URL . 'admin/assets/css/help.css', array(), SP_LOGO_CAROUSEL_PRO_VERSION );
		}
	}

}
new SP_LCPRO_Admin_Scripts();
