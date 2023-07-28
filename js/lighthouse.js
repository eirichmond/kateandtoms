jQuery(function($) {
		/* Do it like this so it'll do it correctly if we refresh halfway down the page */
	calculateScroll();
	jQuery(document).scroll(function() {
		calculateScroll();
	});
	
	function calculateScroll() {
		var point = jQuery(this).scrollTop();
		
		var opacity = 0.8;
		if(point > 450) {
			/* Beyond the first one */
			opacity = 1.0;
		}
		
		/* Work out the color */
		var objColor = {
			'background-color': jQuery('.ktsub_nav_cont').css('background-color'),
			color: jQuery('.ktsub_nav_cont').css('color')
		};
		
		var objSet = {};
		jQuery.each(objColor, function(key, value) {
			/* Use a spot of regex to change the opacity */
			var arrRgb = value.match(/\(([^"]*)\)/);
			var rgb = arrRgb[1];
			
			var arrColor = rgb.split(',');
			
			var newValue = 'rgb(';
			for(var i = 0; i < 3; i++) {
				if(i > 0) { newValue += ','; }
				newValue += arrColor[i];
			}
			
			/* Add in the new opacity */
			newValue += ',';
			newValue += opacity;
			newValue += ')';
			
			objSet[key] = newValue;
		});
		
		/* Set the new CSS */
		jQuery('.ktsub_nav_cont').css(objSet);
	};
});

jQuery(function($) {
  function amendlogo() {
    var $cache = $('.mini-logo');
    if ($(window).scrollTop() > 308) {
      $cache.addClass('chopper');
    } else {
      $cache.removeClass('chopper');
    }
  }
  $(window).scroll(amendlogo);
  amendlogo();
});
