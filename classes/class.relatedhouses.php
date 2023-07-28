<?php
/**
 * Defines core functionality for Related Houses per page.
 *
 * @package kate-and-toms
 */

/**
 * @package kate-and-toms
 * @author  Elliott Richmond
 * @copyright Kate and Tom's Ltd 2012
 */
class Get_Related_Houses {

	public $id;


	public function __construct($house) {
		$this->id = $house->ID;
	}

	public function enable_associated_houses() {
		return get_post_meta($this->id, 'show_associated_houses', true);
	}

public function get_associated_house_feature_term() {
		$term_ids = get_post_meta($this->id, 'ah_from_features', true);
		if (empty($term_ids)) {
			return;
		}
		$features = array();
		foreach ($term_ids as $term_id) {
			$term_object = get_term_by('id', $term_id, 'feature');
			$features[] = $term_object->slug;
		}

		return $features;
	}

	public function get_associated_house_location_term() {
		$term_ids = get_post_meta($this->id, 'ah_from_locations', true);
		if (empty($term_ids)) {
			return;
		}
		$locations = array();
		foreach($term_ids as $term_id) {
			$term_object = get_term_by('id', $term_id, 'location');
			$locations[] = $term_object->slug;
		}

		return $locations;
	}

	public function get_associated_house_size_term() {
		$sleeps = array();
		$term_id = get_post_meta($this->id, 'ah_from_size', true);
		if (empty($term_id)) {
			return;
		}
		$term_object = get_term_by('id', $term_id[0], 'size');

		$sleeps['min'] = get_term_meta( $term_id[0], 'min_no', true );
		$sleeps['max'] = get_term_meta( $term_id[0], 'max_no', true );

		return $sleeps;

	}

	public function get_associated_house_type_term() {
		$term_ids = get_post_meta($this->id, 'ah_from_type', true);
		if (empty($term_ids)) {
			return;
		}
		$types = array();
		foreach($term_ids as $term_id) {
			$term_object = get_term_by('id', $term_id, 'type');
			$types[] = $term_object->slug;
		}

		return $types;
	}

	public function get_associated_house_occasion_term() {
		$term_ids = get_post_meta($this->id, 'ah_from_occasion', true);
		if (empty($term_ids)) {
			return;
		}
		$occasions = array();
		foreach($term_ids as $term_id) {
			$term_object = get_term_by('id', $term_id, 'occasion');
			$occasions[] = $term_object->slug;
		}

		return $occasions;
	}

	

	public function get_associated_house_seasonal() {
		$seasonals = get_post_meta($this->id, 'ah_from_seasonal', true);
		if (empty($seasonals)) {
			return;
		}
		return $seasonals;
	}

	public function date_setup_array($strDateFrom,$strDateTo) {
		$stone = intval(substr($strDateTo,5,2));
		$sttwo = intval(substr($strDateTo,8,2));
		$stthree = intval(substr($strDateTo,0,4));
		$frone = intval(substr($strDateFrom,5,2));
		$frtwo = intval(substr($strDateFrom,8,2));
		$frthree = intval(substr($strDateFrom,0,4));

		$aryRange=array();

		$iDateFrom=mktime(1,0,0, $frone, $frtwo, $frthree);
		$iDateTo=mktime(1, 0, 0, $stone, $sttwo, $stthree);

		if ($iDateTo>=$iDateFrom) {
			array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry

			while ($iDateFrom<$iDateTo) {
			$iDateFrom+=86400; // add 24 hours
			array_push($aryRange,date('Y-m-d',$iDateFrom));
			}
		}
		return $aryRange;

	}


	public function get_associated_house_sections() {
		$section_includes = array();
		$cotswolds = get_post_meta($this->id, 'ah_section_cotswolds', true);
		$sea = get_post_meta($this->id, 'ah_section_coast', true);
		$country = get_post_meta($this->id, 'ah_section_country', true);
		$town = get_post_meta($this->id, 'ah_section_town', true);
		if ($cotswolds == '1') {
			$section_includes[] = 'cotswolds';
		}
		if ($sea == '1') {
			$section_includes[] = 'sea';
		}
		if ($country == '1') {
			$section_includes[] = 'country';
		}
		if ($town == '1') {
			$section_includes[] = 'town';
		}
		return $section_includes;

	}

	public function set_associated_house_sections($section_includes, $sections) {

		foreach($sections as $key => $value) {
			if(in_array($value['name'],$section_includes)) {
				$new_sections[] = $sections[$key];
			}
		}
		return $new_sections;
	}

	public function render_associated_houses() {
// 		$houses = new Hous

		$enabled = $this->enable_associated_houses();
		$feature = $this->get_associated_house_feature_term();
		$location = $this->get_associated_house_location_term();
		$size = $this->get_associated_house_size_term();
		$type = $this->get_associated_house_type_term();
		$occasion = $this->get_associated_house_occasion_term();
		$seasonals = $this->get_associated_house_seasonal();
		$section_includes = $this->get_associated_house_sections();

		$inputs = array('s' => 'all');



		if ($feature) {
			$inputs['taxonomies']['feature'] = $feature;
		}
		if ($location) {
			$inputs['taxonomies']['location'] = $location;
		}
		if ($type) {
			$inputs['taxonomies']['type'] = $type;
		}
		if ($occasion) {
			$inputs['taxonomies']['occasion'] = $occasion;
		}
		if ($seasonals) {
			$inputs['seasonal'] = true;
			foreach($seasonals as $post_id) {
				$beginning = get_post_meta($post_id, 'beginning', true);
				$ending = get_post_meta($post_id, 'ending', true);
				$periods_to_include = get_post_meta($post_id, 'periods_to_include', true);
			}
			$dates = $this->date_setup_array($beginning,$ending);
			OnlineSearch::seasonalSetup($beginning,$ending,$periods_to_include);
			HouseSearch::set_seasonal($inputs['seasonal']);
			HouseSearch::set_specified_seasonal_dates($dates);
		}
		if($size) {
			$inputs['smin'] = $size['min'];
			$inputs['smax'] = $size['max'];
		}

		if ($enabled) {
			$sections = HouseSearch::getSeparatingCats();

			if(!empty($section_includes)) {
				$sections = $this->set_associated_house_sections($section_includes, $sections);
			}

			foreach ($sections as $section) {
				// remove 1 from count to match array index
				$range = count($sections) -1;
				$i = rand(0,$range);
				HouseSearch::prepareAdverts();
				HouseSearch::housesSetup($inputs);

				$house_count = 0;
				$endDiv = false;

				$queriedhouse = HouseSearch::$houses;

				foreach (HouseSearch::$houses as $house) {

					// hack to check individual house
					//if($house->post_id == 65667) {

						if ($house->locations !== false && $house->locations !== "0.000000") {
							$locations = unserialize($house->locations);
							if (is_array($locations)) {

								if ( in_array($section['name'], $locations) ) {

									$house = new HouseSearch($house, $inputs);
									
									if(!empty($size)){
										$house->specificSizeCheck($inputs['smin'], $inputs['smax']);
									}
									
									if ($house->displayCheck()) {

										$house_count ++;
										if ($house_count === 1) {
											echo '<div id="related-houses" class="background-'.$section['color'].'">
												<div class="container"><div class="row">
												<h2 class="span12 type_title">'.$section['title'].'</h2>';
										}
										$endDiv = true;
										$none_display = true;
										$house->displayHouse($section['color'], 330);
									}


								}

							}
						}

					//}

				}
				if($house_count && $i >= 0) {
					//HouseSearch::displayAdverts($sections[$i], $house_count);
					HouseSearch::displayAdverts($section, $house_count);
				}
				if ($endDiv) echo '</div></div></div>';
			}





/*
			$i = rand(0,count($sections));

			HouseSearch::prepareAdverts();

			HouseSearch::housesSetup($inputs);

			$house_count = 0;

			echo '<br><br>';
			echo '<div class="container"><div class="row">';

			foreach (HouseSearch::$houses as $house) {

				if ($house->locations !== false && $house->locations !== "0.000000") {
					$locations = unserialize($house->locations);
					if (is_array($locations)) {

						if ( in_array($location, $locations) ) {
							$house = new HouseSearch($house, $inputs);
							if ($house->displayCheck()) {
								$house_count ++;
								$house->displayHouse();
							}
						} else {
							$house = new HouseSearch($house, $inputs);
							if ($house->displayCheck()) {
								$house_count ++;
								$house->displayHouse();
							}
						}

					}


				}

			}
			HouseSearch::displayAdverts($sections[$i], $house_count);
			echo '</div></div>';
			echo '<br><br>';
*/


		}

	}
}