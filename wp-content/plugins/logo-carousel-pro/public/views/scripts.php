<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

/**
 * Scripts and styles
 */
class SP_LCPRO_Front_Scripts {

	/**
	 * Single instance of the class
	 *
	 * @var null
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * @return SP_LCPRO_Front_Scripts
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
		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
	}

	/**
	 * Plugin Scripts and Styles
	 */
	public function front_scripts() {
		// CSS Files.
		if ( true == sp_lcpro_get_option( 'lcpro_slick_css', true ) ) {
			wp_register_style( 'lcpro-slick', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/css/slick.min.css', array(), SP_LOGO_CAROUSEL_PRO_VERSION );
		}
		if ( true == sp_lcpro_get_option( 'lcpro_bxslider_css', true ) ) {
			wp_register_style( 'lcpro-bxslider', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/css/jquery.bxslider.min.css', array(), SP_LOGO_CAROUSEL_PRO_VERSION );
		}
		if ( true == sp_lcpro_get_option( 'lcpro_fontawesome_css', true ) ) {
			wp_register_style( 'lcpro-font-awesome', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/css/font-awesome.min.css', array(), SP_LOGO_CAROUSEL_PRO_VERSION );
		}
		if ( true == sp_lcpro_get_option( 'lcpro_tooltipster_css', true ) ) {
			wp_register_style( 'lcpro-tooltipster', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/css/tooltipster.min.css', array(), SP_LOGO_CAROUSEL_PRO_VERSION );
		}
		wp_register_style( 'lcpro-custom', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/css/custom.css', array(), SP_LOGO_CAROUSEL_PRO_VERSION );
		wp_register_style( 'lcpro-style', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/css/style.min.css', array(), SP_LOGO_CAROUSEL_PRO_VERSION );

		include SP_LOGO_CAROUSEL_PRO_PATH . '/includes/custom-css.php';
		if ( isset( $custom_css ) && ! empty( $custom_css ) ) {
			wp_add_inline_style( 'lcpro-custom', $custom_css );
		}

		// JS Files.
		if ( true == sp_lcpro_get_option( 'lcpro_bxslider_js', true ) ) {
			wp_register_script( 'lcp-bx-slider-min-js', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/js/jquery.bxslider.min.js', array( 'jquery' ), SP_LOGO_CAROUSEL_PRO_VERSION, true );
		}
		wp_register_script( 'lcp-bx-slider-config', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/js/bx_slider_config.js', array( 'jquery' ), SP_LOGO_CAROUSEL_PRO_VERSION, true );
		wp_register_script( 'lcp-preloader-js', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/js/preloader.js', array( 'jquery' ), SP_LOGO_CAROUSEL_PRO_VERSION, true );

		if ( true == sp_lcpro_get_option( 'lcpro_slick_js', true ) ) {
			wp_register_script( 'lcp-slick-min-js', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/js/slick.min.js', array( 'jquery' ), SP_LOGO_CAROUSEL_PRO_VERSION, true );
		}
		wp_register_script( 'lcp-slick-config', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/js/slick_config.js', array( 'jquery' ), SP_LOGO_CAROUSEL_PRO_VERSION, true );

		if ( true == sp_lcpro_get_option( 'lcpro_tooltipster_js', true ) ) {
			wp_register_script( 'lcp-tooltipster-min-js', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/js/tooltipster.min.js', array( 'jquery' ), SP_LOGO_CAROUSEL_PRO_VERSION, true );
		}
		wp_register_script( 'lcp-tooltip-config', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/js/tooltip_config.js', array( 'jquery' ), SP_LOGO_CAROUSEL_PRO_VERSION, true );

		if ( true == sp_lcpro_get_option( 'lcpro_isotope_js', true ) ) {
			wp_register_script( 'lcp-jquery-isotope-min-js', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/js/jquery.isotope.min.js', array( 'jquery' ), SP_LOGO_CAROUSEL_PRO_VERSION, true );
		}
		wp_register_script( 'lcp-filter-config', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/js/filter-config.js', array( 'jquery' ), SP_LOGO_CAROUSEL_PRO_VERSION, true );
		wp_register_script( 'lcp-filter-opacity-config', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/js/filter-opacity-config.js', array( 'jquery' ), SP_LOGO_CAROUSEL_PRO_VERSION, true );

		if ( true == sp_lcpro_get_option( 'lcpro_remodal_js', true ) ) {
			wp_register_script( 'lcp-remodal-js', SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/js/remodal.min.js', array( 'jquery' ), SP_LOGO_CAROUSEL_PRO_VERSION, true );
		}

	}

}
new SP_LCPRO_Front_Scripts();
