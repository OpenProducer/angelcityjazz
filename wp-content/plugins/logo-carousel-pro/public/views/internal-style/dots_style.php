<?php
/**
 * Dots sytle
 *
 * Style for the dots.
 *
 * @package Logo Carousel Pro
 */

$outline .= '
		div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area ul.slick-dots li button{background: ' . $dots_color . ' !important; }
		div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area ul.slick-dots li.slick-active button{background: ' . $dots_active_color . ' !important; }

		div.sp-logo-carousel-pro-section.nav_position_bottom_left div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev,
		div.sp-logo-carousel-pro-section.nav_position_bottom_left div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next,
		div.sp-logo-carousel-pro-section.nav_position_bottom_right div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev,
		div.sp-logo-carousel-pro-section.nav_position_bottom_right div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next{
		    bottom: -10px;
		}
		div.sp-logo-carousel-pro-section.nav_position_vertical_center div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev,
		div.sp-logo-carousel-pro-section.nav_position_vertical_center div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next,
		div.sp-logo-carousel-pro-section.nav_position_vertical_center_inner div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev,
		div.sp-logo-carousel-pro-section.nav_position_vertical_center_inner div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next,
		div.sp-logo-carousel-pro-section.nav_position_vertical_center_inner_hover div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev,
		div.sp-logo-carousel-pro-section.nav_position_vertical_center_inner_hover div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next{
			margin-top: -33px;
		}

		div.sp-logo-carousel-pro-section.sp-logo-section-id-' . $custom_id . '.nav_position_bottom_right,
		div.sp-logo-carousel-pro-section.sp-logo-section-id-' . $custom_id . '.nav_position_bottom_left {
		    padding-bottom: 10px;
		}';
