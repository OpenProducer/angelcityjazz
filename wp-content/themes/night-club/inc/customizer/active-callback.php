<?php
/**
 * Active callback functions.
 *
 * @package Night Club
 */

function night_club_featured_slider_active( $control ) {
    if( $control->manager->get_setting( 'theme_options[enable_featured_slider_section]' )->value() == true ) {
        return true;
    }else{
        return false;
    }
}

function night_club_featured_slider_page( $control ) {
    $content_type = $control->manager->get_setting( 'theme_options[featured_slider_content_type]' )->value();
    return ( night_club_featured_slider_active( $control ) && ( 'featured_slider_page' == $content_type ) );
}

function night_club_featured_slider_post( $control ) {
    $content_type = $control->manager->get_setting( 'theme_options[featured_slider_content_type]' )->value();
    return ( night_club_featured_slider_active( $control ) && ( 'featured_slider_post' == $content_type ) );
}

function night_club_featured_services_active( $control ) {
    if( $control->manager->get_setting( 'theme_options[enable_featured_services_section]' )->value() == true ) {
        return true;
    }else{
        return false;
    }
}

function night_club_featured_services_page( $control ) {
    $content_type = $control->manager->get_setting( 'theme_options[featured_services_content_type]' )->value();
    return ( night_club_featured_services_active( $control ) && ( 'featured_services_page' == $content_type ) );
}

function night_club_featured_services_post( $control ) {
    $content_type = $control->manager->get_setting( 'theme_options[featured_services_content_type]' )->value();
    return ( night_club_featured_services_active( $control ) && ( 'featured_services_post' == $content_type ) );
}


function night_club_featured_blog_active( $control ) {
    if( $control->manager->get_setting( 'theme_options[enable_featured_blog_section]' )->value() == true ) {
        return true;
    }else{
        return false;
    }
}

function night_club_featured_blog_page( $control ) {
    $content_type = $control->manager->get_setting( 'theme_options[featured_blog_content_type]' )->value();
    return ( night_club_featured_blog_active( $control ) && ( 'featured_blog_page' == $content_type ) );
}

function night_club_featured_blog_post( $control ) {
    $content_type = $control->manager->get_setting( 'theme_options[featured_blog_content_type]' )->value();
    return ( night_club_featured_blog_active( $control ) && ( 'featured_blog_post' == $content_type ) );
}


function night_club_featured_posts_active( $control ) {
    if( $control->manager->get_setting( 'theme_options[enable_featured_posts_section]' )->value() == true ) {
        return true;
    }else{
        return false;
    }
}