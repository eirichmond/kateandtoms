<?php
add_image_size('house_search', 280, 240, true);
add_image_size('cross_promo_wide', 880, 300, true);
add_image_size('cross_promo_wide_prev', 200, 100, true);
add_image_size('cross_promo_narrow', 280, 300, true);
add_image_size('cross_promo_narrow_prev', 100, 150, true);
add_image_size('huge', 1600, 900, true);
add_image_size('square', 580, 580, true);
add_image_size('matrix', 780, 780, true);
add_image_size('square-partners', 550, 450, true);
add_image_size('square-vendor-stats', 470, 365, array('center', 'center'));
add_image_size('large', 1180, 450, true);
add_image_size('thumbnail', 280, 280, true);
add_image_size('blog-wide', 770, 380, true);
add_image_size('blog-square', 380, 380, true);
add_image_size('blog-square-wide', 280, 188, true);
if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1200, 400, true);
}
?>
