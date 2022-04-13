<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// check if class already exists
if( !class_exists('acf_field_image_focus_point') ) :

class acf_field_image_focus_point extends acf_field {


	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct( $settings ) {

		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/

		$this->name = 'image_focus_point';


		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

		$this->label = __('Image Focus Point', 'acf-image_focus_point');


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = 'relational';


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults = array(
			'font_size'	=> 14,
			'x'	=> 0,
			'y'	=> 0,
		);

		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('image_focus_point', 'error');
		*/

		$this->l10n = array(
			'error'	=> __('Error! Please enter click to create a coordinate pair', 'acf-image_focus_point'),
		);


		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/

		$this->settings = $settings;


		// do not delete!
    	parent::__construct();

	}


	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field ) {

		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/

		acf_render_field_setting( $field, array(
			'label'			=> __( 'Image Field Label', 'acf-image_focus_point' ),
			'instructions'	=> __( 'Field label of image to link to', 'acf-image_focus_point' ),
			'placeholder'   => __( 'ACF_IMAGE_FIELD_NAME', 'acf-image_focus_point' ),
			'type'			=> 'text',
			'name'			=> 'image_field_label',
			'required'      => true
		));

		acf_render_field_setting( $field, array(
			'label'			=> __( 'Percentage Based Coordinates', 'acf-image_focus_point' ),
			'instructions'	=> __( 'Convert the coordinate pair to percentages instead of the raw X / Y pair', 'acf-image_focus_point' ),
			'type'			=> 'true_false',
			'name'			=> 'percent_based',
		));

	}



	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field( $field ) {
		/*
		*  Review the data of $field.
		*  This will show what data is available
		*/
		$img_label     = esc_attr( $field['image_field_label'] );
		$field_name    = esc_attr( $field['name'] );
		$field_value   = esc_attr( $field['value'] );
		$percent_based = array_key_exists( 'percent_based', $field ) && $field['percent_based'] ? 1 : 0;
		$xy_pair       = explode( ' ', $field_value );
		if ( 1 < count( $xy_pair ) ) {
			$x = $xy_pair[0];
			$y = $xy_pair[1];
		} else {
			$x = 0;
			$y = 0;
		}
		echo <<< HTML

					<!-- XY Coordinate Pair -->
					<div class="input-container">
						<input class="$this->name-input" type="text" name="$field_name" value="$field_value" />
						<ul class="presets">
							<li>
								<button class="button center">Center</button>
							</li>
						</ul>
					</div>
					<!-- Image where we will catch the user's clicks -->
					<div class="$this->name-image">
						<img src="" data-percent-based="$percent_based" data-label="$img_label" />
						<span style="left:$x;top:$y;"></span>
					</div>
		HTML;
}

	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function input_admin_enqueue_scripts() {

		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];


		// register & include JS
		// wp_register_script( 'acf-input-image_focus_point', "{$url}assets/js/input.js", array('acf-input'), $version );
		wp_register_script( 'acf-input-image_focus_point', "{$url}assets/js/input.js", array('acf-input'), null );
		wp_enqueue_script('acf-input-image_focus_point');


		// register & include CSS
		wp_register_style( 'acf-input-image_focus_point', "{$url}assets/css/input.css", array('acf-input'), $version );
		wp_enqueue_style('acf-input-image_focus_point');

	}
}

// initialize
new acf_field_image_focus_point( $this->settings );


// class_exists check
endif;

?>
