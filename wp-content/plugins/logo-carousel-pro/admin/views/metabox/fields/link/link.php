<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Link
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class SP_LCPRO_Framework_Option_link extends SP_LCPRO_Framework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		$defaults_value = array(
			'link'    => '',
			'ref'    => '',
		);
		$value  = wp_parse_args( $this->element_value(), $defaults_value );
		$link   = esc_url( $value['link'] );

		echo '<input type="text" name="'. $this->element_name('[link]') .'" value="'. $link .'"'. $this->element_class() . $this->element_attributes() .'/>';

		echo sp_add_element( array(
			'pseudo'     => true,
			'type'       => 'checkbox',
			'name'       => $this->element_name( '[ref]' ),
			'value'      => $value['ref'],
			'default'    => ( isset( $this->field['default']['ref'] ) ) ? $this->field['default']['ref'] : '',
			'before'     => 'Nofollow',
		) );

		echo $this->element_after();

	}

}
