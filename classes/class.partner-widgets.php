<?php

/**
 * Defines functionality for partner widgets.
 * Supports all post types and taxonomies
 *
 * @package kate-and-toms
 * @author  Elliott Richmond
 * @copyright Kate and Tom's Ltd 2021
 */
class PartnerHeaderSection extends Widget {

	private $title;
	private $class;
	private $backgroundcolour;
	private $left_column;
	private $button_icon;
	private $button_text;
	private $button_url;
	private $right_column;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key) {

		$this->class = 'partner-header-section';
		$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
		$this->backgroundcolour = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
		$this->left_column = get_post_meta($ID, 'widgets_'.$key.'_wysiwyg_row_col_left', true);
		$this->button_icon = get_post_meta($ID, 'widgets_'.$key.'_basic_button_select', true);
		$this->button_text = get_post_meta($ID, 'widgets_'.$key.'_basic_button_button_title', true);
		$this->button_url = get_post_meta($ID, 'widgets_'.$key.'_basic_button_field_custom_url', true);
		$this->right_column = get_post_meta($ID, 'widgets_'.$key.'_wysiwyg_row_col_right', true);

		
		echo '<div class="widget widget_'.$key.' '.$this->class.' '.$this->backgroundcolour.'"><div class="container main_body"><div class="row">';
		$this->headerSection();
		echo '</div></div></div>';
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function headerSection() {
		include(TEMPLATEPATH . '/template-parts/partner-components/header-section.php');
	}
	
}

/**
 * Undocumented class
 */
class PartnerContentSection extends Widget { 

	private $backgroundcolour;
	private $title;
	private $toptext;
	private $listitems;
	private $bottomtext;
	private $image;


	/**
	 * Setup new widget area.
	 * @param int|string Integer of post
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key) {

		$this->class = 'partner-content-section';
		$this->backgroundcolour = get_post_meta($ID, 'widgets_'.$key.'_background_colour', true);
		$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
		$this->toptext = get_post_meta($ID, 'widgets_'.$key.'_wysiwyg_row_col_left_top', true);
		$this->listitems = get_post_meta($ID, 'widgets_'.$key.'_list_items', true);		
		$this->bottomtext = get_post_meta($ID, 'widgets_'.$key.'_wysiwyg_row_col_left_bottom', true);
		$this->image = get_post_meta($ID, 'widgets_'.$key.'_field_right_image', true);

		
		echo '<div class="widget widget_'.$key.' '.$this->class.' '.$this->backgroundcolour.'"><div class="container main_body"><div class="row">';
		$this->contentSection($ID, $key);
		echo '</div></div></div>';
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function contentSection($ID, $key) {
		include(TEMPLATEPATH . '/template-parts/partner-components/content-section.php');
	}

	private function render_list_items($items, $key, $id) {
		echo '<ul class="unstyled partner-list">';
		for($i = 0; $i < $items; ++$i) {
			echo '<li><span class="partner-icon"><img loading="lazy" src="'.get_template_directory_uri() . '/images/partner-icons/'.get_post_meta($id, 'widgets_'.$key.'_list_items_'.$i.'_select_icon', true).'.svg" alt="bullet point with image"/></span> '.get_post_meta($id, 'widgets_'.$key.'_list_items_'.$i.'_matrix_item_title', true).'</li>';
		}
		echo '</ul>';
	}

}

/**
 * Undocumented class
 */
class PartnerChequeredSection extends Widget { 

	private $class;
	private $backgroundcolour;
	private $items;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key) {

		$this->class = 'partner_chequered_section';
		$this->backgroundcolour = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
		$this->items = get_post_meta($ID, 'widgets_'.$key.'_chequered_inner_section', true);		
		
		echo '<div class="widget widget_'.$key.' '.$this->class.' '.$this->backgroundcolour.'"><div class="container main_body"><div class="row">';
		$this->contentSection($ID, $key);
		echo '</div></div></div>';
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function contentSection($ID, $key) {
		include(TEMPLATEPATH . '/template-parts/partner-components/chequered-section.php');
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $items
	 * @param [type] $key
	 * @param [type] $id
	 * @return void
	 */
	private function render_chequered_items($items, $key, $id) {
		for($i = 0; $i < $items; ++$i) {
			$render_class = get_post_meta($id, 'widgets_'.$key.'_chequered_inner_section_'.$i.'_select_alignment', true);
			$image_id = get_post_meta($id, 'widgets_'.$key.'_chequered_inner_section_'.$i.'_image', true);
			$content = get_post_meta($id, 'widgets_'.$key.'_chequered_inner_section_'.$i.'_field_text_row', true);
			switch ($render_class) {
				case 'image-align-right':
					echo '<div class="row mb-2">
					<div class="span6 left-column text-right">'.$content.'</div>
					<div class="span6 right-column">'.wp_get_attachment_image( $image_id, 'square' ).'</div>
					</div>';
					break;
				default:
					echo '<div class="row mb-2">
					<div class="span6 left-column">'.wp_get_attachment_image( $image_id, 'square' ).'</div>
					<div class="span6 right-column">'.$content.'</div>
					</div>';

			}
		}
	}

}

/**
 * Undocumented class
 */
class PartnerVendorStatsSection extends Widget { 

	private $class;
	private $backgroundcolour;
	private $stats;
	private $lists;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key) {

		$this->class = 'partner_vendor_stats_section';
		$this->backgroundcolour = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
		$this->stats = get_post_meta($ID, 'widgets_'.$key.'_vendor_stats_inner_section', true);		
		
		echo '<div class="widget widget_'.$key.' '.$this->class.' '.$this->backgroundcolour.'"><div class="container main_body"><div class="row">';
		$this->contentSection($ID, $key);
		echo '</div></div></div>';
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function contentSection($ID, $key) {
		include(TEMPLATEPATH . '/template-parts/partner-components/partner-vendor-stats-section.php');
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $items
	 * @param [type] $key
	 * @param [type] $id
	 * @return void
	 */
	private function render_vendor_stats($stats, $key, $id) {
		$counter = 0;
		for($i = 0; $i < $stats; ++$i) {
			$title = get_post_meta($id, 'widgets_'.$key.'_vendor_stats_inner_section_'.$i.'_vendor_stats_title', true);
			$image = get_post_meta($id, 'widgets_'.$key.'_vendor_stats_inner_section_'.$i.'_vendor_stats_image', true);
			$lists = get_post_meta($id, 'widgets_'.$key.'_vendor_stats_inner_section_'.$i.'_vendor_list_vendor_stat', true);
			$footerbyline = get_post_meta($id, 'widgets_'.$key.'_vendor_stats_inner_section_'.$i.'_vendor_stats_footer', true);
			
			if ($counter % 2 == 0) { echo '<div class="row">'; }
			include(TEMPLATEPATH . '/template-parts/partner-components/partner-vendor-stat.php');
			$counter++;
			if ($counter % 2 == 0) { echo '</div>'; }

		}
		
	}

}

/**
 * Undocumented class
 */
class PartnerExperts extends Widget { 

	private $class;
	private $backgroundcolour;
	private $title;
	private $content;
	private $image_a;
	private $image_b;
	private $image_c;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key) {

		$this->class = 'partner_experts';
		$this->backgroundcolour = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
		$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
		$this->content = get_post_meta($ID, 'widgets_'.$key.'_wysiwyg_row_col_left', true);
		$this->image_a = get_post_meta($ID, 'widgets_'.$key.'_field_center_image_one', true);
		$this->image_b = get_post_meta($ID, 'widgets_'.$key.'_field_center_image_two', true);
		$this->image_c = get_post_meta($ID, 'widgets_'.$key.'_field_right_image', true);
		
		echo '<div class="widget widget_'.$key.' '.$this->class.' '.$this->backgroundcolour.'"><div class="container main_body"><div class="row">';
		$this->contentSection($ID, $key);
		echo '</div></div></div>';
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function contentSection($ID, $key) {
		include(TEMPLATEPATH . '/template-parts/partner-components/experts-section.php');
	}


}

/**
 * Undocumented class
 */
class PartnerFeaturesSection extends Widget { 

	private $class;
	private $backgroundcolour;
	private $title;
	private $features;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key) {

		$this->class = 'partner_features_section';
		$this->backgroundcolour = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
		$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
		$this->features = get_post_meta($ID, 'widgets_'.$key.'_partner_feature', true);
		
		echo '<div class="widget widget_'.$key.' '.$this->class.' '.$this->backgroundcolour.'"><div class="container main_body"><div class="row">';
		$this->contentSection($ID, $key);
		echo '</div></div></div>';
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function contentSection($ID, $key) {
		include(TEMPLATEPATH . '/template-parts/partner-components/partner-features-section.php');
	}


}

/**
 * Undocumented class
 */
class PartnerImageTextSection extends Widget { 

	private $class;
	private $backgroundcolour;
	private $image;
	private $title;
	private $content_a;
	private $button_icon;
	private $button_text;
	private $content_b;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key) {

		$this->class = 'partner_image_text_section';
		$this->image_alignment_class = get_post_meta($ID, 'widgets_'.$key.'_align_image', true);
		$this->backgroundcolour = get_post_meta($ID, 'widgets_'.$key.'_background_colour', true);
		$this->image = get_post_meta($ID, 'widgets_'.$key.'_left_aligned_image', true);
		$this->title = get_post_meta($ID, 'widgets_'.$key.'_section_title', true);
		$this->content_a = get_post_meta($ID, 'widgets_'.$key.'_section_text_top', true);
		$this->button_icon = get_post_meta($ID, 'widgets_'.$key.'_section_button_select', true);
		$this->button_text = get_post_meta($ID, 'widgets_'.$key.'_section_button_button_title', true);
		$this->content_b = get_post_meta($ID, 'widgets_'.$key.'_section_text_bottom', true);

		echo '<div class="widget widget_'.$key.' '.$this->class.' '.$this->backgroundcolour.'"><div class="container main_body"><div class="row">';
		$this->contentSection($ID, $key);
		echo '</div></div></div>';
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function contentSection($ID, $key) {
		include(TEMPLATEPATH . '/template-parts/partner-components/partner-image-text-section.php');
	}


}

/**
 * Undocumented class
 */
class PartnerStepsSection extends Widget { 

	private $class;
	private $backgroundcolour;
	private $title;
	private $feature_steps;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key) {

		$this->class = 'partner_steps_section';
		$this->backgroundcolour = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
		$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
		$this->feature_steps = get_post_meta($ID, 'widgets_'.$key.'_partner_feature', true);

		echo '<div class="widget widget_'.$key.' '.$this->class.' '.$this->backgroundcolour.'"><div class="container main_body"><div class="row">';
		$this->contentSection($ID, $key);
		echo '</div></div></div>';
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function contentSection($ID, $key) {
		include(TEMPLATEPATH . '/template-parts/partner-components/partner-steps-section.php');
	}


}


/**
 * Undocumented class
 */
class PartnerTestimonialsSection extends Widget { 

	private $class;
	private $backgroundcolour;
	private $title;
	private $partner_testimonials;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key) {

		$this->class = 'partner_testimonials_section';
		$this->backgroundcolour = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
		$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
		$this->partner_testimonials = get_post_meta($ID, 'widgets_'.$key.'_partner_testimonials', true);

		echo '<div class="widget widget_'.$key.' '.$this->class.' '.$this->backgroundcolour.'"><div class="container main_body"><div class="row">';
		$this->contentSection($ID, $key);
		echo '</div></div></div>';
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function contentSection($ID, $key) {
		include(TEMPLATEPATH . '/template-parts/partner-components/partner-testimonial-section.php');
	}


}


/**
 * Undocumented class
 */
class PartnerStatsSection extends Widget { 

	private $class;
	private $backgroundcolour;
	private $title;
	private $partner_stats;
	private $content_top;
	private $button_icon;
	private $button_text;
	private $button_colour;
	private $content_bottom;


	/**
	 * Setup new widget area.
	 * @param int|string Integer of post
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key) {

		$this->class = 'partner_stats_section';
		$this->backgroundcolour = get_post_meta($ID, 'widgets_'.$key.'_background_colour', true);
		$this->title = get_post_meta($ID, 'widgets_'.$key.'_stats_title', true);
		$this->partner_stats = get_post_meta($ID, 'widgets_'.$key.'_partner_stat', true);
		$this->content_top = get_post_meta($ID, 'widgets_'.$key.'_stats_text_top', true);
		$this->button_icon = get_post_meta($ID, 'widgets_'.$key.'_stat_button_select', true);
		$this->button_text = get_post_meta($ID, 'widgets_'.$key.'_stat_button_button_title', true);
		$this->button_colour = get_post_meta($ID, 'widgets_'.$key.'_stat_button_colour_scheme', true);
		$this->content_bottom = get_post_meta($ID, 'widgets_'.$key.'_stats_text_bottom', true);

		echo '<div class="widget widget_'.$key.' '.$this->class.' '.$this->backgroundcolour.'"><div class="container main_body"><div class="row">';
		$this->contentSection($ID, $key);
		echo '</div></div></div>';
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function contentSection($ID, $key) {
		include(TEMPLATEPATH . '/template-parts/partner-components/partner-stat-section.php');
	}


}


/**
 * Defines functionality for partner widgets.
 * Supports all post types and taxonomies
 *
 * @package kate-and-toms
 * @author  Elliott Richmond
 * @copyright Kate and Tom's Ltd 2021
 */
class PartnerCallToAction extends Widget {

	private $title;
	private $class;
	private $backgroundcolour;
	private $left_text;
	private $button_icon;
	private $button_color;
	private $button_text;
	private $button_url;
	private $right_text;


	/**
	 * Setup new widget area.
	 * @param int|string Integer of post
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key) {

		$this->class = 'partner-call-to-action';
		$this->backgroundcolour = get_post_meta($ID, 'widgets_'.$key.'_background_colour', true);
		$this->title = get_post_meta($ID, 'widgets_'.$key.'_left_col_title', true);
		$this->left_text = get_post_meta($ID, 'widgets_'.$key.'_left_col_text', true);
		$this->button_icon = get_post_meta($ID, 'widgets_'.$key.'_right_cta_button_select', true);
		$this->button_color = get_post_meta($ID, 'widgets_'.$key.'_right_cta_button_colour_scheme', true);
		$this->button_text = get_post_meta($ID, 'widgets_'.$key.'_right_cta_button_button_title', true);
		$this->button_url = get_post_meta($ID, 'widgets_'.$key.'_right_cta_button_field_custom_url', true);
		$this->right_text = get_post_meta($ID, 'widgets_'.$key.'_right_col_text', true);

		
		echo '<div class="widget widget_'.$key.' '.$this->class.' '.$this->backgroundcolour.'"><div class="container main_body"><div class="row">';
		$this->headerSection();
		echo '</div></div></div>';
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function headerSection() {
		include(TEMPLATEPATH . '/template-parts/partner-components/partner-call-to-action-section.php');
	}
	
}