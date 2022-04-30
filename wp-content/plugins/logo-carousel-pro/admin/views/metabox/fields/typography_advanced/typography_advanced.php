<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

/**
 *
 * Field: Typography Advanced
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class SP_LCPRO_Framework_Option_typography_advanced extends SP_LCPRO_Framework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		$defaults_value = array(
			'family'    => 'Arial',
			'variant'   => 'regular',
			'font'      => 'websafe',
			'size'      => '14',
			'height'    => '',
			'alignment' => '',
			'transform' => '',
			'spacing'   => '',
			'color'     => ''
		);

		$default_variants = apply_filters( 'sp_websafe_fonts_variants', array(
			'regular',
			'italic',
			'700',
			'700italic',
			'inherit'
		) );

		$websafe_fonts = apply_filters( 'sp_websafe_fonts', array(
			'Arial',
			'Arial Black',
			'Comic Sans MS',
			'Impact',
			'Lucida Sans Unicode',
			'Tahoma',
			'Trebuchet MS',
			'Verdana',
			'Courier New',
			'Lucida Console',
			'Georgia, serif',
			'Palatino Linotype',
			'Times New Roman'
		) );

		$value         = wp_parse_args( $this->element_value(), $defaults_value );
		$family_value  = $value['family'];
		$variant_value = $value['variant'];
		$is_variant    = ( isset( $this->field['variant'] ) && $this->field['variant'] === false ) ? false : true;
		$is_chosen     = ( isset( $this->field['chosen'] ) && $this->field['chosen'] === false ) ? '' : 'chosen ';
		$google_json   = sp_get_google_fonts();
		$chosen_rtl    = ( is_rtl() && ! empty( $is_chosen ) ) ? 'chosen-rtl ' : '';


		//Container
		echo '<div class="sp_font_field" data-id="' . $this->field['id'] . '">';

		if ( is_object( $google_json ) ) {

			$googlefonts = array();

			foreach ( $google_json->items as $key => $font ) {
				$googlefonts[ $font->family ] = $font->variants;
			}

			$is_google = ( array_key_exists( $family_value, $googlefonts ) ) ? true : false;

			echo '<div class="sp-element sp-typography-family">Font Family<br>';
			echo '<select name="' . $this->element_name( '[family]' ) . '" class="' . $is_chosen . $chosen_rtl . 'sp-typo-family" data-atts="family"' . $this->element_attributes() . '>';

			do_action( 'sp_typography_family', $family_value, $this );

			echo '<optgroup label="' . __( 'Web Safe Fonts', 'logo-carousel-pro' ) . '">';
			foreach ( $websafe_fonts as $websafe_value ) {
				echo '<option value="' . $websafe_value . '" data-variants="' . implode( '|', $default_variants ) . '" data-type="websafe"' . selected( $websafe_value, $family_value, true ) . '>' . $websafe_value . '</option>';
			}
			echo '</optgroup>';

			echo '<optgroup label="' . __( 'Google Fonts', 'logo-carousel-pro' ) . '">';
			foreach ( $googlefonts as $google_key => $google_value ) {

				echo '<option value="' . $google_key . '" data-variants="' . implode( '|', $google_value ) . '" data-type="google" ' . selected( $google_key, $family_value, true ) . '>' . $google_key . '</option>';
			}
			echo '</optgroup>';

			echo '</select>';
			echo '</div>';

			if ( ! empty( $is_variant ) ) {

				$variants = ( $is_google ) ? $googlefonts[ $family_value ] : $default_variants;
				$variants = ( $value['font'] === 'google' || $value['font'] === 'websafe' ) ? $variants : 'regular';
				echo '<div class="sp-element sp-typography-variant">Font Weight<br>';
				echo '<select name="' . $this->element_name( '[variant]' ) . '" class="' . $is_chosen . $chosen_rtl . 'sp-typo-variant" data-atts="variant">';
				foreach ( $variants as $variant ) {
					echo '<option value="' . $variant . '" ' . $this->checked( $variant_value, $variant, 'selected' ) . '>' . $variant . '</option>';
				}
				echo '</select>';
				echo '</div>';

			}

			echo sp_add_element( array(
				'pseudo'     => true,
				'type'       => 'number',
				'name'       => $this->element_name( '[size]' ),
				'value'      => $value['size'],
				'default'    => ( isset( $this->field['default']['size'] ) ) ? $this->field['default']['size'] : '',
				'wrap_class' => 'small-input sp-font-size',
				'before'     => 'Font Size<br>',
				'attributes' => array(
					'title' => __( 'Font Size', 'logo-carousel-pro' ),
				),
			) );

			echo sp_add_element( array(
				'pseudo'     => true,
				'type'       => 'number',
				'name'       => $this->element_name( '[height]' ),
				'value'      => $value['height'],
				'default'    => ( isset( $this->field['default']['height'] ) ) ? $this->field['default']['height'] : '',
				'wrap_class' => 'small-input sp-font-height',
				'before'     => 'Line Height<br>',
				'attributes' => array(
					'title' => __( 'Line Height', 'logo-carousel-pro' ),
				),
			) );
			echo '<div class="sp-divider"></div>';
			echo sp_add_element( array(
				'pseudo'     => true,
				'type'       => 'select',
				'name'       => $this->element_name( '[alignment]' ),
				'value'      => $value['alignment'],
				'default'    => ( isset( $this->field['default']['alignment'] ) ) ? $this->field['default']['alignment'] : '',
				'wrap_class' => 'small-input sp-font-alignment',
				'class' => 'chosen',
				'before'     => 'Alignment<br>',
				'options'    => array(
					'left'   => __( 'Left', 'logo-carousel-pro' ),
					'center' => __( 'Center', 'logo-carousel-pro' ),
					'right'  => __( 'Right', 'logo-carousel-pro' ),
				),

			) );
			echo sp_add_element( array(
				'pseudo'     => true,
				'type'       => 'select',
				'name'       => $this->element_name( '[transform]' ),
				'value'      => $value['transform'],
				'default'    => ( isset( $this->field['default']['transform'] ) ) ? $this->field['default']['transform'] : '',
				'wrap_class' => 'small-input sp-font-transform',
				'class' => 'chosen',
				'before'     => 'Transform<br>',
				'options'    => array(
					'none'       => __( 'None', 'logo-carousel-pro' ),
					'capitalize' => __( 'Capitalize', 'logo-carousel-pro' ),
					'uppercase'  => __( 'Uppercase', 'logo-carousel-pro' ),
					'lowercase'  => __( 'Lowercase', 'logo-carousel-pro' ),
					'initial'    => __( 'Initial', 'logo-carousel-pro' ),
					'inherit'    => __( 'Inherit', 'logo-carousel-pro' ),
				),
			) );
			echo sp_add_element( array(
				'pseudo'     => true,
				'type'       => 'select',
				'name'       => $this->element_name( '[spacing]' ),
				'value'      => $value['spacing'],
				'default'    => ( isset( $this->field['default']['spacing'] ) ) ? $this->field['default']['spacing'] : '',
				'wrap_class' => 'small-input sp-font-spacing',
				'class' => 'chosen',
				'before'     => 'Letter Spacing<br>',
				'options'    => array(
					'normal' => __( 'Normal', 'logo-carousel-pro' ),
					'.3px'   => __( '0.3px', 'logo-carousel-pro' ),
					'.5px'   => __( '0.5px', 'logo-carousel-pro' ),
					'1px'    => __( '1px', 'logo-carousel-pro' ),
					'1.5px'  => __( '1.5px', 'logo-carousel-pro' ),
					'2px'    => __( '2px', 'logo-carousel-pro' ),
					'3px'    => __( '3px', 'logo-carousel-pro' ),
					'5px'    => __( '5px', 'logo-carousel-pro' ),
					'10px'   => __( '10px', 'logo-carousel-pro' ),
				),
			) );
			echo '<div class="sp-divider"></div>';
			if ( isset( $this->field['color'] ) && $this->field['color'] == true ) {
				echo '<div class="sp-element sp-typography-color">Color<br>';
				echo sp_add_element( array(
					'pseudo'     => true,
					'id'         => $this->field['id'] . '_color',
					'type'       => 'color_picker',
					'name'       => $this->element_name( '[color]' ),
					'attributes' => array(
						'data-atts' => 'bgcolor',
					),
					'value'      => $value['color'],
					'default'    => ( isset( $this->field['default']['color'] ) ) ? $this->field['default']['color'] : '',
					'rgba'       => ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				) );
				echo '</div>';
			}

			/**
			 * Font Preview
			 */
			if ( isset( $this->field['preview'] ) && $this->field['preview'] == true ) {
				if ( isset( $this->field['preview_text'] ) ) {
					$preview_text = $this->field['preview_text'];
				} else {
					$preview_text = 'Lorem ipsum dolor sit amet, pro ad sanctus admodum, vim at insolens appellantur. Eum veri adipiscing an, probo nonumy an vis.';
				}
				echo '<div id="preview-' . $this->field['id'] . '" class="sp-font-preview">' . $preview_text . '</div>';
			}

			echo '<input type="text" name="' . $this->element_name( '[font]' ) . '" class="sp-typo-font hidden" data-atts="font" value="' . $value['font'] . '" />';

		} else {

			echo __( 'Error! Can not load json file.', 'logo-carousel-pro' );

		}

		//end container
		echo '</div>';

		echo $this->element_after();

	}

}