<?php
/**
 * Link Functions
 *
 * Display functions (template-tags) for use in WordPress templates.
 */

/**
 * Link to Previous Event (Display)
 *
 * Displays a link to the previous post by start date for the given event
 *
 * @param bool|string $anchor link text. Use %title% to place the post title in your string.
 *
 * @see tribe_get_prev_event_link()
 */
function tribe_the_prev_event_link( $anchor = false ) {
	echo apply_filters( 'tribe_the_prev_event_link', tribe_get_prev_event_link( $anchor ) );
}

/**
 * Returns a link to the previous event by start date for the given event.
 *
 * @since 3.0
 * 
 * @param bool|string $anchor (optional) The link text. Use %title% to place the post title in your string. Default is false.
 *
 * @return string The link to the previous event.
 */
function tribe_get_prev_event_link( $anchor = false ) {
	$event_id = get_the_ID();

	tribe( 'tec.adjacent-events' )->set_current_event_id( $event_id );

	/**
	 * Filter the output of the link to the previous event by start date of a given event.
	 *
	 * @param string $prev_event_link The link to the previous event.
	 * @param int    $event_id        The ID of the reference event.
	 */
	return apply_filters( 'tribe_get_prev_event_link', tribe( 'tec.adjacent-events' )->get_prev_event_link( $anchor ), $event_id );
}

/**
 * Link to Next Event (Display)
 *
 * Display a link to the next post by start date for the given event
 *
 * @param bool|string $anchor link text. Use %title% to place the post title in your string.
 *
 * @return void
 * @see tribe_get_next_event_link()
 */
function tribe_the_next_event_link( $anchor = false ) {
	echo apply_filters( 'tribe_the_next_event_link', tribe_get_next_event_link( $anchor ) );
}

/**
 * Return a link to the next post by start date for the given event
 *
 * @param bool|string $anchor link text. Use %title% to place the post title in your string.
 *
 * @return string
 */
function tribe_get_next_event_link( $anchor = false ) {
	$event_id = get_the_ID();

	tribe( 'tec.adjacent-events' )->set_current_event_id( $event_id );

	/**
	 * Filter the output of the link to the next event by start date of a given event.
	 *
	 * @param string $next_event_link The link to the next event.
	 * @param int    $event_id        The ID of the reference event.
	 */
	return apply_filters( 'tribe_get_next_event_link', tribe( 'tec.adjacent-events' )->get_next_event_link( $anchor ), $event_id );
}

/**
 * Get a link to the previous events
 *
 * @return string
 */
function tribe_get_previous_events_link() {

	$link = '';

	if ( tribe_is_upcoming() && ( ! empty ( $_REQUEST['tribe_paged'] ) && $_REQUEST['tribe_paged'] > 1 ) ) {
		// if we're more than one page into the future, the previous link will be in the future as well
		$link = tribe_get_upcoming_link();
	} else {
		$link = tribe_get_past_link();
	}

	return apply_filters( 'tribe_get_previous_events_link', $link );
}

/**
 * Get a link to the next events
 *
 * @return string
 */
function tribe_get_next_events_link() {

	$link = '';

	if ( tribe_is_past() && ( ! empty ( $_REQUEST['tribe_paged'] ) && $_REQUEST['tribe_paged'] > 1 ) ) {
		// if we're more than one page into the past, the next link will be in the past as well
		$link = tribe_get_past_link();
	} else {
		$link = tribe_get_upcoming_link();
	}

	return apply_filters( 'tribe_get_next_events_link', $link );
}

/**
 * Link to All Events
 *
 * Returns a link to the events URL
 *
 * @param string $context Optional; defaults to 'href'. Can be 'display', in which case non-latin chars are not url-encoded.
 * @return string URL
 */
function tribe_get_events_link( $context = 'href' ) {
	$plugin = Tribe__Events__Main::instance();
	/**
	 * Allows for filtering the main events link.
	 *
	 * Returns a link to the events URL
	 *
	 * @param string $link The main events link.
	 * @param string $context Defaults to 'href'. Can also be 'display', in which case non-latin chars are not url-encoded.
	 */
	return apply_filters( 'tribe_get_events_link', $plugin->getLink( 'home' ), $context );
}

/**
 * Gets a view permalink.
 *
 * Generates the permalink for a specific view based on the provided slug and optional term.
 *
 * @since 5.7.0
 *
 * @hook tribe_get_view_permalink        Filters the overall view permalink.
 * @hook tribe_get_{slug}_view_permalink Filters the specific view permalink.
 *
 * @param string        $slug      The slug of the view for which the permalink is being generated.
 * @param bool|int|null $term      Optional. The term associated with the view. Default is null.
 *
 * @return string       $permalink The generated permalink for the view.
 */
function tribe_get_view_permalink( $slug, $term = null ) {
	$permalink = tribe_events_get_url( $slug );

	/**
	 * Provides an opportunity to modify the overall view permalink.
	 *
	 * @var string $permalink
	 * @var string $slug
	 */
	$permalink = apply_filters( 'tribe_get_view_permalink', $permalink, $slug );

	/**
	 * Provides an opportunity to modify the specific view permalink.
	 *
	 * @var string $permalink
	 * @var string $slug
	 */
	return apply_filters( "tribe_get_{$slug}_view_permalink", $permalink, $slug );
}

/**
 * Link to Grid View
 *
 * Returns a link to the general or category calendar grid view
 *
 * @param string $term Optional event category to link to.
 *
 * @return string URL
 * @todo rename
 */
function tribe_get_gridview_link( $term = null ) {
	$tribe_ecp = Tribe__Events__Main::instance();
	$output    = $tribe_ecp->getLink( \Tribe\Events\Views\V2\Views\Month_View::get_view_slug(), false, $term );

	return apply_filters( 'tribe_get_gridview_link', $output );
}

/**
 * Returns a link to the general or category upcoming view.
 *
 * @since  2.0.1
 *
 * @param  int|null $term Optional. Event category ID to link to. Default null.
 * @return string The URL of the list view.
 */
function tribe_get_listview_link( $term = null ) {
	$tribe_ecp = Tribe__Events__Main::instance();
	$output    = $tribe_ecp->getLink( \Tribe\Events\Views\V2\Views\List_View::get_view_slug(), false, $term );

	return apply_filters( 'tribe_get_listview_link', $output );
}

/**
 * Returns a url to the general or category past view.
 *
 * @since 2.0.1
 *
 * @param int|null $term Optional. Term ID. Default null.
 *
 * @return string URL of the past list view.
 */
function tribe_get_listview_past_link( $term = null ) {
	$tribe_ecp = Tribe__Events__Main::instance();
	$output    = $tribe_ecp->getLink( 'past', false, $term );

	return apply_filters( 'tribe_get_listview_past_link', $output );
}

/**
 * Returns a link to the next or previous list view page.
 *
 * @since 4.0
 *
 * @param string      $direction            'next' or 'prev'.
 * @param int|null    $term                 Optional. Term ID. Default null.
 * @param string|null $currently_displaying Optional. Type of list view page currently displayed ('past' or 'list'). Default null.
 * @param int|null    $page                 Optional. Current page number being displayed. Default null.
 *
 * @return string URL of the next or previous list view page.
 */
function tribe_get_listview_dir_link( $direction = 'next', $term = null, $currently_displaying = null, $page = null ) {
	$link = tribe_get_listview_link( $term );

	// if a page isn't passed in, attempt to fetch it from a get var
	if ( ! $page ) {
		$page = absint( tribe_get_request_var( 'tribe_paged', 1 ) );
	}

	$args = tribe_get_listview_args( $page, $direction, $currently_displaying );
	$link = add_query_arg(
		[
			'tribe_event_display' => $args['display'],
			'tribe_paged'         => absint( $args['page'] ),
		],
		$link
	);

	return apply_filters( 'tribe_get_listview_dir_link', $link, $term );
}

/**
 * Updates pagination and determines the current view display for the list view.
 *
 * This function adjusts the pagination and sets the view to be displayed based on
 * the current page and navigation direction.
 *
 * @since 4.6.12
 *
 * @param int         $page                 The current page number. Default is 1.
 * @param string      $direction            The navigation direction, either 'next' or 'prev'. Default is 'next'.
 * @param string|null $currently_displaying The view currently being displayed, such as 'list' or 'past'.
 *                                          Default is null, which triggers a fallback to the default list view.
 *
 * @return array {
 *      An associative array containing the updated display and page number.
 * 
 *      @type string $display The view to be displayed, either 'list' or 'past'.
 *      @type int    $page    The updated page number.
 * }
 */
function tribe_get_listview_args( $page = 1, $direction = 'next', $currently_displaying = null ) {
	// if what we are currently displaying is not passed in, let's set a default and check $_GET
	if ( ! $currently_displaying ) {
		$currently_displaying = tribe_get_listview_display();
	}

	// assume we want to display what we're currently displaying (until we discover otherwise)
	$display        = $currently_displaying;
	$list_view_slug = \Tribe\Events\Views\V2\Views\List_View::get_view_slug();

	if (
		( 'next' === $direction && $list_view_slug === $currently_displaying )
		|| ( 'prev' === $direction && 'past' === $currently_displaying )
	) {
		$page++;
	} elseif ( $list_view_slug === $currently_displaying && 1 === $page ) {
		$display = 'past';
	} elseif ( 'past' === $currently_displaying && 1 === $page ) {
		$display = $list_view_slug;
	} else {
		$page--;
	}

	return [
		'display' => $display,
		'page'    => $page,
	];
}

/**
 * Validates that the current view is inside of the Two allowed: list or view if not default to the list view.
 *
 * @since 4.6.12
 *
 * @return string
 */
function tribe_get_listview_display() {
	$view_slug       = \Tribe\Events\Views\V2\Views\List_View::get_view_slug();
	$default_display = $view_slug;
	$display         = tribe_get_request_var( 'tribe_event_display', $default_display );
	$valid_values    = [ $view_slug, 'past' ];

	return in_array( $display, $valid_values ) ? $display : $default_display;
}


/**
 * Link to prev List View
 *
 * Returns a link to the previous list view page
 *
 * @param int|null $term Term ID
 *
 * @return string URL
 */
function tribe_get_listview_prev_link( $term = null ) {
	$link = tribe_get_listview_dir_link( 'prev', $term );
	return apply_filters( 'tribe_get_listview_prev_link', $link, $term );
}

/**
 * Link to next List View
 *
 * Returns a link to the next list view page
 *
 * @param int|null $term Term ID
 *
 * @return string URL
 */
function tribe_get_listview_next_link( $term = null ) {
	$link = tribe_get_listview_dir_link( 'next', $term );
	return apply_filters( 'tribe_get_listview_next_link', $link, $term );
}

/**
 * Single Event Link
 *
 * Get link to a single event
 *
 * @param WP_Post|int $post_id   Optional. WP Post that this affects
 * @param bool        $full_link Optional. If true outputs a complete HTML <a> link, otherwise only the URL is output
 *
 * @return string|bool Link to post or false if none found
 */
function tribe_get_event_link( $post_id = null, $full_link = false ) {
	$post_id = Tribe__Main::post_id_helper( $post_id );
	$url = Tribe__Events__Main::instance()->getLink( 'single', $post_id );

	if ( '' != get_option( 'permalink_structure' ) ) {
		$url = trailingslashit( $url );
	}

	if ( $full_link ) {
		$title_args = [ 'post' => $post_id, 'echo' => false ];
		$name       = get_the_title( $post_id );
		$attr_title = the_title_attribute( $title_args );
		$link       = false;

		if ( ! empty( $url ) && ! empty( $name ) ) {
			$link = sprintf(
				'<a href="%1$s" title="%2$s"">%3$s</a>',
				esc_url( $url ),
				$attr_title,
				$name
			);
		}
	} else {
		$link = $url;
	}

	/**
	 * Filters the permalink to events
	 *
	 * @param mixed  $link      The link, possibly HTML, just URL, or false
	 * @param int    $post_id   Post ID
	 * @param bool   $full_link Whether to output full HTML <a> link
	 * @param string $url       The URL itself
	 */
	return apply_filters( 'tribe_get_event_link', $link, $post_id, $full_link, $url );
}

/**
 * Event Website Link (more info)
 *
 * @param null|object|int $event The event object or ID.
 * @param ?string         $label The link label.
 * @param string          $target The link target.
 *
 * @return string $html
 */
function tribe_get_event_website_link( $event = null, $label = null, $target = '_self' ): string {
	// We won't get far without a post ID. Especially since we pass it to filters that depend on it.
	$post_id = Tribe__Events__Main::postIdHelper( $event );
	$url     = tribe_get_event_website_url( $post_id );
	$target  = $target ? $target : '_self';

	/**
	 * Filter the target attribute for the event website link
	 *
	 * @since 5.1.0
	 * @since 5.5.0 Added $post_id argument
	 *
	 * @param string          $target The target attribute string. Defaults to "_self" (above).
	 * @param string          $url    The link URL.
	 * @param null|object|int $post_id  The event the url is attached to.
	 */
	$target = apply_filters( 'tribe_get_event_website_link_target', $target, $url, $post_id );

	// Ensure the target is given a valid value.
	$allowed = [ '_self', '_blank', '_parent', '_top', '_unfencedTop' ];
	if ( ! in_array( $target, $allowed ) ) {
		$target = '_self';
	}

	$rel    = ( '_blank' === $target ) ? 'noopener noreferrer' : 'external';

	if ( ! empty( $url ) ) {
		$label = empty( $label ) ? $url : $label;
		/**
		 * Filter the website link label
		 *
		 * @since 3.0
		 *
		 * @param string $label   The link label/text.
		 * @param int    $post_id The post ID.
		 */
		$label = apply_filters( 'tribe_get_event_website_link_label', $label, $post_id );
		$html  = sprintf(
			'<a href="%s" target="%s" rel="%s">%s</a>',
			esc_url( $url ),
			esc_attr( $target ),
			esc_attr( $rel ),
			esc_html( $label )
		);
	} else {
		$html = '';
	}

	/**
	 * Filter the website link HTML
	 *
	 * @since 3.0
	 *
	 * @param string $html The link HTML.
	 */
	return apply_filters( 'tribe_get_event_website_link', $html );
}

/**
 * Get the link for the event website.
 *
 * @since 5.5.0
 *
 * @param null|int $post_id The event or event ID.
 * @return string  Formatted title for the event website link
 */
function tribe_events_get_event_website_title( $post_id = null ) {
	$post_id = Tribe__Main::post_id_helper( $post_id );

	/**
	 * Allows customization of a event's website title link.
	 *
	 * @since 5.5.0
	 *
	 * @param string $title The title of the event's website link.
	 * @param int 	 $post_id The event ID.
	 */
	return apply_filters( 'tribe_events_get_event_website_title', __( 'Website:', 'the-events-calendar' ), $post_id );
}

/**
 * Event Website URL
 *
 * @param null|object|int $event
 *
 * @return string The event's website URL
 */
function tribe_get_event_website_url( $event = null ) {
	$post_id = ( is_object( $event ) && isset( $event->tribe_is_event ) && $event->tribe_is_event ) ? $event->ID : $event;
	$post_id = ( ! empty( $post_id ) || empty( $GLOBALS['post'] ) ) ? $post_id : get_the_ID();
	$url     = tribe_get_event_meta( $post_id, '_EventURL', true );

	return apply_filters( 'tribe_get_event_website_url', $url, $post_id );
}
