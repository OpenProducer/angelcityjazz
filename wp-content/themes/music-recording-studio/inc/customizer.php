<?php
/**
 * Music Recording Studio Theme Customizer
 *
 * @package Music Recording Studio
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

function music_recording_studio_custom_controls() {
	load_template( trailingslashit( get_template_directory() ) . '/inc/custom-controls.php' );
}
add_action( 'customize_register', 'music_recording_studio_custom_controls' );

function music_recording_studio_customize_register( $wp_customize ) {

load_template( trailingslashit( get_template_directory() ) . '/inc/icon-picker.php' );

	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage'; 
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'blogname', array( 
		'selector' => '.logo .site-title a', 
	 	'render_callback' => 'music_recording_studio_Customize_partial_blogname',
	)); 

	$wp_customize->selective_refresh->add_partial( 'blogdescription', array( 
		'selector' => 'p.site-description', 
		'render_callback' => 'music_recording_studio_Customize_partial_blogdescription',
	));

	//Homepage Settings
	$wp_customize->add_panel( 'music_recording_studio_homepage_panel', array(
		'title' => esc_html__( 'Homepage Settings', 'music-recording-studio' ),
		'panel' => 'music_recording_studio_panel_id',
		'priority' => 20, 
	));

	//Menus Settings
	$wp_customize->add_section( 'music_recording_studio_menu_section' , array(
    	'title' => __( 'Menus Settings', 'music-recording-studio' ),
		'panel' => 'music_recording_studio_homepage_panel'
	) );

	$wp_customize->add_setting('music_recording_studio_navigation_menu_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_navigation_menu_font_size',array(
		'label'	=> __('Menus Font Size','music-recording-studio'),
		'description'	=> __('Enter a value in pixels. Example:20px','music-recording-studio'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'music-recording-studio' ),
        ),
		'section'=> 'music_recording_studio_menu_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('music_recording_studio_navigation_menu_font_weight',array(
        'default' => 500,
        'transport' => 'refresh',
        'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_navigation_menu_font_weight',array(
        'type' => 'select',
        'label' => __('Menus Font Weight','music-recording-studio'),
        'section' => 'music_recording_studio_menu_section',
        'choices' => array(
        	'100' => __('100','music-recording-studio'),
            '200' => __('200','music-recording-studio'),
            '300' => __('300','music-recording-studio'),
            '400' => __('400','music-recording-studio'),
            '500' => __('500','music-recording-studio'),
            '600' => __('600','music-recording-studio'),
            '700' => __('700','music-recording-studio'),
            '800' => __('800','music-recording-studio'),
            '900' => __('900','music-recording-studio'),
        ),
	) );

	// text trasform
	$wp_customize->add_setting('music_recording_studio_menu_text_transform',array(
		'default'=> 'Capitalize',
		'sanitize_callback'	=> 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_menu_text_transform',array(
		'type' => 'radio',
		'label'	=> __('Menus Text Transform','music-recording-studio'),
		'choices' => array(
            'Uppercase' => __('Uppercase','music-recording-studio'),
            'Capitalize' => __('Capitalize','music-recording-studio'),
            'Lowercase' => __('Lowercase','music-recording-studio'),
        ),
		'section'=> 'music_recording_studio_menu_section',
	));

	$wp_customize->add_setting('music_recording_studio_menus_item_style',array(
    'default' => '',
    'transport' => 'refresh',
    'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_menus_item_style',array(
    'type' => 'select',
    'section' => 'music_recording_studio_menu_section',
		'label' => __('Menu Item Hover Style','music-recording-studio'),
		'choices' => array(
            'None' => __('None','music-recording-studio'),
            'Zoom In' => __('Zoom In','music-recording-studio'),
        ),
	) ); 

	$wp_customize->add_setting('music_recording_studio_header_menus_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'music_recording_studio_header_menus_color', array(
		'label'    => __('Menus Color', 'music-recording-studio'),
		'section'  => 'music_recording_studio_menu_section',
	)));

	$wp_customize->add_setting('music_recording_studio_header_menus_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'music_recording_studio_header_menus_hover_color', array(
		'label'    => __('Menus Hover Color', 'music-recording-studio'),
		'section'  => 'music_recording_studio_menu_section',
	)));

	$wp_customize->add_setting('music_recording_studio_header_submenus_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'music_recording_studio_header_submenus_color', array(
		'label'    => __('Sub Menus Color', 'music-recording-studio'),
		'section'  => 'music_recording_studio_menu_section',
	)));

	$wp_customize->add_setting('music_recording_studio_header_submenus_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'music_recording_studio_header_submenus_hover_color', array(
		'label'    => __('Sub Menus Hover Color', 'music-recording-studio'),
		'section'  => 'music_recording_studio_menu_section',
	)));

	//Slider
	$wp_customize->add_section( 'music_recording_studio_slidersettings' , array(
    	'title'      => __( 'Slider Settings', 'music-recording-studio' ),
    	'description' => "Free theme has 3 slides options, For unlimited slides and more options </br><a class='go-pro-btn' target='_blank' href='". esc_url(MUSIC_RECORDING_STUDIO_GO_PRO) ." '>GO PRO</a>",
		'panel' => 'music_recording_studio_homepage_panel'
	) );

	$wp_customize->add_setting( 'music_recording_studio_slider_hide_show',array(
    'default' => 0,
    'transport' => 'refresh',
    'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ));  
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_slider_hide_show',array(
    'label' => esc_html__( 'Show / Hide Slider','music-recording-studio' ),
    'section' => 'music_recording_studio_slidersettings'
  )));

  $wp_customize->add_setting('music_recording_studio_slider_type',array(
        'default' => 'Default slider',
        'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	) );
	$wp_customize->add_control('music_recording_studio_slider_type', array(
        'type' => 'select',
        'label' => __('Slider Type','music-recording-studio'),
        'section' => 'music_recording_studio_slidersettings',
        'choices' => array(
            'Default slider' => __('Default slider','music-recording-studio'),
            'Advance slider' => __('Advance slider','music-recording-studio'),
        ),
	));

	$wp_customize->add_setting('music_recording_studio_advance_slider_shortcode',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_advance_slider_shortcode',array(
		'label'	=> __('Add Slider Shortcode','music-recording-studio'),
		'section'=> 'music_recording_studio_slidersettings',
		'type'=> 'text',
		'active_callback' => 'music_recording_studio_advance_slider'
	));

   //Selective Refresh
  $wp_customize->selective_refresh->add_partial('music_recording_studio_slider_hide_show',array(
		'selector'        => '.slider-btn a',
		'render_callback' => 'music_recording_studio_customize_partial_music_recording_studio_slider_hide_show',
	));

	for ( $count = 1; $count <= 3; $count++ ) {
		$wp_customize->add_setting( 'music_recording_studio_slider_page' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'music_recording_studio_sanitize_dropdown_pages'
		) );
		$wp_customize->add_control( 'music_recording_studio_slider_page' . $count, array(
			'label'    => __( 'Select Slider Page', 'music-recording-studio' ),
			'description' => __('Slider image size (450 x 250)','music-recording-studio'),
			'section'  => 'music_recording_studio_slidersettings',
			'type'     => 'dropdown-pages',
			'active_callback' => 'music_recording_studio_default_slider'
		) );
	}

	$wp_customize->add_setting( 'music_recording_studio_slider_content_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
    ));  
    $wp_customize->add_control( new music_recording_studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_slider_content_hide_show',array(
		'label' => esc_html__( 'Show / Hide Slider Content','music-recording-studio' ),
		'section' => 'music_recording_studio_slidersettings',
		'active_callback' => 'music_recording_studio_default_slider'
    )));

    //Slider excerpt
	$wp_customize->add_setting( 'music_recording_studio_slider_excerpt_number', array(
		'default'           => 15,
		'transport' 	    => 'refresh',
		'sanitize_callback' => 'music_recording_studio_sanitize_number_range'
	) );
	$wp_customize->add_control( 'music_recording_studio_slider_excerpt_number', array(
		'label'       => esc_html__( 'Slider Excerpt length','music-recording-studio' ),
		'section'     => 'music_recording_studio_slidersettings',
		'type'        => 'range',
		'settings'    => 'music_recording_studio_slider_excerpt_number',
		'input_attrs' => array(
			'step' => 5,
			'min'  => 0,
			'max'  => 50,
		),'active_callback' => 'music_recording_studio_default_slider'
	) );

	$wp_customize->add_setting( 'music_recording_studio_slider_speed', array(
		'default'  => 4000,
		'sanitize_callback'	=> 'sanitize_text_field'
	) );
	$wp_customize->add_control( 'music_recording_studio_slider_speed', array(
		'label' => esc_html__('Slider Transition Speed','music-recording-studio'),
		'section' => 'music_recording_studio_slidersettings',
		'type'  => 'text',
		'active_callback' => 'music_recording_studio_default_slider'
	) );

	//Slider height
	$wp_customize->add_setting('music_recording_studio_slider_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_slider_height',array(
		'label'	=> __('Slider Height','music-recording-studio'),
		'description'	=> __('Specify the slider height (px).','music-recording-studio'),
		'input_attrs' => array(
            'placeholder' => __( '500px', 'music-recording-studio' ),
        ),
		'section'=> 'music_recording_studio_slidersettings',
		'type'=> 'text',
		'active_callback' => 'music_recording_studio_default_slider'
	));

	// Services Section
	$wp_customize->add_section('music_recording_studio_service_section',array(
		'title'	=> __('Service Section','music-recording-studio'),
		'description' => "For more options of service section </br><a class='go-pro-btn' target='_blank' href='". esc_url(MUSIC_RECORDING_STUDIO_GO_PRO) ." '>GO PRO</a>",
		'panel' => 'music_recording_studio_homepage_panel',
	));

	$wp_customize->add_setting( 'music_recording_studio_section_small_title', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field'
	) );
	$wp_customize->add_control( 'music_recording_studio_section_small_title', array(
		'label'    => __( 'Add Section Small Title', 'music-recording-studio' ),
		'input_attrs' => array(
            'placeholder' => __( 'What we do', 'music-recording-studio' ),
        ),
		'section'  => 'music_recording_studio_service_section',
		'type'     => 'text'
	) );

	$wp_customize->add_setting( 'music_recording_studio_section_title', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field'
	) );
	$wp_customize->add_control( 'music_recording_studio_section_title', array(
		'label'    => __( 'Add Section Title', 'music-recording-studio' ),
		'input_attrs' => array(
            'placeholder' => __( 'Studio Services', 'music-recording-studio' ),
        ),
		'section'  => 'music_recording_studio_service_section',
		'type'     => 'text'
	) );

	$categories = get_categories();
		$cat_posts = array();
			$i = 0;
			$cat_posts[]='Select';
		foreach($categories as $category){
			if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cat_posts[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('music_recording_studio_service_category',array(
		'default'	=> 'select',
		'sanitize_callback' => 'music_recording_studio_sanitize_choices',
	));
	$wp_customize->add_control('music_recording_studio_service_category',array(
		'type'    => 'select',
		'choices' => $cat_posts,
		'label' => __('Select Service Category','music-recording-studio'),
		'section' => 'music_recording_studio_service_section',
	));

	//About Us Section
	$wp_customize->add_section('music_recording_studio_about', array(
		'title'       => __('About US Section', 'music-recording-studio'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','music-recording-studio'),
		'priority'    => null,
		'panel'       => 'music_recording_studio_homepage_panel',
	));

	$wp_customize->add_setting('music_recording_studio_about_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_about_text',array(
		'description' => __('<p>1. More options for about section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for about section.</p>','music-recording-studio'),
		'section'=> 'music_recording_studio_about',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('music_recording_studio_about_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_about_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url(MUSIC_RECORDING_STUDIO_GETSTARTED_URL) ." '>More Info</a>",
		'section'=> 'music_recording_studio_about',
		'type'=> 'hidden'
	));

	//Recording Studio Section
	$wp_customize->add_section('music_recording_studio_recording_studio', array(
		'title'       => __('Recording Studio Section', 'music-recording-studio'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','music-recording-studio'),
		'priority'    => null,
		'panel'       => 'music_recording_studio_homepage_panel',
	));

	$wp_customize->add_setting('music_recording_studio_recording_studio_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_recording_studio_text',array(
		'description' => __('<p>1. More options for Recording Studio section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for Recording Studio section.</p>','music-recording-studio'),
		'section'=> 'music_recording_studio_recording_studio',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('music_recording_studio_recording_studio_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_recording_studio_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url(MUSIC_RECORDING_STUDIO_GETSTARTED_URL) ." '>More Info</a>",
		'section'=> 'music_recording_studio_recording_studio',
		'type'=> 'hidden'
	));

	//Video Section
	$wp_customize->add_section('music_recording_studio_video', array(
		'title'       => __('Video Section', 'music-recording-studio'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','music-recording-studio'),
		'priority'    => null,
		'panel'       => 'music_recording_studio_homepage_panel',
	));

	$wp_customize->add_setting('music_recording_studio_video_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_video_text',array(
		'description' => __('<p>1. More options for Video section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for Video section.</p>','music-recording-studio'),
		'section'=> 'music_recording_studio_video',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('music_recording_studio_video_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_video_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url(MUSIC_RECORDING_STUDIO_GETSTARTED_URL) ." '>More Info</a>",
		'section'=> 'music_recording_studio_video',
		'type'=> 'hidden'
	));

	//Portfolio Section
	$wp_customize->add_section('music_recording_studio_portfolio', array(
		'title'       => __('Portfolio Section', 'music-recording-studio'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','music-recording-studio'),
		'priority'    => null,
		'panel'       => 'music_recording_studio_homepage_panel',
	));

	$wp_customize->add_setting('music_recording_studio_portfolio_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_portfolio_text',array(
		'description' => __('<p>1. More options for Portfolio section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for Portfolio section.</p>','music-recording-studio'),
		'section'=> 'music_recording_studio_portfolio',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('music_recording_studio_portfolio_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_portfolio_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url(MUSIC_RECORDING_STUDIO_GETSTARTED_URL) ." '>More Info</a>",
		'section'=> 'music_recording_studio_portfolio',
		'type'=> 'hidden'
	));

	//Latest Release Section
	$wp_customize->add_section('music_recording_studio_latest_release', array(
		'title'       => __('Latest Release Section', 'music-recording-studio'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','music-recording-studio'),
		'priority'    => null,
		'panel'       => 'music_recording_studio_homepage_panel',
	));

	$wp_customize->add_setting('music_recording_studio_latest_release_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_latest_release_text',array(
		'description' => __('<p>1. More options for Latest Release section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for Latest Release section.</p>','music-recording-studio'),
		'section'=> 'music_recording_studio_latest_release',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('music_recording_studio_latest_release_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_latest_release_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url(MUSIC_RECORDING_STUDIO_GETSTARTED_URL) ." '>More Info</a>",
		'section'=> 'music_recording_studio_latest_release',
		'type'=> 'hidden'
	));

	//What We Use Section
	$wp_customize->add_section('music_recording_studio_what_we_use', array(
		'title'       => __('What We Use Section', 'music-recording-studio'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','music-recording-studio'),
		'priority'    => null,
		'panel'       => 'music_recording_studio_homepage_panel',
	));

	$wp_customize->add_setting('music_recording_studio_what_we_use_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_what_we_use_text',array(
		'description' => __('<p>1. More options for What We Use section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for What We Use section.</p>','music-recording-studio'),
		'section'=> 'music_recording_studio_what_we_use',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('music_recording_studio_what_we_use_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_what_we_use_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url(MUSIC_RECORDING_STUDIO_GETSTARTED_URL) ." '>More Info</a>",
		'section'=> 'music_recording_studio_what_we_use',
		'type'=> 'hidden'
	));

	//Team Section
	$wp_customize->add_section('music_recording_studio_team', array(
		'title'       => __('Team Section', 'music-recording-studio'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','music-recording-studio'),
		'priority'    => null,
		'panel'       => 'music_recording_studio_homepage_panel',
	));

	$wp_customize->add_setting('music_recording_studio_team_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_team_text',array(
		'description' => __('<p>1. More options for Team section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for Team section.</p>','music-recording-studio'),
		'section'=> 'music_recording_studio_team',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('music_recording_studio_team_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_team_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url(MUSIC_RECORDING_STUDIO_GETSTARTED_URL) ." '>More Info</a>",
		'section'=> 'music_recording_studio_team',
		'type'=> 'hidden'
	));

	//Latest News Section
	$wp_customize->add_section('music_recording_studio_latest_news', array(
		'title'       => __('Latest News Section', 'music-recording-studio'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','music-recording-studio'),
		'priority'    => null,
		'panel'       => 'music_recording_studio_homepage_panel',
	));

	$wp_customize->add_setting('music_recording_studio_latest_news_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_latest_news_text',array(
		'description' => __('<p>1. More options for Latest News section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for Latest News section.</p>','music-recording-studio'),
		'section'=> 'music_recording_studio_latest_news',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('music_recording_studio_latest_news_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_latest_news_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url(MUSIC_RECORDING_STUDIO_GETSTARTED_URL) ." '>More Info</a>",
		'section'=> 'music_recording_studio_latest_news',
		'type'=> 'hidden'
	));

	//Booking Section
	$wp_customize->add_section('music_recording_studio_booking', array(
		'title'       => __('Booking Section', 'music-recording-studio'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','music-recording-studio'),
		'priority'    => null,
		'panel'       => 'music_recording_studio_homepage_panel',
	));

	$wp_customize->add_setting('music_recording_studio_booking_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_booking_text',array(
		'description' => __('<p>1. More options for Booking section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for Booking section.</p>','music-recording-studio'),
		'section'=> 'music_recording_studio_booking',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('music_recording_studio_booking_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_booking_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url(MUSIC_RECORDING_STUDIO_GETSTARTED_URL) ." '>More Info</a>",
		'section'=> 'music_recording_studio_booking',
		'type'=> 'hidden'
	));

	//Instagram Section
	$wp_customize->add_section('music_recording_studio_instagram', array(
		'title'       => __('Instagram Section', 'music-recording-studio'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','music-recording-studio'),
		'priority'    => null,
		'panel'       => 'music_recording_studio_homepage_panel',
	));

	$wp_customize->add_setting('music_recording_studio_instagram_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_instagram_text',array(
		'description' => __('<p>1. More options for Instagram section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for Instagram section.</p>','music-recording-studio'),
		'section'=> 'music_recording_studio_instagram',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('music_recording_studio_instagram_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_instagram_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url(MUSIC_RECORDING_STUDIO_GETSTARTED_URL) ." '>More Info</a>",
		'section'=> 'music_recording_studio_instagram',
		'type'=> 'hidden'
	));

	//Footer Text
	$wp_customize->add_section('music_recording_studio_footer',array(
		'title'	=> esc_html__('Footer Settings','music-recording-studio'),
		'description' => "For more options of footer section </br><a class='go-pro-btn' target='_blank' href='". esc_url(MUSIC_RECORDING_STUDIO_GO_PRO) ." '>GO PRO</a>",
		'panel' => 'music_recording_studio_homepage_panel',
	));	

	$wp_customize->add_setting( 'music_recording_studio_footer_hide_show',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ));
  $wp_customize->add_control( new music_recording_studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_footer_hide_show',array(
    'label' => esc_html__( 'Show / Hide Footer','music-recording-studio' ),
    'section' => 'music_recording_studio_footer'
  )));

	$wp_customize->add_setting('music_recording_studio_footer_background_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'music_recording_studio_footer_background_color', array(
		'label'    => __('Footer Background Color', 'music-recording-studio'),
		'section'  => 'music_recording_studio_footer',
	)));

	$wp_customize->add_setting('music_recording_studio_footer_background_image',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'music_recording_studio_footer_background_image',array(
        'label' => __('Footer Background Image','music-recording-studio'),
        'section' => 'music_recording_studio_footer'
	)));

	// Footer
	$wp_customize->add_setting('music_recording_studio_img_footer',array(
		'default'=> 'scroll',
		'sanitize_callback'	=> 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_img_footer',array(
		'type' => 'select',
		'label'	=> __('Footer Background Attatchment','music-recording-studio'),
		'choices' => array(
            'fixed' => __('fixed','music-recording-studio'),
            'scroll' => __('scroll','music-recording-studio'),
        ),
		'section'=> 'music_recording_studio_footer',
	));

	$wp_customize->add_setting('music_recording_studio_footer_widgets_heading',array(
    'default' => 'Left',
    'transport' => 'refresh',
    'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_footer_widgets_heading',array(
    'type' => 'select',
    'label' => __('Footer Widget Heading','music-recording-studio'),
    'section' => 'music_recording_studio_footer',
    'choices' => array(
    	'Left' => __('Left','music-recording-studio'),
      'Center' => __('Center','music-recording-studio'),
      'Right' => __('Right','music-recording-studio')
    ),
	) );

	$wp_customize->add_setting('music_recording_studio_footer_widgets_content',array(
    'default' => 'Left',
    'transport' => 'refresh',
    'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_footer_widgets_content',array(
    'type' => 'select',
    'label' => __('Footer Widget Content','music-recording-studio'),
    'section' => 'music_recording_studio_footer',
    'choices' => array(
    	'Left' => __('Left','music-recording-studio'),
        'Center' => __('Center','music-recording-studio'),
        'Right' => __('Right','music-recording-studio')
        ),
	) );

	// footer padding
	$wp_customize->add_setting('music_recording_studio_footer_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_footer_padding',array(
		'label'	=> __('Footer Top Bottom Padding','music-recording-studio'),
		'description'	=> __('Enter a value in pixels. Example:20px','music-recording-studio'),
		'input_attrs' => array(
      'placeholder' => __( '10px', 'music-recording-studio' ),
    ),
		'section'=> 'music_recording_studio_footer',
		'type'=> 'text'
	));

  // footer social icon
	$wp_customize->add_setting( 'music_recording_studio_footer_icon',array(
		'default' => false,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
	$wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_footer_icon',array(
		'label' => esc_html__( 'Show / Hide Footer Icon','music-recording-studio' ),
		'section' => 'music_recording_studio_footer'
  )));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('music_recording_studio_footer_text', array( 
		'selector' => '.copyright p', 
		'render_callback' => 'music_recording_studio_Customize_partial_music_recording_studio_footer_text', 
	));

	$wp_customize->add_setting( 'music_recording_studio_copyright_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ));
  $wp_customize->add_control( new music_recording_studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_copyright_hide_show',array(
    'label' => esc_html__( 'Show / Hide Copyright','music-recording-studio' ),
    'section' => 'music_recording_studio_footer'
  )));

	$wp_customize->add_setting('music_recording_studio_copyright_background_color', array(
		'default'           => '#DE3960',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'music_recording_studio_copyright_background_color', array(
		'label'    => __('Copyright Background Color', 'music-recording-studio'),
		'section'  => 'music_recording_studio_footer',
	)));
	
	$wp_customize->add_setting('music_recording_studio_footer_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('music_recording_studio_footer_text',array(
		'label'	=> esc_html__('Copyright Text','music-recording-studio'),
		'input_attrs' => array(
            'placeholder' => esc_html__( 'Copyright 2021, .....', 'music-recording-studio' ),
        ),
		'section'=> 'music_recording_studio_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('music_recording_studio_copyright_alingment',array(
        'default' => 'center',
        'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control(new Music_Recording_Studio_Image_Radio_Control($wp_customize, 'music_recording_studio_copyright_alingment', array(
    'type' => 'select',
    'label' => esc_html__('Copyright Alignment','music-recording-studio'),
    'section' => 'music_recording_studio_footer',
    'settings' => 'music_recording_studio_copyright_alingment',
    'choices' => array(
        'left' => esc_url(get_template_directory_uri()).'/assets/images/copyright1.png',
        'center' => esc_url(get_template_directory_uri()).'/assets/images/copyright2.png',
        'right' => esc_url(get_template_directory_uri()).'/assets/images/copyright3.png'
    ))));

    $wp_customize->add_setting( 'music_recording_studio_hide_show_scroll',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'music_recording_studio_switch_sanitization'
    ));  
    $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_hide_show_scroll',array(
      	'label' => esc_html__( 'Show / Hide Scroll to Top','music-recording-studio' ),
      	'section' => 'music_recording_studio_footer'
    )));

    //Selective Refresh
		$wp_customize->selective_refresh->add_partial('music_recording_studio_scroll_to_top_icon', array( 
		'selector' => '.scrollup i', 
		'render_callback' => 'music_recording_studio_Customize_partial_music_recording_studio_scroll_to_top_icon', 
		));

    $wp_customize->add_setting('music_recording_studio_scroll_to_top_icon',array(
		'default'	=> 'fas fa-long-arrow-alt-up',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new music_recording_studio_Fontawesome_Icon_Chooser(
        $wp_customize,'music_recording_studio_scroll_to_top_icon',array(
		'label'	=> __('Add Scroll to Top Icon','music-recording-studio'),
		'transport' => 'refresh',
		'section'	=> 'music_recording_studio_footer',
		'setting'	=> 'music_recording_studio_scroll_to_top_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('music_recording_studio_scroll_to_top_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_scroll_to_top_font_size',array(
		'label'	=> __('Icon Font Size','music-recording-studio'),
		'description'	=> __('Enter a value in pixels. Example:20px','music-recording-studio'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'music-recording-studio' ),
        ),
		'section'=> 'music_recording_studio_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('music_recording_studio_scroll_to_top_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_scroll_to_top_padding',array(
		'label'	=> __('Icon Top Bottom Padding','music-recording-studio'),
		'description'	=> __('Enter a value in pixels. Example:20px','music-recording-studio'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'music-recording-studio' ),
        ),
		'section'=> 'music_recording_studio_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('music_recording_studio_scroll_to_top_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_scroll_to_top_width',array(
		'label'	=> __('Icon Width','music-recording-studio'),
		'description'	=> __('Enter a value in pixels Example:20px','music-recording-studio'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'music-recording-studio' ),
        ),
		'section'=> 'music_recording_studio_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('music_recording_studio_scroll_to_top_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_scroll_to_top_height',array(
		'label'	=> __('Icon Height','music-recording-studio'),
		'description'	=> __('Enter a value in pixels. Example:20px','music-recording-studio'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'music-recording-studio' ),
        ),
		'section'=> 'music_recording_studio_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'music_recording_studio_scroll_to_top_border_radius', array(
		'default'              => '',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'music_recording_studio_sanitize_number_range'
	) );
	$wp_customize->add_control( 'music_recording_studio_scroll_to_top_border_radius', array(
		'label'       => esc_html__( 'Icon Border Radius','music-recording-studio' ),
		'section'     => 'music_recording_studio_footer',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

  $wp_customize->add_setting('music_recording_studio_scroll_top_alignment',array(
      'default' => 'Right',
      'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control(new Music_Recording_Studio_Image_Radio_Control($wp_customize, 'music_recording_studio_scroll_top_alignment', array(
      'type' => 'select',
      'label' => esc_html__('Scroll To Top','music-recording-studio'),
      'section' => 'music_recording_studio_footer',
      'settings' => 'music_recording_studio_scroll_top_alignment',
      'choices' => array(
          'Left' => esc_url(get_template_directory_uri()).'/assets/images/layout1.png',
          'Center' => esc_url(get_template_directory_uri()).'/assets/images/layout2.png',
          'Right' => esc_url(get_template_directory_uri()).'/assets/images/layout3.png'
  ))));

	//Blog Post Settings
	$wp_customize->add_panel( 'music_recording_studio_blog_post_parent_panel', array(
		'title' => esc_html__( 'Blog Post Settings', 'music-recording-studio' ),
		'panel' => 'music_recording_studio_panel_id',
		'priority' => 20,
	));

	// Post Settings
	$wp_customize->add_section( 'music_recording_studio_post_settings', array(
		'title' => esc_html__( 'Post Settings', 'music-recording-studio' ),
		'panel' => 'music_recording_studio_blog_post_parent_panel',
	));

	//Blog Post Layout
   $wp_customize->add_setting('music_recording_studio_blog_layout_option',array(
        'default' => 'Default',
        'sanitize_callback' => 'music_recording_studio_sanitize_choices'
  ));
  $wp_customize->add_control(new Music_Recording_Studio_Image_Radio_Control($wp_customize, 'music_recording_studio_blog_layout_option', array(
      'type' => 'select',
      'label' => __('Blog Post Layouts','music-recording-studio'),
      'section' => 'music_recording_studio_post_settings',
      'choices' => array(
          'Default' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout1.png',
          'Center' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout2.png',
          'Left' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout3.png',
  ))));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('music_recording_studio_toggle_postdate', array( 
		'selector' => '.post-main-box h2 a', 
		'render_callback' => 'music_recording_studio_Customize_partial_music_recording_studio_toggle_postdate', 
	));

  $wp_customize->add_setting('music_recording_studio_toggle_postdate_icon',array(
		'default'	=> 'fas fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Music_Recording_Studio_Fontawesome_Icon_Chooser(
        $wp_customize,'music_recording_studio_toggle_postdate_icon',array(
		'label'	=> __('Add Post Date Icon','music-recording-studio'),
		'transport' => 'refresh',
		'section'	=> 'music_recording_studio_post_settings',
		'setting'	=> 'music_recording_studio_toggle_postdate_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'music_recording_studio_toggle_blog_postdate',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_toggle_blog_postdate',array(
      'label' => esc_html__( 'Post Date','music-recording-studio' ),
      'section' => 'music_recording_studio_post_settings'
  )));

  $wp_customize->add_setting('music_recording_studio_toggle_author_icon',array(
		'default'	=> 'fas fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Music_Recording_Studio_Fontawesome_Icon_Chooser(
        $wp_customize,'music_recording_studio_toggle_author_icon',array(
		'label'	=> __('Add Author Icon','music-recording-studio'),
		'transport' => 'refresh',
		'section'	=> 'music_recording_studio_post_settings',
		'setting'	=> 'music_recording_studio_toggle_author_icon',
		'type'		=> 'icon'
	)));

  $wp_customize->add_setting( 'music_recording_studio_toggle_blog_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_toggle_blog_author',array(
		'label' => esc_html__( 'Author','music-recording-studio' ),
		'section' => 'music_recording_studio_post_settings'
  )));

  $wp_customize->add_setting('music_recording_studio_toggle_comments_icon',array(
		'default'	=> 'fa fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Music_Recording_Studio_Fontawesome_Icon_Chooser(
        $wp_customize,'music_recording_studio_toggle_comments_icon',array(
		'label'	=> __('Add Comments Icon','music-recording-studio'),
		'transport' => 'refresh',
		'section'	=> 'music_recording_studio_post_settings',
		'setting'	=> 'music_recording_studio_toggle_comments_icon',
		'type'		=> 'icon'
	)));

  $wp_customize->add_setting( 'music_recording_studio_toggle_blog_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_toggle_blog_comments',array(
		'label' => esc_html__( 'Comments','music-recording-studio' ),
		'section' => 'music_recording_studio_post_settings'
  )));

  $wp_customize->add_setting('music_recording_studio_toggle_time_icon',array(
		'default'	=> 'fas fa-clock',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Music_Recording_Studio_Fontawesome_Icon_Chooser(
        $wp_customize,'music_recording_studio_toggle_time_icon',array(
		'label'	=> __('Add Time Icon','music-recording-studio'),
		'transport' => 'refresh',
		'section'	=> 'music_recording_studio_post_settings',
		'setting'	=> 'music_recording_studio_toggle_time_icon',
		'type'		=> 'icon'
	)));

  $wp_customize->add_setting( 'music_recording_studio_toggle_blog_time',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_toggle_blog_time',array(
		'label' => esc_html__( 'Time','music-recording-studio' ),
		'section' => 'music_recording_studio_post_settings'
  )));

  $wp_customize->add_setting( 'music_recording_studio_featured_image_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
	));
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_featured_image_hide_show', array(
		'label' => esc_html__( 'Featured Image','music-recording-studio' ),
		'section' => 'music_recording_studio_post_settings'
  )));

  $wp_customize->add_setting( 'music_recording_studio_featured_image_border_radius_setting', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'music_recording_studio_sanitize_number_range'
	) );
	$wp_customize->add_control( 'music_recording_studio_featured_image_border_radius_setting', array(
		'label'       => esc_html__( 'Featured Image Border Radius','music-recording-studio' ),
		'section'     => 'music_recording_studio_post_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting( 'music_recording_studio_featured_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'music_recording_studio_sanitize_number_range'
	) );
	$wp_customize->add_control( 'music_recording_studio_featured_image_box_shadow', array(
		'label'       => esc_html__( 'Featured Image Box Shadow','music-recording-studio' ),
		'section'     => 'music_recording_studio_post_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Featured Image
	$wp_customize->add_setting('music_recording_studio_blog_post_featured_image_dimension',array(
	       'default' => 'default',
	       'sanitize_callback'	=> 'music_recording_studio_sanitize_choices'
	));
  $wp_customize->add_control('music_recording_studio_blog_post_featured_image_dimension',array(
     'type' => 'select',
     'label'	=> __('Blog Post Featured Image Dimension','music-recording-studio'),
     'section'	=> 'music_recording_studio_post_settings',
     'choices' => array(
          'default' => __('Default','music-recording-studio'),
          'custom' => __('Custom Image Size','music-recording-studio'),
      ),
  ));

	$wp_customize->add_setting('music_recording_studio_blog_post_featured_image_custom_width',array(
			'default'=> '',
			'sanitize_callback'	=> 'sanitize_text_field'
		));
	$wp_customize->add_control('music_recording_studio_blog_post_featured_image_custom_width',array(
			'label'	=> __('Featured Image Custom Width','music-recording-studio'),
			'description'	=> __('Enter a value in pixels. Example:20px','music-recording-studio'),
			'input_attrs' => array(
	    'placeholder' => __( '10px', 'music-recording-studio' ),),
			'section'=> 'music_recording_studio_post_settings',
			'type'=> 'text',
			'active_callback' => 'music_recording_studio_blog_post_featured_image_dimension'
		));

	$wp_customize->add_setting('music_recording_studio_blog_post_featured_image_custom_height',array(
			'default'=> '',
			'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_blog_post_featured_image_custom_height',array(
			'label'	=> __('Featured Image Custom Height','music-recording-studio'),
			'description'	=> __('Enter a value in pixels. Example:20px','music-recording-studio'),
			'input_attrs' => array(
	    'placeholder' => __( '10px', 'music-recording-studio' ),),
			'section'=> 'music_recording_studio_post_settings',
			'type'=> 'text',
			'active_callback' => 'music_recording_studio_blog_post_featured_image_dimension'
	));

  $wp_customize->add_setting( 'music_recording_studio_toggle_tags',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
	));
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_toggle_tags', array(
		'label' => esc_html__( 'Tags','music-recording-studio' ),
		'section' => 'music_recording_studio_post_settings'
  )));

  $wp_customize->add_setting( 'music_recording_studio_excerpt_number', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'music_recording_studio_sanitize_number_range',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'music_recording_studio_excerpt_number', array(
		'label'       => esc_html__( 'Excerpt length','music-recording-studio' ),
		'section'     => 'music_recording_studio_post_settings',
		'type'        => 'range',
		'settings'    => 'music_recording_studio_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
	),
	) );

	$wp_customize->add_setting('music_recording_studio_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','music-recording-studio'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','music-recording-studio'),
		'section'=> 'music_recording_studio_post_settings',
		'type'=> 'text'
	));

    $wp_customize->add_setting('music_recording_studio_excerpt_settings',array(
        'default' => 'Excerpt',
        'transport' => 'refresh',
        'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_excerpt_settings',array(
        'type' => 'select',
        'label' => esc_html__('Post Content','music-recording-studio'),
        'section' => 'music_recording_studio_post_settings',
        'choices' => array(
        	'Content' => esc_html__('Content','music-recording-studio'),
            'Excerpt' => esc_html__('Excerpt','music-recording-studio'),
            'No Content' => esc_html__('No Content','music-recording-studio')
        ),
	) );

  $wp_customize->add_setting('music_recording_studio_blog_page_posts_settings',array(
        'default' => 'Into Blocks',
        'transport' => 'refresh',
        'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_blog_page_posts_settings',array(
        'type' => 'select',
        'label' => __('Display Blog Page posts','music-recording-studio'),
        'section' => 'music_recording_studio_post_settings',
        'choices' => array(
        	'Into Blocks' => __('Into Blocks','music-recording-studio'),
            'Without Blocks' => __('Without Blocks','music-recording-studio')
        ),
	) );

  // Button Settings
	$wp_customize->add_section( 'music_recording_studio_button_settings', array(
		'title' => esc_html__( 'Button Settings', 'music-recording-studio' ),
		'panel' => 'music_recording_studio_blog_post_parent_panel',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('music_recording_studio_button_text', array( 
		'selector' => '.post-main-box .more-btn a', 
		'render_callback' => 'music_recording_studio_Customize_partial_music_recording_studio_button_text', 
	));

    $wp_customize->add_setting('music_recording_studio_button_text',array(
		'default'=> esc_html__('Read More','music-recording-studio'),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_button_text',array(
		'label'	=> esc_html__('Add Button Text','music-recording-studio'),
		'input_attrs' => array(
            'placeholder' => esc_html__( 'Read More', 'music-recording-studio' ),
        ),
		'section'=> 'music_recording_studio_button_settings',
		'type'=> 'text'
	));

	// font size button
	$wp_customize->add_setting('music_recording_studio_button_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_button_font_size',array(
		'label'	=> __('Button Font Size','music-recording-studio'),
		'description'	=> __('Enter a value in pixels. Example:20px','music-recording-studio'),
		'input_attrs' => array(
      'placeholder' => __( '10px', 'music-recording-studio' ),
    ),
    'type'        => 'text',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'music_recording_studio_button_settings',
	));

	$wp_customize->add_setting( 'music_recording_studio_button_border_radius', array(
		'default'              => 5,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'music_recording_studio_sanitize_number_range',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'music_recording_studio_button_border_radius', array(
		'label'       => esc_html__( 'Button Border Radius','music-recording-studio' ),
		'section'     => 'music_recording_studio_button_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

		// button padding
	$wp_customize->add_setting('music_recording_studio_button_top_bottom_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_button_top_bottom_padding',array(
		'label'	=> __('Button Top Bottom Padding','music-recording-studio'),
		'description'	=> __('Enter a value in pixels. Example:20px','music-recording-studio'),
		'input_attrs' => array(
      'placeholder' => __( '10px', 'music-recording-studio' ),
    ),
		'section'=> 'music_recording_studio_button_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('music_recording_studio_button_left_right_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_button_left_right_padding',array(
		'label'	=> __('Button Left Right Padding','music-recording-studio'),
		'description'	=> __('Enter a value in pixels. Example:20px','music-recording-studio'),
		'input_attrs' => array(
      'placeholder' => __( '10px', 'music-recording-studio' ),
    ),
		'section'=> 'music_recording_studio_button_settings',
		'type'=> 'text'
	));

	// text trasform
	$wp_customize->add_setting('music_recording_studio_button_text_transform',array(
		'default'=> 'Capitalize',
		'sanitize_callback'	=> 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_button_text_transform',array(
		'type' => 'radio',
		'label'	=> __('Button Text Transform','music-recording-studio'),
		'choices' => array(
      'Uppercase' => __('Uppercase','music-recording-studio'),
      'Capitalize' => __('Capitalize','music-recording-studio'),
      'Lowercase' => __('Lowercase','music-recording-studio'),
    ),
		'section'=> 'music_recording_studio_button_settings',
	));

	$wp_customize->add_setting('music_recording_studio_button_letter_spacing',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_button_letter_spacing',array(
		'label'	=> __('Button Letter Spacing','music-recording-studio'),
		'description'	=> __('Enter a value in pixels. Example:20px','music-recording-studio'),
		'input_attrs' => array(
      	'placeholder' => __( '10px', 'music-recording-studio' ),
    ),
    	'type'        => 'text',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'music_recording_studio_button_settings',
	));


	// Related Post Settings
	$wp_customize->add_section( 'music_recording_studio_related_posts_settings', array(
		'title' => esc_html__( 'Related Posts Settings', 'music-recording-studio' ),
		'panel' => 'music_recording_studio_blog_post_parent_panel',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('music_recording_studio_related_post_title', array( 
		'selector' => '.related-post h3', 
		'render_callback' => 'music_recording_studio_Customize_partial_music_recording_studio_related_post_title', 
	));

    $wp_customize->add_setting( 'music_recording_studio_related_post',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
    ) );
    $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_related_post',array(
		'label' => esc_html__( 'Related Post','music-recording-studio' ),
		'section' => 'music_recording_studio_related_posts_settings'
    )));

    $wp_customize->add_setting('music_recording_studio_related_post_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_related_post_title',array(
		'label'	=> esc_html__('Add Related Post Title','music-recording-studio'),
		'input_attrs' => array(
            'placeholder' => esc_html__( 'Related Post', 'music-recording-studio' ),
        ),
		'section'=> 'music_recording_studio_related_posts_settings',
		'type'=> 'text'
	));

   	$wp_customize->add_setting('music_recording_studio_related_posts_count',array(
		'default'=> 3,
		'sanitize_callback'	=> 'music_recording_studio_sanitize_number_absint'
	));
	$wp_customize->add_control('music_recording_studio_related_posts_count',array(
		'label'	=> esc_html__('Add Related Post Count','music-recording-studio'),
		'input_attrs' => array(
            'placeholder' => esc_html__( '3', 'music-recording-studio' ),
        ),
		'section'=> 'music_recording_studio_related_posts_settings',
		'type'=> 'number'
	));

	$wp_customize->add_setting( 'music_recording_studio_related_posts_excerpt_number', array(
		'default'              => 20,
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'music_recording_studio_sanitize_number_range'
	) );
	$wp_customize->add_control( 'music_recording_studio_related_posts_excerpt_number', array(
		'label'       => esc_html__( 'Related Posts Excerpt length','music-recording-studio' ),
		'section'     => 'music_recording_studio_related_posts_settings',
		'type'        => 'range',
		'settings'    => 'music_recording_studio_related_posts_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	// Single Posts Settings
	$wp_customize->add_section( 'music_recording_studio_single_blog_settings', array(
		'title' => __( 'Single Post Settings', 'music-recording-studio' ),
		'panel' => 'music_recording_studio_blog_post_parent_panel',
	));

  $wp_customize->add_setting( 'music_recording_studio_toggle_postdate',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'music_recording_studio_switch_sanitization'
    ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_toggle_postdate',array(
    'label' => esc_html__( 'Date','music-recording-studio' ),
    'section' => 'music_recording_studio_single_blog_settings'
  )));

  $wp_customize->add_setting( 'music_recording_studio_toggle_author',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_toggle_author',array(
    'label' => esc_html__( 'Author','music-recording-studio' ),
    'section' => 'music_recording_studio_single_blog_settings'
  )));

  $wp_customize->add_setting( 'music_recording_studio_toggle_comments',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_toggle_comments',array(
    'label' => esc_html__( 'Comments','music-recording-studio' ),
    'section' => 'music_recording_studio_single_blog_settings'
  )));

  $wp_customize->add_setting( 'music_recording_studio_toggle_time',array(
    'default' => 1,
    'transport' => 'refresh',
    'sanitize_callback' => 'music_recording_studio_switch_sanitization'
    ) );
    $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_toggle_time',array(
    'label' => esc_html__( 'Time','music-recording-studio' ),
    'section' => 'music_recording_studio_single_blog_settings'
    )));

	$wp_customize->add_setting( 'music_recording_studio_single_post_breadcrumb',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_single_post_breadcrumb',array(
		'label' => esc_html__( 'Single Post Breadcrumb','music-recording-studio' ),
		'section' => 'music_recording_studio_single_blog_settings'
  )));

  $wp_customize->add_setting( 'music_recording_studio_single_post_category',array(
		'default' => true,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_single_post_category',array(
		'label' => esc_html__( 'Single Post Category','music-recording-studio' ),
		'section' => 'music_recording_studio_single_blog_settings'
  )));

	$wp_customize->add_setting( 'music_recording_studio_toggle_tags',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
	));
  $wp_customize->add_control( new music_recording_studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_toggle_tags', array(
		'label' => esc_html__( 'Tags','music-recording-studio' ),
		'section' => 'music_recording_studio_single_blog_settings'
  )));

	$wp_customize->add_setting('music_recording_studio_single_post_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_single_post_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','music-recording-studio'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','music-recording-studio'),
		'section'=> 'music_recording_studio_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('music_recording_studio_single_blog_comment_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('music_recording_studio_single_blog_comment_title',array(
		'label'	=> __('Add Comment Title','music-recording-studio'),
		'input_attrs' => array(
      'placeholder' => __( 'Leave a Reply', 'music-recording-studio' ),
  	),
		'section'=> 'music_recording_studio_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('music_recording_studio_single_blog_comment_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('music_recording_studio_single_blog_comment_button_text',array(
		'label'	=> __('Add Comment Button Text','music-recording-studio'),
		'input_attrs' => array(
            'placeholder' => __( 'Post Comment', 'music-recording-studio' ),
        ),
		'section'=> 'music_recording_studio_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('music_recording_studio_single_blog_comment_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('music_recording_studio_single_blog_comment_width',array(
		'label'	=> __('Comment Form Width','music-recording-studio'),
		'description'	=> __('Enter a value in %. Example:50%','music-recording-studio'),
		'input_attrs' => array(
            'placeholder' => __( '100%', 'music-recording-studio' ),
        ),
		'section'=> 'music_recording_studio_single_blog_settings',
		'type'=> 'text'
	));

	  // Grid layout setting
	$wp_customize->add_section( 'music_recording_studio_grid_layout_settings', array(
		'title' => __( 'Grid Layout Settings', 'music-recording-studio' ),
		'panel' => 'music_recording_studio_blog_post_parent_panel',
	));

	$wp_customize->add_setting( 'music_recording_studio_grid_postdate',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_grid_postdate',array(
      'label' => esc_html__( 'Post Date','music-recording-studio' ),
      'section' => 'music_recording_studio_grid_layout_settings'
  )));

  $wp_customize->add_setting( 'music_recording_studio_grid_author',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_grid_author',array(
	'label' => esc_html__( 'Author','music-recording-studio' ),
	'section' => 'music_recording_studio_grid_layout_settings'
  )));

  $wp_customize->add_setting( 'music_recording_studio_grid_comments',array(
	'default' => 1,
	'transport' => 'refresh',
	'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_grid_comments',array(
	'label' => esc_html__( 'Comments','music-recording-studio' ),
	'section' => 'music_recording_studio_grid_layout_settings'
  )));

	//Others Settings
	$wp_customize->add_panel( 'music_recording_studio_others_panel', array(
		'title' => esc_html__( 'Others Settings', 'music-recording-studio' ),
		'panel' => 'music_recording_studio_panel_id',
		'priority' => 20,
	));

	// Layout
	$wp_customize->add_section( 'music_recording_studio_left_right', array(
    	'title' => esc_html__( 'General Settings', 'music-recording-studio' ),
		'panel' => 'music_recording_studio_others_panel'
	) );

	$wp_customize->add_setting('music_recording_studio_width_option',array(
        'default' => 'Full Width',
        'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control(new Music_Recording_Studio_Image_Radio_Control($wp_customize, 'music_recording_studio_width_option', array(
        'type' => 'select',
        'label' => esc_html__('Width Layouts','music-recording-studio'),
        'description' => esc_html__('Here you can change the width layout of Website.','music-recording-studio'),
        'section' => 'music_recording_studio_left_right',
        'choices' => array(
            'Full Width' => esc_url(get_template_directory_uri()).'/assets/images/full-width.png',
            'Wide Width' => esc_url(get_template_directory_uri()).'/assets/images/wide-width.png',
            'Boxed' => esc_url(get_template_directory_uri()).'/assets/images/boxed-width.png',
    ))));

	$wp_customize->add_setting('music_recording_studio_theme_options',array(
        'default' => 'Right Sidebar',
        'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_theme_options',array(
        'type' => 'select',
        'label' => esc_html__('Post Sidebar Layout','music-recording-studio'),
        'description' => esc_html__('Here you can change the sidebar layout for posts. ','music-recording-studio'),
        'section' => 'music_recording_studio_left_right',
        'choices' => array(
            'Left Sidebar' => esc_html__('Left Sidebar','music-recording-studio'),
            'Right Sidebar' => esc_html__('Right Sidebar','music-recording-studio'),
            'One Column' => esc_html__('One Column','music-recording-studio'),
            'Grid Layout' => esc_html__('Grid Layout','music-recording-studio')
        ),
	) );

	$wp_customize->add_setting('music_recording_studio_page_layout',array(
        'default' => 'One_Column',
        'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_page_layout',array(
        'type' => 'select',
        'label' => esc_html__('Page Sidebar Layout','music-recording-studio'),
        'description' => esc_html__('Here you can change the sidebar layout for pages. ','music-recording-studio'),
        'section' => 'music_recording_studio_left_right',
        'choices' => array(
            'Left_Sidebar' => esc_html__('Left Sidebar','music-recording-studio'),
            'Right_Sidebar' => esc_html__('Right Sidebar','music-recording-studio'),
            'One_Column' => esc_html__('One Column','music-recording-studio')
        ),
	) );

	// Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'music_recording_studio_woocommerce_shop_page_sidebar', array( 'selector' => '.post-type-archive-product #sidebar', 
		'render_callback' => 'music_recording_studio_customize_partial_music_recording_studio_woocommerce_shop_page_sidebar', ) );

    // Woocommerce Shop Page Sidebar
	$wp_customize->add_setting( 'music_recording_studio_woocommerce_shop_page_sidebar',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_woocommerce_shop_page_sidebar',array(
	'label' => esc_html__( 'Shop Page Sidebar','music-recording-studio' ),
	'section' => 'music_recording_studio_left_right'
  )));

  $wp_customize->add_setting('music_recording_studio_shop_page_layout',array(
        'default' => 'Right Sidebar',
        'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_shop_page_layout',array(
        'type' => 'select',
        'label' => __('Shop Page Sidebar Layout','music-recording-studio'),
        'section' => 'music_recording_studio_left_right',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','music-recording-studio'),
            'Right Sidebar' => __('Right Sidebar','music-recording-studio'),
        ),
	) );

  // Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'music_recording_studio_woocommerce_single_product_page_sidebar', array( 'selector' => '.single-product #sidebar', 
		'render_callback' => 'music_recording_studio_customize_partial_music_recording_studio_woocommerce_single_product_page_sidebar', ) );

    //Woocommerce Single Product page Sidebar
	$wp_customize->add_setting( 'music_recording_studio_woocommerce_single_product_page_sidebar',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
    ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_woocommerce_single_product_page_sidebar',array(
		'label' => esc_html__( 'Single Product Sidebar','music-recording-studio' ),
		'section' => 'music_recording_studio_left_right'
    )));

  $wp_customize->add_setting('music_recording_studio_single_product_layout',array(
      'default' => 'Right Sidebar',
      'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_single_product_layout',array(
      'type' => 'select',
      'label' => __('Shop Page Sidebar Layout','music-recording-studio'),
      'section' => 'music_recording_studio_left_right',
      'choices' => array(
          'Left Sidebar' => __('Left Sidebar','music-recording-studio'),
          'Right Sidebar' => __('Right Sidebar','music-recording-studio'),
        ),
	) );

	$wp_customize->add_setting( 'music_recording_studio_single_page_breadcrumb',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
    ) );
    $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_single_page_breadcrumb',array(
		'label' => esc_html__( 'Single Page Breadcrumb','music-recording-studio' ),
		'section' => 'music_recording_studio_left_right'
    )));

    //Wow Animation
	$wp_customize->add_setting( 'music_recording_studio_animation',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'music_recording_studio_switch_sanitization'
    ));
    $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_animation',array(
        'label' => esc_html__( 'Animation ','music-recording-studio' ),
        'description' => __('Here you can disable overall site animation effect','music-recording-studio'),
        'section' => 'music_recording_studio_left_right'
    )));

    $wp_customize->add_setting('music_recording_studio_reset_all_settings',array(
      'sanitize_callback'	=> 'sanitize_text_field',
   	));
   	$wp_customize->add_control(new Music_Recording_Studio_Reset_Custom_Control($wp_customize, 'music_recording_studio_reset_all_settings',array(
      'type' => 'reset_control',
      'label' => __('Reset All Settings', 'music-recording-studio'),
      'description' => 'music_recording_studio_reset_all_settings',
      'section' => 'music_recording_studio_left_right'
   	)));

    // Pre-Loader
	$wp_customize->add_setting( 'music_recording_studio_loader_enable',array(
        'default' => 0,
        'transport' => 'refresh',
        'sanitize_callback' => 'music_recording_studio_switch_sanitization'
    ) );
    $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_loader_enable',array(
        'label' => esc_html__( 'Pre-Loader','music-recording-studio' ),
        'section' => 'music_recording_studio_left_right'
    )));

	$wp_customize->add_setting('music_recording_studio_preloader_bg_color', array(
		'default'           => '#DE3960',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'music_recording_studio_preloader_bg_color', array(
		'label'    => __('Pre-Loader Background Color', 'music-recording-studio'),
		'section'  => 'music_recording_studio_left_right',
	)));

	$wp_customize->add_setting('music_recording_studio_preloader_border_color', array(
		'default'           => '#ffffff',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'music_recording_studio_preloader_border_color', array(
		'label'    => __('Pre-Loader Border Color', 'music-recording-studio'),
		'section'  => 'music_recording_studio_left_right',
	)));

	//Responsive Media Settings
	$wp_customize->add_section('music_recording_studio_responsive_media',array(
		'title'	=> esc_html__('Responsive Media','music-recording-studio'),
		'panel' => 'music_recording_studio_others_panel',
	));

  $wp_customize->add_setting( 'music_recording_studio_resp_slider_hide_show',array(
    	'default' => 0,
   	'transport' => 'refresh',
    	'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ));  
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_resp_slider_hide_show',array(
    	'label' => esc_html__( 'Show / Hide Slider','music-recording-studio' ),
    	'section' => 'music_recording_studio_responsive_media'
  )));

  $wp_customize->add_setting( 'music_recording_studio_sidebar_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ));  
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_sidebar_hide_show',array(
    	'label' => esc_html__( 'Show / Hide Sidebar','music-recording-studio' ),
    	'section' => 'music_recording_studio_responsive_media'
  )));

  $wp_customize->add_setting( 'music_recording_studio_resp_scroll_top_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ));  
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_resp_scroll_top_hide_show',array(
      	'label' => esc_html__( 'Show / Hide Scroll To Top','music-recording-studio' ),
      	'section' => 'music_recording_studio_responsive_media'
    )));

 	$wp_customize->add_setting('music_recording_studio_resp_menu_toggle_btn_bg_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'music_recording_studio_resp_menu_toggle_btn_bg_color', array(
		'label'    => __('Toggle Button Bg Color', 'music-recording-studio'),
		'section'  => 'music_recording_studio_responsive_media',
	)));

  //Woocommerce settings
	$wp_customize->add_section('music_recording_studio_woocommerce_section', array(
		'title'    => __('WooCommerce Layout', 'music-recording-studio'),
		'priority' => null,
		'panel'    => 'woocommerce',
	));

	// Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'music_recording_studio_woocommerce_shop_page_sidebar', array( 'selector' => '.post-type-archive-product #sidebar', 
		'render_callback' => 'music_recording_studio_customize_partial_music_recording_studio_woocommerce_shop_page_sidebar', ) );

    // Woocommerce Shop Page Sidebar
	$wp_customize->add_setting( 'music_recording_studio_woocommerce_shop_page_sidebar',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_woocommerce_shop_page_sidebar',array(
		'label' => esc_html__( 'Shop Page Sidebar','music-recording-studio' ),
		'section' => 'music_recording_studio_woocommerce_section'
  )));

  $wp_customize->add_setting('music_recording_studio_shop_page_layout',array(
    'default' => 'Right Sidebar',
    'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_shop_page_layout',array(
    'type' => 'select',
    'label' => __('Shop Page Sidebar Layout','music-recording-studio'),
    'section' => 'music_recording_studio_woocommerce_section',
    'choices' => array(
        'Left Sidebar' => __('Left Sidebar','music-recording-studio'),
        'Right Sidebar' => __('Right Sidebar','music-recording-studio'),
        ),
	) );

  // Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'music_recording_studio_woocommerce_single_product_page_sidebar', array( 'selector' => '.single-product #sidebar', 
		'render_callback' => 'music_recording_studio_customize_partial_music_recording_studio_woocommerce_single_product_page_sidebar', ) );

    //Woocommerce Single Product page Sidebar
	$wp_customize->add_setting( 'music_recording_studio_woocommerce_single_product_page_sidebar',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'music_recording_studio_switch_sanitization'
    ) );
  $wp_customize->add_control( new Music_Recording_Studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_woocommerce_single_product_page_sidebar',array(
		'label' => esc_html__( 'Single Product Sidebar','music-recording-studio' ),
		'section' => 'music_recording_studio_woocommerce_section'
    )));

  $wp_customize->add_setting('music_recording_studio_single_product_layout',array(
      'default' => 'Right Sidebar',
      'sanitize_callback' => 'music_recording_studio_sanitize_choices'
	));
	$wp_customize->add_control('music_recording_studio_single_product_layout',array(
      'type' => 'select',
      'label' => __('Shop Page Sidebar Layout','music-recording-studio'),
      'section' => 'music_recording_studio_woocommerce_section',
      'choices' => array(
          'Left Sidebar' => __('Left Sidebar','music-recording-studio'),
          'Right Sidebar' => __('Right Sidebar','music-recording-studio'),
        ),
	) );

	// Related Product
  $wp_customize->add_setting( 'music_recording_studio_related_product_show_hide',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'music_recording_studio_switch_sanitization'
  ) );
  $wp_customize->add_control( new music_recording_studio_Toggle_Switch_Custom_Control( $wp_customize, 'music_recording_studio_related_product_show_hide',array(
      'label' => esc_html__( 'Related product','music-recording-studio' ),
      'section' => 'music_recording_studio_woocommerce_section'
  )));

}

add_action( 'customize_register', 'music_recording_studio_customize_register' );

load_template( trailingslashit( get_template_directory() ) . '/inc/logo/logo-resizer.php' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class Music_Recording_Studio_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	*/
	public function sections( $manager ) {

		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'Music_Recording_Studio_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section( new Music_Recording_Studio_Customize_Section_Pro( $manager,'music_recording_studio_go_pro', array(
			'priority'   => 1,
			'title'    => esc_html__( 'RECORDING STUDIO PRO', 'music-recording-studio' ),
			'pro_text' => esc_html__( 'UPGRADE PRO', 'music-recording-studio' ),
			'pro_url'  => esc_url('https://www.vwthemes.com/themes/recording-studio-wordpress-theme/'),
		) )	);

		// Register sections.
		$manager->add_section(new Music_Recording_Studio_Customize_Section_Pro($manager,'music_recording_studio_get_started_link',array(
			'priority'   => 1,
			'title'    => esc_html__( 'DOCUMENTATION', 'music-recording-studio' ),
			'pro_text' => esc_html__( 'DOCS', 'music-recording-studio' ),
			'pro_url'  => esc_url('https://www.vwthemesdemo.com/docs/free-music-recording-studio/'),
		)));
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'music-recording-studio-customize-controls', trailingslashit( get_template_directory_uri() ) . '/assets/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'music-recording-studio-customize-controls', trailingslashit( get_template_directory_uri() ) . '/assets/css/customize-controls.css' );

		wp_localize_script(
		'music-recording-studio-customize-controls',
		'music_recording_studio_customizer_params',
		array(
			'ajaxurl' =>	admin_url( 'admin-ajax.php' )
		));
	}
}

// Doing this customizer thang!
Music_Recording_Studio_Customize::get_instance();