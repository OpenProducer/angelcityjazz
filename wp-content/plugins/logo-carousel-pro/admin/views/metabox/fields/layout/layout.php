<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Layout
 *
 * @since 3.3.10
 *
 */
class SP_LCPRO_Framework_Option_layout extends SP_LCPRO_Framework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		$input_type  = ( ! empty( $this->field['radio'] ) ) ? 'radio' : 'checkbox';
		$input_attr  = ( ! empty( $this->field['multi_select'] ) ) ? '[]' : '';

		echo $this->element_before();
		echo ( empty( $input_attr ) ) ? '<div class="sp-field-layout">' : '';

		if ( isset( $this->field['options'] ) ) {
			$options  = $this->field['options'];
			foreach ( $options as $key => $value ) {
				echo '<label><div class="sp-lcp-layout"><input type="' . $input_type . '" name="' . $this->element_name( $input_attr ) . '" value="' . $key . '"' . $this->element_class() . $this->element_attributes( $key ) . $this->checked( $this->element_value(), $key ) . '/><img src="' . $value . '" alt="' . $key . '" /><span class="sp-lcp-layout-before"><i class="fa fa-check" aria-hidden="true"></i></span></div><p>' . $key . '</p></label>';
			}
		}

		echo ( empty( $input_attr ) ) ? '</div>' : '';
		echo $this->element_after();

	}
}
