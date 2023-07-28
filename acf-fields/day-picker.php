<?php

/*
*  ACF Text Field Class
*
*  All the logic for this field type
*
*  @class 		acf_field_text
*  @extends		acf_field
*  @package		ACF
*  @subpackage	Fields
*/

if( ! class_exists('acf_field_day_picker') ) :

class acf_field_day_picker extends acf_field {
	
	function __construct() {
		
		// vars
		$this->name = 'day_picker';
		$this->label = __("Day picker",'acf-day_picker');
		$this->defaults = array(
		);
		
		
		// do not delete!
    	parent::__construct();
	}
	
	function render_field( $field ) {

		$display_vals = '';
		if (is_array($field['value'])) {
			foreach ($field['value'] as $v) {
				$display_vals .= $v.',';
			}
			$display_vals = rtrim($display_vals, ",");
		}

		// render
		$e = '<div class="acf-input-wrap">';
		$e .= '<input id="'.$field['id'].'" name="'.$field['name'].'" class="days" hidden="hidden" value="'.$display_vals.'" />'; // Days 
		$e .= '<div class="selector"></div></div>';
		
		$addDates = ($display_vals != '' ? "addDates: [".$display_vals."]," : '');
		
		// return
		echo $e;
	}
	
	function render_field_settings( $field ) {

	}
	
	function input_admin_enqueue_scripts(  ) {
		$dir = get_template_directory_uri();
		wp_register_script('acf-input-day_picker', "{$dir}/js/jquery-ui.multidatespicker.js" );
		wp_enqueue_script('acf-input-day_picker');
	}
	
	function update_value( $value, $post_id, $field ) {
		
		// validate
		if( empty($value) ) {
			return $value;
			
		}
		
		return explode(',', $value);
	}
	
}

new acf_field_day_picker();

endif;

?>