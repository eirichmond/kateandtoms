<?php
/**
 * Defines how widgets are produced and displayed on site's pages.
 *
 * @package kate-and-toms
 */

/**
 * Defines overall functionality for widgets.
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2013
 */
class Widget {


	/**
	 * Create a header for a page.
	 * @param integer|null Integer if a page, null otherwise (i.e. taxonomy, etc)
	 * @param string|null The title if a taxonomy page
	 * @param string|null The URL for the filter further button
	 */
	public static function createHeader($ID = null, $title = null, $url = null)
	{
		global $katglobals;
		$blog_id = get_current_blog_id();
		if($blog_id == 8) {
			return;
		}

		// if not numeric it must be a taxonomy
		if (!is_numeric($ID)) {
			global $blog_id;

			$value = substr(strrchr($url, '='), 1);
			$tax_value = substr(strrchr($url, '&'), 1);
			$taxasarray =  explode('=', $tax_value);
			$term = get_term_by( 'slug', $taxasarray[1], $taxasarray[0] );
			
			//echo '<pre>'; print_r($term); echo '</pre>';
			
			$termId = $term->term_id;
			
			$taxColorBg = get_option(''.$taxasarray[0].'_'.$termId.'_tax_color_scheme');
			$taxBntColor = get_option(''.$taxasarray[0].'_'.$termId.'_tax_filter_button_color_scheme');

			$taxLayoutOption = get_option(''.$taxasarray[0].'_'.$termId.'_taxonomy_filter_layout_option');
						
			
			if (empty($taxBntColor)) $taxBntColor = 'btn-3';
			
			if ($taxBntColor == 'btn-5'){
				$icon = 'icon';
			} else {
				$icon = 'icon-white';
			}
			
			
			//if (is_tax('activity')) {
			if ($taxLayoutOption == 'custom') {

				$taxIntroColorBg = get_option(''.$taxasarray[0].'_'.$termId.'_tax_banner_color');

				echo '<div class="page-title_cont '.$taxIntroColorBg.'">
						<div class="container"><div class="row">';


				$imageId = get_option(''.$taxasarray[0].'_'.$termId.'_taxonomy_intro_image');
				$image = wp_get_attachment_image_src($imageId);
		
						
				echo '<div class="span4">'.(!empty($image) ? '<img loading="lazy" src="'.$image[0].'" alt="'.$term->name.'" />' : 
					'<h1 class="page-title activity">'.$term->name.'</h1> ').'</div>'; 

				$content = apply_filters('the_content',get_option(''.$taxasarray[0].'_'.$termId.'_taxonomy_textarea'));
				
				
				$columns = explode('$$', $content);
				echo (count($columns) > 1 ? '<div class="span4 title_pad">'.$columns[0].'</div><div class="span4 title_pad"><p>'.$columns[1].'</div>':
					'<div class="span8 title_pad">'.$columns[0].'</div>');
		
				echo '</div></div></div>';
				

			} 

		} else {
	
			echo '<div class="page-title_cont '.get_field('title_color', $ID).'">
				<div class="container"><div class="row">';
			$image = get_field('title_image', $ID);
			$image = str_replace('.test', '.com', $image);
			$image = str_replace('staging.', '', $image);

			$title = get_the_title();

			// @TODO refactor this to dynamically pull from the backend not hardcoded
			if(is_front_page()) {
				echo '<div class="span4">'.(!empty($image) ? '<img loading="lazy" src="'.$image.'" alt="'.$title.'" />' : 
					'<h1 class="page-title">'.$title.'</h1>').'</div>'; 
			} else {
				echo '<div class="span4">'.(!empty($image) ? '<img loading="lazy" src="'.$image.'" alt="'.$title.'" />' : 
					'<h1 class="page-title">'.$title.'</h1>').'</div>'; 
			}
		
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
			echo (count($columns) > 1 ? '<div class="span4 title_pad">'.$columns[0].'</div><div class="span4 title_pad"><p>'.$columns[1].'</div>':
				'<div class="span8 title_pad">'.$columns[0].'</div>');
		
			echo '</div></div></div>';
			
		} 

	}

	/**
	 * Create a header for a page.
	 * @param integer|null Integer if a page, null otherwise (i.e. taxonomy, etc)
	 * @param string|null The title if a taxonomy page
	 * @param string|null The URL for the filter further button
	 */
	public static function createTaxfilter($ID = null, $title = null, $url = null)
	{
		if (!is_numeric($ID)) {
			global $blog_id;
				
			$value = substr(strrchr($url, '='), 1);
			$tax_value = substr(strrchr($url, '&'), 1);
			$taxasarray =  explode('=', $tax_value);
			$term = get_term_by( 'slug', $taxasarray[1], $taxasarray[0] );
			
			//echo '<pre>'; print_r($term); echo '</pre>';
			
			$termId = $term->term_id;
			
			$taxColorBg = get_option(''.$taxasarray[0].'_'.$termId.'_tax_color_scheme');
			$taxBntColor = get_option(''.$taxasarray[0].'_'.$termId.'_tax_filter_button_color_scheme');
			
			$taxtitleOverride = get_option(''.$taxasarray[0].'_'.$termId.'_house_for_override');
			
			if ($taxtitleOverride == '') {
				$taxtitleOverride = $term->name;
			} else {
				$taxtitleOverride = $taxtitleOverride . ' ' . $term->name;
			}
			
			if (empty($taxBntColor)) $taxBntColor = 'btn-3';
			
			if ($taxBntColor == 'btn-5'){
				$icon = 'icon';
			} else {
				$icon = 'icon-white';
			}
			
				
			echo '<div id="pintax" class="page-title_cont '.$taxColorBg.'"><div class="container"><div class="row">
					<h1 class="page-title standard span6">'.$taxtitleOverride/* .' '.$term->name */.'</h1>
					<a href="'.$url.'" class="btn '.$taxBntColor.' floatright"><i class="icon-filter '.$icon.' wtpad"></i> Filter Further</a>
				</div></div></div>';
				
				
			return null;

		}
	}

	/**
	 * Create a header for a page.
	 * @param integer|null Integer if a page, null otherwise (i.e. taxonomy, etc)
	 * @param string|null The title if a taxonomy page
	 * @param string|null The URL for the filter further button
	 */
	public static function createTaxfilterSearch($ID = null, $title = null, $url = null)
	{
		
	//	if (!is_numeric($ID)) {
			global $blog_id;
							
			$value = substr(strrchr($url, '='), 1);
						
			$tax_value = substr(strrchr($url, '&'), 1);
						
			$taxasarray =  explode('=', $tax_value);
			
			$term = get_term_by( 'slug', $taxasarray[1], $taxasarray[0] );
			
			//echo '<pre>'; print_r($term); echo '</pre>';
			
			
			$termId = $term->term_id;
			
			$taxColorBg = get_option(''.$taxasarray[0].'_'.$termId.'_tax_color_scheme');
			$taxBntColor = get_option(''.$taxasarray[0].'_'.$termId.'_tax_filter_button_color_scheme');
			
			if (empty($taxBntColor)) $taxBntColor = 'btn-3';
			
			if ($taxBntColor == 'btn-5'){
				$icon = 'icon';
			} else {
				$icon = 'icon-white';
			}
			
				
			echo '<div class="page-title_cont '.$taxColorBg.'"><div class="container"><div class="row">
					<h1 class="page-title span6">Houses for '.$term->name.'</h1>
				</div></div></div>';
			return null;

	//	}
	
	}
	
	/**
	 * Create all widgets for top terms.
	 * @param integer The ID of page to create widgets for
	 * @param bool Whether the page is the secondary one on the page
	 */
	public static function createTermTopWidget($id, $term) {

		$widgets = get_term_meta( $id, 'top_widgets', true);
		if($widgets) {
			foreach ($widgets as $key => $value) {
				if ($value == 'standard_widget') {
					new StandardWidget($id, $key, $term);
				}
				if ($value == 'image_set') {
					new ImageSetWidget($id, $key, $term);
				}
				if ($value == 'button_widget') {
					new ButtonWidget($id, $key, $term);
				}
				if ($value == 'wide_widget') {
					new WideImageWidget($id, $key, $term);
				}
			}
		}
	}

	/**
	 * Create all widgets for bottom terms.
	 * @param integer The ID of page to create widgets for
	 * @param bool Whether the page is the secondary one on the page
	 */
	public static function createTermBottomWidget($id, $term) {
		$widgets = get_term_meta( $id, 'bottom_widgets', true);
		if($widgets) {
			foreach ($widgets as $key => $value) {
				if ($value == 'standard_widget') {
					new StandardWidget($id, $key, $term);
				}
				if ($value == 'image_set') {
					new ImageSetWidget($id, $key, $term);
				}
				if ($value == 'button_widget') {
					new ButtonWidget($id, $key, $term);
				}
				if ($value == 'wide_widget') {
					new WideImageWidget($id, $key, $term);
				}
			}
		}
	}

	/**
	 * Create all widgets for a specified page.
	 * @param integer The ID of page to create widgets for
	 * @param bool Whether the page is the secondary one on the page
	 */
	public static function createWidgets($ID, $secondary = false) 
	{
		if (!is_numeric($ID)) {
			global $wpdb;
			$widgets = $wpdb->get_col($wpdb->prepare(
				"SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",
				$ID . '\_widgets'
			));
			if (empty($widgets)) return null;
			$widgets = unserialize($widgets[0]);
		}
		else {
			$widgets = get_post_meta($ID, 'widgets', true);
		}
		
		if (!empty($widgets)) $separator = array_search('separator_widget', $widgets); 
		else return;
		
		if ($separator !== false) {
			if ($secondary) $widgets = array_slice($widgets, $separator+1, null, true);
			else $widgets = array_slice($widgets, 0, $separator);
		}

		foreach ($widgets as $key => $value)
		{
			if ($value == 'standard_widget') {
				new StandardWidget($ID, $key);
			}
			if ($value == 'script_widget') {
				new ScriptWidget($ID, $key);
			}
			if ($value == 'single_image_link') {
				new SingleImageLinkWidget($ID, $key);
			}
			if ($value == 'video_widget') {
				new VideoWidget($ID, $key);
			}
			if ($value == 'virtual_widget') {
				new VirtualWidget($ID, $key);
			}
			if ($value == 'standard_widget_hybrid') {
				new StandardWidgetHybrid($ID, $key);
			}
			if ($value == 'matrix_widget') {
				new MatrixSetWidget($ID, $key);
			}
			elseif ($value == 'image_set') {
				new ImageSetWidget($ID, $key, $term = null);
			}
			elseif ($value == 'reviews_widget') {
				new ReviewsWidget($ID, $key);
			}
			elseif ($value == 'faq_group') {
				new FAQGroup($ID, $key);
			}
			elseif ($value == 'button_widget') {
				new ButtonWidget($ID, $key, $term = null);
			}
			elseif ($value == 'wide_widget') {
				new WideImageWidget($ID, $key, $term = null);
			}
			elseif ($value == 'cta_widget') {
				new CTAWidget($ID, $key);
			}
			elseif ($value == 'partner_header_section') {
				new PartnerHeaderSection($ID, $key);
			}
			elseif ($value == 'partner_content_section') {
				new PartnerContentSection($ID, $key);
			}
			elseif ($value == 'partner_chequered_section') {
				new PartnerChequeredSection($ID, $key);
			}
			elseif ($value == 'partner_vendor_stats_section') {
				new PartnerVendorStatsSection($ID, $key);
			}
			elseif ($value == 'partner_experts') {
				new PartnerExperts($ID, $key);
			}
			elseif ($value == 'partner_features_section') {
				new PartnerFeaturesSection($ID, $key);
			}
			elseif ($value == 'partner_image_text_section') {
				new PartnerImageTextSection($ID, $key);
			}
			elseif ($value == 'partner_steps_section') {
				new PartnerStepsSection($ID, $key);
			}
			elseif ($value == 'partner_testimonials_section') {
				new PartnerTestimonialsSection($ID, $key);
			}
			elseif ($value == 'partner_stats_section') {
				new PartnerStatsSection($ID, $key);
			}
			elseif ($value == 'partner_call_to_action') {
				new PartnerCallToAction($ID, $key);
			}
			
		}
		
	}

	public static function createKeyFactsWidgets($ID, $secondary = false) 
	{
		if (!is_numeric($ID)) {
			global $wpdb;
			$widgets = $wpdb->get_col($wpdb->prepare(
				"SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",
				$ID . '\_kf_widgets'
			));
			if (empty($widgets)) return null;
			$widgets = unserialize($widgets[0]);
		}
		else {
			$widgets = get_post_meta($ID, 'kf_widgets', true);
		}
		
		if ($widgets) {
			foreach ($widgets as $key => $value)
			{
				if ($value == 'kf_standard_widget') {
					new KfStandardWidget($ID, $key);
				}
				elseif ($value == 'kf_image_set') {
					new KfImageSetWidget($ID, $key);
				}
				elseif ($value == 'kf_button_widget') {
					new KfButtonWidget($ID, $key);
				}
				elseif ($value == 'kf_wide_widget') {
					new KfWideImageWidget($ID, $key);
				}
				elseif ($value == 'floorplan_widget') {
					new FloorPlanWidget($ID, $key);
				}
			}
		}	
	}
}


/**
 * Defines functionality for standard widgets.
 * Supports the following layouts:
 * 'imgleft' - Image left
 * 'imgright' - Image right
 * 'galleryleft' - Gallery left
 * 'galleryright' - Gallery right
 * 'largeimg' - Large image
 * 'text' - Text only
 * 'fourimage' - Four images only
 *
 * Supports all post types and taxonomies
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2013
 */
class SingleImageLinkWidget extends Widget {

	private $title;
	private $subtitle;
	private $body;
	private $images;
	private $image_src;
	private $image_link_to;
	private $colorScheme;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post, or if string taxonomy in format of type_taxID, eg location_4
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key)
	{
		// Assuming if ID is not numeric, it is a taxonomy
		if (!is_numeric($ID)) {
			global $wpdb;
			$this->title = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_title'));
			$this->title = $this->title[0];
			$this->subtitle = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_subtitle'));
			$this->subtitle = $this->subtitle[0];
			$this->layout = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_layout'));
			$this->layout = $this->layout[0];
			$this->colorScheme = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_color_scheme'));
			$this->colorScheme = $this->colorScheme[0];
			$content = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_body'));
			$content = apply_filters('the_content',$content[0]);
			$images = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_gallery'));
			$images = unserialize($images[0]);
		}
		// Can assume it is normal post type
		else {
			$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
			$this->subtitle = get_post_meta($ID, 'widgets_'.$key.'_subtitle', true);
			$this->layout = get_post_meta($ID, 'widgets_'.$key.'_layout', true);
			$this->crop = get_post_meta($ID, 'widgets_'.$key.'_crop_from', true);
			$this->colorScheme = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
			$content = apply_filters('the_content',get_post_meta($ID, 'widgets_'.$key.'_body', true));
			$image_id = get_post_meta($ID, 'widgets_'.$key.'_image', true);

		}
		
		$this->image_src = wp_get_attachment_image_src( $image_id, 'square' );
		$this->image_link_to = get_post_meta( $image_id, '_image_link_to_url', true );
		
		
		$this->body = explode('$$', $content);
		$layoutFunction = $this->layout.'Display';
		
		$noTitle = false;
		
		if (empty($this->title)) {
			$noTitle = ' notitle';
		}

		echo '<div class="widget widget_'.$key.' widget_'.$this->layout.' '.$this->colorScheme.$noTitle.'"><div class="container main_body"><div class="row">';
		$this->$layoutFunction();
		echo '</div></div></div>';
	}
	
	private function imgleftDisplay()
	{
		if ($this->image_link_to) {
			echo '<a href="'.esc_attr( $this->image_link_to ).'" target="_blank"><img loading="lazy" class="span6 imgleftDisplay" src="'.esc_attr( $this->image_src[0] ).'" alt="'.$this->title.'" /></a>';
		} else {
			echo '<img loading="lazy" class="span6 imgleftDisplay" src="'.esc_attr( $this->image_src[0] ).'" alt="'.$this->title.'" />';
		}
		echo '<div class="span6"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) .$this->body[0].'</div>';
	}
	
	private function imgrightDisplay()
	{
		echo '<div class="span6 imgrightDisplay"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) .$this->body[0].'</div>';
		if ($this->image_link_to) {
			echo '<a href="'.esc_attr( $this->image_link_to ).'" target="_blank"><img loading="lazy" class="span6" src="'.esc_attr( $this->image_src[0]).'" alt="'.$this->title.'" /></a>';
		} else {
			echo '<img loading="lazy" class="span6" src="'.esc_attr( $this->image_src[0] ).'" alt="'.$this->title.'" />';
		}
	}
		
}

/**
 * Defines functionality for standard widgets.
 * Supports the following layouts:
 * 'imgleft' - Image left
 * 'imgright' - Image right
 * 'galleryleft' - Gallery left
 * 'galleryright' - Gallery right
 * 'largeimg' - Large image
 * 'text' - Text only
 * 'fourimage' - Four images only
 *
 * Supports all post types and taxonomies
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2013
 */
class StandardWidget extends Widget {

	private $title;
	private $subtitle;
	private $body;
	private $images;
	private $colorScheme;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post, or if string taxonomy in format of type_taxID, eg location_4
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key, $term = false)
	{
		
		// Assuming if ID is not numeric, it is a taxonomy
		if (!is_numeric($ID)) {
			global $wpdb;
			$this->title = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_title'));
			$this->title = $this->title[0];
			$this->subtitle = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_subtitle'));
			$this->subtitle = $this->subtitle[0];
			$this->layout = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_layout'));
			$this->layout = $this->layout[0];
			$this->colorScheme = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_color_scheme'));
			$this->colorScheme = $this->colorScheme[0];
			$content = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_body'));
			$content = apply_filters('the_content',$content[0]);
			$images = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_gallery'));
			$images = unserialize($images[0]);
		}
		// Can assume it is normal post type
		else {
			$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
			$this->subtitle = get_post_meta($ID, 'widgets_'.$key.'_subtitle', true);
			$this->layout = get_post_meta($ID, 'widgets_'.$key.'_layout', true);
			$this->crop = get_post_meta($ID, 'widgets_'.$key.'_crop_from', true);
			$this->colorScheme = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
			$content = apply_filters('the_content',get_post_meta($ID, 'widgets_'.$key.'_body', true));
			$images = get_post_meta($ID, 'widgets_'.$key.'_gallery', true);

		}
		
		// get term top widgets
		if ($term == 'termtop') {
			$this->title = get_term_meta($ID, 'top_widgets_'.$key.'_title', true);
			$this->subtitle = get_term_meta($ID, 'top_widgets_'.$key.'_subtitle', true);
			$this->layout = get_term_meta($ID, 'top_widgets_'.$key.'_layout', true);
			$this->crop = get_term_meta($ID, 'top_widgets_'.$key.'_crop_from', true);
			$this->colorScheme = get_term_meta($ID, 'top_widgets_'.$key.'_color_scheme', true);
			$content = apply_filters('the_content',get_term_meta($ID, 'top_widgets_'.$key.'_body', true));
			$images = get_term_meta($ID, 'top_widgets_'.$key.'_gallery', true);
		}

		// get term bottom widgets
		if ($term == 'termbottom') {
			$this->title = get_term_meta($ID, 'bottom_widgets_'.$key.'_title', true);
			$this->subtitle = get_term_meta($ID, 'bottom_widgets_'.$key.'_subtitle', true);
			$this->layout = get_term_meta($ID, 'bottom_widgets_'.$key.'_layout', true);
			$this->crop = get_term_meta($ID, 'bottom_widgets_'.$key.'_crop_from', true);
			$this->colorScheme = get_term_meta($ID, 'bottom_widgets_'.$key.'_color_scheme', true);
			$content = apply_filters('the_content',get_term_meta($ID, 'bottom_widgets_'.$key.'_body', true));
			$images = get_term_meta($ID, 'bottom_widgets_'.$key.'_gallery', true);
		}

		// Get image URLs for all required sizes
		if ($images) {
			foreach ($images as $k => $v) {
				$imageSrcs[$k]['huge'] = getImage($v, 'huge');
				$imageSrcs[$k]['large'] = getImage($v, 'large');
				$imageSrcs[$k]['square'] = getImage($v, 'square');
				$imageSrcs[$k]['thumbnail'] = getImage($v, 'thumbnail');				
				$imageSrcs[$k]['srcset'] = wp_get_attachment_image_srcset( $v, 'huge' );
				$imageSrcs[$k]['alt'] = get_post_meta($v, '_wp_attachment_image_alt', true);
			}
			$this->images = $imageSrcs;
			$this->imageid = $v;
		}
		
		$this->body = explode('$$', $content);
		$layoutFunction = $this->layout.'Display';

		$noTitle = false;
		
		if (empty($this->title)) {
			$noTitle = ' notitle';
		}

		echo '<div class="widget widget_'.$key.' widget_'.$this->layout.' '.$this->colorScheme.$noTitle.'"><div class="container main_body"><div class="row">';
		$this->$layoutFunction();
		echo '</div></div></div>';
	}
	
	private function imgleftDisplay()
	{
		echo '<img loading="lazy" class="span6 imgleftDisplay" src="'.$this->images[0]['square'].'" alt="'.$this->title.'" />
			<div class="span6"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) .$this->body[0].'</div>';
	}
	
	private function imgrightDisplay()
	{
		echo '<div class="span6 imgrightDisplay"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) .$this->body[0].'</div>
			<img loading="lazy" class="span6" src="'.$this->images[0]['square'].'" alt="'.$this->title.'" />';
	}
	
	private function galleryleftDisplay()
	{
		echo '<div class="span6 galleryleftDisplay image-block"><div class="row">';
		if (is_array($this->images)) {
			foreach ($this->images as $k => $image) {
				echo '<img loading="lazy" class="span3" '. ( $k < 2 ? 'style="margin-bottom:20px;"' : '' ).' src="'.$image['thumbnail'].'" alt="'.$this->title.'" />';
				if ($k == 3) break;
			}
		}
		echo '</div></div>
			<div class="span6"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) . 
			$this->body[0].'</div>';
	}
	
	private function galleryrightDisplay()
	{
		echo '<div class="span6 galleryrightDisplay"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ).
			$this->body[0].'</div>
			<div class="span6 galleryrightDisplay image-block"><div class="row">';
			// added as an error check to reduce error rates added 27th June 2013
			if ($this->images) {
				foreach ($this->images as $k => $image) {
					echo '<img loading="lazy" '.getAlttag($this->imageid).' class="span3" '. ( $k < 2 ? 'style="margin-bottom:20px;"' : '' ).' src="'.$image['thumbnail'].'" />';
				}
			}
		echo '</div></div>';
	}
	
	private function largeimgDisplay()
	{	
		$align = get_img_description($this->imageid);
		if (empty($align)) {$align = 'absoluteCenter';}
		
		echo '<div class="cropped"><img loading="lazy" class="span12 '.$align.'" src="'.$this->images[0]['huge'].'" alt="'.$this->title.'" /></div>';
		
		if (!empty($this->title)) {
			echo '<div class="span12"><h2'.$addclass.'>'.$this->title.'</h2>'.($this->subtitle ? '<h3 class="offset2 span8">'.$this->subtitle.'</h3>' : '' ).'</div>';
		}
		
		if (count($this->body) > 1) echo '<div class="span6">'.$this->body[0].'</div><div class="span6">'.$this->body[1].'</div></div>';
		else echo '<div class="span10 offset1">'.$this->body[0].'</div>';
	}
	
	private function textDisplay()
	{	
		$addclass = null;
		
		if (empty($this->body)) {$addclass = ' class="nomargin"';}
		
			echo '<div class="span12"><h2'.$addclass.'>'.$this->title.'</h2>'.($this->subtitle ? '<h3 class="offset2 span8">'.$this->subtitle.'</h3>' : '' ) . '</div>';
				
		if (is_page(61) || is_page(63)){
		
			if (count($this->body) > 1)
				echo '<div class="span8 offset2">'.$this->body[0].'</div><div class="span4">'.$this->body[1].'</div></div>';
				
			else
				echo '<div class="span8 offset2">'.$this->body[0].'</div>';

		} else {
			
			if (count($this->body) > 1)
				echo '<div class="span6">'.$this->body[0].'</div><div class="span6">'.$this->body[1].'</div></div>';
				
			else
				echo '<div class="span8 offset2">'.$this->body[0].'</div>';

		}

	}
	
	private function fourimageDisplay() {
		$with_text = false;
		if (!empty($this->title)) {
			$with_text = true;
			echo '<div class="mpadder">';
			echo '<div class="span12"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3 class="offset2 span8">'.$this->subtitle.'</h3>' : '' ).'</div>';
			echo '</div>';
		}
		if (!empty($this->body[0]) && count($this->body) > 1) {
			$with_text = true;
			echo '<div class="mpadder">';
			echo '<div class="span6 fourimageDisplay">'.$this->body[0].'</div><div class="span6"><p>'.$this->body[1].'</div><div class="clearfix"></div>';
			echo '</div>';
		} elseif (!empty($this->body[0])) {
			$with_text = true;
			echo '<div class="mpadder">';
			echo '<div class="span8 offset2">'.$this->body[0].'</div>';
			echo '</div>';
		}

		if ($with_text == true) { echo '<div class="text_above">';}
		if (is_array($this->images)) {
			echo '<img loading="lazy" class="span3 four1" src="'.$this->images[0]['thumbnail'].'" scrset="'.$this->images[0]['srcset'].'" alt="'.$this->images[0]['alt'].'" />
				<img loading="lazy" class="span3 four2" src="'.$this->images[1]['thumbnail'].'" scrset="'.$this->images[0]['srcset'].'" alt="'.$this->images[0]['alt'].'" />
				<img loading="lazy" class="span3 four3" src="'.$this->images[2]['thumbnail'].'" scrset="'.$this->images[0]['srcset'].'" alt="'.$this->images[0]['alt'].'" />
				<img loading="lazy" class="span3 four4" src="'.$this->images[3]['thumbnail'].'" scrset="'.$this->images[0]['srcset'].'" alt="'.$this->images[0]['alt'].'" />';
		}
		if ($with_text == true) { echo '</div>';}
	}

	public function fourimagesonly() {
		$this->fourimageDisplay();
	}
	
}


/**
 * Defines functionality for standard widgets.
 * Supports the following layouts:
 * 'imgleft' - Image left
 * 'imgright' - Image right
 * 'galleryleft' - Gallery left
 * 'galleryright' - Gallery right
 * 'largeimg' - Large image
 * 'text' - Text only
 * 'fourimage' - Four images only
 *
 * Supports all post types and taxonomies
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2013
 */
class ScriptWidget extends Widget {

	private $title;
	private $subtitle;
	private $content;
	private $colorScheme;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post, or if string taxonomy in format of type_taxID, eg location_4
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key, $term = false) {
		
		$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
		$this->subtitle = get_post_meta($ID, 'widgets_'.$key.'_subtitle', true);
		$this->colorScheme = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
		$this->content = get_post_meta($ID, 'widgets_'.$key.'_body', true);
	
		echo '<div class="widget widget_'.$key.' widget_script ' .$this->colorScheme . '"><div class="container main_body"><div class="row">';
		echo $this->content;
		echo '</div></div></div>';
	}
	
}


/**
 * Defines functionality for standard widgets.
 * Supports the following layouts:
 *
 * Supports all post types and taxonomies
 *
 * @package kate-and-toms
 * @author  Elliott Richmond
 * @copyright Kate and Tom's Ltd 2013
 */
class CTAWidget extends Widget {

	private $title;
	private $subtext;
	private $buttontext;
	private $buttoncolour;
	private $backgroundcolour;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post, or if string taxonomy in format of type_taxID, eg location_4
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key)
	{
		// Assuming if ID is not numeric, it is a taxonomy
		if (!is_numeric($ID)) {
/*
			global $wpdb;
			$this->title = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_title'));
			$this->title = $this->title[0];
			$this->subtitle = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_subtitle'));
			$this->subtitle = $this->subtitle[0];
			$this->layout = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_layout'));
			$this->layout = $this->layout[0];
			$this->colorScheme = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_color_scheme'));
			$this->colorScheme = $this->colorScheme[0];
			$content = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_body'));
			$content = apply_filters('the_content',$content[0]);
			$images = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_gallery'));
			$images = unserialize($images[0]);
*/
		}
		// Can assume it is normal post type
		else {
			$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
			$this->subtext = get_post_meta($ID, 'widgets_'.$key.'_subtext', true);
			$this->buttontext = get_post_meta($ID, 'widgets_'.$key.'_button', true);
			$this->buttoncolour = get_post_meta($ID, 'widgets_'.$key.'_button_color', true);
			$this->buttonurl = get_post_meta($ID, 'widgets_'.$key.'_button_url', true);
			$this->backgroundcolour = get_post_meta($ID, 'widgets_'.$key.'_background_color', true);

		}
		

		echo '<div class="widget widget_'.$key.' widget_cta '.$this->backgroundcolour.'"><div class="container main_body"><div class="row">';
		$this->ctacontent();
		echo '</div></div></div>';
	}
	
	private function ctacontent() {
			echo '<div class="span12">';
			echo '<h2>'.$this->title.'</h2>';
			echo '<h3>'.$this->subtext.'</h3>';
			echo '<li><a href="'.$this->buttonurl.'" class="btn '.$this->buttoncolour.'">'.$this->buttontext.'</a></li>';
			echo '</div>';
	}
	
}

/**
 * Defines functionality for standard widgets.
 * Supports the following layouts:
 * 'imgleft' - Image left
 * 'imgright' - Image right
 * 'galleryleft' - Gallery left
 * 'galleryright' - Gallery right
 * 'largeimg' - Large image
 * 'text' - Text only
 * 'fourimage' - Four images only
 *
 * Supports all post types and taxonomies
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2013
 */
class VideoWidget extends Widget {

	private $title;
	private $body;
	private $layout;
	private $video;
	private $colorScheme;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post, or if string taxonomy in format of type_taxID, eg location_4
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key)
	{
		// Assuming if ID is not numeric, it is a taxonomy
		if (!is_numeric($ID)) {
			global $wpdb;
			$this->title = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_title'));
			$this->title = $this->title[0];
			$this->layout = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_video_layout'));
			$this->layout = $this->layout[0];
			$this->colorScheme = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_color_scheme'));
			$this->colorScheme = $this->colorScheme[0];
			$content = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_body'));
			$content = apply_filters('the_content',$content[0]);
			$video_url = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_url'));
			$video_url = unserialize($images[0]);
		}
		// Can assume it is normal post type
		else {
			$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
			$this->layout = get_post_meta($ID, 'widgets_'.$key.'_video_layout', true);
			$this->colorScheme = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
			$content = apply_filters('the_content',get_post_meta($ID, 'widgets_'.$key.'_body', true));
			$this->video = get_post_meta($ID, 'widgets_'.$key.'_url', true);

		}
		// Get image URLs for all required sizes
//		if ($images) {
//			foreach ($images as $k => $v) {
//				$imageSrcs[$k]['huge'] = getImage($v, 'huge');
//				$imageSrcs[$k]['large'] = getImage($v, 'large');
//				$imageSrcs[$k]['square'] = getImage($v, 'square');
//				$imageSrcs[$k]['thumbnail'] = getImage($v, 'thumbnail');
//			}
//			$this->images = $imageSrcs;
//			$this->imageid = $v;
//		}

		$this->body = explode('$$', $content);
		$layoutFunction = $this->layout.'Display';

		$noTitle = false;

		if (empty($this->title)) {
			$noTitle = ' notitle';
		}

		echo '<div class="widget widget_'.$key.' widget_'.$this->layout.' '.$this->colorScheme.$noTitle.'"><div class="container main_body"><div class="row">';
		$this->$layoutFunction();
		echo '</div></div></div>';
	}

	private function vidleftDisplay()
	{
		echo '<div class="span6 imgleftDisplay"><div class="videowrapper small">' . wp_oembed_get( $this->video, array('width' => 580) ) .'</div></div>
			<div class="span6 content"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) .$this->body[0].'</div>';
	}

	private function vidrightDisplay()
	{
		echo '<div class="span6 imgrightDisplay content"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) .$this->body[0].'</div>
			<div class="span6"><div class="videowrapper small">' . wp_oembed_get( $this->video, array('width' => 580) ) .'</div></div>';
	}



	private function vidwideDisplay()
	{
		echo '<div class="span12"><div class="videowrapper">' . wp_oembed_get( $this->video, array('width' => 1180) ) .'</div></div>';
	}

}

class VirtualWidget extends Widget {

	private $title;
	private $body;
	private $layout;
	private $virtual;
	private $image;
	private $colorScheme;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post, or if string taxonomy in format of type_taxID, eg location_4
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key)
	{
		// Assuming if ID is not numeric, it is a taxonomy
		if (!is_numeric($ID)) {
			global $wpdb;
			$this->title = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_title'));
			$this->title = $this->title[0];
			$this->layout = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_virtual_layout'));
			$this->layout = $this->layout[0];
			$this->colorScheme = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_color_scheme'));
			$this->colorScheme = $this->colorScheme[0];
			$content = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_body'));
			$content = apply_filters('the_content',$content[0]);
			$virtual_url = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_url'));
			$virtual_url = unserialize($images[0]);
		}
		// Can assume it is normal post type
		else {
			$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
			$this->layout = get_post_meta($ID, 'widgets_'.$key.'_virtual_layout', true);
			$this->colorScheme = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
			$content = apply_filters('the_content',get_post_meta($ID, 'widgets_'.$key.'_body', true));
			$this->image = get_post_meta($ID, 'widgets_'.$key.'_virtual_image', true);
			$this->virtual = get_post_meta($ID, 'widgets_'.$key.'_url', true);

		}
		// Get image URLs for all required sizes
//		if ($images) {
//			foreach ($images as $k => $v) {
//				$imageSrcs[$k]['huge'] = getImage($v, 'huge');
//				$imageSrcs[$k]['large'] = getImage($v, 'large');
//				$imageSrcs[$k]['square'] = getImage($v, 'square');
//				$imageSrcs[$k]['thumbnail'] = getImage($v, 'thumbnail');
//			}
//			$this->images = $imageSrcs;
//			$this->imageid = $v;
//		}

		$this->body = explode('$$', $content);
		$layoutFunction = $this->layout.'Display';

		$noTitle = false;

		if (empty($this->title)) {
			$noTitle = ' notitle';
		}

		echo '<div class="widget widget_'.$key.' widget_'.$this->layout.' '.$this->colorScheme.$noTitle.'"><div class="container main_body"><div class="row">';
		$this->$layoutFunction();
		echo '</div></div></div>';
	}

	private function virtualleftDisplay()
	{
		echo '<div class="span6 imgleftDisplay"><div class="virtualwrapper small"><a href="'.$this->virtual.'" target="_blank">' . wp_get_attachment_image( $this->image, 'square' ) .'</a></div></div>
			<div class="span6 content"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) .$this->body[0].'</div>';
	}

	private function virtualrightDisplay()
	{
		echo '<div class="span6 imgrightDisplay content"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) .$this->body[0].'</div>
			<div class="span6"><div class="virtualwrapper small"><a href="'.$this->virtual.'" target="_blank">' . wp_get_attachment_image( $this->image, 'square' ) . '</a></div></div>';
	}

}


/**
 * Defines functionality for standard widgets.
 * Supports the following layouts:
 * 'imgleft' - Image left
 * 'imgright' - Image right
 * 'galleryleft' - Gallery left
 * 'galleryright' - Gallery right
 * 'largeimg' - Large image
 * 'text' - Text only
 * 'fourimage' - Four images only
 *
 * Supports all post types and taxonomies
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2013
 */
class StandardWidgetHybrid extends Widget {
	private $title;
	private $subtitle;
	private $body;
	private $images;
	private $colorScheme;
	private $imagesetlayout;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post, or if string taxonomy in format of type_taxID, eg location_4
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key)
	{
		// Assuming if ID is not numeric, it is a taxonomy
		if (!is_numeric($ID)) {
			global $wpdb;
						
			$this->title = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_title'));
			$this->title = $this->title[0];
			$this->subtitle = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_subtitle'));
			$this->subtitle = $this->subtitle[0];
			$this->imagesetlayout = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_image_set_layout'));
			$this->imagesetlayout = $this->imagesetlayout[0];
/*
			$this->layout = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_layout'));
			$this->layout = $this->layout[0];
*/
			$this->colorScheme = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_color_scheme'));
			$this->colorScheme = $this->colorScheme[0];
			$content = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_body'));
			$content = apply_filters('the_content',$content[0]);
			$images = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_gallery'));
			//$images = unserialize($images[0]);
		}
		// Can assume it is normal post type
		else {
			$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
			$this->subtitle = get_post_meta($ID, 'widgets_'.$key.'_subtitle', true);
			$this->imagesetlayout = get_post_meta($ID, 'widgets_'.$key.'_image_set_layout', true);
 			$this->layout = get_post_meta($ID, 'widgets_'.$key.'_layout', true);
/* 			$this->crop = get_post_meta($ID, 'widgets_'.$key.'_crop_from', true); */
			$this->colorScheme = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
			$content = apply_filters('the_content',get_post_meta($ID, 'widgets_'.$key.'_body', true));
			$images = get_post_meta($ID, 'widgets_'.$key.'_gallery', true);

		}
		// Get image URLs for all required sizes
		if ($images) {
			foreach ($images as $k => $v) {
				$imageSrcs[$k]['huge'] = getImage($v, 'huge');
				$imageSrcs[$k]['large'] = getImage($v, 'large');
				$imageSrcs[$k]['square'] = getImage($v, 'square');
				$imageSrcs[$k]['thumbnail'] = getImage($v, 'thumbnail');
			}
			$this->images = $imageSrcs;
			$this->imageid = $v;
		}
		
		
		$this->body = explode('$$', $content);
		//$imagesetlayoutFunction = $this->imagesetlayout.'Display';
		$imagesetlayoutFunction = $this->layout.'Display';
		
		
		$noTitle = false;
		
		if (empty($this->title)) {
			$noTitle = ' notitle';
		}

		echo '<div class="widget widget_'.$key.' widget_hybrid '.$this->layout.' '.$this->colorScheme.$noTitle.'"><div class="container main_body"><div class="row">';

		// if($this->imagesetlayout == 'fill') {
		// 	//$imagesetlayoutFunction = $this->imagesetlayout.'Display';
		// 	$object = new StandardWidget($ID, $key);
		// 	//$object->fourimagesonly();
			
		// } else {
			if ($imagesetlayoutFunction == 'imagesetbottomDisplay') {
			// if ($imagesetlayoutFunction == 'fourimageDisplay') {
					echo '<div class="span12"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) . '</div>';
		
				if (count($this->body) > 1)
					echo '<div class="span6">'.$this->body[0].'</div><div class="span6">'.$this->body[1].'</div></div>';
					
				else
					echo '<div class="span8 offset2">'.$this->body[0].'</div>';
						
		
		
				// assume this is not a number so it must be a taxonomy
				if (!is_numeric($ID)) {
				
					$layout = get_option($ID . '_widgets_'.$key.'_surround');
					$colorOverall = get_option($ID . '_widgets_'.$key.'_color_scheme');
					
					echo '<div class="widget widget_imageset '.$colorOverall.'"><div class="container main_body"><div class="row">';
					
					//global $wpdb;
					$i = 0;
					$set = get_option($ID . '_widgets_' . $key . '_imageset');
					$rowCount = get_option($ID . '_widgets_' . $key . '_imageset_' . $i . '_row');
		
		
					for ($i=0; $i < $rowCount; $i++) {
						for ($n=0; $n < $set; $n++) { 
						
							$color = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_colour_scheme');
							$image = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_image');
							$link = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_link_url');
							$custom_url = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_set_custom');
							$subtitle_text = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_subtitle_text');
							$title_text = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_title_text');
							
							$titleid = str_replace(' ', '', $title_text);
							$titleid = strtolower($titleid);
							
							if (is_numeric($link)) $link = get_permalink($link);
							//$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
						
							if ($custom_url) $link = $custom_url;
							
							$size = 'thumbnail';
							
							if ($layout == 'image') $this->getSquareImg($image, $link);
							elseif ($layout == 'triangle') $this->getTriangle($image, $link, $color); 
							elseif (empty($title_text)) $this->getSquareImg($image, $link);
							else {
								echo 	'<div id="'.$titleid.'" class="span3 '.($image ? 'has-image' : 'no-image').'">' ,
										($link ? '<a href="' . $link . '"': '<div') . ' class="imgset_box_'.$layout.' '.$color.'">';
								if 		($image) echo '<div class="absoluteCenterWrapper imgset_wrap_'.$layout
											.'"><img loading="lazy" class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
								echo 	'<div class="box_text"><h2>' , $title_text , '</h2>',
										($this->showSubtitle($layout) ? $subtitle_text : '') , 
										'</div>' , ($link ? '</a>' : '</div>') , '</div>';
							}
		
						}
					}
					
					echo '</div></div></div>';
		
				} else {
		
					$rows = 4;
					
					$layout = get_post_meta($ID, 'widgets_'.$key.'_surround', true);
									
					$rowCount = get_post_meta($ID, 'widgets_'.$key.'_imageset', true);
					
					echo '<div class="widget widget_imageset"><div class="container main_body"><div class="row">';
					
					for ($i=0; $i < $rowCount; $i++) {
						for ($n=0; $n < 4; $n++) { 
							$title_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
							$subtitle_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtitle_text', true);
							$image = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
							$link = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_link_url', true);
							$color = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_colour_scheme', true);
							
							$titleid = str_replace(' ', '', $title_text);
							$titleid = strtolower($titleid);
							
							if (is_numeric($link)) $link = get_permalink($link);
							$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
			
							if ($custom_url) $link = $custom_url;
							
							$size = 'thumbnail';
							
							if ($layout == 'image') $this->getSquareImg($image, $link);
							
							elseif ($layout == 'triangle') $this->getTriangle($image, $link, $color); 
							
							elseif (empty($title_text)) $this->getSquareImg($image, $link);
							
							else {
							
								echo 	'<div id="'.$titleid.'" class="span3 '.($image ? 'has-image' : 'no-image').'">' ,
										($link ? '<a href="' . $link . '"': '<div') . ' class="imgset_box_'.$layout.' '.$color.'">';
								if 		($image) echo '<div class="absoluteCenterWrapper imgset_wrap_'.$layout .'"><img loading="lazy" class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
								echo 	'<div class="box_text"><h2>' , $title_text , '</h2>',
										($this->showSubtitle($layout) ? $subtitle_text : '') , 
										'</div>' , ($link ? '</a>' : '</div>') , '</div>';
										
							}
						}
					}
					
					
					echo '</div></div></div>';
					
				}
			} elseif ($imagesetlayoutFunction == 'imagesetleftDisplay') {
				
				if (!is_numeric($ID)){
				
				echo '<div class="span6"><div class="row">';
				
					$i = 0;
					$rowCount = get_option($ID . '_widgets_' . $key . '_imageset_' . $i . '_row');
									
					for ($i=0; $i < $rowCount; $i++) {
					
						for ($n=0; $n < 1; $n++) { 
						
							$color = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_colour_scheme');
							$image = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_image');
							$link = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_link_url');
							$custom_url = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_set_custom');
							$subtitle_text = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_subtitle_text');
							$title_text = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_title_text');
							
							
							if (is_numeric($link)) $link = get_permalink($link);
							//$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
						
							if ($custom_url) $link = $custom_url;
							
							$size = 'thumbnail';
							
							echo '<div class="span3 has-image '. ($n < 2 ? 'twotop' : '') .'">';
							echo '<a href="' . $link . '" class="imgset_box_fill '.$color.'" >';
							echo '<div class="absoluteCenterWrapper imgset_wrap_fill">';
							echo '<img loading="lazy" class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
							echo '<div class="box_text"><h2>'.$title_text.'</h2>'.$subtitle_text.'</div>';
							echo '</a>';
							echo '</div>';
	
						}
					}
					
				echo '</div></div>';	
	
				
				} else {
				
				echo '<div class="span6"><div class="row">';
				
					$rowCount = get_post_meta($ID, 'widgets_'.$key.'_imageset', true);
					for ($i=0; $i < $rowCount; $i++) {
						for ($n=0; $n < 4; $n++) { 
							$title_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
							$subtitle_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtitle_text', true);
							$image = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
							$color = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_colour_scheme', true);
							$link = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_link_url', true);
				
							if (is_numeric($link)) $link = get_permalink($link);
							$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
				
							if ($custom_url) $link = $custom_url;
				
							$size = 'thumbnail';
							echo '<div class="span3 has-image '. ($n < 2 ? 'twotop' : '') .'">';
							echo '<a href="' . $link . '" class="imgset_box_fill '.$color.'" >';
							echo '<div class="absoluteCenterWrapper imgset_wrap_fill">';
							echo '<img loading="lazy" class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
							echo '<div class="box_text"><h2>'.$title_text.'</h2>'.$subtitle_text.'</div>';
							echo '</a>';
							echo '</div>';
						}
					}	
					
				echo '</div></div>';	
	
				}
				
				
				
				
				echo '<div class="span6"><h2 class="hbh">'.$this->title.'</h2><h3>'.$this->subtitle.'</h3>';
				
					if (count($this->body) > 1)
						echo '<p>'.$this->body[0].'</p><p>'.$this->body[1].'</p>';
						
					else
						echo '<p>'.$this->body[0].'</p>';
						
				echo '</div>';
	
	
			} elseif ($imagesetlayoutFunction == 'fourimageDisplay'){

				$with_text = false;
				if (!empty($this->title)) {
					$with_text = true;
					echo '<div class="mpadder">';
					echo '<div class="span12"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3 class="offset2 span8">'.$this->subtitle.'</h3>' : '' ).'</div>';
					echo '</div>';
				}
				if (!empty($this->body[0]) && count($this->body) > 1) {
					$with_text = true;
					echo '<div class="mpadder">';
					echo '<div class="span6 fourimageDisplay">'.$this->body[0].'</div><div class="span6"><p>'.$this->body[1].'</div><div class="clearfix"></div>';
					echo '</div>';
				} elseif (!empty($this->body[0])) {
					$with_text = true;
					echo '<div class="mpadder">';
					echo '<div class="span8 offset2">'.$this->body[0].'</div>';
					echo '</div>';
				}
				if ($with_text == true) { echo '<div class="text_above">'; }
				$rowCount = get_post_meta($ID, 'widgets_'.$key.'_imageset', true);

				if($rowCount) {

					echo '<div class="span3">';
					echo '<div class="widget widget_'.$key.' widget_imageset '.$colorOverall.'"><div class="container main_body"><div class="row">';
				
					for ($i=0; $i < $rowCount; $i++) {
						for ($n=0; $n < 4; $n++) { 
							$title_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
							$subtitle_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtitle_text', true);
							$image = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
							$link = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_link_url', true);
							$color = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_colour_scheme', true);
							
							$titleid = str_replace(' ', '', $title_text);
							$titleid = strtolower($titleid);
							
							if (is_numeric($link)) $link = get_permalink($link);
							$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
			
							if ($custom_url) $link = $custom_url;
							
							$size = 'thumbnail';
							
							if ($this->layout == 'image') $this->getSquareImg($image, $link);
							elseif ($this->layout == 'triangle') $this->getTriangle($image, $link, $color); 
							elseif ($this->layout == 'fourimage' && $this->imagesetlayout == 'image') {
								echo '<span class="span3 imageset_fourimages">';
								echo ($link ? '<a href="' . $link . '"></a>': '');
								echo '<img loading="lazy" '.getAlttag($image).' class="four1" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" />';
								echo '</span>';
							} 
							//elseif (empty($title_text)) $this->getSquareImg($image, $link);
							elseif ($this->layout == 'fourimage' && $this->imagesetlayout == 'fill') {

								

								echo 	'<div id="'.$titleid.'" class="span3 '.($image ? 'has-image' : 'no-image').'">' ,
										($link ? '<a href="' . $link . '"': '<div') . ' class="imgset_box_'.$this->imagesetlayout.' '.$color.' slideto">';
								if 		($image) echo '<div class="absoluteCenterWrapper imgset_wrap_'.$this->imagesetlayout
											.'"><img loading="lazy" '.getAlttag($image).' class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
								echo 	'<div class="box_text"><h2>' . $title_text . '</h2>',
										($this->showSubtitle($this->imagesetlayout) ? $subtitle_text : '') , 
										'</div>' . ($link ? '</a>' : '</div>') . '</div>';

							} else {
								echo 'there is currenly no render for this setting!';
							}
						}
					}
					
					echo '</div></div></div>';
					echo '</div>';


				}
			







				if (is_array($this->images)) {
					echo '<img loading="lazy" class="span3 four1" src="'.$this->images[0]['thumbnail'].'" alt="'.$this->title.'" />
						<img loading="lazy" class="span3 four2" src="'.$this->images[1]['thumbnail'].'" alt="'.$this->title.'" />
						<img loading="lazy" class="span3 four3" src="'.$this->images[2]['thumbnail'].'" alt="'.$this->title.'" />
						<img loading="lazy" class="span3 four4" src="'.$this->images[3]['thumbnail'].'" alt="'.$this->title.'" />';
				}
				if ($with_text == true) { echo '</div>';}

			} elseif ($imagesetlayoutFunction == 'galleryrightDisplay') {
			
				echo '<div class="span6"><h2 class="hbh">'.$this->title.'</h2><h3>'.$this->subtitle.'</h3>';
				
					if (count($this->body) > 1)
						echo '<p>'.$this->body[0].'</p><p>'.$this->body[1].'</p>';
						
					else
						echo '<p>'.$this->body[0].'</p>';
						
				echo '</div>';
				
				if (!is_numeric($ID)){
				
					echo '<div class="span6"><div class="row">';
					
						$i = 0;
						$rowCount = get_option($ID . '_widgets_' . $key . '_imageset_' . $i . '_row');
										
						for ($i=0; $i < $rowCount; $i++) {
						
							for ($n=0; $n < 1; $n++) { 
							
								$color = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_colour_scheme');
								$image = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_image');
								$link = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_link_url');
								$custom_url = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_set_custom');
								$subtitle_text = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_subtitle_text');
								$title_text = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_title_text');
								
								
								if (is_numeric($link)) $link = get_permalink($link);
								//$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
							
								if ($custom_url) $link = $custom_url;
								
								$size = 'thumbnail';
								
								echo '<div class="span3 has-image '. ($n < 2 ? 'twotop' : '') .'">';
								echo '<a href="' . $link . '" class="imgset_box_fill '.$color.'" >';
								echo '<div class="absoluteCenterWrapper imgset_wrap_fill">';
								echo '<img loading="lazy" class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
								echo '<div class="box_text"><h2>'.$title_text.'</h2>'.$subtitle_text.'</div>';
								echo '</a>';
								echo '</div>';
		
							}
						}
	
					echo '</div></div>';	
	
				} else {
	
	
					echo '<div class="span6"><div class="row">';
					
					$rowCount = get_post_meta($ID, 'widgets_'.$key.'_imageset', true);
					for ($i=0; $i < $rowCount; $i++) {
						for ($n=0; $n < 4; $n++) { 
							$title_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
							$subtitle_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtitle_text', true);
							$image = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
							$color = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_colour_scheme', true);
							$link = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_link_url', true);
				
							if (is_numeric($link)) $link = get_permalink($link);
							$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
				
							if ($custom_url) $link = $custom_url;
				
							$size = 'thumbnail';
							echo '<div class="span3 has-image '. ($n < 2 ? 'twotop' : '') .'">';
							echo '<a href="' . $link . '" class="imgset_box_fill '.$color.'" >';
							echo '<div class="absoluteCenterWrapper imgset_wrap_fill">';
							echo '<img loading="lazy" class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
							echo '<div class="box_text"><h2>'.$title_text.'</h2>'.$subtitle_text.'</div>';
							echo '</a>';
							echo '</div>';
						}
					}	
					
					echo '</div></div>';
				
				}

			} elseif ($imagesetlayoutFunction == 'galleryleftDisplay') {

				
				if (!is_numeric($ID)){
				
					echo '<div class="span6"><div class="row">';
					
						$i = 0;
						$rowCount = get_option($ID . '_widgets_' . $key . '_imageset_' . $i . '_row');
										
						for ($i=0; $i < $rowCount; $i++) {
						
							for ($n=0; $n < 1; $n++) { 
							
								$color = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_colour_scheme');
								$image = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_image');
								$link = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_link_url');
								$custom_url = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_set_custom');
								$subtitle_text = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_subtitle_text');
								$title_text = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_title_text');
								
								
								if (is_numeric($link)) $link = get_permalink($link);
								//$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
							
								if ($custom_url) $link = $custom_url;
								
								$size = 'thumbnail';
								
								echo '<div class="span3 has-image '. ($n < 2 ? 'twotop' : '') .'">';
								echo '<a href="' . $link . '" class="imgset_box_fill '.$color.'" >';
								echo '<div class="absoluteCenterWrapper imgset_wrap_fill">';
								echo '<img loading="lazy" class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
								echo '<div class="box_text"><h2>'.$title_text.'</h2>'.$subtitle_text.'</div>';
								echo '</a>';
								echo '</div>';
		
							}
						}
	
					echo '</div></div>';	
	
				} else {
	
	
					echo '<div class="span6"><div class="row">';
					
					$rowCount = get_post_meta($ID, 'widgets_'.$key.'_imageset', true);
					for ($i=0; $i < $rowCount; $i++) {
						for ($n=0; $n < 4; $n++) { 
							$title_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
							$subtitle_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtitle_text', true);
							$image = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
							$color = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_colour_scheme', true);
							$link = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_link_url', true);
				
							if (is_numeric($link)) $link = get_permalink($link);
							$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
				
							if ($custom_url) $link = $custom_url;
				
							$size = 'thumbnail';
							echo '<div class="span3 has-image '. ($n < 2 ? 'twotop' : '') .'">';
							echo '<a href="' . $link . '" class="imgset_box_fill '.$color.'" >';
							echo '<div class="absoluteCenterWrapper imgset_wrap_fill">';
							echo '<img loading="lazy" class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
							echo '<div class="box_text"><h2>'.$title_text.'</h2>'.$subtitle_text.'</div>';
							echo '</a>';
							echo '</div>';
						}
					}	
					
					echo '</div></div>';
				
				}

				echo '<div class="span6"><h2 class="hbh">'.$this->title.'</h2><h3>'.$this->subtitle.'</h3>';
				
					if (count($this->body) > 1)
						echo '<p>'.$this->body[0].'</p><p>'.$this->body[1].'</p>';
						
					else
						echo '<p>'.$this->body[0].'</p>';
						
				echo '</div>';

			} else {
			
				echo 'Undeveloped rendering for this setting ' . $imagesetlayoutFunction;

			}
		// }

		
		
		echo '</div></div></div>';
		
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
		echo 	'<div class="span3 absoluteCenterWrapper imgset_box_fill">' ,
				($link ? '<a href="' . $link . '"': '<div') . '>';
		if 		($image) echo '<img loading="lazy" class="absoluteCenter" src=' . getImage($image, 'thumbnail') . ' srcset="'.getSrcset($image, $size).'" />';
		echo 	'</div>', ($link ? '</a>' : '</div>') , '</div>';
	}
	
	private function textDisplay()
	{	
		$addclass = null;
		
		if (empty($this->body)) {$addclass = ' class="nomargin"';}
		
			echo '<div class="span12"><h2'.$addclass.'>'.$this->title.'</h2>'.($this->subtitle ? '<h3 class="offset2 span8">'.$this->subtitle.'</h3>' : '' ) . '</div>';
				
		if (is_page(61) || is_page(63)){
		
			if (count($this->body) > 1)
				echo '<div class="span8 offset2"><p>'.$this->body[0].'</p></div><div class="span4"><p>'.$this->body[1].'</p></div>';
				
			else
				echo '<div class="span8 offset2"><p>'.$this->body[0].'</p></div>';

		} else {
			
			if (count($this->body) > 1)
				echo '<div class="span6"><p>'.$this->body[0].'</p></div><div class="span6"><p>'.$this->body[1].'</p></div>';
				
			else
				echo '<div class="span8 offset2"><p>'.$this->body[0].'</p></div>';

		}

	}
	
}

/**
 * Defines functionality for matrix widgets.
 *
 * @package kate-and-toms
 * @author  Elliott Richmond
 * @copyright Kate and Tom's Ltd 2013
 */

class MatrixSetWidget extends Widget {
	
	private $id;
	private $key;
	private $layout;

	/**
	 * Setup the contruct for this class
	 * @param int|string Integer of post
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 * @echo the layout of a possible 3 types
	 */
	public function __construct($ID, $key) {
		$this->id = $ID;
		$this->key = $key;
		echo $this->get_layout();
	}
	
	/**
	 * Setup layout output depending on the $value option
	 * @param string $value the value of the layout choosen
	 * @returns the function relating to the layout of $value
	 */
	public function render_markup($value) {
		
		if ($value == '3-1') {
			$this->one_three();
		}
		if ($value == '1-3') {
			$this->three_one();
		}
		if ($value == 'Flat x 4') {
			$this->flat_four();
		}
		
	}

	/**
	 * get the value of the layout save to the database
	 * @returns the function to render the markup
	 */
	public function get_layout() {
		$this->layout = get_post_meta($this->id, 'widgets_'.$this->key.'_matrix_layout', true);
		$this->render_markup($this->layout);
	}

	/**
	 * get the values of the indexed matrix options saved to the database
	 * @returns array $array an indexed array of the options saved
	 */
	public function get_matrix_array() {
		$rowCount = get_post_meta($this->id, 'widgets_'.$this->key.'_matrix_set', true);
		$this->layout = get_post_meta($this->id, 'widgets_'.$this->key.'_matrix_layout', true);
		$test = 'Flat x 4';
		$layindex = str_replace(' ','-',$this->layout);
		$layindex = strtolower($layindex);

		$imagesize = array(
			'1-3' => array(
				'square','square','thumbnail','thumbnail'
			),
			'3-1' => array(
				'thumbnail','thumbnail','square','square'
			),
			'flat-x-4' => array(
				'thumbnail','thumbnail','thumbnail','thumbnail'
			)
		);
		
		$array = array();
		for ($i=0; $i < $rowCount; $i++) {
			
			
			if (is_numeric(get_post_meta($this->id, 'widgets_'.$this->key.'_matrix_set_'.$i.'_links_to', true))) {
				$link = get_permalink(get_post_meta($this->id, 'widgets_'.$this->key.'_matrix_set_'.$i.'_links_to', true));
			} else {
				$link = get_post_meta($this->id, 'widgets_'.$this->key.'_matrix_set_'.$i.'_links_to', true);
			}
			$image = wp_get_attachment_image_src(get_post_meta($this->id, 'widgets_'.$this->key.'_matrix_set_'.$i.'_image', true), $imagesize[$layindex][$i]);
			
			$array[] = array(
				'link' => $link,
				'image' => $image[0],
				'colour' => get_post_meta($this->id, 'widgets_'.$this->key.'_matrix_set_'.$i.'_colour_scheme', true),
				'title' => get_post_meta($this->id, 'widgets_'.$this->key.'_matrix_set_'.$i.'_matrix_item_title', true),
				'subtitle' => get_post_meta($this->id, 'widgets_'.$this->key.'_matrix_set_'.$i.'_matrix_item_subtitle', true),
				'alt' => get_post_meta($this->id, 'widgets_'.$this->key.'_matrix_set_'.$i.'_image', true)
			);
		}
		
		return $array;
	}

	/**
	 * render the html markup for the linked href panel with its options
	 * @param array $array the array of saved options
	 * @param int $i the index numer from the widgets key value
	 * @output html markup
	 */
	public function render_href_panel($array, $i) {
		$image = $array[$i]['image'];
		$image = str_replace('.test', '.com', $image);
		$image = str_replace('staging.', '', $image);

		?>
		<a href="<?php echo esc_attr($array[$i]['link']); ?>" class="imgset_box_fill <?php echo esc_attr($array[$i]['colour']); ?>"><div class="absoluteCenterWrapper imgset_wrap_fill"><img loading="lazy" <?php echo getAlttag($array[$i]['alt']); ?> class="absoluteCenter" <?php echo esc_attr( getAlttag($id) ); ?> src="<?php echo esc_attr($image); ?>" srcset="<?php echo getSrcset($image, $size); ?>" ></div><div class="box_text matrix"><h2><?php echo esc_attr($array[$i]['title']); ?></h2><?php echo esc_attr($array[$i]['subtitle']); ?></div></a>
		<?php
	}

	/**
	 * output two column layout 1 single large image and 3 smaller to the right column
	 * @output html markup
	 */
	public function one_three() {
		$array = $this->get_matrix_array();
		?>

		<div class="widget widget_0 widget_imagematrix color14">
			<div class="container main_body">
				<div class="row">
				
				
					<div class="span6">
						<div class="row top">
		
							<div class="span3 square">
								<?php $this->render_href_panel($array, 0); ?>
							</div>
							
							<div class="span3 square">
								<?php $this->render_href_panel($array, 1); ?>
							</div>
		
						</div>
						<div class="row bottom">
							<div class="span6 rectangle">
								<?php $this->render_href_panel($array, 2); ?>
							</div>
						</div>
					</div>
					
					
					<div class="span6">
						<div class="row">
							<div class="span6 square">
								<?php $this->render_href_panel($array, 3); ?>
							</div>
						</div>
					</div>
					
					
				</div>
			</div>
		</div>
	
		<?php

	}
	
	/**
	 * output two column layout 3 smaller to the left and 1 large to the right column
	 * @output html markup
	 */
	public function three_one() {
		$array = $this->get_matrix_array();
		?>

		<div class="widget widget_0 widget_imagematrix color14">
			<div class="container main_body">
				<div class="row">
					
					<div class="span6">
						
						<div class="row">
							<div class="span6 square">
								
								<?php $this->render_href_panel($array, 0); ?>
								
							</div>
						</div>
						
					</div>
					
					<div class="span6">
						
						<div class="row">
							<div class="span6 rectangle">
								
								<?php $this->render_href_panel($array,1); ?>
		
							</div>
						</div>
						
						<div class="row">
							<div class="span3 square">
		
								<?php $this->render_href_panel($array, 2); ?>
													
							</div>
							<div class="span3 square">
		
								<?php $this->render_href_panel($array, 3); ?>
								
							</div>
						</div>
						
					</div>
					
				</div>
			</div>
		</div>
	
		<?php

	}

	/**
	 * output 4 column layout of equal size
	 * @output html markup
	 */
	public function flat_four() {
		$array = $this->get_matrix_array();
		?>

		<div class="widget widget_0 widget_imagematrix color14">
			<div class="container main_body">
				<div class="row">
					<div class="span12">
						<div class="row">
		
							<div class="span3 square">
		
								<?php $this->render_href_panel($array, 0); ?>
													
							</div>
							<div class="span3 square">
		
								<?php $this->render_href_panel($array, 1); ?>
								
							</div>
		
							<div class="span3 square">
		
								<?php $this->render_href_panel($array, 2); ?>
													
							</div>
							<div class="span3 square">
		
								<?php $this->render_href_panel($array, 3); ?>
								
							</div>
		
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
	}	
}


/**
 * Defines functionality for Keyfacts standard widgets.
 * Supports the following layouts:
 * 'imgleft' - Image left
 * 'imgright' - Image right
 * 'galleryleft' - Gallery left
 * 'galleryright' - Gallery right
 * 'largeimg' - Large image
 * 'text' - Text only
 * 'fourimage' - Four images only
 *
 * Supports all post types and taxonomies
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2013
 */
class KfStandardWidget extends Widget {

	private $title;
	private $subtitle;
	private $body;
	private $images;
	private $colorScheme;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post, or if string taxonomy in format of type_taxID, eg location_4
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key)
	{
		// Assuming if ID is not numeric, it is a taxonomy
		if (!is_numeric($ID)) {
			global $wpdb;
			$this->title = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_kf_widgets\_' . $key . '\_title'));
			$this->title = $this->title[0];
			$this->subtitle = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_kf_widgets\_' . $key . '\_subtitle'));
			$this->subtitle = $this->subtitle[0];
			$this->layout = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_kf_widgets\_' . $key . '\_layout'));
			$this->layout = $this->layout[0];
			$this->colorScheme = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_kf_widgets\_' . $key . '\_color_scheme'));
			$this->colorScheme = $this->colorScheme[0];
			$content = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_kf_widgets\_' . $key . '\_body'));
			$content = $content[0];
			$images = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_kf_widgets\_' . $key . '\_gallery'));
			$images = unserialize($images[0]);
		}
		// Can assume it is normal post type
		else {
			$this->title = get_post_meta($ID, 'kf_widgets_'.$key.'_title', true);
			$this->subtitle = get_post_meta($ID, 'kf_widgets_'.$key.'_subtitle', true);
			$this->layout = get_post_meta($ID, 'kf_widgets_'.$key.'_layout', true);
			$this->crop = get_post_meta($ID, 'kf_widgets_'.$key.'_crop_from', true);
			$this->colorScheme = get_post_meta($ID, 'kf_widgets_'.$key.'_color_scheme', true);
			$content = apply_filters('the_content',get_post_meta($ID, 'kf_widgets_'.$key.'_body', true));
			$images = get_post_meta($ID, 'kf_widgets_'.$key.'_gallery', true);

		}
		// Get image URLs for all required sizes
		if ($images) {
			foreach ($images as $k => $v) {
				$imageSrcs[$k]['huge'] = getImage($v, 'huge');
				$imageSrcs[$k]['large'] = getImage($v, 'large');
				$imageSrcs[$k]['square'] = getImage($v, 'square');
				$imageSrcs[$k]['thumbnail'] = getImage($v, 'thumbnail');
			}
			$this->images = $imageSrcs;
			$this->imageid = $v;
		}
		
		$this->body = explode('$$', $content);
		$layoutFunction = $this->layout.'Display';
		
		$noTitle = false;
		
		if (empty($this->title)) {
			$noTitle = ' notitle';
		}

		echo '<div class="widget kf widget_'.$this->layout.' '.$this->colorScheme.$noTitle.'"><div class="container main_body"><div class="row">';
		$this->$layoutFunction();
		echo '</div></div></div>';
	}
	
	private function imgleftDisplay()
	{
		echo '<img loading="lazy" class="span6 imgleftDisplay" src="'.$this->images[0]['square'].'" alt="'.$this->title.'" />
			<div class="span6"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) . '<p>'.$this->body[0].'</p></div>';
	}
	
	private function imgrightDisplay()
	{
		echo '<div class="span6 imgrightDisplay"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) . '<p>'.$this->body[0].'</p></div>
			<img loading="lazy" class="span6" src="'.$this->images[0]['square'].'" alt="'.$this->title.'" />';
	}
	
	private function galleryleftDisplay()
	{
		echo '<div class="span6 galleryleftDisplay image-block"><div class="row">';
		foreach ($this->images as $k => $image) {
			echo '<img loading="lazy" class="span3" '. ( $k < 2 ? 'style="margin-bottom:20px;"' : '' ).' src="'.$image['thumbnail'].'" alt="'.$this->title.'" />';
			if ($k == 3) break;
		}
		echo '</div></div>
			<div class="span6 galleryleftDisplay"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) . 
			'<p>'.$this->body[0].'</p></div>';
	}
	
	private function galleryrightDisplay()
	{
		echo '<div class="span6 galleryrightDisplay"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ).
			'<p>'.$this->body[0].'</p></div>
			<div class="span6"><div class="row">';
		foreach ($this->images as $k => $image) {
			echo '<img loading="lazy" class="span3" '. ( $k < 2 ? 'style="margin-bottom:20px;"' : '' ).' src="'.$image['thumbnail'].'" alt="'.$this->title.'" />';
		}
		echo '</div></div>';
	}
	
	private function largeimgDisplay()
	{	
		$align = get_img_description($this->imageid);
		if (empty($align)) {$align = 'absoluteCenter';}
		
		echo '<div class="cropped"><img loading="lazy" class="span12 '.$align.'" src="'.$this->images[0]['huge'].'" alt="'.$this->title.'" /></div>';
		
		if (!empty($this->title)) {
			echo '<div class="span12"><h2'.$addclass.'>'.$this->title.'</h2>'.($this->subtitle ? '<h3 class="offset2 span8">'.$this->subtitle.'</h3>' : '' ).'</div>';
		}
		
		if (count($this->body) > 1) echo '<div class="span6">'.$this->body[0].'</div><div class="span6"><p>'.$this->body[1].'</div></div>';
		else echo '<div class="span10 offset1">'.$this->body[0].'</div>';
	}
	
	private function textDisplay()
	{	
		$addclass = null;
		
		if (empty($this->body)) {$addclass = ' class="nomargin"';}
		
			echo '<div class="span12"><h2'.$addclass.'>'.$this->title.'</h2>'.($this->subtitle ? '<h3 class="offset2 span8">'.$this->subtitle.'</h3>' : '' ) . '</div>';
				
		if (is_page(61) || is_page(63)){
		
			if (count($this->body) > 1)
				echo '<div class="span8 offset2">'.$this->body[0].'</div><div class="span4"><p>'.$this->body[1].'</div></div>';
				
			else
				echo '<div class="span8 offset2">'.$this->body[0].'</div>';

		} else {
			
			if (count($this->body) > 1)
				echo '<div class="span6">'.$this->body[0].'</div><div class="span6"><p>'.$this->body[1].'</div></div>';
				
			else
				echo '<div class="span8 offset2">'.$this->body[0].'</div>';

		}

	}
	
	private function fourimageDisplay()
	{
	
		if (!empty($this->title)) {

			echo '<div class="span12"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3 class="offset2 span8">'.$this->subtitle.'</h3>' : '' ).'</div>';

		}
		if (count($this->body) > 1)

			echo '<div class="span6 fourimageDisplay">'.$this->body[0].'</div><div class="span6 fourimageDisplay"><p>'.$this->body[1].'</div><div class="clearfix"></div>';
		else echo '<div class="span8 offset2">'.$this->body[0].'</div>';


		echo '<img loading="lazy" class="span3" src="'.$this->images[0]['thumbnail'].'" alt="'.$this->title.'" />
			<img loading="lazy" class="span3" src="'.$this->images[1]['thumbnail'].'" alt="'.$this->title.'" />
			<img loading="lazy" class="span3" src="'.$this->images[2]['thumbnail'].'" alt="'.$this->title.'" />
			<img loading="lazy" class="span3" src="'.$this->images[3]['thumbnail'].'" alt="'.$this->title.'" />';
	}
	
}

/**
 * Defines functionality for image set widgets.
 *
 * Supports all post types, NOT taxonomies
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2013
 */
class ImageSetWidget extends Widget {

	/**
	 * Setup new image set.
	 * Does not support taxonomies.
	 */
	public function __construct($ID, $key, $term)
	{
	
		$rows = 4;
			
		// assume this is not a number so it must be a taxonomy
		if (!is_numeric($ID)) {
		
			$layout = get_option($ID . '_widgets_'.$key.'_surround');
			$colorOverall = get_option($ID . '_widgets_'.$key.'_color_scheme');
			
			echo '<div class="widget widget_'.$key.' widget_imageset '.$colorOverall.'"><div class="container main_body"><div class="row">';
			
			//global $wpdb;
			$i = 0;
			$set = get_option($ID . '_widgets_' . $key . '_imageset');
			$rowCount = get_option($ID . '_widgets_' . $key . '_imageset_' . $i . '_row');


			for ($i=0; $i < $rowCount; $i++) {
				for ($n=0; $n < $set; $n++) { 
				
					$color = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_colour_scheme');
					$image = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_image');
					
					$image = str_replace('.test', '.com', $image);
					$image = str_replace('staging.', '', $image);
	
					$link = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_link_url');
					$custom_url = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_set_custom');
					$subtitle_text = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_subtitle_text');
					$title_text = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_title_text');
					
					$titleid = str_replace(' ', '', $title_text);
					$titleid = strtolower($titleid);
					
					if (is_numeric($link)) $link = get_permalink($link);
					//$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
				
					if ($custom_url) $link = $custom_url;
					
					$size = 'thumbnail';
					
					if ($layout == 'image') $this->getSquareImg($image, $link);
					elseif ($layout == 'triangle') $this->getTriangle($image, $link, $color); 
					elseif (empty($title_text)) $this->getSquareImg($image, $link);
					else {
						echo 	'<div id="'.$titleid.'" class="span3 '.($image ? 'has-image' : 'no-image').'">' ,
								($link ? '<a href="' . $link . '"': '<div') . ' class="imgset_box_'.$layout.' '.$color.'">';
						if 		($image) echo '<div class="absoluteCenterWrapper imgset_wrap_'.$layout
									.'"><img loading="lazy" '.getAlttag($image).' class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
						echo 	'<div class="box_text"><h2>' , $title_text , '</h2>',
								($this->showSubtitle($layout) ? $subtitle_text : '') , 
								'</div>' , ($link ? '</a>' : '</div>') , '</div>';
					}

				}
			}
			
			echo '</div></div></div>';

		} elseif ($term == 'termtop') {

			$layout = get_term_meta($ID, 'top_widgets_'.$key.'_surround', true);
			
			$colorOverall = get_term_meta($ID, 'top_widgets_'.$key.'_color_scheme', true);
					
			$rowCount = get_term_meta($ID, 'top_widgets_'.$key.'_imageset', true);
			
			echo '<div class="widget widget_'.$key.' widget_imageset '.$colorOverall.'"><div class="container main_body"><div class="row">';
			
			for ($i=0; $i < $rowCount; $i++) {
				for ($n=0; $n < 4; $n++) { 
					$title_text = get_term_meta($ID, 'top_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
					$subtitle_text = get_term_meta($ID, 'top_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtitle_text', true);
					$image = get_term_meta($ID, 'top_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
					
					$image = str_replace('.test', '.com', $image);
					$image = str_replace('staging.', '', $image);

					$link = get_term_meta($ID, 'top_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_link_url', true);
					$color = get_term_meta($ID, 'top_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_colour_scheme', true);
					
					$titleid = str_replace(' ', '', $title_text);
					$titleid = strtolower($titleid);
					
					if (is_numeric($link)) $link = get_permalink($link);
					$custom_url = get_term_meta($ID, 'top_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
	
					if ($custom_url) $link = $custom_url;
					
					$size = 'thumbnail';
					
					if ($layout == 'image') $this->getSquareImg($image, $link);
					elseif ($layout == 'triangle') $this->getTriangle($image, $link, $color); 
					elseif (empty($title_text)) $this->getSquareImg($image, $link);
					else {
						echo 	'<div id="'.$titleid.'" class="span3 '.($image ? 'has-image' : 'no-image').'">' ,
								($link ? '<a href="' . $link . '"': '<div') . ' class="imgset_box_'.$layout.' '.$color.' slideto">';
						if 		($image) echo '<div class="absoluteCenterWrapper imgset_wrap_'.$layout
									.'"><img loading="lazy" '.getAlttag($image).' class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
						echo 	'<div class="box_text"><h2>' , $title_text , '</h2>',
								($this->showSubtitle($layout) ? $subtitle_text : '') , 
								'</div>' , ($link ? '</a>' : '</div>') , '</div>';
					}
				}
			}
			
			echo '</div></div></div>';
		} elseif ($term == 'termbottom') {

			$layout = get_term_meta($ID, 'bottom_widgets_'.$key.'_surround', true);
			
			$colorOverall = get_term_meta($ID, 'bottom_widgets_'.$key.'_color_scheme', true);
					
			$rowCount = get_term_meta($ID, 'bottom_widgets_'.$key.'_imageset', true);
			
			echo '<div class="widget widget_'.$key.' widget_imageset '.$colorOverall.'"><div class="container main_body"><div class="row">';
			
			for ($i=0; $i < $rowCount; $i++) {
				for ($n=0; $n < 4; $n++) { 
					$title_text = get_term_meta($ID, 'bottom_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
					$subtitle_text = get_term_meta($ID, 'bottom_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtitle_text', true);
					$image = get_term_meta($ID, 'bottom_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
					
					$image = str_replace('.test', '.com', $image);
					$image = str_replace('staging.', '', $image);

					$link = get_term_meta($ID, 'bottom_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_link_url', true);
					$color = get_term_meta($ID, 'bottom_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_colour_scheme', true);
					
					$titleid = str_replace(' ', '', $title_text);
					$titleid = strtolower($titleid);
					
					if (is_numeric($link)) $link = get_permalink($link);
					$custom_url = get_term_meta($ID, 'bottom_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
	
					if ($custom_url) $link = $custom_url;
					
					$size = 'thumbnail';
					
					if ($layout == 'image') $this->getSquareImg($image, $link);
					elseif ($layout == 'triangle') $this->getTriangle($image, $link, $color); 
					elseif (empty($title_text)) $this->getSquareImg($image, $link);
					else {
						echo 	'<div id="'.$titleid.'" class="span3 '.($image ? 'has-image' : 'no-image').'">' ,
								($link ? '<a href="' . $link . '"': '<div') . ' class="imgset_box_'.$layout.' '.$color.' slideto">';
						if 		($image) echo '<div class="absoluteCenterWrapper imgset_wrap_'.$layout
									.'"><img loading="lazy" '.getAlttag($image).' class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
						echo 	'<div class="box_text"><h2>' , $title_text , '</h2>',
								($this->showSubtitle($layout) ? $subtitle_text : '') , 
								'</div>' , ($link ? '</a>' : '</div>') , '</div>';
					}
				}
			}
			
			echo '</div></div></div>';
		} else {
		
			$layout = get_post_meta($ID, 'widgets_'.$key.'_surround', true);
			$colorOverall = get_post_meta($ID, 'widgets_'.$key.'_color_scheme', true);
					
			$rowCount = get_post_meta($ID, 'widgets_'.$key.'_imageset', true);
			
			echo '<div class="widget widget_'.$key.' widget_imageset '.$colorOverall.'"><div class="container main_body"><div class="row">';
			
			for ($i=0; $i < $rowCount; $i++) {
				for ($n=0; $n < 4; $n++) { 
					$title_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
					$subtitle_text = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtitle_text', true);
					$image = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
					
					$image = str_replace('.test', '.com', $image);
					$image = str_replace('staging.', '', $image);

					$link = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_link_url', true);
					$color = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_colour_scheme', true);
					
					$titleid = str_replace(' ', '', $title_text);
					$titleid = strtolower($titleid);
					
					if (is_numeric($link)) $link = get_permalink($link);
					$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
	
					if ($custom_url) $link = $custom_url;
					
					$size = 'thumbnail';
					
					if ($layout == 'image') $this->getSquareImg($image, $link);
					elseif ($layout == 'triangle') $this->getTriangle($image, $link, $color); 
					elseif (empty($title_text)) $this->getSquareImg($image, $link);
					else {
						echo 	'<div id="'.$titleid.'" class="span3 '.($image ? 'has-image' : 'no-image').'">' ,
								($link ? '<a href="' . $link . '"': '<div') . ' class="imgset_box_'.$layout.' '.$color.' slideto">';
						if 		($image) echo '<div class="absoluteCenterWrapper imgset_wrap_'.$layout
									.'"><img loading="lazy" '.getAlttag($image).' class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
						echo 	'<div class="box_text"><h2>' , $title_text , '</h2>',
								($this->showSubtitle($layout) ? $subtitle_text : '') , 
								'</div>' , ($link ? '</a>' : '</div>') , '</div>';
					}
				}
			}
			
			echo '</div></div></div>';
		
		}
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
		echo 	'<div class="span3 absoluteCenterWrapper imgset_box_fill">' ,
				($link ? '<a href="' . $link . '"': '<div') . '>';
		if 		($image) echo '<img loading="lazy" class="absoluteCenter" src=' . getImage($image, 'thumbnail') . ' srcset="'.getSrcset($image, $size).'" />';
		echo 	'</div>', ($link ? '</a>' : '</div>') , '</div>';
	}
}

/**
 * Defines functionality for image set widgets.
 *
 * Supports all post types, NOT taxonomies
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2013
 */
class LowerImageSetWidget extends Widget {

	/**
	 * Setup new image set.
	 * Does not support taxonomies.
	 */
	public function __construct($ID, $key)
	{
	
		$rows = 4;
			
		
		// assume this is not a number so it must be a taxonomy
		if (!is_numeric($ID)) {
		
			$layout = get_option($ID . '_widgets_'.$key.'_surround');
			$colorOverall = get_option($ID . '_widgets_'.$key.'_color_scheme');
			
			echo '<div class="widget widget_'.$key.' widget_imageset '.$colorOverall.'"><div class="container main_body"><div class="row">';
			
			//global $wpdb;
			$i = 0;
			$set = get_option($ID . '_widgets_' . $key . '_imageset');
			$rowCount = get_option($ID . '_widgets_' . $key . '_imageset_' . $i . '_row');


			for ($i=0; $i < $rowCount; $i++) {
				for ($n=0; $n < $set; $n++) { 
				
					$color = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_colour_scheme');
					$image = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_image');
					
					$image = str_replace('.test', '.com', $image);
					$image = str_replace('staging.', '', $image);

					$link = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_link_url');
					$custom_url = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_set_custom');
					$subtitle_text = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_subtitle_text');
					$title_text = get_option($ID . '_widgets_' . $key . '_imageset_' . $n . '_row_' . $i . '_title_text');
					
					$titleid = str_replace(' ', '', $title_text);
					$titleid = strtolower($titleid);
					
					if (is_numeric($link)) $link = get_permalink($link);
					//$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
				
					if ($custom_url) $link = $custom_url;
					
					$size = 'thumbnail';
					
					if ($layout == 'image') $this->getSquareImg($image, $link);
					elseif ($layout == 'triangle') $this->getTriangle($image, $link, $color); 
					elseif (empty($title_text)) $this->getSquareImg($image, $link);
					else {
						echo 	'<div id="'.$titleid.'" class="span3 '.($image ? 'has-image' : 'no-image').'">' ,
								($link ? '<a href="' . $link . '"': '<div') . ' class="imgset_box_'.$layout.' '.$color.'">';
						if 		($image) echo '<div class="absoluteCenterWrapper imgset_wrap_'.$layout
									.'"><img loading="lazy" class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
						echo 	'<div class="box_text"><h2>' , $title_text , '</h2>',
								($this->showSubtitle($layout) ? $subtitle_text : '') , 
								'</div>' , ($link ? '</a>' : '</div>') , '</div>';
					}

				}
			}
			
			echo '</div></div></div>';

		} else {
		
			$layout = get_post_meta($ID, 'lower_widgets_'.$key.'_surround', true);
			
			$colorOverall = get_post_meta($ID, 'lower_widgets_'.$key.'_color_scheme', true);
					
			$rowCount = get_post_meta($ID, 'lower_widgets_'.$key.'_imageset', true);
			
			echo '<div class="widget widget_'.$key.' widget_imageset '.$colorOverall.'"><div class="container main_body"><div class="row">';
			
			for ($i=0; $i < $rowCount; $i++) {
				for ($n=0; $n < 4; $n++) { 
					$title_text = get_post_meta($ID, 'lower_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
					$subtitle_text = get_post_meta($ID, 'lower_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtitle_text', true);
					$image = get_post_meta($ID, 'lower_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
					$link = get_post_meta($ID, 'lower_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_link_url', true);
					$color = get_post_meta($ID, 'lower_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_colour_scheme', true);
					
					$titleid = str_replace(' ', '', $title_text);
					$titleid = strtolower($titleid);
					
					if (is_numeric($link)) $link = get_permalink($link);
					$custom_url = get_post_meta($ID, 'widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);
	
					if ($custom_url) $link = $custom_url;
					
					$size = 'thumbnail';
					
					if ($layout == 'image') $this->getSquareImg($image, $link);
					elseif ($layout == 'triangle') $this->getTriangle($image, $link, $color); 
					elseif (empty($title_text)) $this->getSquareImg($image, $link);
					else {
						echo 	'<div id="'.$titleid.'" class="span3 '.($image ? 'has-image' : 'no-image').'">' ,
								($link ? '<a href="' . $link . '"': '<div') . ' class="imgset_box_'.$layout.' '.$color.' slideto">';
						if 		($image) echo '<div class="absoluteCenterWrapper imgset_wrap_'.$layout
									.'"><img loading="lazy" class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
						echo 	'<div class="box_text"><h2>' , $title_text , '</h2>',
								($this->showSubtitle($layout) ? $subtitle_text : '') , 
								'</div>' , ($link ? '</a>' : '</div>') , '</div>';
					}
				}
			}
			
			echo '</div></div></div>';
		
		}
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
		echo 	'<div class="span3 absoluteCenterWrapper imgset_box_fill">' ,
				($link ? '<a href="' . $link . '"': '<div') . '>';
		if 		($image) echo '<img loading="lazy" class="absoluteCenter" src=' . getImage($image, 'thumbnail') . ' srcset="'.getSrcset($image, $size).'" />';
		echo 	'</div>', ($link ? '</a>' : '</div>') , '</div>';
	}
}

/**
 * Defines functionality for Key facts image set widgets.
 *
 * Supports all post types, NOT taxonomies
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2013
 */
class KfImageSetWidget extends Widget {

	/**
	 * Setup new image set.
	 * Does not support taxonomies.
	 */
	public function __construct($ID, $key)
	{
		$rows = 4;
		
		$layout = get_post_meta($ID, 'kf_widgets_'.$key.'_surround', true);
		$colorOverall = get_post_meta($ID, 'kf_widgets_'.$key.'_color_scheme', true);
				
		$rowCount = get_post_meta($ID, 'kf_widgets_'.$key.'_imageset', true);
		
		echo '<div class="widget widget_'.$key.' kf widget_imageset '.$colorOverall.'"><div class="container main_body"><div class="row">';
		
		for ($i=0; $i < $rowCount; $i++) {
			for ($n=0; $n < 4; $n++) { 
				$title_text = get_post_meta($ID, 'kf_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_title_text', true);
				$subtitle_text = get_post_meta($ID, 'kf_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_subtitle_text', true);
				$image = get_post_meta($ID, 'kf_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_image', true);
				$link = get_post_meta($ID, 'kf_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_link_url', true);
				$color = get_post_meta($ID, 'kf_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_colour_scheme', true);
				
				$titleid = str_replace(' ', '', $title_text);
				$titleid = strtolower($titleid);
				
				if (is_numeric($link)) $link = get_permalink($link);
				$custom_url = get_post_meta($ID, 'kf_widgets_'.$key.'_imageset_'.$i.'_row_'.$n.'_set_custom', true);

				if ($custom_url) $link = $custom_url;
				
				$size = 'thumbnail';
				
				if ($layout == 'image') $this->getSquareImg($image, $link);
				elseif ($layout == 'triangle') $this->getTriangle($image, $link, $color); 
				elseif (empty($title_text)) $this->getSquareImg($image, $link);
				else {
					echo 	'<div id="'.$titleid.'" class="span3 '.($image ? 'has-image' : 'no-image').'">' ,
							($link ? '<a href="' . $link . '"': '<div') . ' class="imgset_box_'.$layout.' '.$color.'">';
					if 		($image) echo '<div class="absoluteCenterWrapper imgset_wrap_'.$layout
								.'"><img loading="lazy" class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" /></div>';
					echo 	'<div class="box_text"><h2>' , $title_text , '</h2>',
							($this->showSubtitle($layout) ? $subtitle_text : '') , 
							'</div>' , ($link ? '</a>' : '</div>') , '</div>';
				}
			}
		}
		
		echo '</div></div></div>';
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
		echo 	'<div class="span3 absoluteCenterWrapper imgset_box_fill">' ,
				($link ? '<a href="' . $link . '"': '<div') . '>';
		if 		($image) echo '<img loading="lazy" class="absoluteCenter" src=' . getImage($image, 'thumbnail') . ' srcset="'.getSrcset($image, $size).'" />';
		echo 	'</div>', ($link ? '</a>' : '</div>') , '</div>';
	}
}

/**
 * Defines functionality for review widgets.
 *
 * Supports all post types, NOT taxonomies
 *
 * @package kate-and-toms
 * @author  Elliott Richmond
 * @copyright Kate and Tom's Ltd 2013
 */
class ReviewsWidget extends Widget {

	private $title;
	private $subtitle;
	private $body;
	private $image;
	private $colorScheme;
	

	/**
	 * Setup new reviews widget.
	 */
	public function __construct($ID, $key) {

		$this->title = get_post_meta($ID, 'widgets_'.$key.'_review_title', true);
		$this->subtitle = get_post_meta($ID, 'widgets_'.$key.'_review_subtitle', true);
		$this->colorScheme = get_post_meta($ID, 'widgets_'.$key.'_review_color_scheme', true);
		$image = get_post_meta($ID, 'widgets_'.$key.'_review_image', true);

		$rowCount = get_post_meta($ID, 'widgets_'.$key.'_review', true);
						
		if ($image) $image = getImage($image, 'large');

		echo '<div class="widget widget_'.$key.' widget_reviews '.$this->colorScheme.'">
				<div class="container">
					<div class="row">
						<div class="span12">
							<img loading="lazy" src="'.$image.'" alt="'.$this->title.'" />
							<h2 style="margin-top:1em;">'.$this->title.'</h2>'.($this->subtitle ? '<h3  class="offset2 span8">'.$this->subtitle.'</h3>' : '' ) . '

						</div>
					</div>
					
					<div id="newtextSlider" class="row">
						<div class="span2 before"><img src="'.get_stylesheet_directory_uri().'/images/prev.png" alt="navigation arrow"></div>
						<div class="sliders">';

						for ($i=0; $i < $rowCount; $i++) {
							$reviewContents = get_post_meta($ID, 'widgets_'.$key.'_review_'.$i.'_field_review_content', true);
							$reviewerNames = get_post_meta($ID, 'widgets_'.$key.'_review_'.$i.'_field_review_name', true);
							$this->showReview($reviewContents, $reviewerNames);
						}

						echo '</div>';
						echo '<div class="span2 after"><img src="'.get_stylesheet_directory_uri().'/images/next.png" alt="navigation arrow"></div>';



			

			echo '</div></div></div>';

			?>
			
			<?php
	}
	
	private function showReview($review, $name) {
		
		echo '<div>'. $review .' - <span class="reviewedby">'. $name .'</span></div>';
		
	}
}

/**
 * Defines functionality for review widgets.
 *
 * Supports all post types, NOT taxonomies
 *
 * @package kate-and-toms
 * @author  Elliott Richmond
 * @copyright Kate and Tom's Ltd 2013
 */
class FAQGroup extends Widget {
	
	private $title;
	private $colorScheme;
	
	public function __construct($ID, $key)
	{
		$this->title = get_post_meta($ID, 'widgets_'.$key.'_title', true);
		$this->colorScheme = get_post_meta($ID, 'widgets_'.$key.'_faq_color_scheme', true);
		$rowCount = get_post_meta($ID, 'widgets_'.$key.'_faq', true);
		
		if ($this->colorScheme == 'color16') {
			$text_colour = '333';
		} else {
			$text_colour = 'fff';
		}
		
		
		echo '<div class="widget widget_'.$key.' widget_faq '.$this->colorScheme.'">
				<div class="container">
				<div class="row">
				<div class="span10 offset1">
				<section class="faqsection">
				<h2>'.$this->title.'</h2>
				<div class="accordion" id="accordion'.$key.'">';
			
		
		
		for ($i=0; $i < $rowCount; $i++) {
			$question = get_post_meta($ID, 'widgets_'.$key.'_faq_'.$i.'_field_the_question', true);
			$answer = get_post_meta($ID, 'widgets_'.$key.'_faq_'.$i.'_field_the_answer', true);
			
			echo '<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion'.$key.'" href="#faq'.$key.$i.'" style="color: #'.$text_colour.'">'.$question.'</a>
							</div>
							<div id="faq'.$key.$i.'" class="accordion-body collapse">
								<div class="accordion-inner">'.$answer.'</div>
							</div>
						</div>';
		}
		
		echo '</div>
				</section>
				</div>
				</div>
				</div>
				</div>';

	}
}

/**
 * Defines functionality for button widgets.
 *
 * Supports all post types, NOT taxonomies
 *
 * @package kate-and-toms
 * @author  Elliott Richmond
 * @copyright Kate and Tom's Ltd 2013
 */
class ButtonWidget extends Widget {

	private $title;
	private $colorScheme;
	private $body;
	
	/**
	 * Setup new reviews widget.
	 */
	public function __construct($ID, $key, $term)
	{
		$value = 0;
		if (!is_numeric($ID)) {
			
			$count = get_option($ID . '_widgets_'.$key.'_buttons');
			$this->title = get_option($ID . '_widgets_'.$key.'_bntintro_title');
			$this->colorScheme = get_option($ID . '_widgets_'.$key.'_buttons_color_scheme');

			
			echo '<div class="widget widget_'.$key.' widget_button '.$this->colorScheme.'"><div class="container main_body"><div class="row">';
			echo '<div class="span12"><ul>';
			
			echo '<li class="title">'.$this->title.'</li>';
			
			for ($i=0; $i < $count; $i++) {
				$buttonTitle = get_option($ID.'_widgets_'.$key.'_buttons_'.$i.'_button_title', true);
				$buttonLink = get_option($ID.'_widgets_'.$key.'_buttons_'.$i.'_button_link', true);
				$buttonUrl = get_option($ID.'_widgets_'.$key.'_buttons_'.$i.'_field_custom_url', true);
				$value++;
				$this->showButton($buttonTitle, $buttonLink, $buttonUrl, $value);
			}

			echo '</ul></div></div></div></div>';

		} elseif ($term == 'termtop') {

			$this->title = get_term_meta($ID, 'top_widgets_'.$key.'_bntintro_title', true);
			$this->colorScheme = get_term_meta($ID, 'top_widgets_'.$key.'_buttons_color_scheme', true);
	
			$rowCount = get_term_meta($ID, 'top_widgets_'.$key.'_buttons', true);
							
			echo '<div class="widget widget_'.$key.' widget_button '.$this->colorScheme.'"><div class="container main_body"><div class="row">
				<div class="span12"><ul><li class="title">'.$this->title.'</li>';
	
			for ($i=0; $i < $rowCount; $i++) {
				$buttonTitle = get_term_meta($ID, 'top_widgets_'.$key.'_buttons_'.$i.'_button_title', true);
				$buttonLink = get_term_meta($ID, 'top_widgets_'.$key.'_buttons_'.$i.'_button_link', true);
				$buttonUrl = get_term_meta($ID, 'top_widgets_'.$key.'_buttons_'.$i.'_field_custom_url', true);
				$value++;
				$this->showButton($buttonTitle, $buttonLink, $buttonUrl, $value);
			}
	
			echo '</ul></div></div></div></div>';
			
		} elseif ($term == 'termbottom') {

			$this->title = get_term_meta($ID, 'bottom_widgets_'.$key.'_bntintro_title', true);
			$this->colorScheme = get_term_meta($ID, 'bottom_widgets_'.$key.'_buttons_color_scheme', true);
	
			$rowCount = get_term_meta($ID, 'bottom_widgets_'.$key.'_buttons', true);
							
			echo '<div class="widget widget_'.$key.' widget_button '.$this->colorScheme.'"><div class="container main_body"><div class="row">
				<div class="span12"><ul><li class="title">'.$this->title.'</li>';
	
			for ($i=0; $i < $rowCount; $i++) {
				$buttonTitle = get_term_meta($ID, 'bottom_widgets_'.$key.'_buttons_'.$i.'_button_title', true);
				$buttonLink = get_term_meta($ID, 'bottom_widgets_'.$key.'_buttons_'.$i.'_button_link', true);
				$buttonUrl = get_term_meta($ID, 'bottom_widgets_'.$key.'_buttons_'.$i.'_field_custom_url', true);
				$value++;
				$this->showButton($buttonTitle, $buttonLink, $buttonUrl, $value);
			}
	
			echo '</ul></div></div></div></div>';
			
		} else {
			
			$this->title = get_post_meta($ID, 'widgets_'.$key.'_bntintro_title', true);
			$this->colorScheme = get_post_meta($ID, 'widgets_'.$key.'_buttons_color_scheme', true);
	
			$rowCount = get_post_meta($ID, 'widgets_'.$key.'_buttons', true);
							
			echo '<div class="widget widget_'.$key.' widget_button '.$this->colorScheme.'"><div class="container main_body"><div class="row">
				<div class="span12"><ul><li class="title">'.$this->title.'</li>';
	
			for ($i=0; $i < $rowCount; $i++) {
				$buttonTitle = get_post_meta($ID, 'widgets_'.$key.'_buttons_'.$i.'_button_title', true);
				$buttonLink = get_post_meta($ID, 'widgets_'.$key.'_buttons_'.$i.'_button_link', true);
				$buttonUrl = get_post_meta($ID, 'widgets_'.$key.'_buttons_'.$i.'_field_custom_url', true);
				$value++;
				$this->showButton($buttonTitle, $buttonLink, $buttonUrl, $value);
			}
	
			echo '</ul></div></div></div></div>';
			
		}

	}
	
	private function showButton($btntitle, $btnlink, $btncustomUrl, $id)
	{	
		if ($btncustomUrl != ''){
			$link = $btncustomUrl;
		} else {
			$link = get_permalink($btnlink);
		}
		
		if($link == '#getintouch') {
			echo '<li id="st-trigger-effects"><a href="#getintouch" data-effect="st-effect-3" data-toggle="modal" class="btn btn-'.$id.'">'.$btntitle.'</a></li>';
		} else {
			echo '<li><a href="'; echo $link; echo '" class="btn btn-'.$id.'" data-toggle="modal">'.$btntitle.'</a></li>';
		}

		
		if ($id==2) echo '<li class="orspace">or</li>';
	}
}

/**
 * Defines functionality for button widgets.
 *
 * Supports all post types, NOT taxonomies
 *
 * @package kate-and-toms
 * @author  Elliott Richmond
 * @copyright Kate and Tom's Ltd 2013
 */
class KfButtonWidget extends Widget {

	private $title;
	private $colorScheme;
	private $body;
	
	/**
	 * Setup new reviews widget.
	 */
	public function __construct($ID, $key)
	{
		$this->title = get_post_meta($ID, 'kf_widgets_'.$key.'_bntintro_title', true);
		$this->colorScheme = get_post_meta($ID, 'kf_widgets_'.$key.'_buttons_color_scheme', true);

		$rowCount = get_post_meta($ID, 'kf_widgets_'.$key.'_buttons', true);		
		echo '<div class="widget widget_'.$key.' kf widget_button '.$this->colorScheme.'"><div class="container main_body"><div class="row">
			<div class="span12"><ul><li class="title">'.$this->title.'</li>';

		for ($i=0; $i < $rowCount; $i++) {
			$buttonTitle = get_post_meta($ID, 'kf_widgets_'.$key.'_buttons_'.$i.'_button_title', true);
			$buttonLink = get_post_meta($ID, 'kf_widgets_'.$key.'_buttons_'.$i.'_button_link', true);
			$buttonUrl = get_post_meta($ID, 'kf_widgets_'.$key.'_buttons_'.$i.'_field_custom_url', true);
			$value++;
			$this->showButton($buttonTitle, $buttonLink, $buttonUrl, $value);
		}

		echo '</ul></div></div></div></div>';
	}
	
	private function showButton($btntitle, $btnlink, $btncustomUrl, $id)
	{	
		if ($btncustomUrl != ''){
			$link = $btncustomUrl;
		} else {
			$link = get_permalink($btnlink);
		}

		echo '<li><a href="'; echo $link; echo '" class="btn btn-'.$id.'" data-toggle="modal">'.$btntitle.'</a></li>';
		if ($id==2) echo '<li class="orspace">or</li>';
	}
}

/**
 * Defines functionality for wide image widgets.
 *
 * Supports all post types, NOT taxonomies
 *
 * @package kate-and-toms
 * @author  Elliott Richmond
 * @copyright Kate and Tom's Ltd 2013
 */
class WideImageWidget extends Widget {

	private $image;

	/**
	 * Setup new wide image widget.
	 */
	public function __construct($ID, $key, $term) {
		
		if($term == 'termtop') {
			$image = get_term_meta($ID, 'top_widgets_'.$key.'_wide_image', true);
		} elseif ($term == 'termbottom') {
			$image = get_term_meta($ID, 'bottom_widgets_'.$key.'_wide_image', true);
		} else {
			// assume this is not a number so it must be a taxonomy
			$image = (is_numeric($ID) ? 
				get_post_meta($ID, 'widgets_'.$key.'_wide_image', true) : 
				get_option($ID . '_widgets_'.$key.'_wide_image')
			);
		}
		
		$align = get_img_description($image);
		if (empty($align)) {$align = 'absoluteCenter';}
						
		if ($image) {
			$image = getImage($image, 'huge');
			$attID = get_post_meta($ID, 'widgets_'.$key.'_wide_image', true);
			// $image_srcset = wp_get_attachment_image_srcset( $attID, 'huge' );
			$image_srcset = getSrcset($attID, 'huge');
		} 
		
		if (!isset($this->title)) $this->title = '';
		
		echo '<div class="widget widget_'.$key.' widget_wideimage">
				<div class="row">
				<div class="cropped">
					<img loading="lazy" '.getAlttag($attID).' class="span12 '.$align.'" src="'.$image.'" srcset="'.$image_srcset.'" alt="'.$this->title.'" />
				</div>
				</div>
			</div>';

		
	}
	
}

/**
 * Defines functionality for floorplan widgets.
 *
 * Supports all post types, NOT taxonomies
 *
 * @package kate-and-toms
 * @author  Elliott Richmond
 * @copyright Kate and Tom's Ltd 2013
 */
class FloorPlanWidget extends Widget {

	private $image;

	/**
	 * Setup new wide image widget.
	 */
	public function __construct($ID, $key)
	{

		// assume this is not a number so it must be a taxonomy
		$image = (is_numeric($ID) ? 
			get_post_meta($ID, 'kf_widgets_'.$key.'_floorplan_image', true) : 
			get_option($ID . '_kf_widgets_'.$key.'_floorplan_image'));

		$align = get_img_description($image);
		if (empty($align)) {$align = 'absoluteCenter';}
						
		if ($image) $image = getImage($image, 'full');
		
		if (!isset($this->title)) $this->title = '';
		
		echo '<div class="widget widget_floorplan color9">
				<div class="container main_body">
					<div class="row">
						<span class="zoom" id="ex2">
							<img loading="lazy" src="'.$image.'" alt="'.$this->title.'" />
						</span>			
					</div>
				</div>
			</div>';

	}
	
}

/**
 * Defines functionality for Key Facts wide image widgets.
 *
 * Supports all post types, NOT taxonomies
 *
 * @package kate-and-toms
 * @author  Elliott Richmond
 * @copyright Kate and Tom's Ltd 2013
 */
class KfWideImageWidget extends Widget {

	private $image;

	/**
	 * Setup new wide image widget.
	 */
	public function __construct($ID, $key)
	{

		$image = get_post_meta($ID, 'kf_widgets_'.$key.'_wide_image', true);
		//$this->crop = get_post_meta($ID, 'widgets_'.$key.'_wide_crop_from', true);
		$align = get_img_description($image);
		if (empty($align)) {$align = 'absoluteCenter';}
						
		if ($image) $image = getImage($image, 'huge');

		echo '<div class="widget widget_'.$key.' kf widget_wideimage">
				<div class="row">
				<div class="cropped">
					<img loading="lazy" class="span12 '.$align.'" src="'.$image.'" alt="'.$this->title.'" />
				</div>
				</div>
			</div>';

	}
	
}

/**
 * Defines functionality for standard widgets.
 * Supports the following layouts:
 * 'imgleft' - Image left
 * 'imgright' - Image right
 * 'galleryleft' - Gallery left
 * 'galleryright' - Gallery right
 * 'largeimg' - Large image
 * 'text' - Text only
 * 'fourimage' - Four images only
 *
 * Supports all post types and taxonomies
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2013
 */
class LowerStandardWidget extends Widget {

	private $title;
	private $subtitle;
	private $body;
	private $images;
	private $colorScheme;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post, or if string taxonomy in format of type_taxID, eg location_4
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key)
	{
		// Assuming if ID is not numeric, it is a taxonomy
		if (!is_numeric($ID)) {
			global $wpdb;
			$this->title = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_title'));
			$this->title = $this->title[0];
			$this->subtitle = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_subtitle'));
			$this->subtitle = $this->subtitle[0];
			$this->layout = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_layout'));
			$this->layout = $this->layout[0];
			$this->colorScheme = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_color_scheme'));
			$this->colorScheme = $this->colorScheme[0];
			$content = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_body'));
			$content = apply_filters('the_content',$content[0]);
			$images = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_gallery'));
			$images = unserialize($images[0]);
		}
		// Can assume it is normal post type
		else {
			$this->title = get_post_meta($ID, 'lower_widgets_'.$key.'_title', true);
			$this->subtitle = get_post_meta($ID, 'lower_widgets_'.$key.'_sub_title', true);
			$this->layout = get_post_meta($ID, 'lower_widgets_'.$key.'_layout', true);
			$this->crop = get_post_meta($ID, 'lower_widgets_'.$key.'_crop_from', true);
			$this->colorScheme = get_post_meta($ID, 'lower_widgets_'.$key.'_color_scheme', true);
			$content = apply_filters('the_content',get_post_meta($ID, 'lower_widgets_'.$key.'_main_body', true));
			$images = get_post_meta($ID, 'lower_widgets_'.$key.'_gallery', true);

		}
		// Get image URLs for all required sizes
		if ($images) {
			foreach ($images as $k => $v) {
				$imageSrcs[$k]['huge'] = getImage($v, 'huge');
				$imageSrcs[$k]['large'] = getImage($v, 'large');
				$imageSrcs[$k]['square'] = getImage($v, 'square');
				$imageSrcs[$k]['thumbnail'] = getImage($v, 'thumbnail');
			}
			$this->images = $imageSrcs;
			$this->imageid = $v;
		}
		
		$this->body = explode('$$', $content);
		$layoutFunction = $this->layout.'Display';
		
		$noTitle = false;
		
		if (empty($this->title)) {
			$noTitle = ' notitle';
		}

		echo '<div class="widget widget_'.$key.' widget_'.$this->layout.' '.$this->colorScheme.$noTitle.'"><div class="container main_body"><div class="row">';
		$this->$layoutFunction();
		echo '</div></div></div>';
	}
	
	private function imgleftDisplay()
	{
		echo '<img loading="lazy" class="span6 imgleftDisplay" src="'.$this->images[0]['square'].'" alt="'.$this->title.'" />
			<div class="span6"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) .$this->body[0].'</div>';
	}
	
	private function imgrightDisplay()
	{
		echo '<div class="span6 imgrightDisplay"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) .$this->body[0].'</div>
			<img loading="lazy" class="span6" src="'.$this->images[0]['square'].'" alt="'.$this->title.'" />';
	}
	
	private function galleryleftDisplay()
	{
		echo '<div class="span6 galleryleftDisplay image-block"><div class="row">';
		if (is_array($this->images)) {
			foreach ($this->images as $k => $image) {
				echo '<img loading="lazy" '.getAlttag($this->imageid).' class="span3" '. ( $k < 2 ? 'style="margin-bottom:20px;"' : '' ).' src="'.$image['thumbnail'].'"  />';
				if ($k == 3) break;
			}
		}
		echo '</div></div>
			<div class="span6"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ) . 
			$this->body[0].'</div>';
	}
	
	private function galleryrightDisplay()
	{
		echo '<div class="span6 galleryrightDisplay"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3>'.$this->subtitle.'</h3>' : '' ).
			$this->body[0].'</div>
			<div class="span6 galleryrightDisplay image-block"><div class="row">';
			// added as an error check to reduce error rates added 27th June 2013
			if ($this->images) {
				foreach ($this->images as $k => $image) {
					echo '<img loading="lazy" '.getAlttag($this->imageid).' class="span3" '. ( $k < 2 ? 'style="margin-bottom:20px;"' : '' ).' src="'.$image['thumbnail'].'"  />';
				}
			}
		echo '</div></div>';
	}
	
	private function largeimgDisplay()
	{	
		$align = get_img_description($this->imageid);
		if (empty($align)) {$align = 'absoluteCenter';}
		
		echo '<div class="cropped"><img loading="lazy" class="span12 '.$align.'" src="'.$this->images[0]['huge'].'" alt="'.$this->title.'" /></div>';
		
		if (!empty($this->title)) {
			echo '<div class="span12"><h2'.$addclass.'>'.$this->title.'</h2>'.($this->subtitle ? '<h3 class="offset2 span8">'.$this->subtitle.'</h3>' : '' ).'</div>';
		}
		
		if (count($this->body) > 1) echo '<div class="span6">'.$this->body[0].'</div><div class="span6">'.$this->body[1].'</div></div>';
		else echo '<div class="span10 offset1">'.$this->body[0].'</div>';
	}
	
	private function textDisplay()
	{	
		$addclass = null;
		
		if (empty($this->body)) {$addclass = ' class="nomargin"';}
		
			echo '<div class="span12"><h2'.$addclass.'>'.$this->title.'</h2>'.($this->subtitle ? '<h3 class="offset2 span8">'.$this->subtitle.'</h3>' : '' ) . '</div>';
				
		if (is_page(61) || is_page(63)){
		
			if (count($this->body) > 1)
				echo '<div class="span8 offset2">'.$this->body[0].'</div><div class="span4">'.$this->body[1].'</div></div>';
				
			else
				echo '<div class="span8 offset2">'.$this->body[0].'</div>';

		} else {
			
			if (count($this->body) > 1)
				echo '<div class="span6">'.$this->body[0].'</div><div class="span6">'.$this->body[1].'</div></div>';
				
			else
				echo '<div class="span8 offset2">'.$this->body[0].'</div>';

		}

	}
	
	private function fourimageDisplay()
	{
		$with_text = false;
		if (!empty($this->title)) {
			$with_text = true;
			echo '<div class="mpadder">';
			echo '<div class="span12"><h2>'.$this->title.'</h2>'.($this->subtitle ? '<h3 class="offset2 span8">'.$this->subtitle.'</h3>' : '' ).'</div>';
			echo '</div>';
		}
		if (!empty($this->body[0]) && count($this->body) > 1) {
			$with_text = true;
			echo '<div class="mpadder">';
			echo '<div class="span6 fourimageDisplay">'.$this->body[0].'</div><div class="span6"><p>'.$this->body[1].'</div><div class="clearfix"></div>';
			echo '</div>';
		} elseif (!empty($this->body[0])) {
			$with_text = true;
			echo '<div class="mpadder">';
			echo '<div class="span8 offset2">'.$this->body[0].'</div>';
			echo '</div>';
		}

		if ($with_text == true) { echo '<div class="text_above">';}
		if (is_array($this->images)) {
			echo '<img loading="lazy" class="span3 four1" src="'.$this->images[0]['thumbnail'].'" alt="'.$this->title.'" />
				<img loading="lazy" class="span3 four2" src="'.$this->images[1]['thumbnail'].'" alt="'.$this->title.'" />
				<img loading="lazy" class="span3 four3" src="'.$this->images[2]['thumbnail'].'" alt="'.$this->title.'" />
				<img loading="lazy" class="span3 four4" src="'.$this->images[3]['thumbnail'].'" alt="'.$this->title.'" />';
		}
		if ($with_text == true) { echo '</div>';}
	}
	
}


class FooterWidgets extends Widget {

	private $title;
	private $subtitle;
	private $body;
	private $images;
	private $colorScheme;

	/**
	 * Setup new widget area.
	 * @param int|string Integer of post, or if string taxonomy in format of type_taxID, eg location_4
	 * @param int The key of the widget on page (starting from 0), so if fourth ID = 3
	 */
	public function __construct($ID, $key) {
		// Assuming if ID is not numeric, it is a taxonomy
		if (!is_numeric($ID)) {
			global $wpdb;
			$this->title = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_title'));
			$this->title = $this->title[0];
			$this->subtitle = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_subtitle'));
			$this->subtitle = $this->subtitle[0];
			$this->layout = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_layout'));
			$this->layout = $this->layout[0];
			$this->colorScheme = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_color_scheme'));
			$this->colorScheme = $this->colorScheme[0];
			$content = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_body'));
			$content = apply_filters('the_content',$content[0]);
			$images = $wpdb->get_col($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",$ID . '\_widgets\_' . $key . '\_gallery'));
			$images = unserialize($images[0]);
		}
		// Can assume it is normal post type
		else {
			$this->title = get_post_meta($ID, 'lower_widgets_'.$key.'_title', true);
			$this->subtitle = get_post_meta($ID, 'lower_widgets_'.$key.'_subtitle', true);
			$this->layout = get_post_meta($ID, 'lower_widgets_'.$key.'_layout', true);
			$this->crop = get_post_meta($ID, 'lower_widgets_'.$key.'_crop_from', true);
			$this->colorScheme = get_post_meta($ID, 'lower_widgets_'.$key.'_color_scheme', true);
			$content = apply_filters('the_content',get_post_meta($ID, 'lower_widgets_'.$key.'_body', true));
			$images = get_post_meta($ID, 'lower_widgets_'.$key.'_gallery', true);

		}
		// Get image URLs for all required sizes
		if ($images) {
			foreach ($images as $k => $v) {
				$imageSrcs[$k]['huge'] = getImage($v, 'huge');
				$imageSrcs[$k]['large'] = getImage($v, 'large');
				$imageSrcs[$k]['square'] = getImage($v, 'square');
				$imageSrcs[$k]['thumbnail'] = getImage($v, 'thumbnail');
			}
			$this->images = $imageSrcs;
			$this->imageid = $v;
		}
		
		$this->body = explode('$$', $content);
		$layoutFunction = $this->layout.'Display';
		
		$noTitle = false;
		
		if (empty($this->title)) {
			$noTitle = ' notitle';
		}

		echo '<div class="widget widget_lower widget_'.$key.' widget_'.$this->layout.'" style="background-colour:'.$this->colorScheme.';"><div class="container main_body"><div class="row">';
		$this->$layoutFunction();
		echo '</div></div></div>';
	}
	
	public static function createWidgets($ID, $secondary = false) {
		$widgets = get_post_meta($ID, 'lower_widgets', true);
		if (empty($widgets)) {
			return;
		}
		foreach ($widgets as $key => $value) {
			if ($value == 'standard') {
				new LowerStandardWidget($ID, $key);
			} elseif ($value == 'image_set') {
				new LowerImageSetWidget($ID, $key);
			}
		}
	}	
	

}

?>
