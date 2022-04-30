<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'sp_lcpro_get_icons' ) ) {
	function sp_lcpro_get_icons() {

		do_action( 'sp_add_icons_before' );

		$jsons = glob( SP_DIR . '/fields/icon/*.json' );

		if( ! empty( $jsons ) ) {

			foreach ( $jsons as $path ) {

				$object = sp_lcpro_get_icon_fonts( 'fields/icon/'. basename( $path ) );

				if( is_object( $object ) ) {

					echo ( count( $jsons ) >= 2 ) ? '<h4 class="sp-icon-title">'. $object->name .'</h4>' : '';

					foreach ( $object->icons as $icon ) {
						echo '<a class="sp-icon-tooltip" data-icon="'. $icon .'" data-title="'. $icon .'"><span class="sp-icon sp-selector"><i class="'. $icon .'"></i></span></a>';
					}

				} else {
					echo '<h4 class="sp-icon-title">'. __( 'Error! Can not load json file.', 'logo-carousel-pro' ) .'</h4>';
				}

			}

		}

		do_action( 'sp_add_icons' );
		do_action( 'sp_add_icons_after' );

		die();
	}
	add_action( 'wp_ajax_sp-lcpro-get-icons', 'sp_lcpro_get_icons' );
}

// /**
//  *
//  * Set icons for wp dialog
//  *
//  * @since 1.0.0
//  * @version 1.0.0
//  *
//  */
// if( ! function_exists( 'sp_set_icons' ) ) {
// 	function sp_set_icons() {

// 		echo '<div id="sp-icon-dialog" class="sp-dialog" title="'. __( 'Add Icon', 'logo-carousel-pro' ) .'">';
// 		echo '<div class="sp-dialog-header sp-text-center"><input type="text" placeholder="'. __( 'Search a Icon...', 'logo-carousel-pro' ) .'" class="sp-icon-search" /></div>';
// 		echo '<div class="sp-dialog-load"><div class="sp-icon-loading">'. __( 'Loading...', 'logo-carousel-pro' ) .'</div></div>';
// 		echo '</div>';

// 	}
// 	add_action( 'admin_footer', 'sp_set_icons' );
// 	add_action( 'customize_controls_print_footer_scripts', 'sp_set_icons' );
// }
