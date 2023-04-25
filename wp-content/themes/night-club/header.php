<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Night Club
 */
/**
* Hook - night_club_action_doctype.
*
* @hooked night_club_doctype -  10
*/
do_action( 'night_club_action_doctype' );
?>
<head>
<?php
/**
* Hook - night_club_action_head.
*
* @hooked night_club_head -  10
*/
do_action( 'night_club_action_head' );
?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>

<?php

/**
* Hook - night_club_action_before.
*
* @hooked night_club_page_start - 10
*/
do_action( 'night_club_action_before' );

/**
*
* @hooked night_club_header_start - 10
*/
do_action( 'night_club_action_before_header' );

/**
*
*@hooked night_club_site_branding - 10
*@hooked night_club_header_end - 15 
*/
do_action('night_club_action_header');

/**
*
* @hooked night_club_content_start - 10
*/
do_action( 'night_club_action_before_content' );

/**
 * Banner start
 * 
 * @hooked night_club_banner_header - 10
*/
do_action( 'night_club_banner_header' );  
