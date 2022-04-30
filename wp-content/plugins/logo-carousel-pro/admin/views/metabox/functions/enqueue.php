<?php 
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Framework admin enqueue style and scripts
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sp_lcpro_admin_enqueue_scripts' ) ) {
	function sp_lcpro_admin_enqueue_scripts() {

		// SP_LCPRO_Framework Logo Carousel Pro.
		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;
		if ( $the_current_post_type == 'sp_lcp_shortcodes' || $the_current_post_type == 'sp_logo_carousel' ) {

			// admin utilities.
			wp_enqueue_media();

			// wp core styles.
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_style( 'jquery-ui-slider' );

			// framework core styles.
			wp_enqueue_style( 'lcpro-sp-framework', SP_URI . '/assets/css/sp-framework.css', array(), '1.1.0', 'all' );
			wp_enqueue_style( 'lcpro-sp-custom', SP_URI . '/assets/css/sp-custom.min.css', array(), '1.1.0', 'all' );
			wp_enqueue_style( 'lcpro-font-awesome', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/css/font-awesome.min.css', array(), '4.2.0', 'all' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'lcpro-sp-framework-rtl', SP_URI . '/assets/css/sp-framework-rtl.css', array(), '1.1.0', 'all' );
			}

			// wp core scripts.
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'jquery-ui-dialog' );
			// wp_enqueue_script( 'jquery-ui-sortable' );.
			wp_enqueue_script( 'jquery-ui-slider' );

			// framework core scripts.
			wp_enqueue_script( 'lcpro-sp-plugins', SP_URI . '/assets/js/sp-plugins.min.js', array( 'jquery' ), '1.1.0', true );
			wp_enqueue_script( 'lcpro-sp-framework', SP_URI . '/assets/js/sp-framework.min.js', array( 'lcpro-sp-plugins' ), '1.1.0', true );
		}

	}

	add_action( 'admin_enqueue_scripts', 'sp_lcpro_admin_enqueue_scripts' );
}
