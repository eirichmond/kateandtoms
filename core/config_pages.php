<?php 
	
//add_action('network_admin_menu', 'add_my_netw_settings_page');
add_action('admin_menu', 'kat_add_pages');
add_action('admin_init', 'kat_init_options' );
add_action('admin_post_update_my_settings',  'update_my_settings');
add_action( 'wp_before_admin_bar_render', 'remove_wp_logo' ); 

if(function_exists("register_options_page"))
{
    register_options_page('Design');
    register_options_page('Adverts');
}

register_activation_hook(__FILE__, 'add_defaults_fn');

function kat_add_pages() {
	add_submenu_page( 'tools.php', __('Kate & Tom\'s Site Settings','kat_settings') , __('Site-Specific Settings','kat_settings') , 'manage_options' , 'kat_settings', 'kat_settings_page' ); 
}

function kat_settings_page() {
?>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/advanced-custom-fields/css/global.css" />
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/advanced-custom-fields/css/acf.css" />
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
		<h2>Kate &amp; Tom's Site Specific Settings</h2>
	<div id="acf-cols">
	<div id="acf-col-left">
		<p>Here, you will find the settings specifically for this site. For network-wide settings like house names, go to <a href="<?php echo network_admin_url(); ?>settings.php?page=kat-network-settings">Kate &amp; Tom's Network Settings</a>.</p>
		<form action="options.php" method="post">
			<?php settings_fields('plugin_options'); ?>
			<?php do_settings_sections(__FILE__); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
		</form>
	</div>
</div>
<?php
}

function kat_init_options(){
	register_setting('plugin_options', 'plugin_options', 'plugin_options_validate' );
	add_settings_section('main_section', 'Main Settings', 'section_text_fn', __FILE__);
	add_settings_field('kat_houses', 'Enable Houses?', 'setting_chk1_fn', __FILE__, 'main_section');
	add_settings_field('kat_suppliers', 'Enable Suppliers?', 'setting_chk2_fn', __FILE__, 'main_section');
	add_settings_field('kat_seasonal', 'Enable Seasonal Periods?', 'setting_chk3_fn', __FILE__, 'main_section');
	add_settings_field('kat_microsite', 'Enable Micro Site?', 'setting_is_microsite', __FILE__, 'main_section');
	add_settings_field('kat_microsite_mainpage', 'Micro Main Page', 'setting_microsite_mainpage', __FILE__, 'main_section');
	add_settings_section('fields_section', 'Custom Field Settings', 'section_text_fn', __FILE__);
	add_settings_field('kat_fields', 'Enable all custom fields?', 'setting_chk4_fn', __FILE__, 'fields_section');
	add_settings_field('kat_audience', 'Enable audience for suppliers?', 'setting_chk5_fn', __FILE__, 'fields_section');
}

function setting_chk1_fn() {
	$options = get_option('plugin_options');
	if($options['activate_houses']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='plugin_chk1' name='plugin_options[activate_houses]' type='checkbox' />";
}

function setting_chk2_fn() {
	$options = get_option('plugin_options');
	if($options['activate_suppliers']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='plugin_chk2' name='plugin_options[activate_suppliers]' type='checkbox' />";
}

function setting_chk3_fn() {
	$options = get_option('plugin_options');
	if($options['activate_seasonal']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='plugin_chk3' name='plugin_options[activate_seasonal]' type='checkbox' />";
}

function setting_is_microsite() {
	$options = get_option('plugin_options');
	if($options['activate_microsite']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='plugin_microsite' name='plugin_options[activate_microsite]' type='checkbox' />";
}

function setting_microsite_mainpage() {
	
	$options = get_option('plugin_options');
	
	if ($options['activate_microsite']) {
		$args = array(
			'post_type' => 'houses',
			'posts_per_page' => -1,
			'post_status' => 'publish'
		);
		
		$houses = get_posts($args);

		echo '<select name="plugin_options[microsite_mainpage]">';
		foreach ($houses as $house) {
			echo '<option>Select</option>';
		?>
			<option value="<?php echo $house->ID; ?>" <?php selected( $options['microsite_mainpage'], $house->ID ); ?>><?php echo $house->post_title; ?></option>
		<?php
		}
		echo '</select>';
	}
	
	
}

function setting_chk4_fn() {
	$options = get_option('plugin_options');
	if($options['activate_fields']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='plugin_chk4' name='plugin_options[activate_fields]' type='checkbox' />";
}

function setting_chk5_fn() {
	$options = get_option('plugin_options');
	if($options['activate_audience']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='plugin_chk4' name='plugin_options[activate_audience]' type='checkbox' />";
}

function update_my_settings(){     
	check_admin_referer('kat_network_nonce');
	if(!current_user_can('manage_network_options')) wp_die('FU');

	$settings = array();
	$settings['house_page_names'] = $_POST['house_page_names'];
	$settings['availability_count'] = $_POST['availability_count'];
	$settings['house_page_names'] = stripslashes(str_replace("'", '++', nl2br($settings['house_page_names'])));

	update_site_option('kat_network_settings', $settings);
	wp_redirect(admin_url('network/settings.php?page=kat-network-settings'));
	exit;
}

function add_defaults_fn() {
	$tmp = get_option('plugin_options');
    if(!is_array($tmp)) {
		$arr = array(
            "activate_houses"    => "on", 
            "activate_suppliers" => "", 
            "activate_seasonal"  => "on", 
            "activate_microsite"  => "", 
            "activate_fields"    => "", 
            "activate_audience"  => "");
		update_option('plugin_options', $arr);
	}
}

function check_microsite() {
	$microsite = false;
	
	$options = get_option('plugin_options');
	if (isset($options['activate_microsite']) && $options['activate_microsite'] == 'on') {
		$microsite = true;
	}
	return $microsite;
}
?>