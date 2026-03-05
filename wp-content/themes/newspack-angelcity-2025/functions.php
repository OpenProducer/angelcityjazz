<?php
/**
 * Theme functions and definitions for newspack-angelcity-2025
 */

// ✅ Enqueue parent and child styles (no @import needed)
function newspack_angelcity_2025_enqueue_styles() {
    $parent_style = 'newspack-style';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'newspack_angelcity_2025_enqueue_styles');

//
// 🗓 Remove end time from The Events Calendar schedule output
//
add_filter('tribe_events_event_schedule_details_formatting', fn($settings) => array_merge($settings, ['time' => false]));

//
// 🔐 Redirect login to WooCommerce My Account
//
add_filter('tribe_tickets_ticket_login_url', function ($original_login_url) {
    $new_url = esc_url(get_permalink(get_option('woocommerce_myaccount_page_id')));
    return $new_url ?: $original_login_url;
});

//
// 🛍 Enable WooCommerce gallery features
//
add_action('after_setup_theme', function () {
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
});
add_theme_support('post-thumbnails', ['post', 'page', 'tribe_events']);

//
// 🏷 Clean up body classes for event views
//
add_filter('body_class', function ($classes) {
    if (is_archive()) $classes[] = 'archive';

    if (in_array('post-type-archive-tribe_events', $classes, true)) {
        unset($classes[array_search('archive', $classes, true)]);
        unset($classes[array_search('single', $classes, true)]);
    }

    if (in_array('tribe_venue-template-default', $classes, true)) {
        unset($classes[array_search('single', $classes, true)]);
        $classes = array_merge($classes, [
            'post-type-archive',
            'post-type-archive-tribe_events',
            'post-template-single-wide'
        ]);
    }

    if (in_array('single-tribe_events', $classes, true)) {
        unset($classes[array_search('single', $classes, true)]);
        $classes = array_merge($classes, [
            'post-template',
            'post-template-single-wide'
        ]);
    }

    return $classes;
});

//
// 🛍 Customize WooCommerce behavior
//
add_filter('woocommerce_continue_shopping_redirect', fn() => wc_get_page_permalink('shop'));
remove_action('woocommerce_no_products_found', 'wc_no_products_found');
add_filter('woocommerce_account_menu_items', function ($menu_links) {
    unset($menu_links['newsletters'], $menu_links['newspack-newsletters'], $menu_links['subscriptions']);
    return $menu_links;
});

//
// 🔍 Adjust search to include only posts and future events
//
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $query->set('post_type', ['post', 'tribe_events']);
        $query->set('author', '');

        if (in_array('tribe_events', (array) $query->get('post_type'), true)) {
            $meta_query = $query->get('meta_query') ?: [];
            $meta_query[] = [
                'relation' => 'OR',
                [
                    'key'     => '_EventStartDate',
                    'value'   => date('Y-m-d'),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ],
                [
                    'key'     => '_EventStartDate',
                    'compare' => 'NOT EXISTS',
                ],
            ];
            $query->set('meta_query', $meta_query);
        }
    }
});

//
// 🏷 Force Events archive <title> (overrides old/Yoast titles)
//
function acj_force_events_archive_title($title) {
    if (function_exists('tribe_is_event_query') && tribe_is_event_query() && is_post_type_archive('tribe_events')) {
        return 'Angel City Jazz Fest 2025 Schedule – Oct 9–19 | Angel City Jazz';
    }
    return $title;
}
add_filter('pre_get_document_title', 'acj_force_events_archive_title', 99);
add_filter('wp_title', 'acj_force_events_archive_title', 99);

//
// ✅ Dynamically append festival year category to Yoast SEO title & meta description
//
add_filter('wpseo_title', function ($title) {
    if (is_singular('tribe_events')) {
        $title = preg_replace('/\s*[-–—|]+\s*$/u', '', $title);
        $terms = get_the_terms(get_the_ID(), 'tribe_events_cat');
        if ($terms && !is_wp_error($terms)) {
            $festival_terms = array_filter($terms, fn($t) => preg_match('/\d{4} Angel City Jazz Festival/', $t->name));
            if (!empty($festival_terms)) {
                $first_term = array_shift($festival_terms);
                $title .= ' – ' . $first_term->name;
            }
        }
    }
    return $title;
});

add_filter('wpseo_metadesc', function ($desc) {
    if (is_singular('tribe_events')) {
        $terms = get_the_terms(get_the_ID(), 'tribe_events_cat');
        if ($terms && !is_wp_error($terms)) {
            $festival_terms = array_filter($terms, fn($t) => preg_match('/\d{4} Angel City Jazz Festival/', $t->name));
            if (!empty($festival_terms)) {
                $first_term = array_shift($festival_terms);
                $desc .= ' | ' . $first_term->name;
            }
        }
    }
    return $desc;
});

//
// 🏠 Force homepage SEO title & meta description (Yoast override)
//
add_filter('wpseo_title', fn($title) => is_front_page() ? 'Angel City Jazz Festival 2025 | Oct 9–19 | Angel City Jazz' : $title, 20);
add_filter('wpseo_metadesc', fn($desc) => is_front_page() ? 'Experience the 18th Angel City Jazz Festival, Oct 9–19, with an eclectic lineup of world-class jazz artists. Tickets available now!' : $desc, 20);

//
// 🖼 Force homepage OG & Twitter images (Yoast override)
//
add_filter('wpseo_opengraph_image', fn($img) => is_front_page() ? home_url('/wp-content/uploads/2025/07/2025acjfbanner.webp') : $img);
add_filter('wpseo_twitter_image', fn($img) => is_front_page() ? home_url('/wp-content/uploads/2025/07/2025acjfbanner.webp') : $img);

//
// 💤 Lazy load all images + set fetchpriority on first event image
//
add_filter('wp_get_attachment_image_attributes', function ($attr) {
    if (!isset($attr['loading'])) $attr['loading'] = 'lazy';
    if (!isset($GLOBALS['first_grid_image_set']) && isset($attr['class']) &&
        strpos($attr['class'], 'tribe-events-pro-photo__event-featured-image') !== false
    ) {
        $attr['fetchpriority'] = 'high';
        $GLOBALS['first_grid_image_set'] = true;
    }
    return $attr;
}, 11);

//
// 🎯 Preload hero banner for faster LCP on homepage
//
add_action('wp_head', function () {
    if (is_front_page()) {
        $image_base = home_url('/wp-content/uploads/2025/07/2025acjfbanner.webp');
        echo '<link rel="preload" as="image" href="' . esc_url($image_base) . '">';
    }
});

//
// 🧹 Performance cleanup – dequeue nonessential frontend assets
//
add_action('wp_enqueue_scripts', function () {
    if (is_admin() || current_user_can('edit_posts')) return;
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    wp_deregister_style('dashicons');
    wp_deregister_script('wp-embed');
}, 100);

//
// 🏟 Remove link from venue name on single event pages
//
add_filter('tribe_get_venue_link', fn($link, $venue_id = null) => is_singular('tribe_events') ? get_the_title($venue_id) : $link, 10, 3);

//
// 🔗 Open Event/Venue/Organizer website links in a new tab
//
add_filter('tribe_get_event_website_link_target', fn() => '_blank');
add_filter('tribe_get_venue_website_link_target', fn() => '_blank');

//
// 🌍 Open *only external* links in event content in a new tab
//
add_filter('the_content', function ($content) {
    if (!is_singular('tribe_events')) return $content;
    $site_url = preg_quote(home_url(), '/');
    return preg_replace_callback(
        '/<a\s+[^>]*href=["\'](https?:\/\/[^"\']+)["\'][^>]*>/i',
        function ($matches) use ($site_url) {
            $tag  = $matches[0];
            $href = $matches[1];
            if (preg_match('/^' . $site_url . '/i', $href)) return $tag;
            if (stripos($tag, 'target=') === false) $tag = str_replace('<a ', '<a target="_blank" ', $tag);
            if (stripos($tag, 'rel=') === false) $tag = str_replace('<a ', '<a rel="noopener noreferrer" ', $tag);
            return $tag;
        },
        $content
    );
}, 20);

//
// 🚀 Performance Enhancements – defer selected scripts
//
function acj_defer_scripts($tag, $handle, $src) {
    $defer = ['woocommerce','mailchimp-woocommerce','woocommerce-analytics','milestone','wpmenucart-ajax-assist'];
    return in_array($handle, $defer, true) ? '<script src="' . esc_url($src) . '" defer></script>' . "\n" : $tag;
}
add_filter('script_loader_tag', 'acj_defer_scripts', 10, 3);

//
// 🖋 Preconnect to Google Fonts
//
add_action('wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
}, 1);
