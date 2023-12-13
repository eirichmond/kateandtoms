<?php
/**
 * Defines the repeating image sets that appear on pages.
 *
 * @package kate-and-toms
 */

/**
 * Defines overall availability functionality for an image set.
 * Supports the following layouts:
 * 'fill' - filled full-size box with 2/3 layout
 * 'half' - half-sized box without subtitle text
 *
 * @package kate-and-toms
 * @author  Oliver Newth
 * @copyright Kate and Tom's Ltd 2012
 */

class ImageSet {

	/**
	 * Setup new image set.
	 */
	public function __construct($ID)
	{
		$rows = get_field('images', $ID);
		if ($rows) {
			echo '<div class="row">';
			foreach ($rows as $row) {
	 			$image		= $row['image'];
				$link		= $row['link_url'];
				$custom_url = $row['custom_url'];
				$layout		= $row['surround'];
				$color		= $row['colour_scheme'];
				$link	= ($custom_url ? $custom_url : $link);
				$size		= ($layout == 'half' ? 'house_slider' : 'thumbnail');
				$alt_text = get_post_meta($image, '_wp_attachment_image_alt', true);

				if ($layout == 'image') $this->getSquareImg($image, $link);
				elseif ($layout == 'triangle') $this->getTriangle($image, $link, $color); 
				else {
					echo 	'<div class="span3">' ,
							($link ? '<a href="' . $link . '"': '<div') . ' class="imgset_box_'.$layout.' '.$color.'">';
					if 		($image) echo '<div class="absoluteCenterWrapper imgset_wrap_'.$layout.'"><img loading="lazy" class="absoluteCenter" src=' . getImage($image, $size) . ' srcset="'.getSrcset($image, $size).'" alt="'.$alt_text.'" /></div>';
					echo 	'<div class="box_text"><h2>' , $row['title_text'] , '</h2>',
							($this->showSubtitle($layout) ? $row['subtitle_text'] : '') , 
							'</div>' , ($link ? '</a>' : '</div>') , '</div>';
				}
			}
			echo '</div>';
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
		if 		($image) echo '<img class="absoluteCenter" src=' . getImage($image, 'thumbnail') . ' srcset="'.getSrcset($image, $size).'" />';
		echo 	'</div>', ($link ? '</a>' : '</div>') , '</div>';
	}
	
	private function getTriangle($image, $link, $color)
	{
		$alt_text = get_post_meta($image, '_wp_attachment_image_alt', true);
		echo 	'<div class="span3">' ,
				($link ? '<a href="' . $link . '"': '<div') . ' class="imgset_box_triangle">';
		if 		($image) echo '<div class="absoluteCenterWrapper imgset_wrap_triangle"><img loading="lazy" class="absoluteCenter" src=' . getImage($image, 'thumbnail') . ' srcset="'.getSrcset($image, $size).'" alt="'.$alt_text.'" /></div>';
		echo 	'<div class="arrow-right-'.$color.'"></div><div class="box_text"><h2>' , $row['title_text'] , '</h2>',
				'</div>' , ($link ? '</a>' : '</div>') , '</div>';
	}
	
}
?>