<?php
/**
 * Vertical Carousel
 * slick_vertical.php
 *
 * @package Logo Carousel Pro
 */

$bottom_minus = $logo_margin + 6;
$top_minus    = $logo_margin + 1;
$outline     .= '
		.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item {
			margin-top: ' . $logo_wrapper_margin . 'px;
			margin-bottom: ' . $logo_wrapper_margin . 'px;
		}
		.nav_position_vertical_center.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-list,
		.nav_position_vertical_center_inner.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-list,
		.nav_position_vertical_center_inner_hover.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-list{
			margin-top: -' . $top_minus . 'px;
		}
		.nav_position_vertical_center_inner.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-list,
		.nav_position_vertical_center_inner_hover.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-list{

			margin-bottom: -' . $bottom_minus . 'px;
		}
		.nav_position_vertical_center.lcp_vertical div#sp-logo-carousel-pro5a30be0935904.sp-logo-carousel-pro-area .slick-list{
			margin: 0;
		}';
if ( 'true' == $nav ) {
	$outline .= '
		.nav_position_top_left.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . ' .slick-prev,
		.nav_position_bottom_left.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . ' .slick-prev{
			left: 0;
		}
		.nav_position_top_left.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . ' .slick-next,
		.nav_position_bottom_left.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . ' .slick-next{
			left: 40px;
		}
		div.sp-logo-carousel-pro-section.nav_position_top_right.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . ' .slick-next,
		div.sp-logo-carousel-pro-section.nav_position_bottom_right.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . ' .slick-next{
			right: 0;
		}
		div.sp-logo-carousel-pro-section.nav_position_top_right.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . ' .slick-prev,
		div.sp-logo-carousel-pro-section.nav_position_bottom_right.lcp_vertical div#sp-logo-carousel-pro' . $custom_id . ' .slick-prev{
			right: 40px;
		}
        .nav_position_vertical_center.lcp_vertical #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area  .slick-prev,
        .nav_position_vertical_center.lcp_vertical #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area  .slick-next {
			left: 50%;
		    margin-left: -15px;
		}
		.nav_position_vertical_center.lcp_vertical #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area  .slick-prev {
			top: inherit;
			bottom: inherit;
		}
		.nav_position_vertical_center.lcp_vertical #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area  .slick-next {
		    top: inherit;
		    bottom: 0;
		}
		.nav_position_vertical_center_inner_hover.lcp_vertical #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev {
	        left: 50%;
	        margin-left: -15px;
	        top: -37px;
	    }
		.nav_position_vertical_center_inner_hover.lcp_vertical #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next{
		    left: 50%;
		    margin-left: -15px;
		    top: inherit;
			bottom: -40px;
		}
		
		.nav_position_vertical_center_inner.lcp_vertical #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev,
		 .nav_position_vertical_center_inner_hover.lcp_vertical #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area:hover .slick-prev{
		    left: 50%;
		    margin-left: -15px;
		    top: 33px;
		 }
		.nav_position_vertical_center_inner_hover.lcp_vertical #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area:hover .slick-next,
		 .nav_position_vertical_center_inner.lcp_vertical #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next{
		    left: 50%;
		    margin-left: -15px;
		    top: inherit;
		    bottom: 0;
		}
		';
}
