<?php
/**
 * Social Links Menu Widget
 *
 * Renders the "social" menu location using the same markup and icon filter
 * the Newspack parent theme uses, so no extra CSS is needed.
 */

class ACJ_Social_Links_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'acj_social_links',
			__( 'Social Links Menu', 'newspack-angelcity-2025' ),
			array( 'description' => __( 'Displays the Social Links Menu with platform icons.', 'newspack-angelcity-2025' ) )
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
				<?php esc_html_e( 'Title:', 'newspack-angelcity-2025' ); ?>
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
