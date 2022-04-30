<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class SP_LCPRO_Framework_Option_icon extends SP_LCPRO_Framework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		$value  = $this->element_value();
		$hidden = ( empty( $value ) ) ? ' hidden' : '';

		echo '<div class="sp-icon-select">';
		echo '<span class="sp-icon-preview' . $hidden . '"><i class="' . $value . '"></i></span>';
		echo '<a href="#" class="button button-primary sp-icon-add">'. __( 'Add Icon', 'logo-carousel-pro' ) . '</a>';
		echo '<a href="#" class="button sp-warning-primary sp-icon-remove'. $hidden .'">'. __( 'Remove Icon', 'logo-carousel-pro' ) .'</a>';
		echo '<input type="text" name="'. $this->element_name() .'" value="'. $value .'"'. $this->element_class( 'sp-icon-value' ) . $this->element_attributes() .' />';
		echo '</div>';

		echo $this->element_after();

	}

}
