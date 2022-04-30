<?php
/**
 * The preloader.
 *
 * @package Logo_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$preloader_image = SP_LOGO_CAROUSEL_PRO_URL . 'public/assets/css/images/bx_loader.gif';
if ( ! empty( $preloader_image ) ) {
	$outline .= '<div id="lcp-preloader-' . $post_id . '" class="sp-logo-carousel-pro-preloader"><img src=" ' . $preloader_image . ' "/></div>';
}
