<?php
/**
 * Grayscale style.
 * grayscale.php
 *
 * @package logo carousel pro
 */

if ( 'always_gray' == $gray_scale ) {
	$outline .= 'div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item img{
				filter: gray;
				-webkit-filter: grayscale(1);
				-webkit-filter: grayscale(100%); /* New WebKit */
				-o-filter: grayscale(1);
				filter: grayscale(1); /* Microsoft Edge and Firefox 35+ */
			}';
}
if ( 'gray_with_normal' == $gray_scale ) {
	$outline .= '
			div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item img{
				filter: gray;
				-webkit-filter: grayscale(1);
				-webkit-filter: grayscale(100%); /* New WebKit */
				-o-filter: grayscale(1);
				filter: grayscale(1); /* Microsoft Edge and Firefox 35+ */
			}';
	if ( 'center' == $carousel_mode ) {
		$outline .= 'div.sp-logo-carousel-pro-section.layout-carousel div#sp-logo-carousel-pro' . $custom_id . ' .sp-lcp-item.slick-center img,';
	}
	$outline .= 'div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item:hover img{
				filter: none;
				-webkit-filter: grayscale(0);
				-o-filter: grayscale(0);
			}';
}
if ( 'gray_on_hover' == $gray_scale ) {
	if ( 'center' == $carousel_mode ) {
		$outline .= 'div.sp-logo-carousel-pro-section.layout-carousel div#sp-logo-carousel-pro' . $custom_id . ' .sp-lcp-item.slick-center img,';
	}
	$outline .= '
			div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item:hover img{
				filter: gray;
				-webkit-filter: grayscale(1);
				-webkit-filter: grayscale(100%); /* New WebKit */
				-o-filter: grayscale(1);
				filter: grayscale(1); /* Microsoft Edge and Firefox 35+ */
			}
		';
}
if ( 'gray_with_normal' == $gray_scale && 'true' == $gray_scale_on_mnt ) {
	$outline .= '@media screen and (max-width: 736px) {';
	if ( 'center' === $carousel_mode ) {
		$outline .= 'div.sp-logo-carousel-pro-section.layout-carousel div#sp-logo-carousel-pro' . $custom_id . ' .sp-lcp-item.slick-center img,';
	}
	$outline .= '
			div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item img{
				filter: gray;
				-webkit-filter: grayscale(1);
				-webkit-filter: grayscale(100%); /* New WebKit */
				-o-filter: grayscale(1);
				filter: grayscale(1); /* Microsoft Edge and Firefox 35+ */
			}
		}';
} elseif ( 'gray_with_normal' === $gray_scale && 'false' == $gray_scale_on_mnt ) {
	$outline .= '@media screen and (max-width: 736px) {
			div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item img{
				filter: none;
				-webkit-filter: grayscale(0);
				-o-filter: grayscale(0);
			}
		}';
}
