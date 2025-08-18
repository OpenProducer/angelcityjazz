<?php
/**
 * Theme functions and definitions for newspack-angelcity-2025
 */

// ✅ Enqueue parent and child styles (no @import needed)
function newspack_angelcity_2025_enqueue_styles() {
    $parent_style = 'newspack-style';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style',
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
    if (is_archive()) {
        $classes[] = 'archive';
    }
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
// 🧼 Remove Eventbrite/price blocks for past events
//
add_filter('render_block', function ($block_content, $block) {
    if (!is_singular('tribe_events')) {
        return $block_content;
    }
    $event_date = get_post_meta(get_the_ID(), '_EventStartDate', true);
    if (!$event_date || strtotime($event_date) > time()) {
        return $block_content;
    }
    $blocks_to_remove = ['jetpack/eventbrite', 'tribe/event-price'];
    return in_array($block['blockName'], $blocks_to_remove, true) ? '' : $block_content;
}, 10, 2);

//
// 🖼 Add OG & Twitter image tags on homepage
//
add_action('wp_head', function () {
    if (is_front_page()) {
        $image_url = home_url('/wp-content/uploads/2025/07/2025acjfbanner.webp');
        echo '<meta property="og:image" content="' . esc_url($image_url) . '"/>';
        echo '<meta property="og:image:width" content="1200"/>';
        echo '<meta property="og:image:height" content="630"/>';
        echo '<meta name="twitter:image" content="' . esc_url($image_url) . '"/>';
    }
}, 5);

//
// 🧠 Force Yoast to pre-render its SEO metadata
//
add_action('wp_head', function () {
    if ((is_front_page() || is_home()) && class_exists('WPSEO_Frontend')) {
        $frontend = WPSEO_Frontend::get_instance();
        if (method_exists($frontend, 'call_wpseo_head')) {
            ob_start();
            $frontend->call_wpseo_head();
            ob_end_clean();
        }
    }
}, 1);

//
// 💤 Lazy load all images + set fetchpriority on first event image
//
add_filter('wp_get_attachment_image_attributes', function ($attr) {
    if (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }
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
        echo '<link rel="preload" as="image" href="' . esc_url($image_base) . '?resize=1200%2C649&amp;ssl=1"
            imagesrcset="
                ' . esc_url($image_base) . '?w=1280&amp;ssl=1 1280w,
                ' . esc_url($image_base) . '?resize=1024%2C554&amp;ssl=1 1024w,
                ' . esc_url($image_base) . '?resize=768%2C415&amp;ssl=1 768w,
                ' . esc_url($image_base) . '?resize=400%2C216&amp;ssl=1 400w"
            imagesizes="(max-width: 1200px) 100vw, 1200px">';
    }
});

//
// 🧹 Performance cleanup – dequeue nonessential frontend assets
//
add_action('wp_enqueue_scripts', function () {
    if (is_admin() || current_user_can('edit_posts')) return;

    // 🧼 Remove emoji styles/scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // 🚫 Disable unneeded assets
    wp_deregister_style('dashicons');
    wp_deregister_script('wp-embed');
}, 100);

//
// 🔍 Log scripts & styles handles to console and debug log
//
add_action('wp_print_scripts', function () {
    if (is_admin()) return;
    global $wp_scripts;
    echo "<script>console.groupCollapsed('📦 Enqueued Scripts');</script>\n";
    foreach ($wp_scripts->queue as $handle) {
        echo "<script>console.log('📜 Script: {$handle}');</script>\n";
        error_log("Script: {$handle}");
    }
    echo "<script>console.groupEnd();</script>\n";
}, 100);

add_action('wp_print_styles', function () {
    if (is_admin()) return;
    global $wp_styles;
    echo "<script>console.groupCollapsed('🎨 Enqueued Styles');</script>\n";
    foreach ($wp_styles->queue as $handle) {
        echo "<script>console.log('🎨 Style: {$handle}');</script>\n";
        error_log("Style: {$handle}");
    }
    echo "<script>console.groupEnd();</script>\n";
}, 100);

//
// 🏟 Remove link from venue name on single event pages
//
add_filter('tribe_get_venue_link', function ($link, $venue_id = null, $full_link = true) {
    if (is_singular('tribe_events')) {
        return get_the_title($venue_id); // plain text, no <a>
    }
    return $link;
}, 10, 3);

//
// 🔗 Open Event/Venue/Organizer website links in a new tab
//
function acj_add_target_blank_to_anchor_html( $html ) {
    if (stripos($html, '<a ') === false) {
        return $html;
    }
    if (stripos($html, 'target=') === false) {
        $html = preg_replace('/<a\s+/i', '<a target="_blank" ', $html, 1);
    }
    if (stripos($html, 'rel=') === false) {
        $html = preg_replace('/<a\s+/i', '<a rel="noopener noreferrer" ', $html, 1);
    }
    return $html;
}
// Apply to all known variants
add_filter('tribe_get_event_website_link', 'acj_add_target_blank_to_anchor_html', 10);
add_filter('tribe_get_venue_website_link', 'acj_add_target_blank_to_anchor_html', 10);
add_filter('tribe_get_venue_website_link_html', 'acj_add_target_blank_to_anchor_html', 10);
add_filter('tribe_get_organizer_website_link', 'acj_add_target_blank_to_anchor_html', 10);

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
            if (preg_match('/^' . $site_url . '/i', $href)) {
                return $tag;
            }
            if (stripos($tag, 'target=') === false) {
                $tag = str_replace('<a ', '<a target="_blank" ', $tag);
            }
            if (stripos($tag, 'rel=') === false) {
                $tag = str_replace('<a ', '<a rel="noopener noreferrer" ', $tag);
            }
            return $tag;
        },
        $content
    );
}, 20);

error_log('Running tribe_get_event_website_link filter');

/**
 * Debug which filters are used for event / venue / ticket links
 */

// Event website link
add_filter( 'tribe_get_event_website_link', function( $link ) {
    error_log('[ACJ DEBUG] tribe_get_event_website_link fired');
    return $link;
}, 10 );

// Venue website link
add_filter( 'tribe_get_venue_website_link', function( $link ) {
    error_log('[ACJ DEBUG] tribe_get_venue_website_link fired');
    return $link;
}, 10 );

// Ticket URL (raw URL only, not <a>)
add_filter( 'tribe_tickets_get_ticket_url', function( $url ) {
    error_log('[ACJ DEBUG] tribe_tickets_get_ticket_url fired');
    return $url;
}, 10 );

// Ticket button (<a> markup)
add_filter( 'tribe_tickets_get_ticket_button', function( $html ) {
    error_log('[ACJ DEBUG] tribe_tickets_get_ticket_button fired');
    return $html;
}, 10 );

// Debug filter for venue website link
add_filter( 'tribe_get_venue_website_link', function( $link, $venue_id ) {
    error_log('[ACJ DEBUG] tribe_get_venue_website_link fired for venue ' . $venue_id);

    // Just return the original link for now so it's never empty
    if ( empty( $link ) ) {
        error_log('[ACJ DEBUG] Venue has no website link stored');
        return $link;
    }

    error_log('[ACJ DEBUG] Returning link: ' . $link);
    return $link;
}, 10, 2 );



