<?php
/**
 * Default theme options.
 *
 * @package Night Club
 */

if ( ! function_exists( 'night_club_get_default_theme_options' ) ) :

	/**
	 * Get default theme options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Default theme options.
	 */
function night_club_get_default_theme_options() {

	$defaults = array();

	// Top Bar
	$defaults['show_header_contact_info'] 		= false;


    // Front Page Content
	$defaults['enable_frontpage_content'] 		= true;

	// Slider Section	
	$defaults['enable_featured_slider_section']		    	= false;
	$defaults['featured_slider_content_type']		    	= 'featured_slider_page';
	$defaults['featured_slider_category_readmore_text']		= esc_html__('Read More','night-club');
	$defaults['data_slick_speed']					    	= 1000;
	$defaults['data_slick_infinite']				    	= 1;
	$defaults['data_slick_arrows']					    	= 1;
	$defaults['data_slick_autoplay']				    	= 0;
	$defaults['data_slick_draggable']				    	= 1;
	$defaults['data_slick_fade']					    	= 1;
	$defaults['number_of_featured_slider_items']	    	= 6;

	// Blog Section	
	$defaults['enable_featured_blog_section']			= false;
	$defaults['number_of_featured_blog_items']			= 6;
	$defaults['featured_blog_section_title']	    	= esc_html__( 'Blog', 'night-club' );
	$defaults['featured_blog_column']					= 'col-3';
	$defaults['featured_blog_content_type']				= 'featured_blog_page';
	
	// Servies Section	
	$defaults['enable_featured_services_section']			= false;
	$defaults['number_of_featured_services_items']			= 6;
	$defaults['featured_services_section_title']	    	= esc_html__( 'Our Service', 'night-club' );
	$defaults['featured_services_column']					= 'col-3';
	$defaults['featured_services_content_type']				= 'featured_services_page';

	// Featured Posts Section
	$defaults['enable_featured_posts_section']			= false;
	$defaults['featured_posts_background_color']    	= '#2196f3';
	$defaults['featured_posts_section_title']	    	= esc_html__( 'Blog', 'night-club' );
	$defaults['featured_posts_category']	   	    	= 0; 
	$defaults['featured_posts_number']					= 6;
	$defaults['featured_posts_column']					= 'col-3';

	// Theme Options
	$defaults['show_header_image']		    			= 'header-image-enable';
	$defaults['show_page_title']		    			= 'page-title-enable';
	$defaults['layout_options_blog']					= 'no-sidebar';
	$defaults['layout_options_archive']					= 'no-sidebar';
	$defaults['layout_options_page']					= 'no-sidebar';	
	$defaults['layout_options_single']					= 'right-sidebar';	
	$defaults['excerpt_length']							= 25;
	$defaults['readmore_text']							= esc_html__('Read more','night-club');
	$defaults['your_latest_posts_title']				= esc_html__('Blog','night-club');

	//Footer section 		
	$defaults['copyright_text']							= esc_html__( 'Copyright &copy; All rights reserved.', 'night-club' );

	// Pass through filter.
	$defaults = apply_filters( 'night_club_filter_default_theme_options', $defaults );
	return $defaults;
}

endif;

/**
*  Get theme options
*/
if ( ! function_exists( 'night_club_get_option' ) ) :

	/**
	 * Get theme option
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Option key.
	 * @return mixed Option value.
	 */
	function night_club_get_option( $key ) {

		$default_options = night_club_get_default_theme_options();
		if ( empty( $key ) ) {
			return;
		}

		$theme_options = (array)get_theme_mod( 'theme_options' );
		$theme_options = wp_parse_args( $theme_options, $default_options );

		$value = null;

		if ( isset( $theme_options[ $key ] ) ) {
			$value = $theme_options[ $key ];
		}

		return $value;

	}

endif;