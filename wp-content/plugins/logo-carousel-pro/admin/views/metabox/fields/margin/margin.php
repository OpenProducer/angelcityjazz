<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

/**
 *
 * Field: Margin
 *
 * @since 3.3
 * @version 3.3
 *
 */
class SP_LCPRO_Framework_Option_margin extends SP_LCPRO_Framework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		$defaults_value = array(
			'left'   => '',
			'top'    => '',
			'bottom' => '',
			'right'  => '',
		);

		$value         = wp_parse_args( $this->element_value(), $defaults_value );

		// Container.
		echo '<div class="sp_lcpro_margin_field" data-id="' . $this->field['id'] . '">';

			if ( isset( $this->field['left'] ) && $this->field['left'] == true ) {
				echo sp_add_element( array(
					'pseudo'     => true,
					'type'       => 'number',
					'name'       => $this->element_name( '[left]' ),
					'value'      => $value['left'],
					'default'    => ( isset( $this->field['default']['left'] ) ) ? $this->field['default']['left'] : '',
					'wrap_class' => 'small-input sp-font-left',
					'before'     => 'Left<br>',
					'attributes' => array(
						'title' => __( 'Left', 'logo-carousel-pro' ),
					),
				) );
			}
			if ( isset( $this->field['top'] ) && $this->field['top'] == true ) {
				echo sp_add_element( array(
					'pseudo'     => true,
					'type'       => 'number',
					'name'       => $this->element_name( '[top]' ),
					'value'      => $value['top'],
					'default'    => ( isset( $this->field['default']['top'] ) ) ? $this->field['default']['top'] : '',
					'wrap_class' => 'small-input sp-font-top',
					'before'     => 'Top<br>',
					'attributes' => array(
						'title' => __( 'Top', 'logo-carousel-pro' ),
					),
				) );
			}
			if ( isset( $this->field['bottom'] ) && $this->field['bottom'] == true ) {
				echo sp_add_element( array(
					'pseudo'     => true,
					'type'       => 'number',
					'name'       => $this->element_name( '[bottom]' ),
					'value'      => $value['bottom'],
					'default'    => ( isset( $this->field['default']['bottom'] ) ) ? $this->field['default']['bottom'] : '',
					'wrap_class' => 'small-input sp-font-bottom',
					'before'     => 'Bottom<br>',
					'attributes' => array(
						'title' => __( 'Bottom', 'logo-carousel-pro' ),
					),
				) );
			}
			if ( isset( $this->field['right'] ) && $this->field['right'] == true ) {
				echo sp_add_element( array(
					'pseudo'     => true,
					'type'       => 'number',
					'name'       => $this->element_name( '[right]' ),
					'value'      => $value['right'],
					'default'    => ( isset( $this->field['default']['right'] ) ) ? $this->field['default']['right'] : '',
					'wrap_class' => 'small-input sp-font-right',
					'before'     => 'Right<br>',
					'attributes' => array(
						'title' => __( 'Right', 'logo-carousel-pro' ),
					),
				) );
			}

		// end container.
		echo '</div>';

		echo $this->element_after();

	}

}
