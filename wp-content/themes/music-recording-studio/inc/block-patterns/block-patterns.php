<?php
/**
 *  Music Recording Studio: Block Patterns
 *
 * @package  Music Recording Studio
 * @since   1.0.0
 */

/**
 * Register Block Pattern Category.
 */
if ( function_exists( 'register_block_pattern_category' ) ) {

	register_block_pattern_category(
		'music-recording-studio',
		array( 'label' => __( 'Music Recording Studio', 'music-recording-studio' ) )
	);
}

/**
 * Register Block Patterns.
 */
if ( function_exists( 'register_block_pattern' ) ) {
	register_block_pattern(
		'music-recording-studio/banner-section',
		array(
			'title'      => __( 'Banner Section', 'music-recording-studio' ),
			'categories' => array( 'music-recording-studio' ),
			'content'    => "<!-- wp:columns {\"className\":\"banner-section\"} -->\n<div class=\"wp-block-columns banner-section\"><!-- wp:column {\"className\":\"banner-section1\"} -->\n<div class=\"wp-block-column banner-section1\"><!-- wp:image {\"align\":\"right\",\"id\":1619,\"width\":407,\"height\":272,\"sizeSlug\":\"full\",\"linkDestination\":\"none\",\"className\":\"banner-section-img \"} -->\n<div class=\"wp-block-image banner-section-img\"><figure class=\"alignright size-full is-resized\"><img src=\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/banner.png\" alt=\"\" class=\"wp-image-1619\" width=\"407\" height=\"272\"/></figure></div>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"className\":\"banner-section2 \"} -->\n<div class=\"wp-block-column banner-section2\"><!-- wp:heading {\"level\":1,\"className\":\"mb-4\"} -->\n<h1 class=\"mb-4\">MUSIC WORDPRESS THEME</h1>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard </p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->",
		)
	);

	register_block_pattern(
		'music-recording-studio/services-section',
		array(
			'title'      => __( 'Services Section', 'music-recording-studio' ),
			'categories' => array( 'music-recording-studio' ),
			'content'    => "<!-- wp:group {\"className\":\"services-section-1\"} -->\n<div class=\"wp-block-group services-section-1\"><!-- wp:paragraph {\"align\":\"center\",\"style\":{\"typography\":{\"fontSize\":\"15px\"},\"color\":{\"text\":\"#de3960\"}},\"className\":\"services-section-p\"} -->\n<p class=\"has-text-align-center services-section-p has-text-color\" style=\"color:#de3960;font-size:15px\">What we do</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"textAlign\":\"center\",\"style\":{\"typography\":{\"fontSize\":\"25px\"}},\"className\":\"services-section-heading \"} -->\n<h2 class=\"has-text-align-center services-section-heading\" style=\"font-size:25px\">Studio Services</h2>\n<!-- /wp:heading -->\n\n<!-- wp:columns {\"className\":\"services-section\"} -->\n<div class=\"wp-block-columns services-section\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"align\":\"center\",\"id\":1628,\"width\":370,\"height\":480,\"sizeSlug\":\"full\",\"linkDestination\":\"none\",\"className\":\"services-section img1\"} -->\n<div class=\"wp-block-image services-section img1\"><figure class=\"aligncenter size-full is-resized\"><img src=\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/services-1.png\" alt=\"\" class=\"wp-image-1628\" width=\"370\" height=\"480\"/></figure></div>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"textAlign\":\"center\",\"level\":3,\"style\":{\"typography\":{\"fontSize\":\"18px\"}}} -->\n<h3 class=\"has-text-align-center\" style=\"font-size:18px\">Audio Editing</h3>\n<!-- /wp:heading --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"align\":\"center\",\"id\":1629,\"width\":370,\"height\":480,\"sizeSlug\":\"full\",\"linkDestination\":\"none\",\"className\":\"services-section img2\"} -->\n<div class=\"wp-block-image services-section img2\"><figure class=\"aligncenter size-full is-resized\"><img src=\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/services-2.png\" alt=\"\" class=\"wp-image-1629\" width=\"370\" height=\"480\" title=\"services-section img2\"/></figure></div>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"textAlign\":\"center\",\"level\":3,\"style\":{\"typography\":{\"fontSize\":\"18px\"}}} -->\n<h3 class=\"has-text-align-center\" style=\"font-size:18px\">Sound Mixing</h3>\n<!-- /wp:heading --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"align\":\"center\",\"id\":1630,\"width\":370,\"height\":480,\"sizeSlug\":\"full\",\"linkDestination\":\"none\",\"className\":\"services-section img3\"} -->\n<div class=\"wp-block-image services-section img3\"><figure class=\"aligncenter size-full is-resized\"><img src=\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/services-3.png\" alt=\"\" class=\"wp-image-1630\" width=\"370\" height=\"480\"/></figure></div>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"textAlign\":\"center\",\"level\":3,\"style\":{\"typography\":{\"fontSize\":\"18px\"}}} -->\n<h3 class=\"has-text-align-center\" style=\"font-size:18px\">Voice Recording</h3>\n<!-- /wp:heading --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:group -->",
		)
	);
}