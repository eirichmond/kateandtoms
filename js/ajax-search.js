(function( $ ) {

	'use strict';
	
	var date = 'input[name="date"]';
	var dtypebutton = '.btn.dtype-toggle';
	var size = 'input[name="size"]';
	var location = 'input[name="location"]';
	var selector = '.btn.feature-toggle';
	var opencloser = '.search-all-trigger';

	
	$('button.btn').on('click touchstart', function(){

		if(this.attributes.name.value === 'dtype') {
			$('input[name="dtype"]').val(this.value);
		}
		if(this.attributes.name.value === 'size') {
			$('input[name="size"]').val(this.value);
		}
		if(this.attributes.name.value === 'location') {
			$('input[name="location"]').val(this.value);
		}
		if(this.attributes.name.value === 'feature') {
			$('input[name="feature"]').val(this.value);
		}

		if($(this).hasClass('dropdown-toggle')) {
			if($(this).hasClass('dropdown-size')) {
				$('.size-toggle').removeClass('active');
			}
			if($(this).hasClass('dropdown-location')) {
				$('.location-toggle').removeClass('active');
			}
			if($(this).hasClass('dropdown-feature')) {
				$('.feature-toggle').removeClass('active');
			}
			//debugger;
			//return; // stop early to prevent ajax sending twice
		}

		if($(this).hasClass('dtype-toggle')) {
			$('.dtype-toggle').removeClass('active');
			$(this).addClass('active');
			$('.dtypep').html($(this).text()+'s');
		}
		if($(this).hasClass('size-toggle')) {
			$('.size-toggle').removeClass('active');
			$(this).addClass('active');
			$('.search_area_size').removeClass('hidden');
			$('.search_desc_size').html($(this).text());
			$('.search_dropdown_current_size').html($(this).text());
		}
		if($(this).hasClass('feature-toggle')) {
			$('.feature-toggle').removeClass('active');
			$(this).addClass('active');
			$('.search_area_feature').removeClass('hidden');
			$('.search_desc_feature').html($(this).text());	
			$('.search_dropdown_current_feature').html($(this).text());				
		}
		if($(this).hasClass('location-toggle')) {
			$('.location-toggle').removeClass('active');
			$(this).addClass('active');
			$('.search_area_location').removeClass('hidden');
			$('.search_desc_location').html($(this).text());
			$('.search_dropdown_current_location').html($(this).text());
		}

		var object = check_what_is_toggled();
		post_via_ajax(object);
	
		// changed_action(date, 'change', e);
		// changed_action(dtypebutton, 'click touchstart', e);
		// changed_action(size, 'change', e);
		// changed_action(location, 'change', e);
		// changed_action(selector, 'click touchstart', e);
			
	});

	$('#datepicker2').datepicker({
		dateFormat: 'dd-mm-yy',
		onSelect: function(e) {
			$('.dtype-toggle').removeClass('active');
			$('.dtype-toggle[value="1"]').addClass('active');
			$('input[name="date"]').val(e);
			$('input[name="dtype"]').val(1);
			var date = e;
			var dtype = 1;
			var size = $('input[name="size"]').val();
			var location = $('input[name="location"]').val();
			var feature = $('input[name="feature"]').val();
	
			//var object = check_what_is_toggled();
			var object = { date : date, dtype : dtype, size : size, locationbutton : location, featurebutton : feature, }
			post_via_ajax(object);
		}
	});

	$('.mautosel').change(function() {

		var date = $('input[name="date"]').val();
		var dtype = $('input[name="dtype"]').val();
		var size = $('input[name="size"]').val();
		var location = $('input[name="location"]').val();
		var feature = $('input[name="feature"]').val();

		//var object = check_what_is_toggled();
		var object = { date : date, dtype : dtype, size : size, locationbutton : location, featurebutton : feature, }
		post_via_ajax(object);
		
	});

	$()

	$('.dropdown-menu li a').on('click touchstart', function(){
		var date = $('input[name="date"]').val();
		var size = $('input[name="size"]').val();
		var location = $('input[name="location"]').val();
		var feature = $('input[name="feature"]').val();
	
		var dtype = $('input[name="dtype"]').val();

		if($(this).data('type') == 'size'){
			size = $(this).data('value');
			$('.search_dropdown_current_size').attr('data-size', $(this).data('value')).text($(this).text());
		}
		if($(this).data('type') == 'location'){
			location = $(this).data('value');
			$('.btn.location-toggle').removeClass('active');
			$('.search_dropdown_current_location').attr('data-location', $(this).data('value')).text($(this).text());
		}
		if($(this).data('type') == 'feature'){
			feature = $(this).data('value');
			$('.btn.feature-toggle').removeClass('active');
			$('.search_dropdown_current_feature').attr('data-feature', $(this).data('value')).text($(this).text());
		}
		
		//var object = check_what_is_toggled();
		var object = { date : date, dtype : dtype, size : size, locationbutton : location, featurebutton : feature, }
		post_via_ajax(object);

		
	});


	
	function check_what_is_toggled() {

		//var features = [];
		var dtype = $('.btn.dtype-toggle.active').val(); // value of active date type button
		var sizebutton = $('.btn.size-toggle.active').val(); // value of active size button
		var locationbutton = $('.btn.location-toggle.active').val(); // value of active location button
		var featurebutton = $('.btn.feature-toggle.active').val(); // value of active feature button

		var date = $('input[name="date"]').val();

		if(sizebutton == undefined) {
			sizebutton = $('.search_dropdown_current_size').data('size'); // value of active location dropdown
		}
		if(locationbutton == undefined) {
			locationbutton = $('.search_dropdown_current_location').data('location'); // value of active location dropdown
		}
		if(featurebutton == undefined) {
			featurebutton = $('.search_dropdown_current_feature').data('feature'); // value of active location dropdown
		}

		
		var object = { date : date, dtype : dtype, size : size, sizebutton : sizebutton, locationbutton : locationbutton, featurebutton : featurebutton, }
		
		return object;

	}
	
	function post_via_ajax(object) {
		
		var ajax_post = { 'action': 'search_houses', 'nextNonce': search_object.nextNonce }
		
		if(object.date != null || object.date != undefined) {
			ajax_post.date = object.date;
		}
		
		if(object.dtype != null || object.dtype != undefined) {
			ajax_post.dtype = object.dtype;
		}
		
		if(object.size != null || object.size != undefined) {
			ajax_post.size = object.size;
		}
		
		if(object.sizebutton != null || object.sizebutton != undefined) {
			ajax_post.size = object.sizebutton;
		}
		
		if(object.locationbutton != null || object.locationbutton != undefined) {
			ajax_post.local = object.locationbutton;
		}
		
		if(object.featurebutton != null || object.featurebutton != undefined) {
			ajax_post.feature = object.featurebutton;
		}

		$('#search-ajax').fadeTo(300,0.3, function () { //fade out the content area
			//jQuery("#loader").show(); // show the loader animation
			$.post(
			    search_object.ajax_url, 
			    ajax_post, 
			    function(response){
			        $('#search-ajax').html(response);
					//jQuery("#loader").hide(); // show the loader animation
			        jQuery('#search-ajax').fadeTo(100, 1, function () {}); 
			        
			    }
			);
			
			
			
		});
	}
	
	function changed_action(element, action, e) {
	    $(element).on(action,function(e){
			e.preventDefault();
			var object = check_what_is_toggled(e);
			post_via_ajax(object);
		});
	}

	


	$('.search-all-filters').slideUp();
	$(opencloser).on('touchstart', function(e){
		if($(this).hasClass('open')) {
			$(this).removeClass('open');
			$('.search-all-filters').slideUp();
		} else {
			$(this).addClass('open');
			$('.search-all-filters').slideDown();
		}
	});
	

})( jQuery );