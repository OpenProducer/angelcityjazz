<?php

	$music_recording_studio_custom_css= "";

	/*----------------------First highlight color-------------------*/

	$music_recording_studio_first_color = get_theme_mod('music_recording_studio_first_color');

	if($music_recording_studio_first_color != false){
		$music_recording_studio_custom_css .='.more-btn a:hover,input[type="submit"]:hover,#comments input[type="submit"]:hover,#comments a.comment-reply-link:hover,.pagination .current,.pagination a:hover,#footer .tagcloud a:hover,#sidebar .tagcloud a:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover,.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,.widget_product_search button:hover,nav.woocommerce-MyAccount-navigation ul li:hover, .main-navigation ul li a:hover, .main-navigation ul.sub-menu>li>a:before, #slider .carousel-control-next i:hover, #slider .carousel-control-prev i:hover, .view-all-btn a,.more-btn a,#comments input[type="submit"],#comments a.comment-reply-link,input[type="submit"],.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button,.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt,nav.woocommerce-MyAccount-navigation ul li,.pro-button a, .woocommerce a.added_to_cart.wc-forward, #service-section .owl-nav button:hover i, #preloader, #footer-2, #footer .wp-block-search .wp-block-search__button, #sidebar .wp-block-search .wp-block-search__button, .scrollup i, #sidebar h3, .pagination span, .pagination a, .widget_product_search button, .woocommerce span.onsale, .toggle-nav button, .bradcrumbs a:hover, .bradcrumbs span{';
			$music_recording_studio_custom_css .='background-color: '.esc_attr($music_recording_studio_first_color).';';
		$music_recording_studio_custom_css .='}';
	}
 
	if($music_recording_studio_first_color != false){
		$music_recording_studio_custom_css .='a, #service-section strong, #service-section .owl-nav button i, .post-main-box:hover .post-info span a, .single-post .post-info:hover a, .middle-bar h6, .post-main-box:hover h2 a, #footer .textwidget a,#footer li a:hover,.post-main-box:hover h3 a,#sidebar ul li a:hover,.post-navigation a:hover .post-title, .post-navigation a:focus .post-title,.post-navigation a:hover,.post-navigation a:focus,.wp-block-quote, .wp-block-quote:not(.is-large):not(.is-style-large), .wp-block-pullquote{';
			$music_recording_studio_custom_css .='color: '.esc_attr($music_recording_studio_first_color).';';
		$music_recording_studio_custom_css .='}';
	}

	if($music_recording_studio_first_color != false){
		$music_recording_studio_custom_css .='#slider .carousel-control-next i:hover, #slider .carousel-control-prev i:hover, #service-section .owl-nav button i,.wp-block-quote, .wp-block-quote:not(.is-large):not(.is-style-large), .wp-block-pullquote{';
			$music_recording_studio_custom_css .='border-color: '.esc_attr($music_recording_studio_first_color).';';
		$music_recording_studio_custom_css .='}';
	}

	if($music_recording_studio_first_color != false){
		$music_recording_studio_custom_css .='.main-header{';
			$music_recording_studio_custom_css .='border-bottom-color: '.esc_attr($music_recording_studio_first_color).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_custom_css .='@media screen and (max-width:1000px) {';
		if($music_recording_studio_first_color != false){
			$music_recording_studio_custom_css .='.main-navigation a:hover{
			color:'.esc_attr($music_recording_studio_first_color).'!important;
			}';
		}
	$music_recording_studio_custom_css .='}';

	$music_recording_studio_custom_css .='@media screen and (max-width:768px) {';
		if($music_recording_studio_first_color != false){
			$music_recording_studio_custom_css .='.page-template-custom-home-page .main-header{
			border-bottom-color:'.esc_attr($music_recording_studio_first_color).'!important;
			}';
		}
	$music_recording_studio_custom_css .='}';

	/*---------------------------Width Layout -------------------*/

	$music_recording_studio_theme_lay = get_theme_mod( 'music_recording_studio_width_option','Full Width');
    if($music_recording_studio_theme_lay == 'Boxed'){
		$music_recording_studio_custom_css .='body{';
			$music_recording_studio_custom_css .='max-width: 1140px; width: 100%; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto;';
		$music_recording_studio_custom_css .='}';
	}else if($music_recording_studio_theme_lay == 'Wide Width'){
		$music_recording_studio_custom_css .='body{';
			$music_recording_studio_custom_css .='width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;';
		$music_recording_studio_custom_css .='}';
	}else if($music_recording_studio_theme_lay == 'Full Width'){
		$music_recording_studio_custom_css .='body{';
			$music_recording_studio_custom_css .='max-width: 100%;';
		$music_recording_studio_custom_css .='}';
	}

	/*--------------------- Blog Page Posts -------------------*/

	$music_recording_studio_blog_page_posts_settings = get_theme_mod( 'music_recording_studio_blog_page_posts_settings','Into Blocks');
    if($music_recording_studio_blog_page_posts_settings == 'Without Blocks'){
		$music_recording_studio_custom_css .='.post-main-box{';
			$music_recording_studio_custom_css .='box-shadow: none; border: none; margin:30px 0;';
		$music_recording_studio_custom_css .='}';
	}

	// featured image dimention
	$music_recording_studio_blog_post_featured_image_dimension = get_theme_mod('music_recording_studio_blog_post_featured_image_dimension', 'default');
	$music_recording_studio_blog_post_featured_image_custom_width = get_theme_mod('music_recording_studio_blog_post_featured_image_custom_width',250);
	$music_recording_studio_blog_post_featured_image_custom_height = get_theme_mod('music_recording_studio_blog_post_featured_image_custom_height',250);
	if($music_recording_studio_blog_post_featured_image_dimension == 'custom'){
		$music_recording_studio_custom_css .='.box-image img{';
			$music_recording_studio_custom_css .='width: '.esc_attr($music_recording_studio_blog_post_featured_image_custom_width).'; height: '.esc_attr($music_recording_studio_blog_post_featured_image_custom_height).';';
		$music_recording_studio_custom_css .='}';
	}
	
	$music_recording_studio_featured_image_border_radius_setting = get_theme_mod('music_recording_studio_featured_image_border_radius_setting', 0);
	if($music_recording_studio_featured_image_border_radius_setting != false){
		$music_recording_studio_custom_css .='.box-image img, .feature-box img{';
			$music_recording_studio_custom_css .='border-radius: '.esc_attr($music_recording_studio_featured_image_border_radius_setting).'px;';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_featured_image_box_shadow = get_theme_mod('music_recording_studio_featured_image_box_shadow',0);
	if($music_recording_studio_featured_image_box_shadow != false){
		$music_recording_studio_custom_css .='.box-image img, .feature-box img, #content-vw img{';
			$music_recording_studio_custom_css .='box-shadow: '.esc_attr($music_recording_studio_featured_image_box_shadow).'px '.esc_attr($music_recording_studio_featured_image_box_shadow).'px '.esc_attr($music_recording_studio_featured_image_box_shadow).'px #cccccc;';
		$music_recording_studio_custom_css .='}';
	}

	/*----------------Responsive Media -----------------------*/

	$music_recording_studio_resp_slider = get_theme_mod( 'music_recording_studio_resp_slider_hide_show',false);
	if($music_recording_studio_resp_slider == true && get_theme_mod( 'music_recording_studio_slider_hide_show', false) == false){
    	$music_recording_studio_custom_css .='#slider{';
			$music_recording_studio_custom_css .='display:none;';
		$music_recording_studio_custom_css .='} ';
	}
    if($music_recording_studio_resp_slider == true){
    	$music_recording_studio_custom_css .='@media screen and (max-width:575px) {';
		$music_recording_studio_custom_css .='#slider{';
			$music_recording_studio_custom_css .='display:block;';
		$music_recording_studio_custom_css .='} }';
	}else if($music_recording_studio_resp_slider == false){
		$music_recording_studio_custom_css .='@media screen and (max-width:575px) {';
		$music_recording_studio_custom_css .='#slider{';
			$music_recording_studio_custom_css .='display:none;';
		$music_recording_studio_custom_css .='} }';
		$music_recording_studio_custom_css .='@media screen and (max-width:575px){';
		$music_recording_studio_custom_css .='.page-template-custom-home-page.admin-bar .homepageheader{';
			$music_recording_studio_custom_css .='margin-top: 45px;';
		$music_recording_studio_custom_css .='} }';
	}

	$music_recording_studio_resp_sidebar = get_theme_mod( 'music_recording_studio_sidebar_hide_show',true);
    if($music_recording_studio_resp_sidebar == true){
    	$music_recording_studio_custom_css .='@media screen and (max-width:575px) {';
		$music_recording_studio_custom_css .='#sidebar{';
			$music_recording_studio_custom_css .='display:block;';
		$music_recording_studio_custom_css .='} }';
	}else if($music_recording_studio_resp_sidebar == false){
		$music_recording_studio_custom_css .='@media screen and (max-width:575px) {';
		$music_recording_studio_custom_css .='#sidebar{';
			$music_recording_studio_custom_css .='display:none;';
		$music_recording_studio_custom_css .='} }';
	}

	$music_recording_studio_resp_scroll_top = get_theme_mod( 'music_recording_studio_resp_scroll_top_hide_show',true);
	if($music_recording_studio_resp_scroll_top == true && get_theme_mod( 'music_recording_studio_hide_show_scroll',true) == false){
    	$music_recording_studio_custom_css .='.scrollup i{';
			$music_recording_studio_custom_css .='visibility:hidden !important;';
		$music_recording_studio_custom_css .='} ';
	}
    if($music_recording_studio_resp_scroll_top == true){
    	$music_recording_studio_custom_css .='@media screen and (max-width:575px) {';
		$music_recording_studio_custom_css .='.scrollup i{';
			$music_recording_studio_custom_css .='visibility:visible !important;';
		$music_recording_studio_custom_css .='} }';
	}else if($music_recording_studio_resp_scroll_top == false){
		$music_recording_studio_custom_css .='@media screen and (max-width:575px){';
		$music_recording_studio_custom_css .='.scrollup i{';
			$music_recording_studio_custom_css .='visibility:hidden !important;';
		$music_recording_studio_custom_css .='} }';
	}

	$music_recording_studio_resp_menu_toggle_btn_bg_color = get_theme_mod('music_recording_studio_resp_menu_toggle_btn_bg_color');
	if($music_recording_studio_resp_menu_toggle_btn_bg_color != false){
		$music_recording_studio_custom_css .='.toggle-nav button{';
			$music_recording_studio_custom_css .='background: '.esc_attr($music_recording_studio_resp_menu_toggle_btn_bg_color).';';
		$music_recording_studio_custom_css .='}';
	}
	
	/*---------------- Button Settings ------------------*/

	$music_recording_studio_button_border_radius = get_theme_mod('music_recording_studio_button_border_radius');
	if($music_recording_studio_button_border_radius != false){
		$music_recording_studio_custom_css .='.post-main-box .more-btn a{';
			$music_recording_studio_custom_css .='border-radius: '.esc_attr($music_recording_studio_button_border_radius).'px;';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_button_top_bottom_padding = get_theme_mod('music_recording_studio_button_top_bottom_padding');
	$music_recording_studio_button_left_right_padding = get_theme_mod('music_recording_studio_button_left_right_padding');
	if($music_recording_studio_button_top_bottom_padding != false || $music_recording_studio_button_left_right_padding != false){
		$music_recording_studio_custom_css .='.post-main-box .more-btn a{';
			$music_recording_studio_custom_css .='padding-top: '.esc_attr($music_recording_studio_button_top_bottom_padding).'!important; padding-bottom: '.esc_attr($music_recording_studio_button_top_bottom_padding).'!important;padding-left: '.esc_attr($music_recording_studio_button_left_right_padding).'!important;padding-right: '.esc_attr($music_recording_studio_button_left_right_padding).'!important;';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_button_font_size = get_theme_mod('music_recording_studio_button_font_size',14);
	$music_recording_studio_custom_css .='.more-btn a{';
		$music_recording_studio_custom_css .='font-size: '.esc_attr($music_recording_studio_button_font_size).';';
	$music_recording_studio_custom_css .='}';

	$music_recording_studio_theme_lay = get_theme_mod( 'music_recording_studio_button_text_transform','Capitalize');
	if($music_recording_studio_theme_lay == 'Capitalize'){
		$music_recording_studio_custom_css .='.more-btn a{';
			$music_recording_studio_custom_css .='text-transform:Capitalize;';
		$music_recording_studio_custom_css .='}';
	}
	if($music_recording_studio_theme_lay == 'Lowercase'){
		$music_recording_studio_custom_css .='.more-btn a{';
			$music_recording_studio_custom_css .='text-transform:Lowercase;';
		$music_recording_studio_custom_css .='}';
	}
	if($music_recording_studio_theme_lay == 'Uppercase'){
		$music_recording_studio_custom_css .='.more-btn a{';
			$music_recording_studio_custom_css .='text-transform:Uppercase;';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_button_letter_spacing = get_theme_mod('music_recording_studio_button_letter_spacing',14);
	$music_recording_studio_custom_css .='.more-btn a{';
		$music_recording_studio_custom_css .='letter-spacing: '.esc_attr($music_recording_studio_button_letter_spacing).';';
	$music_recording_studio_custom_css .='}';

	/*---------------- Single Post Settings ------------------*/

	$music_recording_studio_single_blog_comment_title = get_theme_mod('music_recording_studio_single_blog_comment_title', 'Leave a Reply');
	if($music_recording_studio_single_blog_comment_title == ''){
		$music_recording_studio_custom_css .='#comments h2#reply-title {';
			$music_recording_studio_custom_css .='display: none;';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_single_blog_comment_button_text = get_theme_mod('music_recording_studio_single_blog_comment_button_text', 'Post Comment');
	if($music_recording_studio_single_blog_comment_button_text == ''){
		$music_recording_studio_custom_css .='#comments p.form-submit {';
			$music_recording_studio_custom_css .='display: none;';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_comment_width = get_theme_mod('music_recording_studio_single_blog_comment_width');
	if($music_recording_studio_comment_width != false){
		$music_recording_studio_custom_css .='#comments textarea{';
			$music_recording_studio_custom_css .='width: '.esc_attr($music_recording_studio_comment_width).';';
		$music_recording_studio_custom_css .='}';
	}

	/*---------------------------Blog Layout -------------------*/

	$music_recording_studio_theme_lay = get_theme_mod( 'music_recording_studio_blog_layout_option','Default');
    if($music_recording_studio_theme_lay == 'Default'){
		$music_recording_studio_custom_css .='.post-main-box{';
			$music_recording_studio_custom_css .='';
		$music_recording_studio_custom_css .='}';
	}else if($music_recording_studio_theme_lay == 'Center'){
		$music_recording_studio_custom_css .='.post-main-box, .post-main-box h2, .post-info, .new-text p, .content-bttn{';
			$music_recording_studio_custom_css .='text-align:center;';
		$music_recording_studio_custom_css .='}';
		$music_recording_studio_custom_css .='.post-info{';
			$music_recording_studio_custom_css .='margin-top:10px;';
		$music_recording_studio_custom_css .='}';
		$music_recording_studio_custom_css .='.post-info hr{';
			$music_recording_studio_custom_css .='margin:15px auto;';
		$music_recording_studio_custom_css .='}';
	}else if($music_recording_studio_theme_lay == 'Left'){
		$music_recording_studio_custom_css .='.post-main-box, .post-main-box h2, .post-info, .new-text p, .content-bttn, #our-services p{';
			$music_recording_studio_custom_css .='text-align:Left;';
		$music_recording_studio_custom_css .='}';
		$music_recording_studio_custom_css .='.post-info hr{';
			$music_recording_studio_custom_css .='margin-bottom:10px;';
		$music_recording_studio_custom_css .='}';
		$music_recording_studio_custom_css .='.post-main-box h2{';
			$music_recording_studio_custom_css .='margin-top:10px;';
		$music_recording_studio_custom_css .='}';
	}

	/*-------------- Copyright Alignment ----------------*/

	$music_recording_studio_copyright_background_color = get_theme_mod('music_recording_studio_copyright_background_color');
	if($music_recording_studio_copyright_background_color != false){
		$music_recording_studio_custom_css .='#footer-2{';
			$music_recording_studio_custom_css .='background-color: '.esc_attr($music_recording_studio_copyright_background_color).';';
		$music_recording_studio_custom_css .='}';
	} 

	$music_recording_studio_footer_widgets_heading = get_theme_mod( 'music_recording_studio_footer_widgets_heading','Left');
    if($music_recording_studio_footer_widgets_heading == 'Left'){
		$music_recording_studio_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label{';
		$music_recording_studio_custom_css .='text-align: left;';
		$music_recording_studio_custom_css .='}';
	}else if($music_recording_studio_footer_widgets_heading == 'Center'){
		$music_recording_studio_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label{';
			$music_recording_studio_custom_css .='text-align: center;';
		$music_recording_studio_custom_css .='}';
	}else if($music_recording_studio_footer_widgets_heading == 'Right'){
		$music_recording_studio_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label{';
			$music_recording_studio_custom_css .='text-align: right;';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_footer_widgets_content = get_theme_mod( 'music_recording_studio_footer_widgets_content','Left');
    if($music_recording_studio_footer_widgets_content == 'Left'){
		$music_recording_studio_custom_css .='#footer .widget{';
		$music_recording_studio_custom_css .='text-align: left;';
		$music_recording_studio_custom_css .='}';
	}else if($music_recording_studio_footer_widgets_content == 'Center'){
		$music_recording_studio_custom_css .='#footer .widget{';
			$music_recording_studio_custom_css .='text-align: center;';
		$music_recording_studio_custom_css .='}';
	}else if($music_recording_studio_footer_widgets_content == 'Right'){
		$music_recording_studio_custom_css .='#footer .widget{';
			$music_recording_studio_custom_css .='text-align: right;';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_copyright_alingment = get_theme_mod('music_recording_studio_copyright_alingment');
	if($music_recording_studio_copyright_alingment != false){
		$music_recording_studio_custom_css .='.copyright p{';
			$music_recording_studio_custom_css .='text-align: '.esc_attr($music_recording_studio_copyright_alingment).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_footer_padding = get_theme_mod('music_recording_studio_footer_padding');
	if($music_recording_studio_footer_padding != false){
		$music_recording_studio_custom_css .='#footer{';
			$music_recording_studio_custom_css .='padding: '.esc_attr($music_recording_studio_footer_padding).' 0;';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_footer_icon = get_theme_mod('music_recording_studio_footer_icon');
	if($music_recording_studio_footer_icon == false){
		$music_recording_studio_custom_css .='.copyright p{';
			$music_recording_studio_custom_css .='width:100%; text-align:center; float:none;';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_footer_background_image = get_theme_mod('music_recording_studio_footer_background_image');
	if($music_recording_studio_footer_background_image != false){
		$music_recording_studio_custom_css .='#footer{';
			$music_recording_studio_custom_css .='background: url('.esc_attr($music_recording_studio_footer_background_image).');';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_footer_background_color = get_theme_mod('music_recording_studio_footer_background_color');
	if($music_recording_studio_footer_background_color != false){
		$music_recording_studio_custom_css .='#footer{';
			$music_recording_studio_custom_css .='background-color: '.esc_attr($music_recording_studio_footer_background_color).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_theme_lay = get_theme_mod( 'music_recording_studio_img_footer','scroll');
	if($music_recording_studio_theme_lay == 'fixed'){
		$music_recording_studio_custom_css .='#footer{';
			$music_recording_studio_custom_css .='background-attachment: fixed !important; background-position: center !important;';
		$music_recording_studio_custom_css .='}';
	}elseif ($music_recording_studio_theme_lay == 'scroll'){
		$music_recording_studio_custom_css .='#footer{';
			$music_recording_studio_custom_css .='background-attachment: scroll !important; background-position: center !important;';
		$music_recording_studio_custom_css .='}';
	}


	/*------------------ Logo  -------------------*/

	$music_recording_studio_logo_padding = get_theme_mod('music_recording_studio_logo_padding');
	if($music_recording_studio_logo_padding != false){
		$music_recording_studio_custom_css .='.main-header .logo{';
			$music_recording_studio_custom_css .='padding: '.esc_attr($music_recording_studio_logo_padding).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_logo_margin = get_theme_mod('music_recording_studio_logo_margin');
	if($music_recording_studio_logo_margin != false){
		$music_recording_studio_custom_css .='.main-header .logo{';
			$music_recording_studio_custom_css .='margin: '.esc_attr($music_recording_studio_logo_margin).';';
		$music_recording_studio_custom_css .='}';
	}

	// Site title Font Size
	$music_recording_studio_site_title_font_size = get_theme_mod('music_recording_studio_site_title_font_size');
	if($music_recording_studio_site_title_font_size != false){
		$music_recording_studio_custom_css .='.logo h1, .logo p.site-title{';
			$music_recording_studio_custom_css .='font-size: '.esc_attr($music_recording_studio_site_title_font_size).';';
		$music_recording_studio_custom_css .='}';
	}

	// Site tagline Font Size
	$music_recording_studio_site_tagline_font_size = get_theme_mod('music_recording_studio_site_tagline_font_size');
	if($music_recording_studio_site_tagline_font_size != false){
		$music_recording_studio_custom_css .='.logo p.site-description{';
			$music_recording_studio_custom_css .='font-size: '.esc_attr($music_recording_studio_site_tagline_font_size).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_header_menus_color = get_theme_mod('music_recording_studio_header_menus_color');
	if($music_recording_studio_header_menus_color != false){
		$music_recording_studio_custom_css .='.main-navigation a{';
			$music_recording_studio_custom_css .='color: '.esc_attr($music_recording_studio_header_menus_color).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_header_menus_hover_color = get_theme_mod('music_recording_studio_header_menus_hover_color');
	if($music_recording_studio_header_menus_hover_color != false){
		$music_recording_studio_custom_css .='.main-navigation ul li a:hover{';
			$music_recording_studio_custom_css .='color: '.esc_attr($music_recording_studio_header_menus_hover_color).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_header_submenus_color = get_theme_mod('music_recording_studio_header_submenus_color');
	if($music_recording_studio_header_submenus_color != false){
		$music_recording_studio_custom_css .='.main-navigation ul ul a{';
			$music_recording_studio_custom_css .='color: '.esc_attr($music_recording_studio_header_submenus_color).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_header_submenus_hover_color = get_theme_mod('music_recording_studio_header_submenus_hover_color');
	if($music_recording_studio_header_submenus_hover_color != false){
		$music_recording_studio_custom_css .='.main-navigation ul.sub-menu a:hover{';
			$music_recording_studio_custom_css .='color: '.esc_attr($music_recording_studio_header_submenus_hover_color).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_menus_item = get_theme_mod( 'music_recording_studio_menus_item_style','None');
    if($music_recording_studio_menus_item == 'None'){
		$music_recording_studio_custom_css .='.main-navigation a{';
			$music_recording_studio_custom_css .='';
		$music_recording_studio_custom_css .='}';
	}else if($music_recording_studio_menus_item == 'Zoom In'){
		$music_recording_studio_custom_css .='.main-navigation a:hover{';
			$music_recording_studio_custom_css .='transition: all 0.3s ease-in-out !important; transform: scale(1.2) !important; color: #ffcc05;';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_navigation_menu_font_weight = get_theme_mod('music_recording_studio_navigation_menu_font_weight','500');
	if($music_recording_studio_navigation_menu_font_weight != false){
		$music_recording_studio_custom_css .='.main-navigation a{';
			$music_recording_studio_custom_css .='font-weight: '.esc_attr($music_recording_studio_navigation_menu_font_weight).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_theme_lay = get_theme_mod( 'music_recording_studio_menu_text_transform','Capitalize');
	if($music_recording_studio_theme_lay == 'Capitalize'){
		$music_recording_studio_custom_css .='.main-navigation a{';
			$music_recording_studio_custom_css .='text-transform:Capitalize;';
		$music_recording_studio_custom_css .='}';
	}
	if($music_recording_studio_theme_lay == 'Lowercase'){
		$music_recording_studio_custom_css .='.main-navigation a{';
			$music_recording_studio_custom_css .='text-transform:Lowercase;';
		$music_recording_studio_custom_css .='}';
	}
	if($music_recording_studio_theme_lay == 'Uppercase'){
		$music_recording_studio_custom_css .='.main-navigation a{';
			$music_recording_studio_custom_css .='text-transform:Uppercase;';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_navigation_menu_font_size = get_theme_mod('music_recording_studio_navigation_menu_font_size');
	if($music_recording_studio_navigation_menu_font_size != false){
		$music_recording_studio_custom_css .='.main-navigation a{';
			$music_recording_studio_custom_css .='font-size: '.esc_attr($music_recording_studio_navigation_menu_font_size).';';
		$music_recording_studio_custom_css .='}';
	}

	/*------------------ Preloader Background Color  -------------------*/

	$music_recording_studio_preloader_bg_color = get_theme_mod('music_recording_studio_preloader_bg_color');
	if($music_recording_studio_preloader_bg_color != false){
		$music_recording_studio_custom_css .='#preloader{';
			$music_recording_studio_custom_css .='background-color: '.esc_attr($music_recording_studio_preloader_bg_color).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_preloader_border_color = get_theme_mod('music_recording_studio_preloader_border_color');
	if($music_recording_studio_preloader_border_color != false){
		$music_recording_studio_custom_css .='.loader-line{';
			$music_recording_studio_custom_css .='border-color: '.esc_attr($music_recording_studio_preloader_border_color).'!important;';
		$music_recording_studio_custom_css .='}';
	}

	// Slider CSS
	if(get_theme_mod('music_recording_studio_slider_hide_show') == false){
		$music_recording_studio_custom_css .=' .page-template-custom-home-page .main-header{';
				$music_recording_studio_custom_css .=' position: static; border-bottom: 1px solid #DE3960;';
		$music_recording_studio_custom_css .='}';
		$music_recording_studio_custom_css .=' .page-template-custom-home-page p.site-title a, .page-template-custom-home-page .logo h1 a, .page-template-custom-home-page .logo p.site-description{';
				$music_recording_studio_custom_css .=' color: #000;';
		$music_recording_studio_custom_css .='}';
	}
	
	/*---------------------------Slider Height ------------*/

	$music_recording_studio_slider_height = get_theme_mod('music_recording_studio_slider_height');
	if($music_recording_studio_slider_height != false){
		$music_recording_studio_custom_css .='#slider .carousel-caption img{';
			$music_recording_studio_custom_css .='height: '.esc_attr($music_recording_studio_slider_height).';';
		$music_recording_studio_custom_css .='}';
	}

	/*----------------Woocommerce Products Settings ------------------*/

	$music_recording_studio_related_product_show_hide = get_theme_mod('music_recording_studio_related_product_show_hide',true);
	if($music_recording_studio_related_product_show_hide != true){
		$music_recording_studio_custom_css .='.related.products{';
			$music_recording_studio_custom_css .='display: none;';
		$music_recording_studio_custom_css .='}';
	}


	/*----------------Sroll to top Settings ------------------*/

	$music_recording_studio_scroll_to_top_font_size = get_theme_mod('music_recording_studio_scroll_to_top_font_size');
	if($music_recording_studio_scroll_to_top_font_size != false){
		$music_recording_studio_custom_css .='.scrollup i{';
			$music_recording_studio_custom_css .='font-size: '.esc_attr($music_recording_studio_scroll_to_top_font_size).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_scroll_to_top_padding = get_theme_mod('music_recording_studio_scroll_to_top_padding');
	$music_recording_studio_scroll_to_top_padding = get_theme_mod('music_recording_studio_scroll_to_top_padding');
	if($music_recording_studio_scroll_to_top_padding != false){
		$music_recording_studio_custom_css .='.scrollup i{';
			$music_recording_studio_custom_css .='padding-top: '.esc_attr($music_recording_studio_scroll_to_top_padding).';padding-bottom: '.esc_attr($music_recording_studio_scroll_to_top_padding).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_scroll_to_top_width = get_theme_mod('music_recording_studio_scroll_to_top_width');
	if($music_recording_studio_scroll_to_top_width != false){
		$music_recording_studio_custom_css .='.scrollup i{';
			$music_recording_studio_custom_css .='width: '.esc_attr($music_recording_studio_scroll_to_top_width).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_scroll_to_top_height = get_theme_mod('music_recording_studio_scroll_to_top_height');
	if($music_recording_studio_scroll_to_top_height != false){
		$music_recording_studio_custom_css .='.scrollup i{';
			$music_recording_studio_custom_css .='height: '.esc_attr($music_recording_studio_scroll_to_top_height).';';
		$music_recording_studio_custom_css .='}';
	}

	$music_recording_studio_scroll_to_top_border_radius = get_theme_mod('music_recording_studio_scroll_to_top_border_radius');
	if($music_recording_studio_scroll_to_top_border_radius != false){
		$music_recording_studio_custom_css .='.scrollup i{';
			$music_recording_studio_custom_css .='border-radius: '.esc_attr($music_recording_studio_scroll_to_top_border_radius).'px;';
		$music_recording_studio_custom_css .='}';
	}