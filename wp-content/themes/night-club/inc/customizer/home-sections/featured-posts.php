<?php
/**
 * Featured Posts Options.
 *
 * @package Night Club
 */

$default = night_club_get_default_theme_options();

// Featured Posts Section
$wp_customize->add_section( 'section_featured_posts',
	array(
		'title'      => __( 'Latest Posts Section', 'night-club' ),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'home_page_panel',
	)
);

// Enable Section
$wp_customize->add_setting('theme_options[enable_featured_posts_section]', 
	array(
	'default' 			=> $default['enable_featured_posts_section'],
	'type'              => 'theme_mod',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'night_club_sanitize_checkbox'
	)
);

$wp_customize->add_control('theme_options[enable_featured_posts_section]', 
	array(		
	'label' 	=> __('Enable Section', 'night-club'),
	'section' 	=> 'section_featured_posts',
	'settings'  => 'theme_options[enable_featured_posts_section]',
	'type' 		=> 'checkbox',	
	)
);

// Section Title
$wp_customize->add_setting('theme_options[featured_posts_section_title]', 
	array(
	'default'           => $default['featured_posts_section_title'],
	'type'              => 'theme_mod',
	'capability'        => 'edit_theme_options',	
	'sanitize_callback' => 'sanitize_text_field'
	)
);

$wp_customize->add_control('theme_options[featured_posts_section_title]', 
	array(
	'label'       => __('Section Title', 'night-club'),
	'section'     => 'section_featured_posts',   
	'settings'    => 'theme_options[featured_posts_section_title]',	
	'active_callback' => 'night_club_featured_posts_active',		
	'type'        => 'text'
	)
);

// Featured Posts Number.
$wp_customize->add_setting( 'theme_options[featured_posts_number]',
	array(
		'default'           => $default['featured_posts_number'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'night_club_sanitize_number_range',
		)
);
$wp_customize->add_control( 'theme_options[featured_posts_number]',
	array(
		'label'       		=> __('Items (Max: 6)', 'night-club'),
		'section'     		=> 'section_featured_posts',
		'active_callback' 	=> 'night_club_featured_posts_active',		
		'type'        		=> 'number',
		'input_attrs' 		=> array( 
			'min' => 1, 
			'max' => 6, 
			'step' => 1, 
			'style' => 'width: 115px;' 
		),
	)
);

// Column
$wp_customize->add_setting('theme_options[featured_posts_column]', 
	array(
	'default' 			=> $default['featured_posts_column'],
	'type'              => 'theme_mod',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'night_club_sanitize_select'
	)
);

$wp_customize->add_control(new night_club_Image_Radio_Control($wp_customize, 'theme_options[featured_posts_column]', 
	array(		
	'label' 	=> __('Select Column', 'night-club'),
	'section' 	=> 'section_featured_posts',
	'settings'  => 'theme_options[featured_posts_column]',
	'type' 		=> 'radio-image',
	'active_callback' => 'night_club_featured_posts_active',
	'choices' 	=> array(		
		'col-1' 	=> esc_url(get_template_directory_uri()) . '/assets/images/column-1.jpg',						
		'col-2' 	=> esc_url(get_template_directory_uri()) . '/assets/images/column-2.jpg',
		'col-3' 	=> esc_url(get_template_directory_uri()) . '/assets/images/column-3.jpg',
		'col-4' 	=> esc_url(get_template_directory_uri()) . '/assets/images/column-4.jpg',
		'col-5' 	=> esc_url(get_template_directory_uri()) . '/assets/images/column-5.jpg',
		'col-6' 	=> esc_url(get_template_directory_uri()) . '/assets/images/column-6.jpg',
		),	
	))
);

$featured_posts_number = night_club_get_option( 'featured_posts_number' );

// Setting Category.
$wp_customize->add_setting( 'theme_options[featured_posts_category]',
	array(
	'default'           => $default['featured_posts_category'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control(
	new night_club_Dropdown_Taxonomies_Control( $wp_customize, 'theme_options[featured_posts_category]',
		array(
		'label'    => __( 'Select Category', 'night-club' ),
		'section'  => 'section_featured_posts',
		'settings' => 'theme_options[featured_posts_category]',	
		'active_callback' => 'night_club_featured_posts_active',		
		)
	)
);