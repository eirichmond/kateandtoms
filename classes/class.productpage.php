<?php
/**
 * Defines how product pages should be produced.
 *
 * @package kate-and-toms
 */

/**
 * Defines overall product page functionality for site.
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2012
 */
abstract class ProductPage {

	/**
	 * Page names.
	 * @var array An array of all page names for product type.
	 */
 	protected $allPageNames;

	/**
	 * Product name.
	 * @var string Name of the product.
	 */
 	protected $name;

	/**
	 * Product ID.
	 * @var integer ID of the product.
	 */
 	protected $ID;

	/**
	 * Current page slug.
	 * @var string Page slug that is being displayed.
	 */
 	protected $page;

	/**
	 * Current page name.
	 * @var string Page name that is being displayed.
	 */
 	protected $pageName;

	/**
	 * All pages for this product.
	 * @var string Page name that is being displayed.
	 */
 	protected $pages;

	/**
	 * Images for the specified page.
	 * @var array Array of images to display.
	 */
 	protected $pageImages;

	/**
	 * Current house message.
	 * @var string Message, e.g. booking success, specified date etc.
	 */
 	protected $message;

	/**
	 * Current page content.
	 * @var string Content to be displayed.
	 */
 	protected $content;

	/**
	 * Setup of new product page.
	 * @param integer ID of house
	 */
 	public function __construct($ID) {

 	}

	/**
	 * Get name of the product.
	 *
	 * @return string Name of page
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get the content for current page.
	 *
	 * @return Page contents
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * Get the name for the current page.
	 *
	 * @return Page name
	 */
	public function getPageName() {
		$pages = array (
			'availability' => 'Check Availability',
			'book' => 'Book Now',
			'gallery' => 'Gallery',
			'more' => 'Things To Do',
		);
		if (isset($pages[$this->getPage()])) return $pages[$this->getPage()];
		else return null;
	}

	/**
	 * Get message for current page.
	 *
	 * @return string Message
	 */
	public function getMessage() {
		return $this->message;
	}


	public function getContent() {
		return $this->content;
	}

	public function listPages() {
		$pages = array();
		foreach ($this->pages as $page) {
			$pages[$page] = $this->allPageNames[$page];
		}
		return $pages;
	}

	public function showCarousel() {
		$pagesToHide = array('availability', 'book', 'gallery');

		return !in_array($this->getPage(), $pagesToHide);
	}

	public function getTitleClass()
	{
		if (has_term( 'cotswolds' , 'location')) {
			return 'title_color1';
		}
		elseif (has_term( 'sea' , 'location')) {
			return 'title_color2';
		}
		elseif (has_term( 'country' , 'location')) {
			return 'title_color3';
		}
	}

 	abstract public function setPageNames();

	public function startPageWrap()
	{
		echo '<div class="main_body_cont"><div class="container main_body">
			<!-- Main content area -->
			<div id="house-'. $this->ID . '">';
	}

	public function endPageWrap()
	{
		echo '</div></div></div>';
	}


}

/**
 * Extends functionality for house pages.
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2012
 */
class HousePage extends ProductPage {

	/**
	 * Get page names for house pages.
	 */
 	public function setPageNames() {

		$pages = get_site_option('kat_network_settings');
		$pages = str_replace("++","'",$pages['house_page_names']);
		$pagesTemp = array();
		foreach (explode("<br />", $pages) as $page) {
			list ($slug, $name) = explode(' : ', $page, 2);
			$slug = trim($slug);
			$pagesTemp[$slug] = $name;
		}
		$pagesTemp['edit'] = 'Edit House';
		$allPageNames = 'allPageNames';
		$this->allPageNames = $pagesTemp;

	}

	/**
	 * Setup house.
	 */
	public function __construct($ID) {
		$this->ID = $ID;
		self::setPageNames();
		$this->setImages();
		$this->setCurrentPage();
		$this->setPages();
	}

	/**
	 * Set current page details.
	 */
	private function setCurrentPage() {

		global $wp_query;



		if (isset($wp_query->query_vars['houses'])) {
			$this->name = urldecode($wp_query->query_vars['houses']);
		}
		else {
			// Added to work with single house sites
			global $the_query;
			$this->name = $the_query->post->post_name;
		}


		if(isset($wp_query->query_vars['current_house_page'])) {
			$this->page = urldecode($wp_query->query_vars['current_house_page']);
			$this->message = (isset($wp_query->query_vars['addvariable']) ? urldecode($wp_query->query_vars['addvariable']) : null);
		} else {
			if (is_page_template( 'page-microsite-default-page.php' )) {
				$this->page = 'mirco_default_page';
			} else {
				$this->page = 'house_home';
			}
		}

		if (extension_loaded('newrelic')) {
			newrelic_add_custom_parameter ('house_name', $this->name);
			newrelic_add_custom_parameter ('house_page', $this->page);
		}

	}

	/**
	 * Set current house pages.
	 */
	private function setPages() {

		$pagesTemp = get_post_custom_values('house_pages');
		$pagesTemp = unserialize($pagesTemp[0]);

		if ($pagesTemp) {
			foreach ($pagesTemp as $key => $value) {
				$name = get_post_custom_values('house_pages_'.$key.'_page_name');
				$this->pages[$key] = $name[0];
				if ($name[0] == $this->page) {
					$content = get_post_custom_values('house_pages_'.$key.'_page_content');
					$this->content = apply_filters('the_content',$content[0]);
					$this->setImages($key);
				}
			}
		}

	}

	/**
	 * Set images for specified house page.
	 * @param integer Key of page to show photos of
	 */
	public function setImages($key = null) {
		$meta = ($key ? 'house_pages_'.$key.'_house_sub_gallery' : 'house_photos');
		$images = get_post_custom_values($meta);
		$this->pageImages = unserialize($images[0]);
		if (!$this->pageImages && $key) $this->setImages();
	}

	/**
	 * Get images for page.
	 * @return array Images with src, title and desc
	 */
	public function getImages($size, $count = null) {
		if (!empty($this->pageImages)) {
			$c = 0;
			$images = array();
			foreach($this->pageImages as $image) {
				$url = str_replace('.test', '.com', wp_get_attachment_image_src( $image, $size ));
				$srcset = str_replace('.test', '.com', wp_get_attachment_image_srcset( $image, $size ));

				$images[$c]['src'] = $url[0];
				$images[$c]['srcset'] = $srcset;
				$images[$c]['title'] = get_the_title($image);
				$images[$c]['description'] = get_post_field('post_content', $image);
				$images[$c]['id'] = $image;
				$c++;
				if ($count && $count === $c) break;
			}
			return $images;
		}

	}

	public function get_source_gallery_images($id,$blog_id) {

		global $wpdb;
		$tablename = $wpdb->prefix;

		$sql = $wpdb->prepare(
			"
			SELECT availability_site_ref, availability_site_post_id
			FROM houses
			WHERE post_id = %d
			AND blog_id = %d
			",
			$id, $blog_id
		);

		//$sql = 'SELECT availability_site_ref, availability_site_post_id FROM houses WHERE post_id = $id AND blog_id = $blog_id';
		$results = $wpdb->get_results( $sql , ARRAY_A );


		//var_dump($results);

		$site_ref = 'wp_' . $results[0]['availability_site_ref'] .'_postmeta';
		$post_id = $results[0]['availability_site_post_id'];

		$sql = $wpdb->prepare( "SELECT meta_value FROM $site_ref WHERE post_id = %d && meta_key = %s",$post_id,'house_photos' );
		$results = $wpdb->get_results( $sql , ARRAY_A );

		//var_dump($results);

		return $results[0]['meta_value'];

	}

}

?>