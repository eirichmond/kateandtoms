<?php
	global $katglobals;
	$blog_id = get_current_blog_id();
	$dtypes = array('1' => 'Weekend', '2' => 'Week', '3' => 'Midweek');
	
	$sleep_custom      = $katglobals['sleep'];
	$sleep_default     = $katglobals['sleep_default'];	
	
	$berth_custom  	   = $katglobals['berth'];	
	$berth_default     = $katglobals['berth_default'];	
	
	$locations_custom  = $katglobals['locations'];
	$locations_default = $katglobals['locations_default'];
	
	$features_custom   = $katglobals['features'];
	$features_default  = $katglobals['features_default'];
	
	$sleep_sizes = (array_key_exists($blog_id, $sleep_custom) ? $sleep_custom[$blog_id] : $sleep_default);
	$locations = (array_key_exists($blog_id, $locations_custom) ? $locations_custom[$blog_id] : $locations_default);
	$features = (array_key_exists($blog_id, $features_custom) ? $features_custom[$blog_id] : $features_default);
	$berth = (array_key_exists($blog_id, $berth_custom) ? $berth_custom[$blog_id] : $berth_default);
	
	$search_detail_areas = array(
		'date' => array (
			'icon' => 'calendar',
			'is_taxonomy' => false,
			'prefix' => ''
		),
		'size' => array (
			'icon' => 'home',
			'is_taxonomy' => false,
			'prefix' => $berth
		),
		'location' => array (
			'icon' => 'map-marker',
			'is_taxonomy' => true,
			'prefix' => ''
		),
		'feature' => array (
			'icon' => 'tag',
			'is_taxonomy' => true,
			'prefix' => ''
		
		)
		// 'activity' => array (
		// 	'icon' => 'star',
		// 	'is_taxonomy' => true,
		// 	'prefix' => ''
		// )
	);
	
	$hidden_inputs = array(
		array('s2', 's', false),
		array('date', 'date', false),
		array('dateValue', 'dtype', false),
		array('size', 'size', false),
		array('locationToggle', 'location', true),
		array('feature', 'feature', true),
		// array('activity', 'activity', true)
	);
	
	$all_locations = get_terms("location");
	$all_features = get_terms("feature");
	

	$locations_dropdown = getDropdownItems($locations, $all_locations, 'location');
	$features_dropdown = getDropdownItems($features, $all_features, 'feature');

	$inputs = getSearchInputs();

	function getSearchInputs() {
	    $inputs = array();

	    foreach (array('s', 'date', 'dtype', 'size' ,'lall', 'fall') as $input) {
	         if (isset($_GET[$input])) {
				 if (extension_loaded('newrelic')) {
					newrelic_add_custom_parameter('$input', $_GET[$input]);
				 }
				 $inputs[$input] = $_GET[$input];
			 }
	    }

	    foreach (array('location', 'feature', 'activity') as $input) {
	         if (isset($_GET[$input])) {
				 $inputs['taxonomies'][$input] = $_GET[$input];
			 }
	    }
		
		if (isset($inputs['date'])) {
			$date = str_replace('/', '-', $inputs['date']);
			preg_match('^(.*)[-](.*)[-](.*)$^', $date, $matches);
		
			if ($matches) {
				if (strlen($matches[3]) == 2) {
					$matches[3] = '20'.$matches[3];
				}
				$inputs['date'] = $matches[1].'-'.$matches[2].'-'.$matches[3];
			}
		}
		
		// Set if a taxonomy page
		// Also consider is_tag and is_category
		if (is_tax() && !array_key_exists('s', $inputs)) {
			global $wp_query;
			$inputs['taxonomy_page'] = true;
			$type = $wp_query->queried_object->taxonomy; 
			$vars = $wp_query->query_vars; 	
			$term = $vars['term'];
			$inputs['taxonomies'][$type] = $term;
			if ($type == 'size') {
				$term_id = $wp_query->queried_object->term_id; 
				$inputs['smin'] = get_field('min_no','size_'.$term_id);
				$inputs['smax'] = get_field('max_no','size_'.$term_id);
			}

		}
		if (get_post_type() == 'seasonal') {
			$inputs['seasonal'] = true;
			$beginning = get_field('beginning');
			$ending = get_field('ending');
			$periodsToInclude = get_field('periods_to_include');
			if (in_array('Week',$periodsToInclude)) array_push($periodsToInclude,'WeekFridays');
			HouseSearch::seasonalSetup($beginning,$ending,$periodsToInclude);
		}
		
		if (get_post_type() == 'availability') {
			$inputs['availability'] = true;
			$rolling_period = get_field('rolling_upcoming_period');
			$periods_to_include = get_field('periods_to_include');
			$show_associated_houses = get_field('show_associated_houses');
			if($show_associated_houses) {
				$avail_from_features = get_field('avail_from_features');
			}
			HouseSearch::get_availability_for_these_periods( $rolling_period, $periods_to_include, $avail_from_features );
		}

		
		// Set to show all
		if (array_key_exists('s', $inputs)) {
			if ($inputs['s'] == '') {
				$inputs['s'] = 'all';
			}
		}
		else $inputs['s'] = 'all';

		return $inputs;
	}
	
	function isActive($inputs, $type, $is_taxonomy, $value = null) {
		if ($is_taxonomy) {
			if (array_key_exists('taxonomies', $inputs)) {
				return isActive($inputs['taxonomies'], $type, false, $value);
			}
		}
		else {
			if (array_key_exists($type, $inputs))  {
				if ($value) {
					return $inputs[$type] == $value;
				}
				else {
					return $inputs[$type] != '';
				}
			}
		}
		return false;
	}
	
	function getValue($inputs, $name, $is_taxonomy) {
		if ($is_taxonomy) {
			if (array_key_exists('taxonomies', $inputs)) {
				return getValue($inputs['taxonomies'], $name, false);
			}
		}
		else {
			if (array_key_exists($name, $inputs)) {
				return $inputs[$name];
			}
		}
		return '';
	}
	
	// top items = array, all items = array of objects
	function getDropdownItems($top_items, $all_items, $type) {
		$result = $top_items;
		foreach ($all_items as $term) {
			// Uses includeTerm() which is in KAT master settings
			if (includeTerm($term->term_id, $type) && !in_array($term->name, $top_items)) {
				$result = $result + array($term->slug => $term->name);
			}
		}
		return $result;
	}

	function makeButtonGroup($inputArray, $inputs, $is_taxonomy, $type) {
		echo '<div class="btn-group" data-toggle="buttons-radio" data-toggle-name="'.$type.'">';
		makeButtons($inputArray, $inputs, $is_taxonomy, $type);
		echo '</div>';
	}
	
	function makeButtons($inputArray, $inputs, $is_taxonomy, $type) {
		foreach($inputArray as $value => $name) {
			$active = isActive($inputs, $type, $is_taxonomy, $value);
			makeButton($name, $value, $active, $type);
		}
	}
	
	function makeDropdownToggle($inputs, $type, $is_taxonomy, $taxonomy_names_array) {
		$active = isActive($inputs, $type, $is_taxonomy);
		$class = 'btn dropdown-'.$type.' dropdown-toggle';
		if ($is_taxonomy) {
			$label = ($active ? $taxonomy_names_array[$inputs['taxonomies'][$type]] : 'Any');
		}
		else {
			$label = ($active ? $taxonomy_names_array[$inputs[$type]] : 'Any');
		}
		
		echo '<a class="'.$class.'" data-toggle="dropdown" href="javascript:;">
			<span class="search_dropdown_current_'.$type.'">'.$label.' </span> <span class="caret"></span>
			</a>';
	}

	
	function makeDropdown($inputArray, $type) {
		echo '<ul class="dropdown-menu">
			<li><a href="javascript:;" data-type="'.$type.'" data-value="">Any</a></li>';
		foreach($inputArray as $value => $name) {
			makeListItem($name, $value, $type);
		}
		echo '</ul>';
	}
	
	function makeHiddenInputs($inputs, $input_array) {
		foreach ($input_array as $i) {
			makeHiddenInput($inputs, $i[0], $i[1], $i[2]);
		}
	}
	
	function makeOptions($values) {
		foreach ($values as $name => $value) {
			makeOption($name, $value);
		}
	}
	
	function makeButton($name, $value, $active, $type) {
		$class = 'btn '.$type.'-toggle '.($active ? ' active' : '');
		echo '<button type="button" value="'.$value.'" name="'.$type.'" class="'.$class.'" data-toggle="button">'.$name.'</button>';
	}
	function makeListItem($name, $value, $type) {
		echo '<li><a href="javascript:;" data-type="'.$type.'" data-value="'.$value.'" >'.$name.'</a></li>';
	}
	
	function makeOption($name, $value) {
		echo '<option value="'.$name.'">'.$value.'</option>';
	}
	
	function makeHiddenInput($inputs, $id, $name, $isTaxonomy) {
		$value = getValue($inputs, $name, $isTaxonomy);
		echo '<input type="hidden" hidden="true" name="'.$name.'" value="'.$value.'">
		';
	}
	
	function makeDatePicker($inputs) {
		$value = getValue($inputs, 'date', false);
		$class = 'datepicker input-medium small';
		echo '<input id="datepicker2" value="'.$value.'"name="date" type="text" size="11" placeholder="Select Date..." class="'.$class.'" />';
	}
	
	function getSearchTitle($inputs) {
		if (isset($inputs['taxonomy_page'])) {
			if ($inputs['taxonomy_page']) {
				$description = category_description();
				return ($description != "" ? $description : single_cat_title('Big Cottages with a ') );
			}
		}
		return 'Search All Houses';
	}
	
	function isTaxonomyPage($inputs) {
		if (isset($inputs['taxonomy_page'])) {
			if ($inputs['taxonomy_page']) return true;
		}
		return false;
	}
	
	function isSeasonalPage($inputs) {
		if (isset($inputs['seasonal'])) {
			if ($inputs['seasonal']) return true;
		}
		return false;
	}
	
	function isAvailabilityPage($inputs) {
		if (isset($inputs['availability'])) {
			if ($inputs['availability']) return true;
		}
		return false;
	}



	function getSearchDetailTitle($inputs, $area, $is_taxonomy) {
		if ($is_taxonomy) {
			if (isset($inputs['taxonomies'][$area])) {
				$term = get_term_by('slug', $inputs['taxonomies'][$area], $area); 
				return $term->name;
			}
		}
		elseif (isset($inputs[$area])) {
			if ($area == 'size') {
				global $sleep_sizes;
				$types = array( 6 => '6 to 12', 14 => '14 to 20', 20 => '20 or more');
				if (array_key_exists($inputs[$area], $sleep_sizes)) {
					return $sleep_sizes[$inputs[$area]];
				}
			}
			elseif ($area = 'date') {
				$periods = array( 1 => '<var class="dtypep">Weekends</var>', 2 => '<var class="dtypep">Weeks</var>', 3 => '<var class="dtypep">Midweek</var>');
				$date_time = strtotime($inputs['date']);
				$date_time_corrected = OnlineSearch::getDateToShow($date_time, $inputs['dtype']);
				return $periods[$inputs['dtype']].' from '.date('l j F Y', $date_time_corrected);
			}
		}
		return '';
	}
	
	// Return false if the required values exist to show
	function isSearchDetailHidden($inputs, $area, $is_taxonomy) {
		$hidden = true;
		if ($is_taxonomy) {
			if (isset($inputs['taxonomies'])) {
				return isSearchDetailHidden($inputs['taxonomies'], $area, false);
			}
		} elseif (isset($inputs[$area])) {
			if ($area == 'date' && $inputs[$area] != '') {
				$hidden = false;
			}
			if ($area == 'size' && $inputs[$area] != '') {
				$hidden = false;
			}
		}
		return $hidden;
	}
	
	function makeTaxonomyImage($image_id) {
		$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : 'kate and tom\'s taxonomy';
		$align = get_img_description($image_id);
		$align = (!empty($align) ? $align : 'absoluteCenter');

    	$image = wp_get_attachment_image_src($image_id,'post-thumbnail');
		$image = $image[0];
		
		echo '<div style="height:400px;"><div class="" style="height: 400px; overflow:hidden; ">';
	    echo '<div class="cropped"><img src="'.$image.'" class="'.$align.' wp-post-image" alt="'.$image_alt.'" /></div>';
	    echo '</div></div>';
	}
	
	function makeTaxonomyHeader($inputs) {
		global $wp_query;
		$taxonomy_value = reset($inputs['taxonomies']);
		$taxonomy_type = key($inputs['taxonomies']);
		$cat = $wp_query->queried_object->term_id; 
		$title = $wp_query->queried_object->name; 
		$url = '/?s=all&'.$taxonomy_type.'='.$taxonomy_value;
		$post_id = $taxonomy_type . '_' . $cat;
	
		$image_id = get_option($post_id.'_tax_intro_image');
		
		$taxLayoutOption = get_option($type.'_'.$cat.'_taxonomy_filter_layout_option');
		
	    if ($image_id != "") {
			makeTaxonomyImage($image_id);
	    }
		else echo '<div class="headspace"></div>';
		
		Widget::createHeader(null, $title, $url);
		
		if ($taxLayoutOption == 'custom') {
			Widget::createWidgets($post_id, $url);
		}
	
		Widget::createTaxfilter(null, $title, $url);
	}
	
	
	if (isTaxonomyPage($inputs)) {
		makeTaxonomyHeader($inputs);
	} elseif ( isSeasonalPage($inputs) || isAvailabilityPage($inputs) ) {
		if (!has_post_thumbnail()) { echo '<div class="headspace"></div>'; }
		else echo '<div class="h-responsive overflow-hidden flex justify-content-center align-items-center">'; the_post_thumbnail('huge', array('class' => 'attachment-post-thumbnail')); echo '</div>';
	
		Widget::createHeader($post->ID);
		Widget::createWidgets($post->ID);
	} 
	else {
	
?>
<div class="search_title_holder">
	<div class="container searchall">
		<div class="row">
			<h1 class="page-title standard span9"><?php echo getSearchTitle($inputs); ?></h1>
			<?php 
				/*if (isTaxonomyPage($inputs)) {
					echo '<a href="/?s=all&amp;location=sea" class="btn btn-5 floatright">
						<i class="icon-filter icon wtpad"></i> Filter Further</a>';
				}
				else { */
					echo '<div class="span12 menu_links"><span class="search_desc">';
					foreach ($search_detail_areas as $name => $properties) {
						$area_class = 'search_area_'.$name;
						$desc_class = 'search_desc_'.$name;
						$icon = 'icon-'.$properties['icon'];
						$title = getSearchDetailTitle($inputs, $name, $properties['is_taxonomy']);
						$hidden = isSearchDetailHidden($inputs, $name, $properties['is_taxonomy']);
						$hidden_class = ($hidden ? ' hidden' : '');
						$prefix = (isset($properties['prefix']) ? $properties['prefix'] : '');
						echo '<span class="'.$area_class.$hidden_class.'"><i class="'.$icon.'"></i> '.$prefix.' <span class="'.$desc_class.'">'.$title.'</span></span>'; 
					}
					echo '</span></div>';
				//}
			?>
		</div>
	</div>
	<div id="pin" class="page-title_cont color9">
		<div class="container">
			<div class="row">
				<div class="span12 accordion-group">

				<!-- <div class="search-all-trigger">
					<span class="spadder">Open Seach Filters</span>
				</div>

				<div class="search-all-filters">
					<span class="spadder">This is the filter</span>
				</div> -->
				

					<form id="searchBar" action="/" method="GET">
						<?php makeHiddenInputs($inputs, $hidden_inputs); ?>
						<div class="row">
							<div class="span3">
								<div class="search_title">
									<i class="icon-calendar"></i> When <?php makeDatePicker($inputs); ?>
								</div>
								<?php makeButtonGroup($dtypes, $inputs, false, 'dtype'); ?>
							</div>
							<div class="mhide span3">
								<div class="search_title">
									<i class="icon-home"></i> <?php echo $berth; ?>
									<div class="btn-group">
										<?php 
											makeDropdownToggle($inputs, 'size', false, $sleep_sizes);
											makeDropdown($sleep_sizes, 'size'); 
										?>
									</div>
								</div>
								<?php makeButtonGroup($sleep_sizes, $inputs, false, 'size'); ?>
							</div>
							<div class="mhide span3">
								<div class="search_title">
									<i class="icon-map-marker"></i> Where
									<div class="btn-group">
										<?php 
											makeDropdownToggle($inputs, 'location', true, $locations_dropdown);
											makeDropdown($locations_dropdown, 'location'); 
										?>
									</div>
								</div>
								<?php makeButtonGroup($locations, $inputs, true, 'location'); ?>
				
							</div>
							<div class="mhide span3">
								<div class="search_title">
									<i class="icon-tag"></i> Features
									<div class="btn-group">
										<?php 
											makeDropdownToggle($inputs, 'feature', true, $features_dropdown);
											makeDropdown($features_dropdown, 'feature'); 
										?>
									</div>
								</div>
								<?php makeButtonGroup($features, $inputs, true, 'feature'); ?>
							</div>
							
							<div class="dhide"><div id="mseloader"></div></div>
							<select class="dhide mautosel" name="size" id="msize">
								<?php makeOptions(array('' => 'Size') + $sleep_sizes); ?>
							</select>
							<select class="dhide mautosel" name="location">
								<?php makeOptions(array('' => 'Location') + $locations_dropdown); ?>
							</select>
							<select class="dhide mautosel" name="feature">
								<?php makeOptions(array('' => 'Feature') + $features_dropdown); ?>
							</select>
						</div>
					</form>
					<!--<div class="dhide" id="refine-search">
						<a href="#" class="letsgo">Search</a>
					</div>-->
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>