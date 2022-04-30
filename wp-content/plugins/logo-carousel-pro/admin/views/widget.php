<?php
/**
 * Woo Product Slider Pro Widget
 *
 * @return void
 */
function sp_logo_carousel_pro_widget() {
	register_widget( 'SP_Logo_Carousel_Pro_Widget_Content' );
}

add_action( 'widgets_init', 'sp_logo_carousel_pro_widget' );

/**
 * Constructor of the widget.
 */
class SP_Logo_Carousel_Pro_Widget_Content extends WP_Widget {

	/**
	 * Constructor of the widget.
	 */
	public function __construct() {
		parent::__construct( 'SP_Logo_Carousel_Pro_Widget_Content', __( 'Logo Carousel Pro', 'logo-carousel-pro' ),
			array(
				'description' => __( 'Display Logo Carousel.', 'logo-carousel-pro' ),
			)
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args widget argument.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$title        = apply_filters( 'widget_title', esc_attr( $instance['title'] ) );
		$shortcode_id = isset( $instance['shortcode_id'] ) ? absint( $instance['shortcode_id'] ) : 0;

		if ( ! $shortcode_id ) {
			return;
		}

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo do_shortcode( '[logo_carousel_pro id=' . $shortcode_id . ']' );
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget form.
	 */
	public function form( $instance ) {
		$shortcodes   = $this->shortcodes_list();
		$shortcode_id = ! empty( $instance['shortcode_id'] ) ? absint( $instance['shortcode_id'] ) : null;
		$title        = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';

		if ( count( $shortcodes ) > 0 ) {

			echo sprintf( '<p><label for="%1$s">%2$s</label>', $this->get_field_id( 'title' ), esc_html__( 'Title:', 'logo-carousel-pro' ) );
			echo sprintf( '<input type="text" class="widefat" id="%1$s" name="%2$s" value="%3$s" /></p>', $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $title );

			echo sprintf( '<p><label>%s</label>', esc_html__( 'Shortcode:', 'logo-carousel-pro' ) );
			echo sprintf( '<select class="widefat" name="%s">', $this->get_field_name( 'shortcode_id' ) );
			foreach ( $shortcodes as $shortcode ) {
				$selected = $shortcode->id == $shortcode_id ? 'selected="selected"' : '';
				echo sprintf(
					'<option value="%1$d" %3$s>%2$s</option>',
					$shortcode->id,
					$shortcode->title,
					$selected
				);
			}
			echo '</select></p>';

		} else {
			echo sprintf( '<p>%1$s <a href="' . esc_url( admin_url( 'post-new.php?post_type=sp_lcp_shortcodes' ) ) . '">%3$s</a> %2$s</p>',
				esc_html__( 'You did not generate any shortcode yet.', 'logo-carousel-pro' ),
				esc_html__( 'to generate a new shortcode now.', 'logo-carousel-pro' ),
				esc_html__( 'click here', 'logo-carousel-pro' )
			);
		}
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance New instance.
	 * @param array $old_instance Old instance.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                 = array();
		$instance['title']        = sanitize_text_field( $new_instance['title'] );
		$instance['shortcode_id'] = absint( $new_instance['shortcode_id'] );

		return $instance;
	}

	/**
	 * Shortcode list
	 *
	 * @return statement
	 */
	private function shortcodes_list() {
		$shortcodes = get_posts( array(
			'post_type'   => 'sp_lcp_shortcodes',
			'post_status' => 'publish',
			'posts_per_page' => - 1,
		) );

		if ( count( $shortcodes ) < 1 ) {
			return array();
		}

		return array_map( function ( $shortcode ) {
			return (object) array(
				'id'    => absint( $shortcode->ID ),
				'title' => esc_html( $shortcode->post_title ),
			);
		}, $shortcodes );
	}

}
