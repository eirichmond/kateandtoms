<?php
$themename = "clubsandwich";
$attchments_options = array(
    'image_link_to_url' => array(
        'label'       => __( 'Image LinkTo', $themename ),
        'input'       => 'text',
        'helps'       => __( 'Paste the url this image should link to.', $themename ),
        'application' => 'image',
        'exclusions'  => array( 'audio', 'video' ),
//         'required'    => true,
        //'error_text'  => __( 'Copyright field required', $themename )
    )
/*
    'image_author_desc' => array(
        'label'       => __( 'Image author description', $themename ),
        'input'       => 'textarea',
        'application' => 'image',
        'exclusions'   => array( 'audio', 'video' ),
    ),
    'image_watermark' => array(
        'label'       => __( 'Image watermark', $themename ),
        'input'       => 'checkbox',
        'application' => 'image',
        'exclusions'   => array( 'audio', 'video' )
    ),
    'image_stars' => array(
        'label'       => __( 'Image rating', $themename ),
        'input'       => 'radio',
        'options' => array(
            '0' => 0,
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4
        ),
        'application' => 'image',
        'exclusions'   => array( 'audio', 'video' )
    ),
    'image_disposition' => array(
        'label'       => __( 'Image disposition', $themename ),
        'input'       => 'select',
        'options' => array(
            'portrait' => __( 'portrtait', $themename ),
            'landscape' => __( 'landscape', $themename )
        ),
        'application' => 'image',
        'exclusions'   => array( 'audio', 'video' )
    )
*/
);