<?php

class SpecialOffers {

	public $ID;

	public function __construct($ID = null) {
		$this->ID = $ID;
	}

	public function prepareAds($count, $location = '') {

		$advertsTemp = get_field('adverts','option');

		$images = array();
		foreach ($advertsTemp as $advert) {
			if ($location) {
				if ($advert['location'] == $location) {
					$images[] = $advert['advert_image'];
				}
			} else {
				$images[] = $advert['advert_image'];
			}

		}
		$advertsTemp = $images;

		shuffle($advertsTemp);

		if($count > 0) {
			$randAdverts = array_rand($advertsTemp,$count);

			for($i = 0; $i < $count; $i++){
				$url = wp_get_attachment_image_src( $advertsTemp[$i], 'large' );
				echo '<div class="span3 advert"  style="background: url('.$url[0].') center center;"></div>';
			}
		}
	}

	public function advertCount($count) {
		$show = (4-(($count)%4))%4;
		return $show;
	}

	/**
	 * Get style for box.
	 *
	 * @param string The type of layout required
	 * @param string Colour for the box
	 * @return string Class for the box wrapped in class
	 */
	private function getStyle($layout, $color)
	{
		if ($layout == 'fill') return 'class="box_surround '.$color.'"';
		if ($layout == 'half') return 'class="box_half '.$color.'"';
		return 'class="box_border '.$color.'"';
	}

	private function showSubtitle($layout)
	{
		if ($layout == 'half') return false;
		return true;
	}

	private function getSquareImg($image, $link)
	{
		$alt_text = get_post_meta($image, '_wp_attachment_image_alt', true);
		echo 	'<div class="span3 absoluteCenterWrapper imgset_box_fill">' ,
				($link ? '<a href="' . $link . '"': '<div') . '>';
		if 		($image) echo '<img loading="lazy" class="absoluteCenter" src=' . getImage($image, 'thumbnail') . ' srcset="'.getSrcset($image, $size).'" alt="'.$alt_text.'" />';
		echo 	'</div>', ($link ? '</a>' : '</div>') , '</div>';
	}

	public function remap_special_offers($newoffers) {


		$array = array();
		$i = 0;
		foreach ($newoffers as $k => $newoffer) {

			if ($newoffer['offer_period_name'] != '') {
				$anchor = $newoffer['offer_period_identitfier'];
				$title = $newoffer['offer_period_name'];
				$ref = $i;
				$n = 0;
			}
			$array[$ref]['anchor'] = $anchor;
			$array[$ref]['offer_period_name'] = $title;
			$array[$ref]['houses'][$n]['image'] = $newoffer['offer_image'];
			$array[$ref]['houses'][$n]['house'] = get_post($newoffer['offer_house']);
			$array[$ref]['houses'][$n]['offer_details'] = $newoffer['offer_details'];
			$array[$ref]['houses'][$n]['offer_details_bc_only'] = $newoffer['offer_details_bc_only'];
			$array[$ref]['houses'][$n]['expiry_date'] = date('Ymd', strtotime($newoffer['offer_date']));

			$n++;
			$i++;
		}


		return $array;
	}

	public function get_image_settings($ID, $key) {

		$array = array();

		$layout = get_post_meta($ID, 'widgets_'.$key.'_surround', true);
		$colorOverall = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
		$rowCount = get_post_meta($ID, 'widgets_'.$key.'_imageset', true);

		for ($i=0; $i < $rowCount; $i++) {
			for ($n=0; $n < 4; $n++) {
				$title_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
				$subtitle_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtitle_text', true);
				$subtext_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtext_text', true);
				$image = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
				$link = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_link_url', true);
				$color = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_colour_scheme', true);
				$anchor = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);

				$array[$i][$n]['anchor'] = $anchor;
				$array[$i][$n]['colour'] = $color;
				$array[$i][$n]['title'] = $title_text;
				$array[$i][$n]['subtext_text'] = $subtext_text;

				$titleid = str_replace(' ', '', $title_text);
				$titleid = strtolower($titleid);

				if (is_numeric($link)) $link = get_permalink($link);

				if (!empty($custom_url)) {
					$link = $custom_url;
				}

				$size = 'thumbnail';

			}
		}

		$newarray = array();
		foreach ($array as $value){
			foreach ($value as $item) {
				$newarray[] = $item;
			}
		}
		return $newarray;
	}

	public function output_offer_section_header($offer) {

		//$ID = $this->ID;
		$ID = 22983;

		switch_to_blog(11);

		$widgets = get_post_meta($ID, 'widgets', true);

		foreach ($widgets as $key => $value) {
			if ($value == 'image_set') {
				$array = $this->get_image_settings($ID, $key);
			}
		}

		//var_dump($array);

		foreach ($array as $k => $setting) {
			if ($offer['anchor'] == $setting['anchor']) {
				$offer['colour'] = $setting['colour'];
				$offer['title'] = $setting['title'];
				$offer['subtext_text'] = $setting['subtext_text'];
			}
		}

		//var_dump($offer);
		if(isset($offer['subtext_text'])) {
			$subtexts = strpos($offer['subtext_text'], '$$');
		}

		if (!empty($subtexts)) {
			$subtexts = explode('$$', $offer['subtext_text']);
		}

		if (isset($offer['anchor']) && !empty($offer['anchor'])) {
			echo '<div id="'.str_replace('#', '', $offer['anchor']).'" class="page-title_cont '.$offer['colour'].'">
				<div class="container">
					<div class="row">
						<div class="span6">
							<h2 class="page-title">'.$offer['title'].'</h2>
						</div>';
						if (is_array($subtexts) && !empty($subtexts)){
							echo '<div class="span3 title_pad">
							<p>'.$subtexts[0].'</p>
						</div>
						<div class="span3 title_pad">
							<p>'.$subtexts[1].'</p>
						</div>';
						} else {
							echo '<div class="span6 title_pad">
							<p>'.$offer['subtext_text'].'</p>
						</div>';
						}
					echo '</div>
				</div>
			</div>';
		}

		restore_current_blog();




/*
		echo '<div class="page-title_cont '.get_field('title_color', $ID).'">
			<div class="container"><div class="row">';
		$image = get_field('title_image', $ID);
		$title = get_the_title();

		echo '<div class="span4">'.(!empty($image) ? '<img src="'.$image.'" alt="'.$title.'" />' :
			'<h1 class="page-title">'.$title.'</h1>').'</div>';

		echo '<div class="dhide mobilesearch">
		<a href="/houses/"><i class="icon-search"></i> What are you looking for?</a>
		</div>';

		$text_custom = $katglobals['home_intro'];
		$text_default = $katglobals['home_intro_default'];
		$id = get_current_blog_id();

		$text = (array_key_exists($id, $text_custom) ? $text_custom[$id] : $text_default);

		echo '<div class="span4 title_pad dhide nopad"><p>'.$text.'</p></div>';

		$content = apply_filters('the_content',get_field('title_textarea', $ID));
		$columns = explode('$$', $content);
		echo (count($columns) > 1 ? '<div class="span4 title_pad mhide">'.$columns[0].'</div><div class="span4 title_pad mhide"><p>'.$columns[1].'</div>':
			'<div class="span8 title_pad mhide">'.$columns[0].'</div>');

		echo '</div></div></div>';
*/
	}

	/**
	 * check if a house qualifies for BC offers only
	 *
	 * @param int $id
	 * @return void
	 */
	public function special_offer_bc_only($id) {
		$boolean = false;
		$array = array(
			16914,
			19733,
			19660,
			81970,
			56703,
			11105,
			11069,
			34285,
			34215,
			14767,
			11105,
			39234,
			39236,
			39595,
			39584,
			39253,
			39223,
			39234,
			45716,
			45725,
			45723,
			45755,
			45772,
			45721
		);
		if(in_array($id,$array)){
			$boolean = true;
		}
		return $boolean;
	}


}
?>
