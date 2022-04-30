<?php
/**
 * Horizontal Carousel.
 *
 * The stile for the horizontal carousel.
 *
 * @package Logo Carousel Pro
 */
if ( 'none' !== $border_style ) {
	$logo_border_padding = '3px';
} else {
	$logo_border_padding = '0';
}

$outline .= '
		.lcp_horizontal div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item {
			padding-left: ' . $logo_wrapper_margin . 'px;
			padding-right: ' . $logo_wrapper_margin . 'px;
		}
		.lcp_horizontal div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-list{
			margin-left: -' . $logo_wrapper_margin . 'px;
			margin-right: -' . $logo_wrapper_margin . 'px;
			padding-right: ' . $logo_border_padding . ';
		}';
if ( 'true' == $nav ) {
	$outline .= '
		.nav_position_top_left.lcp_horizontal div#sp-logo-carousel-pro' . $custom_id . ' .slick-prev,
		.nav_position_bottom_left.lcp_horizontal div#sp-logo-carousel-pro' . $custom_id . ' .slick-prev{
			left: 0;
		}
		.nav_position_top_left.lcp_horizontal div#sp-logo-carousel-pro' . $custom_id . ' .slick-next,
		.nav_position_bottom_left.lcp_horizontal div#sp-logo-carousel-pro' . $custom_id . ' .slick-next{
			left: 40px;
		}
		div.sp-logo-carousel-pro-section.nav_position_top_right.lcp_horizontal div#sp-logo-carousel-pro' . $custom_id . ' .slick-next,
		div.sp-logo-carousel-pro-section.nav_position_bottom_right.lcp_horizontal div#sp-logo-carousel-pro' . $custom_id . ' .slick-next{
			right: 0;
		}
		div.sp-logo-carousel-pro-section.nav_position_top_right.lcp_horizontal div#sp-logo-carousel-pro' . $custom_id . ' .slick-prev,
		div.sp-logo-carousel-pro-section.nav_position_bottom_right.lcp_horizontal div#sp-logo-carousel-pro' . $custom_id . ' .slick-prev{
			right: 40px;
		}';
}
if ( '2' <= $lcp_row && 'carousel' == $layout ) {
	$outline .= '
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item {
		padding-top: ' . $logo_margin . 'px;
	}
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-slide {
		margin-top: -' . $logo_margin . 'px;
	}';
} else {
	$outline .= '
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item {
		padding-top: 0;
	}
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-slide {
		margin-top: 0;
	}';
}

$outline .= '
@media (min-width: 981px) and (max-width: 1200px) {';
if ( '2' <= $lcp_row_desktop && 'carousel' == $layout ) {
	$outline .= '
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item {
		padding-top: ' . $logo_margin . 'px;
	}
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-slide {
		margin-top: -' . $logo_margin . 'px;
	}';
} else {
	$outline .= '
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item {
		padding-top: 0;
	}
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-slide {
		margin-top: 0;
	}';
}
$outline .= '}';
$outline .= '
@media (min-width: 737px) and (max-width: 980px) {';
if ( '2' <= $lcp_row_desktop_small && 'carousel' == $layout ) {
	$outline .= '
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item {
		padding-top: ' . $logo_margin . 'px;
	}
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-slide {
		margin-top: -' . $logo_margin . 'px;
	}';
} else {
	$outline .= '
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item {
		padding-top: 0;
	}
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-slide {
		margin-top: 0;
	}';
}
$outline .= '}';
$outline .= '@media (min-width: 481px) and (max-width: 736px) {';
if ( '2' <= $lcp_row_tablet && 'carousel' == $layout ) {
	$outline .= '
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item {
		padding-top: ' . $logo_margin . 'px;
	}
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-slide {
		margin-top: -' . $logo_margin . 'px;
	}';
} else {
	$outline .= '
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item {
		padding-top: 0;
	}
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-slide {
		margin-top: 0;
	}';
}
$outline .= '}';
$outline .= '@media (max-width: 480px) {';
if ( '2' <= $lcp_row_mobile && 'carousel' == $layout ) {
	$outline .= '
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item {
		padding-top: ' . $logo_margin . 'px;
	}
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-slide {
		margin-top: -' . $logo_margin . 'px;
	}';
} else {
	$outline .= '
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item {
		padding-top: 0;
	}
	div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-slide {
		margin-top: 0;
	}';
}
$outline .= '}';
