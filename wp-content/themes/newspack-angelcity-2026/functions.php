<?php
/**
 * Theme functions and definitions for newspack-angelcity-2026
 */

// Enqueue parent and child styles.
// Priority 20 runs after the parent theme's newspack_scripts() (priority 10), so we can
// safely deregister the 'newspack-style' handle — the parent registers it pointing at the
// child stylesheet (get_stylesheet_uri() returns child when a child theme is active), which
// would cause a double-load. We replace it with the correct parent URL here.
function newspack_angelcity_2026_enqueue_styles() {
    wp_deregister_style('newspack-style');
    wp_enqueue_style(
        'newspack-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme()->parent()->get('Version')
    );
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('newspack-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'newspack_angelcity_2026_enqueue_styles', 20);

// Re-assert child theme color variables after the parent's wp_head inline styles.
// The parent theme outputs a <style id="custom-theme-colors"> block via wp_head at
// priority 10 (newspack_colors_css_wrap). Because inline <style> tags appear after
// <link> stylesheet tags in the HTML, they always win in the cascade regardless of
// what the child stylesheet declares. Hooking at priority 20 ensures our :root block
// appears after the parent's and takes precedence.
add_action('wp_head', function () {
    ?>
    <style id="child-theme-color-overrides">
    :root {
        --newspack-theme-color-primary: #541a17;
        --newspack-theme-color-secondary: #d5621c;
        --newspack-theme-color-primary-against-white: #541a17;
        --newspack-theme-color-secondary-against-white: #d5621c;
        --newspack-theme-color-against-primary: #fff;
        --newspack-theme-color-against-secondary: #fff;
    }
    </style>
    <?php
}, 20);

//
// 🗓 Remove end time from The Events Calendar schedule output
//
add_filter('tribe_events_event_schedule_details_formatting', fn($settings) => array_merge($settings, ['time' => false]));

//
// Fix specificMode WP_Query for tribe_events in the Content Loop block.
//
// newspack-blocks always includes post_type in its WP_Query args (class-newspack-blocks.php:626),
// even in specificMode where post__in already identifies the exact posts. TEC hooks pre_get_posts
// (suppress_filters is false) and interferes with queries that specify post_type=>['tribe_events']
// directly, causing them to return no results. When specific post IDs are provided, post_type
// filtering is redundant -- switching to 'any' lets WP_Query find the posts by ID alone,
// bypassing TEC's query modification hooks entirely.
//
// Two code paths reach this filter:
//   Frontend (render_callback): $attributes has specificMode+specificPosts → build_articles_query
//     sets $args['post__in'] before the filter fires. We check $args['post__in'].
//   Editor (posts_endpoint REST): queryCriteriaFromAttributes (utils.ts) converts specificMode
//     + specificPosts into just include=[ids] before sending to the server. posts_endpoint()
//     calls build_articles_query() WITHOUT specificMode/specificPosts, so $args['post__in'] is
//     never set. posts_endpoint() then sets $args['post__in'] from $attributes['include'] AFTER
//     the filter fires. We check $attributes['include'] to catch this case.
//
// Filter: newspack_blocks_build_articles_query (class-newspack-blocks.php:773)
//
add_filter( 'newspack_blocks_build_articles_query', function ( $args, $attributes, $block_name ) {
    $has_specific_posts = ! empty( $args['post__in'] );                      // frontend path
    $has_include        = ! empty( $attributes['include'] ?? [] );           // editor path
    if ( ( $has_specific_posts || $has_include ) &&
         in_array( 'tribe_events', (array) $args['post_type'], true ) ) {
        $args['post_type'] = 'any';
    }
    return $args;
}, 10, 3 );

/**
 * ACJ Artist Page - Event Display Customizations
 *
 * Controls how TEC event data appears in the Content Loop block
 * on acj_artist pages. Add future event display adjustments here.
 *
 * Filter: newspack_blocks_formatted_displayed_post_date (priority 20)
 * Overrides the newspack-blocks TEC integration (priority 10) which
 * returns tribe_events_event_schedule_details() without year or venue separation.
 */
function acj_artist_event_display( $date_string, $post ) {
    if ( get_post_type( $post ) !== 'tribe_events' ) {
        return $date_string;
    }

    // newspack_blocks_formatted_displayed_post_date output is wrapped in esc_html() by
    // article.php:242, so only plain text can be returned here. Venue is handled separately
    // by TEC's newspack_blocks_article_meta_footer hook; CSS on .single-acj_artist scopes
    // its display without affecting single event pages.

    // Format: "October 17, 2026"
    $start_date = get_post_meta( $post->ID, '_EventStartDate', true );
    return $start_date
        ? date_i18n( 'F j, Y', strtotime( $start_date ) )
        : $date_string;
}
add_filter( 'newspack_blocks_formatted_displayed_post_date', 'acj_artist_event_display', 20, 2 );

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
add_theme_support('post-thumbnails', ['post', 'page', 'tribe_events', 'acj_artist']);

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
// 🖋 Preconnect to Google Fonts
//
add_action('wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
}, 1);

// 🧩 Footer Social Links Widget (ACJ_Social_Links_Widget)
class ACJ_Social_Links_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'acj_social_links',
			__( 'Social Links Menu', 'newspack-angelcity-2026' ),
			array( 'description' => __( 'Displays the Social Links Menu with platform icons.', 'newspack-angelcity-2026' ) )
		);
	}

	public function widget( $args, $instance ) {
		if ( ! has_nav_menu( 'social' ) ) {
			return;
		}

		$title = apply_filters( 'widget_title', $instance['title'] ?? '' );

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		newspack_social_menu_footer();

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = $instance['title'] ?? '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'newspack-angelcity-2026' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}
}

function acj_register_social_links_widget() {
	register_widget( 'ACJ_Social_Links_Widget' );
}
add_action( 'widgets_init', 'acj_register_social_links_widget' );

//
// 🎤 Register acj_artist custom post type
//
add_action('init', function () {
    register_post_type('acj_artist', [
        'labels' => [
            'name'               => 'Artists',
            'singular_name'      => 'Artist',
            'add_new_item'       => 'Add New Artist',
            'edit_item'          => 'Edit Artist',
            'new_item'           => 'New Artist',
            'view_item'          => 'View Artist',
            'search_items'       => 'Search Artists',
            'not_found'          => 'No artists found',
            'not_found_in_trash' => 'No artists found in Trash',
        ],
        'public'       => true,
        'has_archive'  => true,
        'show_in_rest' => true,
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon'    => 'dashicons-groups',
        'rewrite'      => ['slug' => 'artist'],
    ]);
});

//
// 🏷 Register acj_performance taxonomy (shared between acj_artist and tribe_events)
//
add_action('init', function () {
    register_taxonomy('acj_performance', ['acj_artist', 'tribe_events'], [
        'labels' => [
            'name'              => 'Performances',
            'singular_name'     => 'Performance',
            'search_items'      => 'Search Performances',
            'all_items'         => 'All Performances',
            'edit_item'         => 'Edit Performance',
            'update_item'       => 'Update Performance',
            'add_new_item'      => 'Add New Performance',
            'new_item_name'     => 'New Performance Name',
            'menu_name'         => 'Performances',
        ],
        'public'       => true,
        'show_in_rest' => true,
        'rest_base'    => 'acj_performance',
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'performance'],
    ]);
});

add_filter( 'rest_acj_performance_query', function( $args, $request ) {
    return $args;
}, 10, 2 );

add_filter( 'block_editor_settings_all', function( $settings ) {
    $settings['acj_performance'] = get_terms( [
        'taxonomy'   => 'acj_performance',
        'hide_empty' => false,
    ] );
    return $settings;
} );

//
// 🧩 Pattern markup — referenced by both register_block_pattern and default_content filter
//
function acj_artist_page_pattern_content(): string {
    return <<<'PATTERN'
<!-- wp:group {"align":"full","className":"event-header","backgroundColor":"secondary","textColor":"white"} -->
<div class="wp-block-group alignfull event-header has-white-color has-secondary-background-color has-text-color has-background"><!-- wp:post-title /-->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"65%"} -->
<div class="wp-block-column" style="flex-basis:65%"><!-- wp:post-featured-image /-->
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"35%"} -->
<div class="wp-block-column" style="flex-basis:35%"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase","fontSize":"0.75rem","letterSpacing":"0.1em"}},"textColor":"white"} -->
<p class="has-white-color has-text-color" style="font-size:0.75rem;font-style:normal;font-weight:700;letter-spacing:0.1em;text-transform:uppercase">Performing on</p>
<!-- /wp:paragraph -->

<!-- wp:newspack-blocks/homepage-articles {"specificMode":true,"specificPosts":[],"postType":["tribe_events"],"showImage":false,"showExcerpt":false,"showAuthor":false,"showDate":true,"showCategory":false,"typeScale":3,"imageScale":1} /-->
</div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","backgroundColor":"white"} -->
<div class="wp-block-group alignfull has-white-background-color has-background"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"style":{"spacing":{"padding":{"top":"20px","right":"20px","bottom":"20px","left":"20px"}}},"backgroundColor":"secondary-variation"} -->
<div class="wp-block-column has-secondary-variation-background-color has-background" style="padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:paragraph {"align":"center","textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color"><strong>LINE UP</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color">Artist Name – instrument<br><strong>Artist Name</strong> – instrument</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"spacing":{"padding":{"top":"20px","right":"20px","bottom":"20px","left":"20px"}}},"backgroundColor":"primary","textColor":"white"} -->
<div class="wp-block-column has-white-color has-primary-background-color has-text-color has-background" style="padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color">Performance description and context.</p>
<!-- /wp:paragraph -->

<!-- wp:quote {"textColor":"white"} -->
<blockquote class="wp-block-quote has-white-color has-text-color"><!-- wp:paragraph {"textColor":"white"} -->
<p class="has-white-color has-text-color">"A great quote about the performance."</p>
<!-- /wp:paragraph --><cite>– Source</cite></blockquote>
<!-- /wp:quote --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"video","backgroundColor":"light-gray"} -->
<div class="wp-block-group alignfull video has-light-gray-background-color has-background"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:embed {"url":"https://www.youtube.com/watch?v=","type":"video","providerNameSlug":"youtube","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
<figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
https://www.youtube.com/watch?v=
</div></figure>
<!-- /wp:embed --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:embed {"url":"https://www.youtube.com/watch?v=","type":"video","providerNameSlug":"youtube","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
<figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
https://www.youtube.com/watch?v=
</div></figure>
<!-- /wp:embed --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:media-text {"align":"full","mediaPosition":"right","mediaType":"image","mediaWidth":39,"imageFill":true,"backgroundColor":"primary","textColor":"white"} -->
<div class="wp-block-media-text alignfull has-media-on-the-right is-stacked-on-mobile is-image-fill-element has-white-color has-primary-background-color has-text-color has-background" style="grid-template-columns:auto 39%"><div class="wp-block-media-text__content"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">About the Artist</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"white"} -->
<p class="has-white-color has-text-color">Artist biography goes here.</p>
<!-- /wp:paragraph --></div><figure class="wp-block-media-text__media"></figure></div>
<!-- /wp:media-text --></div>
<!-- /wp:group -->
PATTERN;
}

add_action('init', function () {
    register_block_pattern_category('acj', ['label' => 'Angel City Jazz']);
    register_block_pattern('acj/artist-page', [
        'title'       => 'Artist Page',
        'description' => 'Scaffold for ACJ artist page content: line-up, videos, and bio.',
        'categories'  => ['acj'],
        'content'     => acj_artist_page_pattern_content(),
    ]);
});

function acj_event_page_pattern_content(): string {
    return <<<'PATTERN'
<!-- wp:group {"align":"full","className":"event-header","backgroundColor":"secondary","textColor":"white"} -->
<div class="wp-block-group alignfull event-header has-white-color has-secondary-background-color has-text-color has-background"><!-- wp:post-title {"level":2} /-->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"65%"} -->
<div class="wp-block-column" style="flex-basis:65%"><!-- wp:post-featured-image /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"35%"} -->
<div class="wp-block-column" style="flex-basis:35%"><!-- wp:tribe/event-datetime /-->

<!-- wp:tribe/event-venue /-->

<!-- wp:tribe/event-price /-->

<!-- wp:jetpack/eventbrite {"url":"","eventId":0,"style":"modal"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","backgroundColor":"white"} -->
<div class="wp-block-group alignfull has-white-background-color has-background"><!-- wp:columns {"align":"full"} -->
<div class="wp-block-columns alignfull"><!-- wp:column {"style":{"spacing":{"padding":{"top":"20px","right":"20px","bottom":"20px","left":"20px"}}},"backgroundColor":"secondary-variation"} -->
<div class="wp-block-column has-secondary-variation-background-color has-background" style="padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:columns {"backgroundColor":"light-gray"} -->
<div class="wp-block-columns has-light-gray-background-color has-background"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p>7:30pm | Doors</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p>8:00pm | <a href="#">Artist Name</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p>9:15pm | <a href="#">Artist Name</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:tribe/related-events /-->
PATTERN;
}

// Register acj/event-page block pattern
//
add_action('init', function () {
    register_block_pattern_category('acj-patterns', ['label' => 'ACJ Patterns']);
    register_block_pattern('acj/event-page', [
        'title'       => 'Event Page',
        'description' => 'Scaffold for ACJ event page content: header with datetime/venue, schedule bar.',
        'categories'  => ['acj-patterns'],
        'postTypes'   => ['tribe_events'],
        'content'     => acj_event_page_pattern_content(),
    ]);
});

// Pre-populate new posts with the relevant ACJ block pattern based on post type.
// NOTE: the tribe_events branch below is a no-op — Gutenberg creates event auto-drafts
// via REST API with empty content, bypassing this filter. New events are handled by the
// enqueue_block_editor_assets hook below instead.
add_filter( 'default_content', function ( $content, $post ) {
    if ( $post->post_type === 'tribe_events' ) {
        return acj_event_page_pattern_content();
    }
    if ( $post->post_type === 'acj_artist' ) {
        return acj_artist_page_pattern_content();
    }
    return $content;
}, 10, 2 );

// Pre-populate new tribe_events posts with the ACJ event page pattern via JS.
// Gutenberg creates auto-drafts through the REST API (bypassing default_content), so
// we wait for TEC's default template blocks to load, then replace them with our pattern.
add_action( 'enqueue_block_editor_assets', function () {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( ! $screen || $screen->post_type !== 'tribe_events' ) {
        return;
    }

    global $post;
    if ( ! $post || $post->post_status !== 'auto-draft' ) {
        return;
    }

    // Register a stub handle (no file) to carry dependencies and inline code.
    wp_register_script(
        'acj-event-page-defaults',
        false,
        [ 'wp-blocks', 'wp-data', 'wp-dom-ready' ],
        null,
        true
    );
    wp_enqueue_script( 'acj-event-page-defaults' );

    // Pass the pattern markup from PHP to JS without duplicating it.
    wp_localize_script( 'acj-event-page-defaults', 'acjEventPageData', [
        'content' => acj_event_page_pattern_content(),
    ] );

    wp_add_inline_script( 'acj-event-page-defaults', <<<'JS'
wp.domReady( function () {
    var data = window.acjEventPageData;
    if ( ! data || ! data.content ) return;

    var replaced = false;

    var unsubscribe = wp.data.subscribe( function () {
        if ( replaced ) return;

        var editor = wp.data.select( 'core/editor' );
        if ( ! editor ) return;

        // Bail if this is not a new auto-draft (e.g. user opened an existing event).
        var status = editor.getCurrentPostAttribute( 'status' );
        if ( status && status !== 'auto-draft' ) {
            replaced = true;
            unsubscribe();
            return;
        }

        // Wait until the block editor has finished loading its initial blocks.
        var blockEditor = wp.data.select( 'core/block-editor' );
        if ( ! blockEditor || blockEditor.getBlocks().length === 0 ) return;

        replaced = true;
        unsubscribe();

        // Replace TEC's default template with the ACJ event page pattern.
        wp.data.dispatch( 'core/block-editor' ).resetBlocks(
            wp.blocks.parse( data.content )
        );
    } );
} );
JS
    );
} );

//
// 🔗 Register ACF field groups for artist ↔ event relationships
//
add_action('acf/init', function () {
    if ( ! function_exists('acf_add_local_field_group') ) {
        return;
    }

    // Field group on tribe_events: pick related acj_artist posts
    acf_add_local_field_group([
        'key'    => 'group_acj_event_artists',
        'title'  => 'Artist Pages',
        'fields' => [
            [
                'key'           => 'field_acj_related_artists',
                'label'         => 'Related Artists',
                'name'          => 'acj_related_artists',
                'type'          => 'relationship',
                'post_type'     => ['acj_artist'],
                'return_format' => 'post_object',
                'filters'       => ['search'],
                'elements'      => [],
                'min'           => 0,
                'max'           => 0,
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'tribe_events',
                ],
            ],
        ],
        'position'   => 'normal',
        'menu_order' => 0,
        'style'      => 'seamless',
    ]);
});
