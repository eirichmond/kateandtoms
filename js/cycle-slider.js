(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(function() {
        var cycler = $('.cslider').cycle({
			delay: 250,
            speed: 1000,
			timeout: 3000
        });
        
		var homecycler = $('.homecslider').cycle({
            delay: 1000,
            speed: 1000,
			slides: '> div'
        });

		var textSlider = $('#newtextSlider').cycle({
			timeout: 7000,
            speed: 1000,
			slides: '> .sliders div',
			slideClass: 'span9 offset2 cycle-slide'
        });


	});	


	// #newtextSlider {
	// 	display: flex;
	// 	flex-direction: row;
	// 	justify-content: center;
	// 	align-items: center;
	//   }
	//   #newtextSlider :nth-child(1) {
	// 	order: 2;
	//   }
	//   #newtextSlider :nth-child(2) {
	// 	order: 1;
	//   }
	//   #newtextSlider .cycle-slide-active {
	// 	margin-left: 170px;
	// 	background: red;
	//   }
	//   #newtextSlider :nth-child(4) {
	// 	order: 3;
	//   }
	  

})( jQuery );


