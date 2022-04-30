<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Slider
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class SP_LCPRO_Framework_Option_slider extends SP_LCPRO_Framework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		$options = array(
			'step'  => ( empty( $this->field['options']['step'] ) ) ? 1 : $this->field['options']['step'],
			'unit'  => ( empty( $this->field['options']['unit'] ) ) ? '' : $this->field['options']['unit'],
			'min'   => ( empty( $this->field['options']['min'] ) ) ? 0 : $this->field['options']['min'],
			'max'   => ( empty( $this->field['options']['max'] ) ) ? 200 : $this->field['options']['max'],
		);

		echo $this->element_before();
		echo '<div class="sp-lcp-slider"><div></div></div>';
		echo '<input data-slider=\'' . wp_json_encode( $options ) . '\' type="'. $this->element_type() .'" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .' readonly/>';
		echo $this->element_after();

	}

}