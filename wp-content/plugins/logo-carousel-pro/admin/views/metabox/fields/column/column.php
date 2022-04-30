<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Column
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class SP_LCPRO_Framework_Option_Column extends SP_LCPRO_Framework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		$defaults_value = array(
			'column1'   => '',
			'column2'   => '',
			'column3'   => '',
			'column4'   => '',
			'column5'   => '',
			'title1'    => '',
			'title2'    => '',
			'title3'    => '',
			'title4'    => '',
			'title5'    => '',
			'help1'     => '',
			'help2'     => '',
			'help3'     => '',
			'help4'     => '',
			'help5'     => '',
		);
		$value  = wp_parse_args( $this->element_value(), $defaults_value );
		$title  = wp_parse_args( $this->field['default'], $defaults_value );
		$help   = wp_parse_args( $this->field['default'], $defaults_value );

		if ( isset( $this->field['column1'] ) && $this->field['column1'] == true ) {
			echo sp_add_element( array(
				'pseudo'     => true,
				'type'       => 'number',
				'name'       => $this->element_name( '[column1]' ),
				'value'      => $value['column1'],
				'default'    => ( isset( $this->field['default']['column1'] ) ) ? $this->field['default']['column1'] : '',
				'wrap_class' => 'small-input sp-column-field',
				'before'     => '<span>' . $title['title1'] . '</span><br>',
				'help'       => $help['help1'],
				'attributes' => array(
					'min' => 1,
					'max' => 30,
				),
			) );
		}
		if ( isset( $this->field['column2'] ) && $this->field['column2'] == true ) {
			echo sp_add_element( array(
				'pseudo'     => true,
				'type'       => 'number',
				'name'       => $this->element_name( '[column2]' ),
				'value'      => $value['column2'],
				'default'    => ( isset( $this->field['default']['column2'] ) ) ? $this->field['default']['column2'] : '',
				'wrap_class' => 'small-input sp-column-field',
				'before'     => '<span>' . $title['title2'] . '</span><br>',
				'help'       => $help['help2'],
				'attributes' => array(
					'min' => 1,
					'max' => 30,
				),
			) );
		}
		if ( isset( $this->field['column3'] ) && $this->field['column3'] == true ) {
			echo sp_add_element( array(
				'pseudo'     => true,
				'type'       => 'number',
				'name'       => $this->element_name( '[column3]' ),
				'value'      => $value['column3'],
				'default'    => ( isset( $this->field['default']['column3'] ) ) ? $this->field['default']['column3'] : '',
				'wrap_class' => 'small-input sp-column-field',
				'before'     => '<span>' . $title['title3'] . '</span><br>',
				'help'       => $help['help3'],
				'attributes' => array(
					'min' => 1,
					'max' => 30,
				),
			) );
		}
		if ( isset( $this->field['column4'] ) && $this->field['column4'] == true ) {
			echo sp_add_element( array(
				'pseudo'     => true,
				'type'       => 'number',
				'name'       => $this->element_name( '[column4]' ),
				'value'      => $value['column4'],
				'default'    => ( isset( $this->field['default']['column4'] ) ) ? $this->field['default']['column4'] : '',
				'wrap_class' => 'small-input sp-column-field',
				'before'     => '<span>' . $title['title4'] . '</span><br>',
				'help'       => $help['help4'],
				'attributes' => array(
					'min' => 1,
					'max' => 30,
				),
			) );
		}
		if ( isset( $this->field['column5'] ) && $this->field['column5'] == true ) {
			echo sp_add_element( array(
				'pseudo'     => true,
				'type'       => 'number',
				'name'       => $this->element_name( '[column5]' ),
				'value'      => $value['column5'],
				'default'    => ( isset( $this->field['default']['column5'] ) ) ? $this->field['default']['column5'] : '',
				'wrap_class' => 'small-input sp-column-field',
				'before'     => '<span>' . $title['title5'] . '</span><br>',
				'help'       => $help['help5'],
				'attributes' => array(
					'min' => 1,
					'max' => 30,
				),
			) );
		}

		echo $this->element_after();

	}

}
