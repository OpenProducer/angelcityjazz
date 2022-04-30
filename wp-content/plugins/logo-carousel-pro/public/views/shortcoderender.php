<?php
/**
 * This file render the shortcode to the frontend
 *
 * @package logo-carousel-pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Logo Carousel Pro - Shortcode Render class
 *
 * @since 3.3
 */
if ( ! class_exists( 'LCPRO_Shortcode_Render' ) ) {
	/**
	 * LCPRO_Shortcode_Render class
	 */
	class LCPRO_Shortcode_Render {

		/**
		 * @var LCPRO_Shortcode_Render single instance of the class
		 *
		 * @since 3.3
		 */
		protected static $_instance = null;


		/**
		 * LCPRO_Shortcode_Render Instance
		 *
		 * @since 3.3
		 * @static
		 * @return self Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * LCPRO_Shortcode_Render constructor.
		 */
		public function __construct() {
			add_shortcode( 'logo_carousel_pro', array( $this, 'shortcode_render' ) );
		}

		/**
		 * @param $attributes
		 *
		 * @return string
		 * @since 3.3
		 */
		public function shortcode_render( $attributes ) {
			shortcode_atts(
				array(
					'id' => '',
				),
				$attributes,
				'logo_carousel_pro'
			);

			$post_id = $attributes['id'];

			// All Options of Shortcode.
			$logo_data = get_post_meta( $post_id, 'sp_lcp_shortcode_options', true );

			// Check if the Shortcode exist.
			if ( ! is_array( $logo_data ) ) {
					return;
			}
			$section_title               = ( ( $logo_data['lcp_section_title'] ) == true ? 'true' : 'false' );
			$section_title_margin_bottom = $logo_data['lcp_section_title_margin'];

			/**
			 * Section Title Typography
			 */
			$title_typography           = $logo_data['lcp_section_title_typography'];
			$title_typography_family    = $title_typography['family'];
			$title_typography_size      = $title_typography['size'];
			$title_typography_height    = $title_typography['height'];
			$title_typography_alignment = $title_typography['alignment'];
			$title_typography_transform = $title_typography['transform'];
			$title_typography_spacing   = $title_typography['spacing'];
			$title_typography_color     = $title_typography['color'];

			$layout                        = $logo_data['lcp_layout'];
			$list_style                    = $logo_data['lcp_list_style'];
			$display_logos_from            = ( isset( $logo_data['lcp_display_logos_from'] ) ? $logo_data['lcp_display_logos_from'] : '' );
			$logo_category                 = ( isset( $logo_data['lcp_logos_from_category'] ) ? $logo_data['lcp_logos_from_category'] : '' );
			$logo_category_operator        = ( isset( $logo_data['lcp_category_operator'] ) ? $logo_data['lcp_category_operator'] : '' );
			$specific_logos                = ( isset( $logo_data['lcp_specific_logos'] ) ? $logo_data['lcp_specific_logos'] : '' );
			$logo_color                    = ( isset( $logo_data['lcp_logo_color'] ) ? $logo_data['lcp_logo_color'] : '' );
			$logo_opacity                  = ( isset( $logo_data['lcp_logo_opacity'] ) ? $logo_data['lcp_logo_opacity'] : '' );
			$filter_style                  = ( isset( $logo_data['lcp_filter_style'] ) ? $logo_data['lcp_filter_style'] : '' );
			$layout_mode                   = ( isset( $logo_data['lcp_layout_mode'] ) ? $logo_data['lcp_layout_mode'] : '' );
			$link_type                     = ( isset( $logo_data['lcp_link_type'] ) ? $logo_data['lcp_link_type'] : '' );
			$all_tab_text                  = ( isset( $logo_data['lcp_all_tab_text'] ) ? $logo_data['lcp_all_tab_text'] : 'All' );
			$filter_cat_border             = ( isset( $logo_data['lcp_filter_cat_border'] ) ? $logo_data['lcp_filter_cat_border'] : '' );
			$filter_cat_border_width       = ( isset( $filter_cat_border['width'] ) ? $filter_cat_border['width'] : '0' );
			$filter_cat_border_style       = ( isset( $filter_cat_border['style'] ) ? $filter_cat_border['style'] : 'none' );
			$filter_cat_border_color       = ( isset( $filter_cat_border['color'] ) ? $filter_cat_border['color'] : '' );
			$filter_cat_border_hover_color = ( isset( $filter_cat_border['hover_color'] ) ? $filter_cat_border['hover_color'] : '' );

			$description_type           = ( isset( $logo_data['lcp_description_type'] ) ? $logo_data['lcp_description_type'] : '' );
			$description_words_limit    = ( isset( $logo_data['lcp_description_words_limit'] ) ? $logo_data['lcp_description_words_limit'] : '' );
			$description_read_more_text = ( isset( $logo_data['lcp_description_read_more_text'] ) ? $logo_data['lcp_description_read_more_text'] : '' );
			$read_more_color            = ( isset( $logo_data['lcp_read_more_color'] ) ? $logo_data['lcp_read_more_color'] : '' );
			$description_read_more      = ( isset( $logo_data['lcp_description_read_more'] ) && true == $logo_data['lcp_description_read_more'] ? 'true' : 'false' );
			$outer_border               = ( isset( $logo_data['lcp_logo_outer_border'] ) && true == $logo_data['lcp_logo_outer_border'] ? 'true' : 'false' );
			$show_image_title_attr      = ( isset( $logo_data['lcp_image_title_attr'] ) && true == $logo_data['lcp_image_title_attr'] ? 'true' : 'false' );

			$section_title_font_load          = ( isset( $logo_data['lcp_section_title_font_load'] ) && true !== $logo_data['lcp_section_title_font_load'] ? 'false' : 'true' );
			$filter_font_load                 = ( isset( $logo_data['lcp_filter_font_load'] ) && true !== $logo_data['lcp_filter_font_load'] ? 'false' : 'true' );
			$logo_title_font_load             = ( isset( $logo_data['lcp_logo_title_font_load'] ) && true !== $logo_data['lcp_logo_title_font_load'] ? 'false' : 'true' );
			$logo_description_font_load       = ( isset( $logo_data['lcp_logo_description_font_load'] ) && true !== $logo_data['lcp_logo_description_font_load'] ? 'false' : 'true' );
			$read_more_font_load              = ( isset( $logo_data['lcp_read_more_font_load'] ) && true !== $logo_data['lcp_read_more_font_load'] ? 'false' : 'true' );
			$logo_popup_title_font_load       = ( isset( $logo_data['lcp_logo_popup_title_font_load'] ) && true !== $logo_data['lcp_logo_popup_title_font_load'] ? 'false' : 'true' );
			$logo_popup_description_font_load = ( isset( $logo_data['lcp_logo_popup_description_font_load'] ) && true !== $logo_data['lcp_logo_popup_description_font_load'] ? 'false' : 'true' );
			$preloader                        = ( isset( $logo_data['lcp_preloader'] ) && true == $logo_data['lcp_preloader'] ? 'true' : 'false' );
			$all_tab                          = ( isset( $logo_data['lcp_all_tab'] ) && true !== $logo_data['lcp_all_tab'] ? 'false' : 'true' );
			if ( 'false' == $all_tab ) {
				if ( $logo_category ) {
					$active_class = get_term( $logo_category[0] )->slug;
				} else {
					$active_class = get_terms( 'sp_logo_carousel_cat' )[0]->slug;
				}
				$active_fitler = '.sp_logo_carousel_cat-' . $active_class;
			} else {
				$active_fitler = '*';
			}

			$columns             = $logo_data['lcp_number_of_columns'];
			$items               = $columns['column1'];
			$items_desktop       = $columns['column2'];
			$items_desktop_small = $columns['column3'];
			$items_tablet        = $columns['column4'];
			$items_mobile        = $columns['column5'];
			$total_items         = $logo_data['lcp_number_of_total_items'];

			$columns_ticker = $logo_data['lcp_number_of_columns_ticker'];
			$maximum        = $columns_ticker['column1'];
			$minimum        = $columns_ticker['column2'];

			$link          = ( ( $logo_data['lcp_logo_link_show'] ) == true ? 'true' : 'false' );
			$target        = $logo_data['lcp_link_open_target'];
			$inner_padding = $logo_data['lcp_logo_inner_padding'];
			$carousel_mode = $logo_data['lcp_logo_carousel_mode'];

			$center_mode         = ( ( $logo_data['lcp_logo_carousel_mode'] ) == 'center' ? 'true' : 'false' );
			$center_mode_padding = $logo_data['lcp_logo_carousel_center_padding'];

			$vertical       = ( ( $logo_data['lcp_vertical_horizontal'] ) == 'vertical' ? 'true' : 'false' );
			$vertical_class = ( ( $logo_data['lcp_vertical_horizontal'] ) == 'vertical' ? 'lcp_vertical' : 'lcp_horizontal' );

			$auto_play       = ( ( $logo_data['lcp_carousel_auto_play'] ) == true ? 'true' : 'false' );
			$auto_play_speed = $logo_data['lcp_carousel_auto_play_speed'];
			$speed           = $logo_data['lcp_carousel_scroll_speed'];
			$ticker_speed    = $logo_data['lcp_carousel_scroll_speed_ticker'];

			$pause_on_hover = ( ( $logo_data['lcp_carousel_pause_on_hover'] ) == true ? 'true' : 'false' );
			// Navigation.
			$nav_data = ( isset( $logo_data['lcp_nav_show'] ) ? $logo_data['lcp_nav_show'] : '' );
			if ( 'show' == $nav_data ) {
				$nav        = 'true';
				$nav_mobile = 'true';
			} elseif ( 'hide_on_mobile' == $nav_data ) {
				$nav        = 'true';
				$nav_mobile = 'false';
			} else {
				$nav        = 'false';
				$nav_mobile = 'false';
			}

			$nav_position           = $logo_data['lcp_nav_position'];
			$nav_type               = $logo_data['lcp_nav_type'];
			$nav_border_radius      = $logo_data['lcp_nav_border_radius'];
			$nav_color_data         = $logo_data['lcp_nav_color'];
			$nav_color              = $nav_color_data['color1'];
			$nav_hover_color        = $nav_color_data['color2'];
			$nav_bg                 = $nav_color_data['color3'];
			$nav_hover_bg           = $nav_color_data['color4'];
			$nav_border_color       = $nav_color_data['color5'];
			$nav_border_hover_color = $nav_color_data['color6'];

			$pagination_color_data  = $logo_data['lcp_pagination_color'];
			$pagination_color       = $pagination_color_data['color1'];
			$pagination_hv_color    = $pagination_color_data['color2'];
			$pagination_bg_color    = $pagination_color_data['color3'];
			$pagination_bg_hv_color = $pagination_color_data['color4'];

			$pagination_margin = $logo_data['lcp_pagination_margin'];

			$dots_data = ( isset( $logo_data['lcp_carousel_dots'] ) ? $logo_data['lcp_carousel_dots'] : '' );
			if ( 'show' == $dots_data ) {
				$dots        = 'true';
				$dots_mobile = 'true';
			} elseif ( 'hide_on_mobile' == $dots_data ) {
				$dots        = 'true';
				$dots_mobile = 'false';
			} else {
				$dots        = 'false';
				$dots_mobile = 'false';
			}
			$dots_color_data   = $logo_data['lcp_carousel_dots_color'];
			$dots_color        = $dots_color_data['color1'];
			$dots_active_color = $dots_color_data['color2'];

			$draggable       = ( ( $logo_data['lcp_carousel_draggable'] ) == true ? 'true' : 'false' );
			$swipe           = ( ( $logo_data['lcp_carousel_swipe'] ) == true ? 'true' : 'false' );
			$infinite        = ( ( $logo_data['lcp_carousel_infinite'] ) == true ? 'true' : 'false' );
			$adaptive_height = ( ( $logo_data['lcp_carousel_adaptive_height'] ) == true ? 'true' : 'false' );

			$order_by = $logo_data['lcp_item_order_by'];
			$order    = $logo_data['lcp_item_order'];

			$filter_cat_color_data     = $logo_data['lcp_filter_cat_color'];
			$filter_cat_color          = $filter_cat_color_data['color1'];
			$filter_cat_hover_color    = $filter_cat_color_data['color2'];
			$filter_cat_bg_color       = $filter_cat_color_data['color3'];
			$filter_cat_bg_hover_color = $filter_cat_color_data['color4'];

			$title          = $logo_data['lcp_logo_title'];
			$title_position = $logo_data['lcp_logo_title_position'];
			$title_hover_bg = $logo_data['lcp_logo_title_hover_bg'];

			/**
			 * Logo Title Typography
			 */
			$logo_title_typo      = $logo_data['lcp_logo_title_typography'];
			$logo_title_family    = $logo_title_typo['family'];
			$logo_title_size      = $logo_title_typo['size'];
			$logo_title_height    = $logo_title_typo['height'];
			$logo_title_alignment = $logo_title_typo['alignment'];
			$logo_title_transform = $logo_title_typo['transform'];
			$logo_title_spacing   = $logo_title_typo['spacing'];
			$logo_title_color     = $logo_title_typo['color'];

			/**
			 * Logo description typography
			 */
			$description          = $logo_data['lcp_logo_description'];
			$description_position = $logo_data['lcp_logo_description_position'];

			$description_typography = $logo_data['lcp_logo_description_typography'];

			$logo_desc_family    = $description_typography['family'];
			$logo_desc_size      = $description_typography['size'];
			$logo_desc_height    = $description_typography['height'];
			$logo_desc_alignment = $description_typography['alignment'];
			$logo_desc_transform = $description_typography['transform'];
			$logo_desc_spacing   = $description_typography['spacing'];
			$logo_desc_color     = $description_typography['color'];

			$logo_zoom_effect = $logo_data['lcp_logo_zoom_effect_types'];
			$logo_blur_effect = ( ( $logo_data['lcp_logo_blur_effect'] ) == true ? 'true' : 'false' );

			$shadow_type        = $logo_data['lcp_logo_shadow_type'];
			$shadow_color_data  = $logo_data['lcp_logo_shadow_color'];
			$shadow_color       = $shadow_color_data['color1'];
			$shadow_hover_color = $shadow_color_data['color2'];

			// Border.
			$border_data        = $logo_data['lcp_logo_border'];
			$border_style       = $border_data['style'];
			$border_width       = $border_data['width'];
			$border_color       = $border_data['color'];
			$border_hover_color = $border_data['hover_color'];
			$border_radius      = $logo_data['lcp_border_radius'];

			$gray_scale        = $logo_data['lcp_logo_gray_scale'];
			$gray_scale_on_mnt = ( ( $logo_data['lcp_mobile_tablet_gray_off'] ) == true ? 'true' : 'false' );

			// Tooltip.
			$tooltip            = ( ( $logo_data['lcp_logo_tooltip'] ) == true ? 'true' : 'false' );
			$tooltip_position   = $logo_data['lcp_logo_tooltip_position'];
			$tooltip_width      = $logo_data['lcp_logo_tooltip_width'];
			$tooltip_effect     = $logo_data['lcp_logo_tooltip_effect'];
			$tooltip_color_data = $logo_data['lcp_logo_tooltip_color'];
			$tooltip_color      = $tooltip_color_data['color1'];
			$tooltip_bg         = $tooltip_color_data['color2'];

			$width = $logo_data['lcp_image_width'];

			$img_height = $logo_data['lcp_image_height'];
			$height     = ( $img_height > '0' ? $img_height . 'px' : '' );

			$crop = $logo_data['lcp_image_crop'];

			$logo_margin         = $logo_data['lcp_logo_margin'];
			$logo_wrapper_margin = ( $logo_margin / 2 );

			$rtl_mode = ( isset( $logo_data['lcp_rtl_mode'] ) ? $logo_data['lcp_rtl_mode'] : 'false' );
			$the_rtl  = ( 'true' === $rtl_mode ) ? ' dir="rtl"' : ' dir="ltr"';

			/*Horizontal Arrow*/
			$nav_arrow_type_horizon = $logo_data['lcp_nav_arrow_type_horizontal'];

			$lazy = ( ( $logo_data['lcp_logo_lazy_load'] ) == true ? 'true' : 'false' );

			// Center Mode.
			$side_opacity = $logo_data['lcp_logo_carousel_side_opacity'];

			// BxSlider.
			$carousel_layout    = $logo_data['lcp_vertical_horizontal'];
			$max_width_of_slide = $logo_data['lcp_max_width_of_slide'];

			$old_slides_to_scroll = ( isset( $logo_data['lcp_number_of_slides_to_scroll'] ) ? $logo_data['lcp_number_of_slides_to_scroll'] : '1' );
			// Since 3.2.9.
			$old_slides_to_scroll_mobile = ( isset( $logo_data['lcp_number_of_slides_to_scroll_mobile'] ) ? $logo_data['lcp_number_of_slides_to_scroll_mobile'] : '1' );

			$lcp_scrolls                    = ( isset( $logo_data['lcp_slides_to_scroll'] ) ? $logo_data['lcp_slides_to_scroll'] : '' );
			$slides_to_scroll               = ( isset( $lcp_scrolls['column1'] ) ? $lcp_scrolls['column1'] : $old_slides_to_scroll );
			$slides_to_scroll_desktop       = ( isset( $lcp_scrolls['column2'] ) ? $lcp_scrolls['column2'] : $old_slides_to_scroll );
			$slides_to_scroll_desktop_small = ( isset( $lcp_scrolls['column3'] ) ? $lcp_scrolls['column3'] : $old_slides_to_scroll );
			$slides_to_scroll_tablet        = ( isset( $lcp_scrolls['column4'] ) ? $lcp_scrolls['column4'] : $old_slides_to_scroll );
			$slides_to_scroll_mobile        = ( isset( $lcp_scrolls['column5'] ) ? $lcp_scrolls['column5'] : $old_slides_to_scroll_mobile );

			if ( 'standard' == $carousel_mode && 'horizontal' == $carousel_layout ) {
				// Support old version row.
				$old_lcp_row = ( isset( $logo_data['lcp_row'] ) ? $logo_data['lcp_row'] : '1' );

				$lcp_rows              = ( isset( $logo_data['lcp_rows'] ) ? $logo_data['lcp_rows'] : '' );
				$lcp_row               = ( isset( $lcp_rows['column1'] ) ? $lcp_rows['column1'] : $old_lcp_row );
				$lcp_row_desktop       = ( isset( $lcp_rows['column2'] ) ? $lcp_rows['column2'] : $old_lcp_row );
				$lcp_row_desktop_small = ( isset( $lcp_rows['column3'] ) ? $lcp_rows['column3'] : $old_lcp_row );
				$lcp_row_tablet        = ( isset( $lcp_rows['column4'] ) ? $lcp_rows['column4'] : $old_lcp_row );
				$lcp_row_mobile        = ( isset( $lcp_rows['column5'] ) ? $lcp_rows['column5'] : $old_lcp_row );
			} else {
				$lcp_row               = '1';
				$lcp_row_desktop       = '1';
				$lcp_row_desktop_small = '1';
				$lcp_row_tablet        = '1';
				$lcp_row_mobile        = '1';
			}

			$inline_grid_va = $logo_data['lcp_grid_inline_vertical_alignment'];

			$custom_id = uniqid();

			$read_more_typo   = $logo_data['lcp_read_more_typography'];
			$popup_title_typo = $logo_data['lcp_logo_popup_title_typography'];
			$popup_desc_typo  = $logo_data['lcp_logo_popup_description_typography'];
			$filter_typo      = $logo_data['lcp_filter_typography'];

			if ( true == sp_lcpro_get_option( 'lcpro_google_fonts', true ) ) {
				/**
				 * Google font link enqueue
				 */
				$enqueue_fonts    = array();
				$lcp_typography   = array();
				$lcp_typography[] = $logo_data['lcp_section_title_typography'];
				$lcp_typography[] = $logo_data['lcp_logo_title_typography'];
				$lcp_typography[] = $logo_data['lcp_logo_description_typography'];
				$lcp_typography[] = $logo_data['lcp_read_more_typography'];
				$lcp_typography[] = $logo_data['lcp_logo_popup_title_typography'];
				$lcp_typography[] = $logo_data['lcp_logo_popup_description_typography'];
				$lcp_typography[] = $logo_data['lcp_filter_typography'];
				if ( ! empty( $lcp_typography ) ) {
					foreach ( $lcp_typography as $font ) {
						if ( isset( $font['font'] ) && 'google' == $font['font'] ) {
							$variant         = ( isset( $font['variant'] ) && 'regular' !== $font['variant'] ) ? ':' . $font['variant'] : '';
							$enqueue_fonts[] = $font['family'] . $variant;
						}
					}
				}
				if ( ! empty( $enqueue_fonts ) ) {
					wp_enqueue_style( 'sp-lc-google-fonts' . $custom_id, esc_url( add_query_arg( 'family', urlencode( implode( '|', $enqueue_fonts ) ), '//fonts.googleapis.com/css' ) ), array(), '1.0', false );
				}
			}

			if ( 'specific_logos' == $display_logos_from && ! empty( $specific_logos ) ) {
				$specific_logo_ids = $specific_logos;
			} else {
				$specific_logo_ids = null;
			}

			// Query for the normal logos.
			$arguments = array(
				'post_type'      => 'sp_logo_carousel',
				'orderby'        => $order_by,
				'order'          => $order,
				'posts_per_page' => $total_items,
				'post__in'       => $specific_logo_ids,
			);
			if ( 'category' == $display_logos_from ) {
				$arguments['tax_query'][] = array(
					'taxonomy' => 'sp_logo_carousel_cat',
					'field'    => 'term_id',
					'terms'    => $logo_category,
					'operator' => $logo_category_operator,
				);
			}

			$que = new WP_Query( $arguments );

			// Query for the logos with pagination.
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

			$args = array(
				'post_type'      => 'sp_logo_carousel',
				'orderby'        => $order_by,
				'order'          => $order,
				'posts_per_page' => $total_items,
				'paged'          => $paged,
				'post__in'       => $specific_logo_ids,
			);
			if ( 'category' == $display_logos_from ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_logo_carousel_cat',
					'field'    => 'term_id',
					'terms'    => $logo_category,
					'operator' => $logo_category_operator,
				);
			}

			$page_que = new WP_Query( $args );

			// Enqueue Scripts.
			if ( 'carousel' == $layout ) {
				if ( 'ticker' == $carousel_mode ) {
					$auto_direction = 'false' === $rtl_mode ? 'next' : 'prev';
					$lcp_bx_option  = 'data-minslide="' . $minimum . '" data-maxslide="' . $maximum . '" data-slidewidth="' . $max_width_of_slide . '" data-slidemargin="' . $logo_margin . '" data-speed="' . $ticker_speed . '" data-mode="' . $carousel_layout . '" data-direction="' . $auto_direction . '" data-tickerhover="' . $pause_on_hover . '"';
					if ( true == sp_lcpro_get_option( 'lcpro_bxslider_js', true ) ) {
						wp_enqueue_script( 'lcp-bx-slider-min-js' );
					}
					wp_enqueue_script( 'lcp-bx-slider-config' );
					if ( true == sp_lcpro_get_option( 'lcpro_bxslider_css', true ) ) {
						wp_enqueue_style( 'lcpro-bxslider' );
					}
				} else {
					if ( true == sp_lcpro_get_option( 'lcpro_slick_js', true ) ) {
						wp_enqueue_script( 'lcp-slick-min-js' );
					}
					if ( true == sp_lcpro_get_option( 'lcpro_slick_css', true ) ) {
						wp_enqueue_style( 'lcpro-slick' );
					}
					wp_enqueue_script( 'lcp-slick-config' );
					$lcp_slick_options  = 'data-slick=\'{ "centerMode":' . $center_mode . ', "centerPadding": "' . $center_mode_padding . 'px", "adaptiveHeight":' . $adaptive_height . ', "arrows":' . $nav . ', "autoplay":' . $auto_play . ', "autoplaySpeed":' . $auto_play_speed . ', "dots":' . $dots . ', "infinite":' . $infinite . ', "speed":' . $speed . ', "pauseOnHover":' . $pause_on_hover . ', "slidesToScroll":' . $slides_to_scroll . ', "slidesToShow":' . $items . ', "rows":' . $lcp_row . ', "rtl":' . $rtl_mode . ', "vertical":' . $vertical . ', "swipe": ' . $swipe . ', "draggable": ' . $draggable . ', "responsive":[ { "breakpoint":1200, "settings": { "slidesToShow":' . $items_desktop . ', "rows":' . $lcp_row_desktop . ', "slidesToScroll":' . $slides_to_scroll_desktop . ' } }, { "breakpoint":980, "settings":{ "slidesToShow":' . $items_desktop_small . ', "rows":' . $lcp_row_desktop_small . ', "slidesToScroll":' . $slides_to_scroll_desktop_small . ' } }, { "breakpoint":736, "settings": { "slidesToShow":' . $items_tablet . ', "rows":' . $lcp_row_tablet . ', "slidesToScroll":' . $slides_to_scroll_tablet . ' } }, {"breakpoint":480, "settings":{ "slidesToShow":' . $items_mobile . ', "arrows": ' . $nav_mobile . ', "dots": ' . $dots_mobile . ', "rows":' . $lcp_row_mobile . ', "slidesToScroll":' . $slides_to_scroll_mobile . ' } } ] }\' data-nav_type=' . $nav_type . ' data-arrowtype="' . $nav_arrow_type_horizon . '" data-vertical=' . $vertical . '';
					$lcp_slick_options .= $the_rtl;

				}
			} elseif ( 'filter' == $layout ) {
				if ( true == sp_lcpro_get_option( 'lcpro_isotope_js', true ) ) {
					wp_enqueue_script( 'lcp-jquery-isotope-min-js' );
				}
				$lcp_filter_option = 'data-filter="' . $active_fitler . '" data-layout_mode="' . $layout_mode . '" ';
				if ( 'normal' == $filter_style ) {
					wp_enqueue_script( 'lcp-filter-config' );
				} elseif ( 'opacity' == $filter_style ) {
					wp_enqueue_script( 'lcp-filter-opacity-config' );
				}
			}

			if ( 'true' == $link && 'popup' == $link_type && true == sp_lcpro_get_option( 'lcpro_remodal_js', true ) ) {
				wp_enqueue_script( 'lcp-remodal-js' );
			}

			if ( 'true' == $tooltip ) {
				$lcp_tooltip_option = 'data-animation="' . $tooltip_effect . '" data-side="' . $tooltip_position . '" data-maxwidth="' . $tooltip_width . '"';
				if ( true == sp_lcpro_get_option( 'lcpro_tooltipster_js', true ) ) {
					wp_enqueue_script( 'lcp-tooltipster-min-js' );
				}
				wp_enqueue_script( 'lcp-tooltip-config' );
				if ( true == sp_lcpro_get_option( 'lcpro_tooltipster_css', true ) ) {
					wp_enqueue_style( 'lcpro-tooltipster' );
				}
			}

			wp_enqueue_style( 'lcpro-font-awesome' );
			wp_enqueue_style( 'lcpro-custom' );
			wp_enqueue_style( 'lcpro-style' );

			$outline  = '';
			$outline .= '<style>
			div.sp-logo-carousel-pro-section div#sp-logo-carousel-pro' . $custom_id . ' .sp-lcp-item img{
				opacity: ' . $logo_opacity . ' !important;
			}	
			div.sp-logo-carousel-pro-section div#sp-logo-carousel-pro' . $custom_id . ' .sp-lcp-item:hover img{
				opacity: 1 !important;
			}	
			div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .sp-lcp-item-border,
			div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item.sp-lcp-item-border{
				padding: ' . $inner_padding . 'px;
				background: ' . $logo_color['color1'] . ';
			}
			div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item:hover .sp-lcp-item-border,
			div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item:hover.sp-lcp-item-border{
				background: ' . $logo_color['color2'] . ';
			}
			div.sp-logo-carousel-pro-section.layout-carousel div#sp-logo-carousel-pro' . $custom_id . ' .slick-slide {
				float: none !important;
				display: inline-block;
				vertical-align: ' . $inline_grid_va . ';
			}
			div.sp-logo-carousel-pro-section div#sp-logo-carousel-pro' . $custom_id . ' [class*="lcp-col"]{
				vertical-align: ' . $inline_grid_va . ';
				margin: 0;
				box-shadow: none;
			}';

			/**
			 * RTL support for Ticker mode.
			 *
			 * @since 3.2.8
			 */
			if ( 'carousel' == $layout && 'ticker' == $carousel_mode && 'true' == $rtl_mode ) {
				$outline .= '
				div.sp-logo-section-id-' . $custom_id . ' .bx-viewport{
				direction: ltr;
				}';
			}
			if ( 'popup' == $link_type ) {
				$outline .= '
				.sp-lcpro-modal-logo-' . $post_id . ' .sp-lcpro-modal-logo-content .lcpro-logo-title{
					margin: 20px 0 15px 0;
					color: ' . $popup_title_typo['color'] . ';
					font-size: ' . $popup_title_typo['size'] . 'px;';
				if ( 'true' == $logo_popup_title_font_load ) {
					$outline .= 'font-family: ' . $popup_title_typo['family'] . ';
						' . $this->lcpro_the_font_variants( $popup_title_typo['variant'] ) . '';
				}
					$outline .= 'line-height:' . $popup_title_typo['height'] . 'px;
					letter-spacing: ' . $popup_title_typo['spacing'] . ';
					text-transform: ' . $popup_title_typo['transform'] . ';
					text-align: ' . $popup_title_typo['alignment'] . ';
				}
				.sp-lcpro-modal-logo-' . $post_id . ' .sp-lcpro-modal-logo-content .lcpro-description{
					color: ' . $popup_desc_typo['color'] . ';
					font-size: ' . $popup_desc_typo['size'] . 'px;';
				if ( 'true' == $logo_popup_description_font_load ) {
					$outline .= 'font-family: ' . $popup_desc_typo['family'] . ';
						' . $this->lcpro_the_font_variants( $popup_desc_typo['variant'] ) . '';
				}
					$outline .= 'line-height:' . $popup_desc_typo['height'] . 'px;
					letter-spacing: ' . $popup_desc_typo['spacing'] . ';
					text-transform: ' . $popup_desc_typo['transform'] . ';
					text-align: ' . $popup_desc_typo['alignment'] . ';
				}
				';
			}

			if ( 'true' == $description_read_more ) {
				$outline .= '.sp-logo-carousel-pro-section #sp-logo-carousel-pro' . $custom_id . ' .sp-lcpro-readmore-area{
					text-align: ' . $read_more_typo['alignment'] . ';
				}
				.sp-logo-carousel-pro-section #sp-logo-carousel-pro' . $custom_id . ' .sp-lcpro-readmore-area .sp-lcpro-readmore{
					background: ' . $read_more_color['color3'] . ';
					color: ' . $read_more_color['color1'] . ';
					padding: 5px 13px;
					margin-bottom: 18px;
					display: inline-block;
					border-radius: 2px;
					border: 2px solid ' . $read_more_color['color5'] . ' !important;
					font-size: ' . $read_more_typo['size'] . 'px;';
				if ( 'true' == $read_more_font_load ) {
					$outline .= 'font-family: ' . $read_more_typo['family'] . ';
						' . $this->lcpro_the_font_variants( $read_more_typo['variant'] ) . '';
				}
					$outline .= 'line-height:' . $read_more_typo['height'] . 'px;
					letter-spacing: ' . $read_more_typo['spacing'] . ';
					text-transform: ' . $read_more_typo['transform'] . ';
				}
				.sp-logo-carousel-pro-section #sp-logo-carousel-pro' . $custom_id . ' .sp-lcpro-readmore-area .sp-lcpro-readmore:hover{
					background: ' . $read_more_color['color4'] . ';
					color: ' . $read_more_color['color2'] . ';
					border: 2px solid ' . $read_more_color['color6'] . ' !important;
				}';
			}

			// Gray Scale.
			if ( file_exists( SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/internal-style/grayscale.php' ) ) {
				require SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/internal-style/grayscale.php';
			}
			if ( 'horizontal' == $carousel_layout ) {
				$outline .= 'div.sp-logo-section-id-' . $custom_id . ' .bx-viewport.bx-viewport { height: auto !important; }';
			}
			if ( 'center' == $carousel_mode ) {
				$outline .= '
				div.sp-logo-carousel-pro-section.layout-carousel div#sp-logo-carousel-pro' . $custom_id . ' .sp-lcp-item {
				opacity: ' . $side_opacity . ';
				-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=' . $side_opacity . ')";
				filter: alpha(opacity=' . $side_opacity . ');
				margin: 0;
				-webkit-transform: scale(0.8);
				-moz-transform: scale(0.8);
				-ms-transform: scale(0.8);
				-o-transform: scale(0.8);
				transform: scale(0.8);
				}
				div.sp-logo-carousel-pro-section.layout-carousel div#sp-logo-carousel-pro' . $custom_id . ' .slick-center .sp-lcp-item {
				-webkit-transform: scale(1.1);
				-moz-transform: scale(1.1);
				-ms-transform: scale(1.1);
				-o-transform: scale(1.1);
				transform: scale(1.1);
				opacity: 1;
				}
				.sp-logo-carousel-pro-section.lcp_horizontal div#sp-logo-carousel-pro' . $custom_id . ' .slick-track{
					padding-top: 8px;
					padding-bottom: 8px;
				}
				';
			}
			if ( 'true' == $logo_blur_effect ) {
				$outline .= '
				div.sp-logo-carousel-pro-section div#sp-logo-carousel-pro' . $custom_id . ' .sp-lcp-item img{  filter: blur(1px);
				-moz-transform:: blur(1px);
				-webkit-filter: blur(1px);
				}';
				if ( 'center' == $carousel_mode ) {
					$outline .= '
					div.sp-logo-carousel-pro-section.layout-carousel div#sp-logo-carousel-pro' . $custom_id . ' .sp-lcp-item.slick-center img,';
				}
				$outline .= 'div.sp-logo-carousel-pro-section div#sp-logo-carousel-pro' . $custom_id . ' .sp-lcp-item:hover img{
					filter: blur(0);
					-moz-transform:: blur(0);
					-webkit-filter: blur(0);
				}';
			}

			// Zoom Effect.
			if ( 'zoom_in' == $logo_zoom_effect ) {
				$outline .= '
				div.sp-logo-carousel-pro-section div#sp-logo-carousel-pro' . $custom_id . ' .sp-lcp-item:hover img{
					-webkit-transform: scale(1.2);
					-moz-transform: scale(1.2);
					transform: scale(1.2);
				}';
			}
			if ( 'zoom_out' == $logo_zoom_effect ) {
				$outline .= '
					div.sp-logo-carousel-pro-section div#sp-logo-carousel-pro' . $custom_id . ' .sp-lcp-item img{
						-webkit-transform: scale(1.2);
						-moz-transform: scale(1.2);
						transform: scale(1.2);
					}
					div.sp-logo-carousel-pro-section div#sp-logo-carousel-pro' . $custom_id . ' .sp-lcp-item:hover img{
						-webkit-transform: scale(1);
						-moz-transform: scale(1);
						transform: scale(1);
					}';
			}
			if ( 'true' == $title && 'middle' == $title_position ) {
				$outline .= '
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .middle-description{
					padding-top: 0;
				}';
			}
			if ( 'true' == $title ) {
				$outline .= '
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .hover-full-title,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .hover-bottom-title {
					background-color: ' . $title_hover_bg . ';
					color: ' . $logo_title_color . ';
					font-size: ' . $logo_title_size . 'px;';
				if ( 'true' == $logo_title_font_load ) {
					$outline .= 'font-family: ' . $logo_title_family . ';
						' . $this->lcpro_the_font_variants( $logo_title_typo['variant'] ) . '';
				}
					$outline .= 'line-height:' . $logo_title_height . 'px;
					letter-spacing: ' . $logo_title_spacing . ';
					text-transform: ' . $logo_title_transform . ';
					text-align: ' . $logo_title_alignment . ';
				}
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .top-title,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .middle-title,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .bottom-title,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .list-title{
					color: ' . $logo_title_color . ';
					font-size: ' . $logo_title_size . 'px;';
				if ( 'true' == $logo_title_font_load ) {
					$outline .= 'font-family: ' . $logo_title_family . ';
						' . $this->lcpro_the_font_variants( $logo_title_typo['variant'] ) . '';
				}
					$outline .= 'line-height:' . $logo_title_height . 'px;
					letter-spacing: ' . $logo_title_spacing . ';
					text-transform: ' . $logo_title_transform . ';
					text-align: ' . $logo_title_alignment . ';

				}';
			}
			if ( 'true' == $description ) {
				$outline .= '
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .bottom-description,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .middle-description{
					font-size:' . $logo_desc_size . 'px;';
				if ( 'true' == $logo_description_font_load ) {
					$outline .= 'font-family:' . $logo_desc_family . ';
						' . $this->lcpro_the_font_variants( $description_typography['variant'] ) . '';
				}
					$outline .= 'line-height:' . $logo_desc_height . 'px; 
					text-transform:' . $logo_desc_transform . '; 
					text-align:' . $logo_desc_alignment . '; 
					letter-spacing:' . $logo_desc_spacing . ';
					color:' . $logo_desc_color . ';
				}';
			}

			if ( 'none' !== $border_style && 'true' !== $outer_border && 'inline' == $layout ) {
				$outline .= '
				.layout-inline #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .sp-lcp-item-border,
				.layout-inline #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item.sp-lcp-item-border{
					overflow: inherit;
				}
				.layout-inline #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .sp-lcp-item-border:before,
				.layout-inline #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item.sp-lcp-item-border:before{
					content: "";
					position: absolute;
					height: 100%;
					width: 100%;
					top: 0;
					left: -' . $border_width . 'px;
					border-left: ' . $border_width . 'px ' . $border_style . ' ' . $border_color . ';
					z-index: 1;
				}
				.layout-inline #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .sp-lcp-item-border:after,
				.layout-inline #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item.sp-lcp-item-border:after{
					content: "";
					position: absolute;
					height: 100%;
					width: 100%;
					top: -' . $border_width . 'px;
					left: 0;
					border-top: ' . $border_width . 'px ' . $border_style . ' ' . $border_color . ';
					z-index: 1;
				}';
			} elseif ( 'none' !== $border_style ) {
				/* Border */
				$outline .= '
				.layout-inline #sp-logo-carousel-pro' . $custom_id . '.lcp-container{
					padding-right: ' . $border_width . 'px;
				}
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .sp-lcp-item-border,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item.sp-lcp-item-border{
					border: ' . $border_width . 'px ' . $border_style . ' ' . $border_color . ';
				}
				.layout-inline #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .sp-lcp-item-border,
				.layout-inline #sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item.sp-lcp-item-border{
					margin: 0 -' . $border_width . 'px -' . $border_width . 'px 0;
				}
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item:hover .sp-lcp-item-border,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item:hover.sp-lcp-item-border{
					border-color: ' . $border_hover_color . ';
				}';
			}
			if ( '' !== $border_radius && 'inline' !== $layout ) {
				/* Border Radius */
				$outline .= '
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .sp-lcp-item-border,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item.sp-lcp-item-border{
					border-radius: ' . $border_radius . ';
					z-index: 1;
					overflow: hidden;
				}';
			}
			if ( 'carousel' == $layout ) {
				if ( 'ticker' == $carousel_mode ) {
					$outline .= '
					div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item{
					margin: 0;
					float: none !important;
					display: inline-block;
					vertical-align: ' . $inline_grid_va . ';}';
				} else {
					if ( '' !== $logo_margin ) {
						/* Border Radius */
						if ( 'false' == $vertical ) {
							if ( file_exists( SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/internal-style/slick_horizontal.php' ) ) {
								require SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/internal-style/slick_horizontal.php';
							}
						} else {
							if ( file_exists( SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/internal-style/slick_vertical.php' ) ) {
								require SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/internal-style/slick_vertical.php';
							}
						}
					}
					if ( 'true' == $nav ) {
						/* Nav Border Radius */
						$outline .= '
						div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev,
						div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next {
							border-radius: ' . $nav_border_radius . ';
						}';

						if ( 'nav_text' == $nav_type ) {
							/* Round */
							$outline .= '
							div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev,
							div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next {
								width: 35px;
								font-size: 12px;
							}
						div.sp-logo-carousel-pro-section.nav_position_top_center div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev,
						div.sp-logo-carousel-pro-section.nav_position_bottom_center div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev{
						margin-left: -38px;
						}
						div.sp-logo-carousel-pro-section.nav_position_top_center div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next,
						div.sp-logo-carousel-pro-section.nav_position_bottom_center div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next{
							margin-right: -38px;
						}
						div.sp-logo-carousel-pro-section.nav_position_top_right div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev,
						div.sp-logo-carousel-pro-section.nav_position_bottom_right div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev {
							right: 48px;
						}
						div.sp-logo-carousel-pro-section.nav_position_top_left div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next,
						div.sp-logo-carousel-pro-section.nav_position_bottom_left div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next {
							left: 47px;
						}
						';}
						if ( 'vertical_center' == $nav_position ) {
							$outline .= '
						div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area {
							padding-left: 27px;
							padding-right: 27px;
						}';
						} elseif ( 'vertical_center_inner' == $nav_position || 'vertical_center_inner_hover' == $nav_position ) {
							$outline .= 'div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area{
					overflow: hidden;
				}';
						}
						$outline .= '
						div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev,
						div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next{
							background-color: ' . $nav_bg . ';
							border: 1px solid ' . $nav_border_color . ';
							color: ' . $nav_color . ';
						}
						div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-prev:hover,
						div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-next:hover{
							background-color: ' . $nav_hover_bg . ';
							border: 1px solid ' . $nav_border_hover_color . ';
							color: ' . $nav_hover_color . ';
						}
						div.sp-logo-section-id-' . $custom_id . '.nav_position_top_left,
						div.sp-logo-section-id-' . $custom_id . '.nav_position_top_right,
						div.sp-logo-section-id-' . $custom_id . '.nav_position_top_center {
							padding-top: 45px;
							overflow: hidden;
						}
						div.sp-logo-section-id-' . $custom_id . '.nav_position_bottom_right,
						div.sp-logo-section-id-' . $custom_id . '.nav_position_bottom_left,
						div.sp-logo-section-id-' . $custom_id . '.nav_position_bottom_center{
							padding-bottom: 45px;
							overflow: hidden;
						}';
					}
				}
			}

			// Shadow.
			if ( 'shadow_outset' == $shadow_type ) {
				$outline .= '
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .sp-lcp-item-border,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item.sp-lcp-item-border{
					-webkit-box-shadow: 0 0 10px 0 ' . $shadow_color . ';
					-moz-box-shadow:: 0 0 10px 0 ' . $shadow_color . ';
					box-shadow: 0 0 10px 0 ' . $shadow_color . ';
					margin-top: 6px;
					margin-bottom:6px;
				}
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item:hover .sp-lcp-item-border,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item:hover.sp-lcp-item-border{
					-webkit-box-shadow: 0 0 10px 0 ' . $shadow_hover_color . ';
					-moz-box-shadow:: 0 0 10px 0 ' . $shadow_hover_color . ';
					box-shadow: 0 0 10px 0 ' . $shadow_hover_color . ';
				}
				
				div.sp-logo-carousel-pro-section.layout-carousel.lcp_horizontal div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .slick-list{
					margin-left: 0;
					margin-right: 0;
				}
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area [class*="lcp-col-"]{
					margin-bottom: 6px;
				}';
			}
			if ( 'shadow_inset' == $shadow_type ) {
				$outline .= '
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item .sp-lcp-item-border,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item.sp-lcp-item-border{
				-webkit-box-shadow: inset 0px 0px 20px 3px ' . $shadow_color . ';
				-moz-box-shadow: inset 0px 0px 20px 3px ' . $shadow_color . ';
				box-shadow: inset 0px 0px 20px 3px ' . $shadow_color . ';
				}
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item:hover .sp-lcp-item-border,   
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item:hover.sp-lcp-item-border{    
				-webkit-box-shadow: inset 0px 0px 20px 3px ' . $shadow_hover_color . ';
				-moz-box-shadow: inset 0px 0px 20px 3px ' . $shadow_hover_color . ';
				box-shadow: inset 0px 0px 20px 3px ' . $shadow_hover_color . ';
				}
				';
			}

			if ( 'grid' || 'filter' == $layout ) {
				$outline .= 'div.sp-logo-carousel-pro-section div#sp-logo-carousel-pro' . $custom_id . ' [class*="lcp-col"]{
					margin: 0;
					box-shadow: none;
				}
				div.sp-logo-carousel-pro-section.layout-grid div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area [class*="lcp-col"],
				div.sp-logo-carousel-pro-section.layout-filter div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area [class*="lcp-col"]{
					padding-left: ' . $logo_wrapper_margin . 'px;
					padding-right: ' . $logo_wrapper_margin . 'px;
					padding-bottom: ' . $logo_margin . 'px;
				}
				div.sp-logo-carousel-pro-section.layout-grid div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area.lcp-container,
				div.sp-logo-carousel-pro-section.layout-filter div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area.lcp-container{
					margin-left: -' . $logo_wrapper_margin . 'px;
					margin-right: -' . $logo_wrapper_margin . 'px;
					margin-bottom: -' . $logo_margin . 'px;
				}
				';
			}
			if ( 'grid' == $layout ) {
				$outline .= '
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area{overflow: hidden;}
				';
			}
			if ( 'filter' == $layout ) {
				$outline .= '
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-logo-filter li button,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-logo-filter li button{
					background-color: ' . $filter_cat_bg_color . ';
					color: ' . $filter_cat_color . ';
					border: ' . $filter_cat_border_width . 'px ' . $filter_cat_border_style . ' ' . $filter_cat_border_color . ';
					font-size: ' . $filter_typo['size'] . 'px;';
				if ( 'true' == $filter_font_load ) {
					$outline .= 'font-family: ' . $filter_typo['family'] . ';
						' . $this->lcpro_the_font_variants( $filter_typo['variant'] ) . '';
				}
					$outline .= 'line-height:' . $filter_typo['height'] . 'px;
					letter-spacing: ' . $filter_typo['spacing'] . ';
					text-transform: ' . $filter_typo['transform'] . ';
					text-align: ' . $filter_typo['alignment'] . ';
				}
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-logo-filter li button:hover,
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-logo-filter li button.active{
					background-color: ' . $filter_cat_bg_hover_color . ';
					color: ' . $filter_cat_hover_color . ';
					border-color: ' . $filter_cat_border_hover_color . ';
				}
				';
			}
			if ( 'list' == $layout ) {
				$outline .= '
				.layout-list div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .sp-lcp-item{
					margin-bottom: ' . $logo_margin . 'px;
				}
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .list-title{
					color: ' . $logo_title_color . ';
					font-size: ' . $logo_title_size . 'px;';
				if ( 'true' == $logo_title_font_load ) {
					$outline .= 'font-family: ' . $logo_title_family . ';
						' . $this->lcpro_the_font_variants( $logo_title_typo['variant'] ) . '';
				}
					$outline .= 'line-height:' . $logo_title_height . 'px;
					letter-spacing: ' . $logo_title_spacing . ';
					text-transform: ' . $logo_title_transform . ';
					text-align: ' . $logo_title_alignment . ';
				}
				div#sp-logo-carousel-pro' . $custom_id . '.sp-logo-carousel-pro-area .list-description{
					font-size:' . $logo_desc_size . 'px;';
				if ( 'true' == $logo_description_font_load ) {
					$outline .= 'font-family:' . $logo_desc_family . ';
						' . $this->lcpro_the_font_variants( $description_typography['variant'] ) . '';
				}
					$outline .= 'line-height:' . $logo_desc_height . 'px;
					text-transform:' . $logo_desc_transform . ';
					text-align:' . $logo_desc_alignment . ';
					letter-spacing:' . $logo_desc_spacing . ';
					color:' . $logo_desc_color . ';
				}
				';
				if ( 'left' || 'right' == $list_style ) {
					$outline .= '
					div.sp-logo-carousel-pro-section.layout-list div#sp-logo-carousel-pro' . $custom_id . '.list-container    .sp-lcp-item {
					display: flex;
					align-items: center;
				}';
				}
				if ( 'right' == $list_style ) {
					$outline .= '
						div.sp-logo-carousel-pro-section.layout-list div#sp-logo-carousel-pro' . $custom_id . '.list-container .lcp-pagination {
							display: block;
							text-align: right;
						}
					}';
				}
				if ( 'center' == $list_style ) {
					$outline .= '
					div.sp-logo-carousel-pro-section.layout-list div#sp-logo-carousel-pro' . $custom_id . '.list-container    .sp-lcp-item {
					display: block;
				}
				div.sp-logo-carousel-pro-section.layout-list div#sp-logo-carousel-pro' . $custom_id . '.list-container .lcp-pagination {
					display: block;
					text-align: center;
				}';
				}
			}

			if ( 'carousel' !== $layout ) {
				$outline .= '
				div#sp-logo-carousel-pro' . $custom_id . ' .lcp-pagination{
					margin: ' . $pagination_margin['top'] . 'px 0 ' . $pagination_margin['bottom'] . 'px 0;
				}
				div#sp-logo-carousel-pro' . $custom_id . ' .lcp-pagination li span,
				div#sp-logo-carousel-pro' . $custom_id . ' .lcp-pagination li a{
				background-color: ' . $pagination_bg_color . '; 
				color: ' . $pagination_color . ';
				}
				div#sp-logo-carousel-pro' . $custom_id . ' .lcp-pagination li span.current,
				div#sp-logo-carousel-pro' . $custom_id . ' .lcp-pagination li a:hover{
				background-color: ' . $pagination_bg_hv_color . ';
				color: ' . $pagination_hv_color . ';
				}';
			}

			// Tooltip.
			if ( 'true' == $tooltip ) {
				if ( file_exists( SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/internal-style/tooltip_style.php' ) ) {
					require SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/internal-style/tooltip_style.php';
				}
			}

			// Dots.
			if ( 'ticker' !== $carousel_mode ) {
				if ( 'true' == $dots ) {
					if ( file_exists( SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/internal-style/dots_style.php' ) ) {
						require SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/internal-style/dots_style.php';
					}
				}
			}

			if ( 'true' == $preloader ) {
				$outline .= '
				.sp-logo-carousel-pro-section.sp-lcpro-id-' . $post_id . '{
					position: relative;
				}
				#lcp-preloader-' . $post_id . '{
					position: absolute;
					left: 0;
					top: 0;
					height: 100%;
					width: 100%;
					text-align: center;
					display: flex;
					align-items: center;
					justify-content: center;
					background: #fff;
					z-index: 9999;
				}
				';
			}

			$outline .= '</style>';

			if ( 'true' == $preloader ) {
				wp_enqueue_script( 'lcp-preloader-js' );
				$preloader_class = ' lcp-preloader';
			} else {
				$preloader_class = '';
			}

			if ( 'carousel' == $layout ) {
				if ( file_exists( SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/layout/carousel.php' ) ) {
					require SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/layout/carousel.php';
				}
			} elseif ( 'filter' == $layout ) {
				if ( file_exists( SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/layout/filter.php' ) ) {
					require SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/layout/filter.php';
				}
			} elseif ( 'grid' == $layout ) {
				if ( file_exists( SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/layout/grid.php' ) ) {
					require SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/layout/grid.php';
				}
			} elseif ( 'inline' == $layout ) {
				if ( file_exists( SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/layout/inline.php' ) ) {
					require SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/layout/inline.php';
				}
			} elseif ( 'list' == $layout ) {
				if ( file_exists( SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/layout/list.php' ) ) {
					require SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/layout/list.php';
				}
			}

			return $outline;

		}

		/**
		 * The font variants for the Advanced Typography.
		 *
		 * @param string $sp_lcpro_font_variant The typography field ID with.
		 * @return string
		 * @since 3.0.0
		 */
		private function lcpro_the_font_variants( $sp_lcpro_font_variant ) {
			$lcpro_font_style  = 'normal';
			$lcpro_font_weight = '400';
			switch ( $sp_lcpro_font_variant ) {
				case '100':
					$lcpro_font_weight = '100';
					break;
				case '100italic':
					$lcpro_font_weight = '100';
					$lcpro_font_style  = 'italic';
					break;
				case '200':
					$lcpro_font_weight = '200';
					break;
				case '200italic':
					$lcpro_font_weight = '200';
					$lcpro_font_style  = 'italic';
					break;
				case '300':
					$lcpro_font_weight = '300';
					break;
				case '300italic':
					$lcpro_font_weight = '300';
					$lcpro_font_style  = 'italic';
					break;
				case '500':
					$lcpro_font_weight = '500';
					break;
				case '500italic':
					$lcpro_font_weight = '500';
					$lcpro_font_style  = 'italic';
					break;
				case '600':
					$lcpro_font_weight = '600';
					break;
				case '600italic':
					$lcpro_font_weight = '600';
					$lcpro_font_style  = 'italic';
					break;
				case '700':
					$lcpro_font_weight = '700';
					break;
				case '700italic':
					$lcpro_font_weight = '700';
					$lcpro_font_style  = 'italic';
					break;
				case '800':
					$lcpro_font_weight = '800';
					break;
				case '800italic':
					$lcpro_font_weight = '800';
					$lcpro_font_style  = 'italic';
					break;
				case '900':
					$lcpro_font_weight = '900';
					break;
				case '900italic':
					$lcpro_font_weight = '900';
					$lcpro_font_style  = 'italic';
					break;
				case 'italic':
					$lcpro_font_style = 'italic';
					break;
			}
			return 'font-style: ' . $lcpro_font_style . '; font-weight: ' . $lcpro_font_weight . ';';
		}

	}

	new LCPRO_Shortcode_Render();
}
