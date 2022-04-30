<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK SETTINGS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$settings = array(
	'menu_title'      => __( 'Settings', 'logo-carousel-pro' ),
	'menu_parent'     => 'edit.php?post_type=sp_logo_carousel',
	'menu_type'       => 'submenu', // menu, submenu, options, theme, etc.
	'menu_slug'       => 'lcpro_settings',
	'ajax_save'       => true,
	'show_reset_all'  => false,
	'framework_title' => __( 'Logo Carousel Pro', 'logo-carousel-pro' ),
);

// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$options = array();

// ----------------------------------------
// a option section for options overview  -
// ----------------------------------------
$options[] = array(
	'name'   => 'advanced_settings',
	'title'  => __( 'Advanced Settings', 'logo-carousel-pro' ),
	'icon'   => 'fa fa-cogs',

	// begin: fields.
	'fields' => array(
		array(
			'id'         => 'lcpro_data_remove',
			'type'       => 'checkbox',
			'title'      => __( 'Remove Data when Delete', 'logo-carousel-pro' ),
			'desc'       => __( 'Check this box if you would like Logo Carousel Pro to completely remove all of its data when the plugin is deleted.', 'logo-carousel-pro' ),
			'default'    => false,
		),
		array(
			'id'      => 'lcpro_google_fonts',
			'type'    => 'switcher',
			'title'   => __( 'Google Fonts', 'logo-carousel-pro' ),
			'desc'    => __( 'On/off the switch to enqueue/dequeue google fonts.', 'logo-carousel-pro' ),
			'default' => true,
		),
		array(
			'id'         => 'lcpro_enqueue_css_heading',
			'type'       => 'subheading',
			'content'    => __( 'CSS Enqueue/Dequeue', 'logo-carousel-pro' ),
		),
		array(
			'id'         => 'lcpro_fontawesome_css',
			'type'       => 'switcher',
			'title'      => __( 'FontAwesome CSS', 'logo-carousel-pro' ),
			'desc'       => __( 'On/off the switch to enqueue/dequeue fontawesome css.', 'logo-carousel-pro' ),
			'default'    => true,
		),
		array(
			'id'         => 'lcpro_slick_css',
			'type'       => 'switcher',
			'title'      => __( 'Slick CSS', 'logo-carousel-pro' ),
			'desc'       => __( 'On/off the switch to enqueue/dequeue slick css.', 'logo-carousel-pro' ),
			'default'    => true,
		),
		array(
			'id'         => 'lcpro_bxslider_css',
			'type'       => 'switcher',
			'title'      => __( 'BXSlider CSS', 'logo-carousel-pro' ),
			'desc'       => __( 'On/off the switch to enqueue/dequeue bxslider css.', 'logo-carousel-pro' ),
			'default'    => true,
		),
		array(
			'id'         => 'lcpro_tooltipster_css',
			'type'       => 'switcher',
			'title'      => __( 'Tooltipster CSS', 'logo-carousel-pro' ),
			'desc'       => __( 'On/off the switch to enqueue/dequeue tooltipster css.', 'logo-carousel-pro' ),
			'default'    => true,
		),
		array(
			'id'         => 'lcpro_enqueue_js_heading',
			'type'       => 'subheading',
			'content'    => __( 'JS Enqueue/Dequeue', 'logo-carousel-pro' ),
		),
		array(
			'id'         => 'lcpro_remodal_js',
			'type'       => 'switcher',
			'title'      => __( 'Remodal JS', 'logo-carousel-pro' ),
			'desc'       => __( 'On/off the switch to enqueue/dequeue remodal js.', 'logo-carousel-pro' ),
			'default'    => true,
		),
		array(
			'id'         => 'lcpro_bxslider_js',
			'type'       => 'switcher',
			'title'      => __( 'BXSlider JS', 'logo-carousel-pro' ),
			'desc'       => __( 'On/off the switch to enqueue/dequeue bxslider js.', 'logo-carousel-pro' ),
			'default'    => true,
		),
		array(
			'id'         => 'lcpro_slick_js',
			'type'       => 'switcher',
			'title'      => __( 'Slick JS', 'logo-carousel-pro' ),
			'desc'       => __( 'On/off the switch to enqueue/dequeue slick js.', 'logo-carousel-pro' ),
			'default'    => true,
		),
		array(
			'id'         => 'lcpro_tooltipster_js',
			'type'       => 'switcher',
			'title'      => __( 'Tooltipster JS', 'logo-carousel-pro' ),
			'desc'       => __( 'On/off the switch to enqueue/dequeue tooltipster js.', 'logo-carousel-pro' ),
			'default'    => true,
		),
		array(
			'id'         => 'lcpro_isotope_js',
			'type'       => 'switcher',
			'title'      => __( 'Isotope JS', 'logo-carousel-pro' ),
			'desc'       => __( 'On/off the switch to enqueue/dequeue isotope js.', 'logo-carousel-pro' ),
			'default'    => true,
		),

	), // end: fields.
);

// ------------------------------
// Custom CSS                   -
// ------------------------------
$options[] = array(
	'name'   => 'custom_css_section',
	'title'  => __( 'Custom CSS', 'logo-carousel-pro' ),
	'icon'   => 'fa fa-css3',
	'fields' => array(

		array(
			'id'    => 'lcpro_custom_css',
			'type'  => 'textarea',
			'title' => __( 'Custom CSS', 'logo-carousel-pro' ),
			'desc'  => __( 'Type your custom css.', 'logo-carousel-pro' ),
		),
	),
);

SP_LCPRO_Framework::instance( $settings, $options );
