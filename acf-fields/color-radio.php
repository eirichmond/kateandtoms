<?php

class acf_field_color_radio extends acf_field {
	
	function __construct() {
		$this->name = 'color_radio';
		$this->label = __('Color radio', 'acf-color_radio');
		$this->category = 'basic';
    	parent::__construct();
	}

	function render_field( $field ) {
		$field['layout'] = isset($field['layout']) ? $field['layout'] : 'vertical';
		$field['choices'] = isset($field['choices']) ? $field['choices'] : array();
		if(empty($field['choices']))
		{
			echo '<p>' . __("No colours to choose from :(",'acf') . '</p>';
			return false;
		}
				
		echo '<ul class="radio_list ' . $field['class'] . ' ' . $field['layout'] . '">';
		
		$i = 0;
		foreach($field['choices'] as $key => $value)
		{
			$i++;
			
			// if there is no value and this is the first of the choices and there is no "0" choice, select this on by default
			// the 0 choice would normally match a no value. This needs to remain possible for the create new field to work.
			if(!$field['value'] && $i == 1 && !isset($field['choices']['0']))
			{
				$field['value'] = $key;
			}
			
			$selected = '';
			
			if($key == $field['value'])
			{
				$selected = 'checked="checked" data-checked="checked"';
			}
			
			echo '<li><input id="' . $field['id'] . '-' . $key . '" class="color_'.$key.'" type="radio" name="' . $field['name'] . '" value="' . $key . '" ' . $selected . ' style="" /></li>';
		}
		
		echo '</ul>';


	}
	
}

new acf_field_color_radio();

?>
