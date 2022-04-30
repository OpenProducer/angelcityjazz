<?php
/**
 * Visual composer map for Logo Carousel Pro
 *
 * @package logo carousel pro
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
	/**
	 * Carousel map for the VC
	 */
	class SPLC_VC_Element {

		/**
		 * SPLC_VC_Element constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'sp_logo_carousel_vc' ) );
		}

		/**
		 * Integrate with visual composer
		 */
		public function sp_logo_carousel_vc() {
			// Check if Visual Composer is installed.
			if ( ! defined( 'WPB_VC_VERSION' ) ) {
				return;
			}

			vc_map( array(
				'name'        => esc_html__( 'Logo Carousel Pro', 'logo-carousel-pro' ),
				'base'        => 'logo_carousel_pro',
				'controls'    => 'full',
				'icon'        => SP_LOGO_CAROUSEL_PRO_URL . 'admin/assets/images/icon-32.png',
				'class'       => '',
				'description' => esc_html__( 'Display Logo Carousel .', 'logo-carousel-pro' ),
				'category'    => esc_html__( 'ShapedPlugin', 'logo-carousel-pro' ),
				'params'      => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Shortcode:', 'logo-carousel-pro' ),
						'description' => esc_html__( 'Select a shortcode.', 'logo-carousel-pro' ),
						'param_name'  => 'id',
						'value'       => $this->shortcodes_list(),
					),
				),
			) );
		}

		/**
		 * Generate shortcode list
		 *
		 * @return array
		 */
		private function shortcodes_list() {
			$shortcodes = get_posts( array(
				'post_type'      => 'sp_lcp_shortcodes',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			) );

			if ( count( $shortcodes ) < 1 ) {
				return array();
			}

			$result = array();

			foreach ( $shortcodes as $shortcode ) {
				$result[ esc_html( $shortcode->post_title ) ] = $shortcode->ID;
			}

			return $result;
		}

	}
	new SPLC_VC_Element();
}
