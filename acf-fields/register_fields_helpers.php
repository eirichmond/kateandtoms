<?php

function listSites()
{
    $blog_list    = get_blogs_of_user(get_current_user_id());
    //$sites = array(false -> 'None');
    $sites[false] = 'None';
    foreach ($blog_list as $site) {
        $sites[$site->userblog_id] = $site->blogname;
    }
    return $sites;
}

function kat_field($type, $key, $label, $name, $order, $row_id = null, $colors = 'all') {
    $default = array(
        'key' => ($row_id === null ? $key : $key . '_' . $row_id),
        'label' => $label,
        'name' => $name,
        'order_no' => $order
    );



    switch ($type) {
        case 'text':
            return $default + array(
                'type' => 'text',
                'default_value' => '',
                'formatting' => 'html'
            );
        case 'textarea':
            return $default + array(
                'type' => 'textarea',
                'default_value' => '',
                'formatting' => 'html'
            );
        case 'wysiwyg':
            return $default + array(
                'type' => 'wysiwyg',
                'toolbar' => 'basic',
                'instructions' => 'To split text into two columns, use <code>$$</code> Make sure they\'re not bold!
					<br/><br/><code>&lt;strong&gt;<strong>Bold text</strong>&lt;/strong&gt;</code>
					<br/><code>&lt;a href="http://link.com" class="btn"&gt;Text&lt;/a&gt;</code>',
                'default_value' => '',
                'the_content' => 'yes',
                'formatting' => 'html'
            );
		case 'wysiwyg_basic':
				return $default + array(
					'type' => 'wysiwyg',
					'toolbar' => 'full',
					'media_upload' => 0,
					'instructions' => '<code>&lt;strong&gt;<strong>Bold text</strong>&lt;/strong&gt;</code>
						<br/><code>&lt;a href="http://link.com" class="btn"&gt;Text&lt;/a&gt;</code>',
					'default_value' => '',
					'the_content' => 'yes',
					'formatting' => 'html'
				);
		case 'image':
            return $default + array(
                'type' => 'image',
                'save_format' => 'id'
            );
        case 'gallery':
            return $default + array(
                'type' => 'gallery'
            );
        case 'google_map':
            return $default + array(
                'type' => 'google_map',
                'instructions' => '',
                'required' => '0',
                'default_value' => '',
                'val' => 'address',
                'center' => '51.835778,-1.422729',
                'zoom' => '8'
            );
        case 'date_picker':
            return $default + array(
                'type' => 'date_picker',
                'instructions' => '',
                'required' => '1',
                'date_format' => 'yy-mm-dd',
                'display_format' => 'dd/mm/yy'
            );
        case 'true_false':
            return $default + array(
                'type' => 'true_false',
                'instructions' => '',
                'required' => '0',
                'message' => ''
            );
        case 'day_picker':
            return $default + array(
                'type' => 'day_picker'
            );
		case 'select_alignment':
			return $default + array(
				'type' => 'select',
				'required' => '0',
				'choices' => array(
					'image-align-left' => 'Image align left',
					'image-align-right' => 'Image align right',
				),
				'default_value' => 'image-align-left',
				'allow_null' => '0',
				'multiple' => '0'
			);
		case 'select_icon':
			return $default + array(
				'type' => 'select',
				'required' => '0',
				'choices' => array(
					'5-stars' => 'Five Stars',
					'beds' => 'Bed',
					'building' => 'Building',
					'contact-form' => 'Contact Form',
					'discuss-chat' => 'Discuss Chat',
					'handshake' => 'Handshake',
					'heart' => 'Heart',
					'house' => 'House',
					'info-symbol' => 'Info symbol',
					'megaphone' => 'Megaphone',
					'thumbs-up' => 'Thumbs up',
					'web-page' => 'Web Page'
				),
				'default_value' => 'glyphicon-comment',
				'allow_null' => '0',
				'multiple' => '0'
			);
        case 'select_layouts':
            return $default + array(
                'type' => 'select',
                'required' => '0',
                'choices' => array(
                    'imgleft' => 'Image left',
                    'imgright' => 'Image right',
                    'galleryleft' => 'Gallery left',
                    'galleryright' => 'Gallery right',
                    'largeimg' => 'Large image',
                    'text' => 'Text only',
                    'fourimage' => 'Four images only'
                ),
                'default_value' => 'imgleft',
                'allow_null' => '0',
                'multiple' => '0'
            );
        case 'select_layout_option':
            return $default + array(
                'type' => 'select',
                'required' => '0',
                'choices' => array(
                    'imgleft' => 'Image left',
                    'imgright' => 'Image right',
                ),
                'default_value' => 'imgleft',
                'allow_null' => '0',
                'multiple' => '0'
            );
        case 'select_video_layouts':
            return $default + array(
                'type' => 'select',
                'required' => '0',
                'choices' => array(
                    'vidleft' => 'Video left',
                    'vidright' => 'Video right',
                    'vidwide' => 'Video wide',
                ),
                'default_value' => 'vidleft',
                'allow_null' => '0',
                'multiple' => '0'
            );
        case 'select_virtual_layouts':
            return $default + array(
                'type' => 'select',
                'required' => '0',
                'choices' => array(
                    'virtualleft' => 'Virtual left',
                    'virtualright' => 'Virtual right',
                ),
                'default_value' => 'virtualleft',
                'allow_null' => '0',
                'multiple' => '0'
            );
        case 'select_image_set_layout':
            return $default + array(
                'type' => 'select',
                'required' => '0',
                'choices' => array(
                    'imagesetbottom' => 'Image set bottom',
                    'imagesetleft' => 'Image set left',
                    'imagesetright' => 'Image set right'
                ),
                'default_value' => 'imagesetbottom',
                'allow_null' => '0',
                'multiple' => '0'
            );
        case 'color_radio':
            return $default + array(
                'choices' => kat_colors_array($colors),
                'type' => 'color_radio',
                'layout' => 'horizontal',
                'default_value' => '',
                'allow_null' => '0',
                'multiple' => '0'
            );
        case 'color_picker':
            return $default + array(
                'type' => 'color_picker',
                'layout' => 'horizontal',
                'default_value' => '#ffffff',
                'allow_null' => '0',
                'multiple' => '0'
            );
        case 'radio_imageset':
            // When used in widgets, 'image' isn't an option. Should remove it?
            return $default + array(
                'choices' => array(
                    'fill' => 'Full-size box',
                    'half' => 'Half-size box',
                    'image' => 'Full-size image only'
                ),
                'type' => 'radio',
                'layout' => 'vertical',
                'default_value' => '',
                'allow_null' => '0',
                'multiple' => '0'
            );
            case 'radio_layout':
                // When used in widgets, 'image' isn't an option. Should remove it?
                return $default + array(
                    'choices' => array (
                        '1-3' => '1-3',
                        '3-1' => '3-1',
                        'Flat x 4' => 'Flat x 4',
                    ),
                    'type' => 'radio',
                    'layout' => 'vertical',
                    'default_value' => '',
                );
            case 'radio_layout_5':
                // When used in widgets, 'image' isn't an option. Should remove it?
                return $default + array(
                    'choices' => array (
                        '1-4' => '1-4',
                        '4-1' => '4-1',
                    ),
                    'type' => 'radio',
                    'layout' => 'vertical',
                    'default_value' => '',
                );
            case 'page_selector':
            return $default + array(
                'type' => 'select',
                'choices' => kat_set_pages_array(),
                'allow_null' => '1',
                'multiple' => '0'
            );
        case 'repeater_imageset':
            return $default + array(
                'type' => 'repeater',
                'instructions' => 'To stretch a box to take up two boxes, use <code>&lt;newline&gt;</code>.
					If you wish to split text over two lines, use <code>&lt;br&gt;</code>.',
                'sub_fields' => array(
                    kat_field('repeater_imageset_row', 'field_widget_imageset_row', 'Set', 'row', 0, $row_id)
                ),
                'row_min' => '1',
                'row_limit' => '',
                'layout' => 'table',
                'button_label' => 'Add Row'
            );
        case 'repeater_matrix':
            return $default + array(
                'type' => 'repeater',
                'instructions' => '',
					'min' => 4,
					'max' => 4,
					'layout' => 'table',
					'button_label' => 'Add Matrix',
					'sub_fields' => array (
						kat_field('page_selector', 'links_to', 'Links to', 'links_to', 0, $row_id),
						kat_field('image', 'image_override', 'Image', 'image', 1, $row_id),
						kat_field('text', 'item_title', 'Title', 'matrix_item_title', 2, $row_id),
						kat_field('text', 'item_subtitle', 'Subtitle', 'matrix_item_subtitle', 2, $row_id),
						kat_field('color_radio', 'field_set_color', 'Colour', 'colour_scheme', 3, $row_id, 'dark and greys'),
						//kat_field('color_picker', 'field_custom_color', 'Custom Colour', 'custom_colour_scheme', 4, $row_id, '#ffffff'),
					),
                'row_min' => '1',
                'row_limit' => '',
                'layout' => 'table',
                'button_label' => 'Add Row'
            );
        case 'repeater_matrix_5':
            return $default + array(
                'type' => 'repeater',
                'instructions' => '',
					'min' => 5,
					'max' => 5,
					'layout' => 'table',
					'button_label' => 'Add Matrix',
					'sub_fields' => array (
						kat_field('page_selector', 'links_to', 'Links to', 'links_to', 0, $row_id),
						kat_field('image', 'image_override', 'Image', 'image', 1, $row_id),
						kat_field('text', 'item_title', 'Title', 'matrix_item_title', 2, $row_id),
						kat_field('text', 'item_subtitle', 'Subtitle', 'matrix_item_subtitle', 2, $row_id),
						kat_field('color_radio', 'field_set_color', 'Colour', 'colour_scheme', 3, $row_id, 'dark and greys'),
						//kat_field('color_picker', 'field_custom_color', 'Custom Colour', 'custom_colour_scheme', 4, $row_id, '#ffffff'),
					),
                'row_min' => '1',
                'row_limit' => '',
                'layout' => 'table',
                'button_label' => 'Add Row'
            );
		case 'list_items':
			return $default + array(
				'type' => 'repeater',
				'instructions' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'table',
					'button_label' => 'Add List Items',
					'sub_fields' => array (
						kat_field('select_icon', 'select_icon', 'Icon', 'select_icon', 0, $row_id),
						kat_field('text', 'item_title', 'Title', 'matrix_item_title', 1, $row_id),
					),
				'row_min' => '1',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add List'
			);
		case 'vendor_stats_inner_section':
				return $default + array(
					'type' => 'repeater',
					'instructions' => '',
						'min' => 0,
						'max' => 0,
						'button_label' => 'Add List Item',
						'sub_fields' => array (
							kat_field('image', 'vendor_stats_image', 'Image', 'vendor_stats_image', 0, $row_id),
							kat_field('text', 'vendor_stats_title', 'Title', 'vendor_stats_title', 1, $row_id),
							kat_field('vendor_list_vendor_stat', 'vendor_list_vendor_stat', 'List Item', 'vendor_list_vendor_stat', 2, $row_id),
							kat_field('text', 'vendor_stats_footer', 'Footer byline', 'vendor_stats_footer', 3, $row_id),

						),
					'row_min' => '1',
					'row_limit' => '',
					'layout' => 'row',
					'button_label' => 'Add List Items'
				);
		case 'vendor_list_vendor_stat':
			return $default + array(
				'type' => 'repeater',
				'instructions' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'row',
					'button_label' => 'Add List Item',
					'sub_fields' => array (
						kat_field('text', 'list_item', 'List Item', 'list_item', 0, $row_id),
					),
				'row_min' => '1',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add List Items'
			);
		case 'chequered_inner_section':
			return $default + array(
				'type' => 'repeater',
				'instructions' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'row',
					'button_label' => 'Add Section Items',
					'sub_fields' => array (
						kat_field('select_alignment', 'select_alignment', 'Layout', 'select_alignment', 0, $row_id),
						kat_field('image', 'image_override', 'Image', 'image', 1, $row_id),
						kat_field('wysiwyg_basic', 'field_text_row', 'Content', 'field_text_row', 2, $row_id),
					),
				'row_min' => '1',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Section'
			);
		case 'partner_stat':
			return $default + array(
				'type' => 'repeater',
				'instructions' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'row',
					'button_label' => 'Add Stat',
					'sub_fields' => array (
						kat_field('text', 'stat_title', 'Title', 'stat_title', 0, $row_id),
						kat_field('textarea', 'stat_text', 'Stat', 'stat_text', 1, $row_id),
					),
				'row_min' => '1',
				'row_limit' => '',
				'layout' => 'row',
				'button_label' => 'Add Stat'
			);

		case 'partner_feature':
			return $default + array(
				'type' => 'repeater',
				'instructions' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'row',
					'button_label' => 'Add Feature',
					'sub_fields' => array (
						kat_field('select_icon', 'select_icon', 'Icon', 'select_icon', 0, $row_id),
						kat_field('text', 'feature_title', 'Title', 'feature_title', 1, $row_id),
						kat_field('textarea', 'feature_text', 'Feature', 'feature_text', 2, $row_id),
					),
				'row_min' => '1',
				'row_limit' => '',
				'layout' => 'row',
				'button_label' => 'Add Feature'
			);

		case 'partner_testimonials':
			return $default + array(
				'type' => 'repeater',
				'instructions' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'row',
					'button_label' => 'Add Testimonial',
					'sub_fields' => array (
						kat_field('textarea', 'testimonial_text', 'Testimonial', 'testimonial_text', 1, $row_id),
						// kat_field('image', 'testimonial_image', 'Image', 'testimonial_image', 2, $row_id),
						kat_field('text', 'testimonial_partner_name', 'Partner Name', 'testimonial_partner_name', 3, $row_id),
						kat_field('wysiwyg_basic', 'testimonial_partner_subtext', 'Partner Subtext', 'testimonial_partner_subtext', 4, $row_id),
					),
				'row_min' => '1',
				'row_limit' => '',
				'layout' => 'row',
				'button_label' => 'Add Testimonial'
			);
		case 'partner_steps':
			return $default + array(
				'type' => 'repeater',
				'instructions' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'row',
					'button_label' => 'Add Step',
					'sub_fields' => array (
						kat_field('select_icon', 'select_icon', 'Icon', 'select_icon', 0, $row_id),
						kat_field('text', 'feature_title', 'Title', 'feature_title', 1, $row_id),
						kat_field('textarea', 'feature_text', 'Feature', 'feature_text', 2, $row_id),
					),
				'row_min' => '1',
				'row_limit' => '',
				'layout' => 'row',
				'button_label' => 'Add Step'
			);

		case 'column_set':
            return $default + array(
                'type' => 'repeater',
                'instructions' => '',
					'min' => 2,
					'max' => 2,
					'layout' => 'table',
					'button_label' => 'Add Matrix',
					'sub_fields' => array (
						kat_field('text', 'item_title', 'Title', 'matrix_item_title', 2, $row_id),
						kat_field('text', 'item_subtitle', 'Subtitle', 'matrix_item_subtitle', 2, $row_id),
						kat_field('color_radio', 'field_set_color', 'Colour', 'colour_scheme', 3, $row_id, 'dark and greys'),
						//kat_field('color_picker', 'field_custom_color', 'Custom Colour', 'custom_colour_scheme', 4, $row_id, '#ffffff'),
					),
                'row_min' => '1',
                'row_limit' => '',
                'layout' => 'row',
                'button_label' => 'Add Row'
            );
        case 'repeater_imageset_row':
            return $default + array(
                'type' => 'repeater',
                'sub_fields' => array(
                    kat_field('image', 'field_set_image', 'Image', 'image', 0, $row_id),
                    kat_field('textarea', 'field_set_title', 'Title', 'title_text', 1, $row_id),
                    kat_field('textarea', 'field_set_subtitle', 'Subtitle', 'subtitle_text', 2, $row_id),
                    kat_field('textarea', 'field_set_subtext', 'Subtext', 'subtext_text', 3, $row_id),
                    kat_field('color_radio', 'field_set_color', 'Colour', 'colour_scheme', 4, $row_id, 'dark'),
                    //kat_field('page_selector', 'field_set_link', 'Link', 'link_url', 5, $row_id),
                    kat_field('text', 'field_set_custom', 'Custom URL', 'set_custom', 6, $row_id)
                ),
                'row_min' => '4',
                'row_limit' => '4',
                'layout' => 'table',
                'button_label' => ''
            );
        case 'repeater_buttons':
            return $default + array(
                'type' => 'repeater',
                'sub_fields' => array(
                    kat_field('text', 'field_button_title', 'Button Title', 'button_title', 0, $row_id),
                    kat_field('page_selector', 'field_button_link', 'Button Link', 'button_link', 1, $row_id),
                    kat_field('text', 'field_the_customurl', 'Custom URL', 'field_custom_url', 2, $row_id)
                ),
                'instructions' => 'To call the contact form, simply use #getintouch as the Custom Url.',
                'default_value' => '',
                'row_min' => '1',
                'row_limit' => '3',
                'layout' => 'table',
                'button_label' => 'Add Button'
            );
		case 'basic_button':
			return $default + array(
				'type' => 'group',
				'sub_fields' => array(
					kat_field('select_icon', 'field_select_icon', 'Icon', 'select', 0, $row_id),
					kat_field('text', 'field_button_title', 'Button Title', 'button_title', 1, $row_id),
                    kat_field('color_radio', 'field_set_color', 'Colour', 'colour_scheme', 2, $row_id, 'dark'),
					kat_field('text', 'field_the_customurl', 'Custom URL', 'field_custom_url', 3, $row_id)
				),
				'instructions' => 'To call the contact form, simply use #getintouch as the Custom Url.',
				'default_value' => '',
				'row_min' => '1',
				'row_limit' => '3',
				'layout' => 'table',
				'button_label' => 'Add Button'
			);
		case 'repeater_reviews':
            return $default + array(
                'type' => 'repeater',
                'sub_fields' => array(
                    kat_field('textarea', 'field_the_review', 'Review Content', 'field_review_content', 0, $row_id),
                    kat_field('text', 'field_the_reviewer', 'Reviewed by', 'field_review_name', 1, $row_id)
                ),
                'instructions' => '',
                'default_value' => '',
                'row_min' => '1',
                'row_limit' => '',
                'layout' => 'table',
                'button_label' => 'Add Review'
            );
        case 'repeater_faq':
            return $default + array(
                'type' => 'repeater',
                'sub_fields' => array(
                    kat_field('text', 'field_the_question', 'Question', 'field_the_question', 0, $row_id),
                    kat_field('textarea', 'field_the_answer', 'Answer', 'field_the_answer', 1, $row_id)
                ),
                'instructions' => '',
                'default_value' => '',
                'row_min' => '1',
                'row_limit' => '',
                'layout' => 'row',
                'button_label' => 'Add FAQ'
            );
    }
}

function kat_widget_row($type, $label, $name, $row_id) {
    $default = array(
        'label' => $label,
        'name' => $name,
        'display' => 'row'
    );
    $c       = 0;
    switch ($type) {

        case 'standard_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('text', 'field_widget_subtitle', 'Subtitle', 'subtitle', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Main body', 'body', $c++, $row_id),
                    kat_field('gallery', 'field_widget_gallery', 'Gallery', 'gallery', $c++, $row_id),
                    kat_field('select_layouts', 'field_widget_layout', 'Layout', 'layout', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id)
                )
            );
        case 'script_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('text', 'field_widget_subtitle', 'Subtitle', 'subtitle', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Embedded Script', 'body', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id)
                )
            );
        case 'footer_standard_widget':

            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('text', 'field_widget_subtitle', 'Subtitle', 'subtitle', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Main body', 'body', $c++, $row_id),
                    kat_field('gallery', 'field_widget_gallery', 'Gallery', 'gallery', $c++, $row_id),
                    kat_field('select_layouts', 'field_widget_layout', 'Layout', 'layout', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id)
                )
            );
        case 'standard_widget_hybrid':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('text', 'field_widget_subtitle', 'Subtitle', 'subtitle', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Main body', 'body', $c++, $row_id),
                    // Validate that the below works.
                    kat_field('repeater_imageset', 'field_widget_imageset', 'Navigation image set', 'imageset', $c++, $row_id),
                    kat_field('gallery', 'field_widget_gallery', 'Gallery', 'gallery', $c++, $row_id),
                    kat_field('select_layouts', 'field_widget_layout', 'Layout', 'layout', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id),
                    kat_field('radio_imageset', 'field_widget_image_set_layout', 'Image Set Layout', 'image_set_layout', $c++, $row_id)
                )
            );
        case 'video_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Main body', 'body', $c++, $row_id),
                    kat_field('text', 'field_widget_video_url', 'Youtube URL', 'url', $c++, $row_id),
                    kat_field('select_video_layouts', 'field_widget_video_layout', 'Video Layout', 'video_layout', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id)
                )
            );
        case 'virtual_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Main body', 'body', $c++, $row_id),
                    kat_field('text', 'field_widget_virtual_url', 'Virtual URL', 'url', $c++, $row_id),
                    kat_field('select_virtual_layouts', 'field_widget_virtual_layout', 'Virtual Layout', 'virtual_layout', $c++, $row_id),
                    kat_field('image', 'field_virtual_image', 'Virtual image', 'virtual_image', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id)
                )
            );
        case 'image_set':
            return $default + array(
                'sub_fields' => array(
                    kat_field('radio_imageset', 'field_set_style', 'Style', 'surround', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_imgset_color', 'Background colour', 'color_scheme', $c++, $row_id, 'light'),
                    kat_field('repeater_imageset', 'field_widget_imageset', 'Navigation image set', 'imageset', $c++, $row_id)
                )
            );
        case 'partner_header_section':
            return $default + array(
                'sub_fields' => array(
                    kat_field('color_radio', 'field_widget_imgset_color', 'Background colour', 'color_scheme', $c++, $row_id, 'all'),
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('wysiwyg_basic', 'field_wysiwyg_row_col_left', 'Column Left', 'wysiwyg_row_col_left', $c++, $row_id),
                    kat_field('basic_button', 'field_button_row_widget_set', 'Column Right Button', 'basic_button', $c++, $row_id),
					kat_field('wysiwyg_basic', 'field_wysiwyg_row_col_right', 'Column Right Text', 'wysiwyg_row_col_right', $c++, $row_id),
				)
            );
		case 'partner_image_text_section':
			return $default + array(
				'sub_fields' => array(
					kat_field('select_alignment', 'alignment', 'Align Image', 'align_image', $c++, $row_id),
					kat_field('color_radio', 'background_colour', 'Background colour', 'background_colour', $c++, $row_id, 'all'),
					kat_field('image', 'left_aligned_image', 'Left Aligned Image', 'left_aligned_image', $c++, $row_id),
					kat_field('text', 'section_title', 'Title', 'section_title', $c++, $row_id),
					kat_field('wysiwyg_basic', 'section_text_top', 'Content', 'section_text_top', $c++, $row_id),
					kat_field('basic_button', 'section_button', 'Call to action button', 'section_button', $c++, $row_id),
					kat_field('wysiwyg_basic', 'section_text_bottom', 'Content', 'section_text_bottom', $c++, $row_id),
				)
			);
		case 'partner_chequered_section':
			return $default + array(
				'sub_fields' => array(
					kat_field('color_radio', 'field_widget_imgset_color', 'Background colour', 'color_scheme', $c++, $row_id, 'all'),
					kat_field('chequered_inner_section', 'field_chequered_inner_section', 'Sections', 'chequered_inner_section', $c++, $row_id),
				)
			);
		case 'partner_vendor_stats_section':
			return $default + array(
				'sub_fields' => array(
					kat_field('color_radio', 'field_widget_imgset_color', 'Background colour', 'color_scheme', $c++, $row_id, 'all'),
					kat_field('vendor_stats_inner_section', 'vendor_stats_inner_section', 'List items', 'vendor_stats_inner_section', $c++, $row_id),
				)
			);
		case 'partner_features_section':
			return $default + array(
				'sub_fields' => array(
					kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
					kat_field('color_radio', 'field_widget_imgset_color', 'Background colour', 'color_scheme', $c++, $row_id, 'all'),
					kat_field('partner_feature', 'field_partner_feature', 'Feature', 'partner_feature', $c++, $row_id),
				)
			);

		case 'partner_call_to_action':
			return $default + array(
				'sub_fields' => array(
					kat_field('color_radio', 'background_colour', 'Background colour', 'background_colour', $c++, $row_id, 'all'),
					kat_field('text', 'left_col_title', 'Left Column Title', 'left_col_title', $c++, $row_id),
					kat_field('wysiwyg_basic', 'left_col_text', 'Left Column Text', 'left_col_text', $c++, $row_id),
					kat_field('basic_button', 'right_cta_button', 'Right call to action button', 'right_cta_button', $c++, $row_id),
					kat_field('wysiwyg_basic', 'right_col_text', 'Right Column Text', 'right_col_text', $c++, $row_id),
				)
			);


		case 'partner_stats_section':
			return $default + array(
				'sub_fields' => array(
					kat_field('text', 'stats_title', 'Title', 'stats_title', $c++, $row_id),
					kat_field('color_radio', 'background_colour', 'Background colour', 'background_colour', $c++, $row_id, 'all'),
					kat_field('partner_stat', 'partner_stat', 'Partner Stat', 'partner_stat', $c++, $row_id),
					kat_field('wysiwyg_basic', 'stats_text_top', 'Text', 'stats_text_top', $c++, $row_id),
					kat_field('basic_button', 'stat_button', 'Call to action button', 'stat_button', $c++, $row_id),
					kat_field('wysiwyg_basic', 'stats_text_bottom', 'Text', 'stats_text_bottom', $c++, $row_id),
				)
			);

		case 'partner_testimonials_section':
			return $default + array(
				'sub_fields' => array(
					kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
					kat_field('color_radio', 'field_widget_imgset_color', 'Background colour', 'color_scheme', $c++, $row_id, 'all'),
					kat_field('partner_testimonials', 'partner_testimonial', 'Testimonials', 'partner_testimonials', $c++, $row_id),
				)
			);

		case 'partner_steps_section':
			return $default + array(
				'sub_fields' => array(
					kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
					kat_field('color_radio', 'field_widget_imgset_color', 'Background colour', 'color_scheme', $c++, $row_id, 'all'),
					kat_field('partner_steps', 'field_partner_feature', 'Feature', 'partner_feature', $c++, $row_id),
				)
			);

		case 'partner_content_section':
			return $default + array(
				'sub_fields' => array(
					kat_field('color_radio', 'background_colour', 'Background colour', 'background_colour', $c++, $row_id, 'all'),
					kat_field('wysiwyg_basic', 'field_widget_title', 'Title', 'title', $c++, $row_id),
					kat_field('wysiwyg_basic', 'field_wysiwyg_row_col_left_top', 'Column Left Top Text', 'wysiwyg_row_col_left_top', $c++, $row_id),
					kat_field('list_items', 'field_list_items', 'List Items', 'list_items', $c++, $row_id),
					kat_field('wysiwyg_basic', 'field_wysiwyg_row_col_left_bottom', 'Column Right Bottom Text', 'wysiwyg_row_col_left_bottom', $c++, $row_id),
					kat_field('image', 'field_right_image', 'Column Right Image', 'field_right_image', $c++, $row_id),
				)
			);
		case 'partner_experts':
			return $default + array(
				'sub_fields' => array(
					kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
					kat_field('color_radio', 'field_widget_imgset_color', 'Background colour', 'color_scheme', $c++, $row_id, 'all'),
					kat_field('wysiwyg_basic', 'field_wysiwyg_row_col_left', 'Column Left Top Text', 'wysiwyg_row_col_left', $c++, $row_id),
					kat_field('image', 'field_center_image_one', 'Center Image Top', 'field_center_image_one', $c++, $row_id),
					kat_field('image', 'field_center_image_two', 'Center Image Bottom', 'field_center_image_two', $c++, $row_id),
					kat_field('image', 'field_right_image', 'Main Image Right', 'field_right_image', $c++, $row_id),
				)
			);
			case 'footer_image_set':
            return $default + array(
                'sub_fields' => array(
                    kat_field('radio_imageset', 'field_set_style', 'Style', 'surround', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_imgset_color', 'Background colour', 'color_scheme', $c++, $row_id, 'light'),
                    kat_field('repeater_imageset', 'field_widget_imageset', 'Navigation image set', 'imageset', $c++, $row_id)
                )
            );
        case 'button_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_bntintro_title', 'Buttons Intro', 'bntintro_title', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_button_color', 'Background Colour', 'buttons_color_scheme', $c++, $row_id),
                    kat_field('repeater_buttons', 'field_buttons', 'Buttons', 'buttons', $c++, $row_id)
                )
            );
        case 'wide_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('image', 'field_wide_image', 'Full Width Image', 'wide_image', $c++, $row_id)
                )
            );
        case 'floorplan_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('image', 'field_floorplan_image', 'Floorplan Image', 'floorplan_image', $c++, $row_id)
                )
            );
        case 'reviews_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_review_title', 'Review Title', 'review_title', $c++, $row_id),
                    kat_field('text', 'field_review_subtitle', 'Review Subtitle', 'review_subtitle', $c++, $row_id),
                    kat_field('repeater_reviews', 'field_reviews', 'Reviews', 'review', $c++, $row_id),
                    kat_field('image', 'field_review_image', 'Review image', 'review_image', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_review_color', 'Colour', 'review_color_scheme', $c++, $row_id, 'light')
                )
            );
        case 'separator_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('gallery', 'field_widget_sep_rotator', 'Separator Rotator', 'separator_rotator', $c++, $row_id)
                )
            );
        case 'matrix_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('radio_layout', 'field_matrix_widget_layout', 'Layout', 'matrix_layout', $c++, $row_id),
                    kat_field('repeater_matrix', 'field_matrix_widget_set', 'Items', 'matrix_set', $c++, $row_id)
                )
            );
        case 'matrix_widget_5':
            return $default + array(
                'sub_fields' => array(
                    kat_field('radio_layout_5', 'field_matrix_widget_layout', 'Layout', 'matrix_5_layout', $c++, $row_id),
                    kat_field('repeater_matrix_5', 'field_matrix_widget_set', 'Items', 'matrix_5_set', $c++, $row_id)
                )
            );
        case 'single_image_link':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('text', 'field_widget_subtitle', 'Subtitle', 'subtitle', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Main body', 'body', $c++, $row_id),
                    kat_field('image', 'field_widget_image', 'Image', 'image', $c++, $row_id),
                    kat_field('select_layout_option', 'field_widget_layout', 'Layout', 'layout', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id)
                )
            );
         case 'faq_group':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'faq_title', 'Title', 'title', $c++, $row_id),
                    kat_field('repeater_faq', 'field_faq', 'FAQ\'s', 'faq', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_faq_color', 'Colour', 'faq_color_scheme', $c++, $row_id)
                )
            );
         case 'cta_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'cta_title', 'Title', 'title', $c++, $row_id),
                    kat_field('true_false', 'cta_heading_type', 'Set Title as H1', 'heading_type', $c++, $row_id),
                    kat_field('textarea', 'cta_sub', 'Subtext', 'subtext', $c++, $row_id),
                    kat_field('text', 'cta_button_text', 'Button Text', 'button', $c++, $row_id),
                    kat_field('color_radio', 'cta_button_color', 'Button Colour', 'button_color', $c++, $row_id),
                    kat_field('text', 'cta_button_url', 'Button Url', 'button_url', $c++, $row_id),
                    kat_field('color_radio', 'cta_background_color', 'Background Colour', 'background_color', $c++, $row_id)
                )
            );
         case 'trustpilot_micro_combo_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('color_radio', 'trustpilot_micro_combo__background_color', 'Background Colour', 'background_color', $c++, $row_id)
                )
            );
         case 'youtube_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('color_radio', 'youtube_background_color', 'Background Colour', 'background_color', $c++, $row_id),
                    kat_field('text', 'youtube_content', 'Feed ShortCode', 'youtube_content', $c++, $row_id)
                )
            );
   }
}

function kat_term_widget_row($key_id, $type, $label, $name, $row_id)  {
    $default = array(
        'key' => $key_id,
        'label' => $label,
        'name' => $name,
        'display' => 'row'
    );
    $c       = 0;
    switch ($type) {
         case 'trustpilot_micro_combo_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('color_radio', 'trustpilot_micro_combo__background_color', 'Background Colour', 'background_color', $c++, $row_id)
                )
            );
         case 'youtube_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('color_radio', 'youtube_background_color', 'Background Colour', 'background_color', $c++, $row_id),
                    kat_field('text', 'youtube_content', 'Feed ShortCode', 'youtube_content', $c++, $row_id)

                )
            );
        case 'standard_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('text', 'field_widget_subtitle', 'Subtitle', 'subtitle', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Main body', 'body', $c++, $row_id),
                    kat_field('gallery', 'field_widget_gallery', 'Gallery', 'gallery', $c++, $row_id),
                    kat_field('select_layouts', 'field_widget_layout', 'Layout', 'layout', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id)
                )
            );
        case 'script_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('text', 'field_widget_subtitle', 'Subtitle', 'subtitle', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Embedded Script', 'body', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id)
                )
            );
        case 'footer_standard_widget':

            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('text', 'field_widget_subtitle', 'Subtitle', 'subtitle', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Main body', 'body', $c++, $row_id),
                    kat_field('gallery', 'field_widget_gallery', 'Gallery', 'gallery', $c++, $row_id),
                    kat_field('select_layouts', 'field_widget_layout', 'Layout', 'layout', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id)
                )
            );
        case 'standard_widget_hybrid':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('text', 'field_widget_subtitle', 'Subtitle', 'subtitle', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Main body', 'body', $c++, $row_id),
                    // Validate that the below works.
                    kat_field('repeater_imageset', 'field_widget_imageset', 'Navigation image set', 'imageset', $c++, $row_id),
                    kat_field('gallery', 'field_widget_gallery', 'Gallery', 'gallery', $c++, $row_id),
                    kat_field('select_layouts', 'field_widget_layout', 'Layout', 'layout', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id),
                    kat_field('radio_imageset', 'field_widget_image_set_layout', 'Image Set Layout', 'image_set_layout', $c++, $row_id)
                )
            );
        case 'video_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Main body', 'body', $c++, $row_id),
                    kat_field('text', 'field_widget_video_url', 'Youtube URL', 'url', $c++, $row_id),
                    kat_field('select_video_layouts', 'field_widget_video_layout', 'Video Layout', 'video_layout', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id)
                )
            );
        case 'virtual_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Main body', 'body', $c++, $row_id),
                    kat_field('text', 'field_widget_virtual_url', 'Virtual URL', 'url', $c++, $row_id),
                    kat_field('select_virtual_layouts', 'field_widget_virtual_layout', 'Virtual Layout', 'virtual_layout', $c++, $row_id),
                    kat_field('image', 'field_virtual_image', 'Virtual image', 'virtual_image', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id)
                )
            );
        case 'image_set':
            return $default + array(
                'sub_fields' => array(
                    kat_field('radio_imageset', 'field_set_style', 'Style', 'surround', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_imgset_color', 'Background colour', 'color_scheme', $c++, $row_id, 'light'),
                    kat_field('repeater_imageset', 'field_widget_imageset', 'Navigation image set', 'imageset', $c++, $row_id)
                )
            );
        case 'footer_image_set':
            return $default + array(
                'sub_fields' => array(
                    kat_field('radio_imageset', 'field_set_style', 'Style', 'surround', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_imgset_color', 'Background colour', 'color_scheme', $c++, $row_id, 'light'),
                    kat_field('repeater_imageset', 'field_widget_imageset', 'Navigation image set', 'imageset', $c++, $row_id)
                )
            );
        case 'button_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_bntintro_title', 'Buttons Intro', 'bntintro_title', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_button_color', 'Background Colour', 'buttons_color_scheme', $c++, $row_id),
                    kat_field('repeater_buttons', 'field_buttons', 'Buttons', 'buttons', $c++, $row_id)
                )
            );
        case 'wide_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('image', 'field_wide_image', 'Full Width Image', 'wide_image', $c++, $row_id)
                )
            );
        case 'floorplan_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('image', 'field_floorplan_image', 'Floorplan Image', 'floorplan_image', $c++, $row_id)
                )
            );
        case 'reviews_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_review_title', 'Review Title', 'review_title', $c++, $row_id),
                    kat_field('text', 'field_review_subtitle', 'Review Subtitle', 'review_subtitle', $c++, $row_id),
                    kat_field('repeater_reviews', 'field_reviews', 'Reviews', 'review', $c++, $row_id),
                    kat_field('image', 'field_review_image', 'Review image', 'review_image', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_review_color', 'Colour', 'review_color_scheme', $c++, $row_id, 'light')
                )
            );
        case 'separator_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('gallery', 'field_widget_sep_rotator', 'Separator Rotator', 'separator_rotator', $c++, $row_id)
                )
            );
        case 'matrix_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('radio_layout', 'field_matrix_widget_layout', 'Layout', 'matrix_layout', $c++, $row_id),
                    kat_field('repeater_matrix', 'field_matrix_widget_set', 'Items', 'matrix_set', $c++, $row_id)
                )
            );
        case 'single_image_link':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'field_widget_title', 'Title', 'title', $c++, $row_id),
                    kat_field('text', 'field_widget_subtitle', 'Subtitle', 'subtitle', $c++, $row_id),
                    kat_field('textarea', 'field_widget_body', 'Main body', 'body', $c++, $row_id),
                    kat_field('image', 'field_widget_image', 'Image', 'image', $c++, $row_id),
                    kat_field('select_layout_option', 'field_widget_layout', 'Layout', 'layout', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_color', 'Colour', 'color_scheme', $c++, $row_id)
                )
            );
         case 'faq_group':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'faq_title', 'Title', 'title', $c++, $row_id),
                    kat_field('repeater_faq', 'field_faq', 'FAQ\'s', 'faq', $c++, $row_id),
                    kat_field('color_radio', 'field_widget_faq_color', 'Colour', 'faq_color_scheme', $c++, $row_id)
                )
            );
         case 'cta_widget':
            return $default + array(
                'sub_fields' => array(
                    kat_field('text', 'cta_title', 'Title', 'title', $c++, $row_id),
                    kat_field('textarea', 'cta_sub', 'Subtext', 'subtext', $c++, $row_id),
                    kat_field('text', 'cta_button_text', 'Button Text', 'button', $c++, $row_id),
                    kat_field('color_radio', 'cta_button_color', 'Button Colour', 'button_color', $c++, $row_id),
                    kat_field('text', 'cta_button_url', 'Button Url', 'button_url', $c++, $row_id),
                    kat_field('color_radio', 'cta_background_color', 'Background Colour', 'background_color', $c++, $row_id)
                )
            );
   }
}

function kat_repeater($key, $label, $name, $button_label, $order, $sub_fields, $min = '0', $max = '')
{
    return array(
        'key' => $key,
        'label' => $label,
        'name' => $name,
        'button_label' => $button_label,
        'order_no' => $order,
        'sub_fields' => $sub_fields,
        'min' => $min,
        'max' => $max,
        'type' => 'repeater',
        'instructions' => '',
        'required' => '0',
        'layout' => 'table'
    );
}

function kat_conditional($option, $value, Array $field)
{
    $field['conditional_logic'] = array(
        'status' => '1',
        'rules' => array(
            array(
                'field' => $option,
                'operator' => '==',
                'value' => $value
            )
        ),
        'allorany' => 'all'
    );
    return $field;
}

function kat_select($key, $label, $name, $order, $choices, $multiple = '0')
{
    return array(
        'key' => $key,
        'label' => $label,
        'name' => $name,
        'order_no' => $order,
        'choices' => $choices,
        'type' => 'select',
        'default_value' => '',
        'allow_null' => '0',
        'multiple' => $multiple
    );
}

function kat_radio($key, $label, $name, $order, $choices)
{
    return array(
        'key' => $key,
        'label' => $label,
        'name' => $name,
        'order_no' => $order,
        'choices' => $choices,
        'layout' => 'horizontal',
        'type' => 'radio',
        'instructions' => '',
        'default_value' => '',
        'allow_null' => '0',
        'multiple' => '0'
    );
}

function kat_relationship($key, $label, $name, Array $post_type, Array $taxonomy, $max, $order)
{
    return array(
        'key' => $key,
        'label' => $label,
        'name' => $name,
        'post_type' => $post_type,
        'taxonomy' => $taxonomy,
        'max' => $max,
        'order_no' => $order,
        'type' => 'relationship',
        'instructions' => '.',
        'required' => '0'
    );
}

function kat_make_tab($label, $count)
{
    return array(
        'key' => 'field_tab_' . $count,
        'label' => $label,
        'name' => '',
        'type' => 'tab',
        'instructions' => '',
        'required' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => ''
        ),
        'placement' => 'top',
        'endpoint' => 0
    );
}

function kat_colors_array($type) {
    $dark = array(
        'color1' => 'Colour 1',
        'color2' => 'Colour 2',
        'color3' => 'Colour 3',
        'color4' => 'Colour 4',
        'color5' => 'Colour 5',
        'color6' => 'Colour 6',
        'color7' => 'Colour 7',
        'color8' => 'Colour 8'
    );

    $light = array(
        'color9' => 'Colour 9',
        'color10' => 'Colour 10',
        'color11' => 'Colour 11',
        'color12' => 'Colour 12',
        'color13' => 'Colour 13',
        'color14' => 'Colour 14',
        'color15' => 'Colour 15',
        'color16' => 'Colour 16'
    );

    $greys = array(
        'color17' => 'Colour 17',
        'color18' => 'Colour 18',
        'color19' => 'Colour 19'
    );
    switch ($type) {
        case 'all':
            return $dark + $light;
        case 'dark':
            return $dark;
        case 'dark and greys':
            return $dark + $greys;
        case 'light':
            return $light;
    }
    return false;
}

$kat_pages_array = null;

function cmp($a, $b)
{
    return strcmp($a->taxonomy . $a->name, $b->taxonomy . $a->name);
}

function kat_set_pages_array()
{

    $nice_names = array(
        'houses' => 'House',
        'page' => 'Page',
        'post' => 'Post',
        'suppliers' => 'Suppliers',
        'seasonal' => 'Seasonal',
        'activity' => 'Activity',
        'feature' => 'Feature',
        'location' => 'Location',
        'size' => 'Size'
    );

    global $wpdb;
    global $kat_pages_array;

    if ($kat_pages_array !== null)
        return $kat_pages_array;

    $choices = array();

    $choices['/']       = 'Home Page';
    $choices['/houses'] = 'Search All Houses';

    $post_types = array(
        'houses',
        'seasonal',
        'page',
        'post',
        'suppliers'
    );

    $posts = $wpdb->get_results(" SELECT ID, post_title, post_type
        FROM $wpdb->posts
        WHERE post_status = 'publish'
            AND post_type IN ('houses','seasonal','page','post','suppliers')
        ORDER BY post_type ASC, post_title ASC ");

    foreach ($posts as $post) {
        $choices[$post->ID] = $nice_names[$post->post_type] . ' - ' . $post->post_title;
    }

    $cats = get_categories(array(
        'type' => 'houses',
        'hide_empty' => 0,
        'taxonomy' => array(
            'location',
            'feature',
            'size',
            'activity'
        ),
        'orderby' => 'name'
    ));

    if (!array_key_exists('errors', $cats)) {
        usort($cats, 'cmp');

        foreach ($cats as $cat) {
            $choices['/' . $cat->taxonomy . '/' . $cat->slug . '/'] = $nice_names[$cat->taxonomy] . ' - ' . $cat->name;
        }
    }

    $kat_pages_array = $choices;
    return $choices;

}

function kat_dates_array($month_count = 33)
{
    $periods = array(
        '' => ''
    );
    for ($c = 0; $c < $month_count; $c++) {

        $value            = date('m-Y', strtotime('first day of +'.$c.' month'));
        $label            = date('M y', strtotime('first day of +'.$c.' month'));
        $periods[$value]  = $label;
    }
    return $periods;
}

function kat_register_field_group($id, $title, $fields, $location_rules, $options = null)
{
    if ($options === null) {
        $options = array(
            'position' => 'normal',
            'layout' => 'no_box',
            'hide_on_screen' => array()
        );
    }

    $config = array(
        'id' => $id,
        'title' => $title,
        'fields' => $fields,
        'location' => array(
            'rules' => $location_rules,
            'allorany' => 'all'
        ),
        'options' => $options,
        'menu_order' => 0
    );

    register_field_group($config);
}

?>
