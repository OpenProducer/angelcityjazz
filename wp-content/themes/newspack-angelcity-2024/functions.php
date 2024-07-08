<?php
// Enqueue parent theme styles
function newspack_angelcity_2024_enqueue_styles() {
    $parent_style = 'newspack-style'; // This is 'newspack-style' for the Newspack theme.

    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'newspack_angelcity_2024_enqueue_styles');
