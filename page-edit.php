<?php
/*
Template Name: Edit House
*/
get_header(); ?>
<script type="text/javascript" src="http://bcc1.olivernewth.com/wp-content/plugins/advanced-custom-fields/core/fields/date_picker/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" id="admin-bar-css" href="http://bcc1.olivernewth.com/wp-content/plugins/advanced-custom-fields/core/fields/date_picker/style.date_picker.css" type="text/css" media="all">
<div id="content">
<?php the_post(); ?>
<h1 class="page-title"><?php the_title(); ?></h1>
</div>
</div>
<?php 
	if ( has_post_thumbnail() ) {
		echo '<div style="height:310px;overflow:hidden;">';
		the_post_thumbnail();
		echo '</div>';
	} 
?>
<style>
#contentcontainer {
	border-bottom:5px solid #dfd9b7;
}
</style>
<div class="house_main_container">
</div>
<?php get_footer(); ?>