document.addEventListener('DOMContentLoaded', function () {
	var menu3 = document.getElementById('menu-3');
	menu3.style.display = 'block';
});

jQuery(function($) {
	// toggle nav on small screens
	var trigger = document.getElementById( 'site-navigation' );

	trigger.addEventListener('touchstart', function(){
		$('.ktsub_nav_cont').toggleClass('toggled');
		check_menus_toggle_state();
	});
	
	// further sub menu items:
	window.addEventListener("resize", mobilenav_width_callback);

	//let mobilenav_width = document.getElementById('mobilenav').clientWidth;	
	let mobilenav_width = window.innerWidth;	
	

	function change_mobile_size(mobilenav_width) {

		let mobilenav_height = window.innerHeight - 100;
		
		//let subarrow = document.getElementById('submenutoggler');
		let subclass = document.getElementsByClassName('submenutoggler');
		
		if(subclass === null) {
			return;
		}

		Array.from(subclass).forEach(function (element) {
			element.onclick = function() {
				console.log(mobilenav_width);
				let subsubclass = document.getElementsByClassName('subsubmenutoggler');
				$('#mobilenav').css({
					'left':'-'+mobilenav_width.toString()+'px',
					'width':mobilenav_width * 2,
					'height': mobilenav_height
				});
				$(element).parent().find('.secondary-nav.submenu').css({'display':'block','left':''+mobilenav_width.toString()+'px'});
				Array.from(subsubclass).forEach(function (subelement) {
					console.log(mobilenav_width);
					var moveagain = mobilenav_width * 2;
					subelement.onclick = function() {
						$('#mobilenav').css({
							'left':'-'+moveagain.toString()+'px',
							'width':mobilenav_width * 3
						});
						$(subelement).parent().find('.tertiery-nav.submenu').css({'display':'block','left':''+mobilenav_width.toString()+'px'});
					}
				});
			};
		});
	
		// subarrow.onclick = function() {
		// 	$('#mobilenav').css({'left':'-'+mobilenav_width.toString()+'px'});
		// 	//$(this).parent().next('ul').css({'left':''+mobilenav_width.toString()+'px'});
		// };
		//var submenureturn = document.getElementById('submenureturn');	
		var returnmenu = document.getElementsByClassName('menu-icon-return');

		Array.from(returnmenu).forEach(function (element){
			element.onclick = function() {
				console.log($(element).parent());
				$('#mobilenav').css({
					'left':'0px',
					'width':mobilenav_width
				});
				$(element).parent().css({'display':'none'});
			};
	
		});
		// submenureturn.onclick = function() {
		// 	$('#mobilenav').css({'left':'0px'});
		// 	$('.secondary-nav.submenu').css({'left':''+mobilenav_width.toString()+'px'});
		// };
	}

	function mobilenav_width_callback() {
		let mobilenav_width = document.getElementById("mobilenav").clientWidth;

		//$('.secondary-nav.submenu').css({'left':''+mobilenav_width.toString()+'px'});

		change_mobile_size(mobilenav_width);
	}

	function check_menus_toggle_state() {
		if($('.ktsub_nav_cont').hasClass('toggled')) {
			$('#mobilenav').css({
				'height': 'auto'
			});
		} else {
			$('#mobilenav').css({
				'height': '0px'
			});
		}
		
	}
	check_menus_toggle_state();
	change_mobile_size(mobilenav_width);
	
	//let mobilenav_width = document.getElementById('mobilenav').clientWidth;
	
	

	$('select.mautosel').bind('focusin select', function(e){
		e.preventDefault();
	});
	
	$('#booknow').click(function() {
		$(this).addClass('active');
		$('#availability').removeClass('active');
	});
	
	$( ".selector" ).datepicker( "setDate", "10/12/2012" );

	$('#refine-search a').click(function() {
		$('#searchBar').slideToggle();
		$('#pin').css({'height':'none'});
	});
	
	$('#results-filter').click(function() {
		window.scrollTo(0, 0);
	});
	
	$( ".widget_imagematrix:first" ).addClass( "top" );
	$( ".widget_imagematrix:last" ).addClass( "bottom" );
	

	// happens after first load on mobile
    var mobile = window.matchMedia( '(min-width: 480px)' );
    // if (mobile.matches) {
    // } else {
	//     mobheight = jQuery(window).height();
	//     var $cache = jQuery('#pin');
	//     $cache.css({'height':mobheight+'px'});
	    
	//     $('#gallery-1').removeClass('gallery').on('click', function(event) {
	// 	    event.preventDefault();
	//     });
    // }
    
    // $(window).scroll(function(){
    //     if ($(window).scrollTop() > 100) {
	// 		$('#searchBar').addClass('short');
	// 		$('#pin').css({'height':'auto'});
    //     } else {
	// 		$('#searchBar').removeClass('short');
    //     }
    // });


	
	/* Do it like this so it'll do it correctly if we refresh halfway down the page */
	calculateScroll();
	$(document).scroll(function() {
		calculateScroll();
	});
	
	function calculateScroll() {
		var point = $(this).scrollTop();
		
		var opacity = 0.8;
		if(point > 125) {
			/* Beyond the first one */
			opacity = 1.0;
		}
		
		/* Work out the color */
		var objColor = {
			'background-color': jQuery('.ktsub_nav_cont').css('background-color'),
			color: jQuery('.ktsub_nav_cont').css('color')
		};
		
		var objSet = {};
		$.each(objColor, function(key, value) {
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
		$('.ktsub_nav_cont').css(objSet);
	};
	
	$('#searchBar button, #searchBar .dropdown-menu a').on('click', scrollToTop);

	function scrollToTop() {
		verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
		element = $('body .page-title.standard.span9');
		offset = element.offset();
		offsetTop = offset.top;
		$('html, body').animate({scrollTop: offsetTop}, 500, 'linear');
	}

	function fixDiv() {
		var mq = window.matchMedia( '(min-width: 668px)' );
		var $cache = $('#pin');
		if (mq.matches) {
			
			var $top = '95px';
			
			var $topper = $('#topper');
			if ($(window).scrollTop() > 125) {
				$cache.css({
				'position': 'fixed',
				'top': $top,
				'width' : '100%',
				'z-index' : '998',
				'min-height' : '96px',
				});
				$topper.css({'margin-top' : '96px'});
			} else {
				$cache.css({
				'position': 'relative',
				'top': 'auto'
				});
				$topper.css({'margin-top' : '0px'});
			}
		} else {
			if ($(window).scrollTop() > 416) {
				$('.archive #results-filter, .search #results-filter').css({'display' : 'block'})
			} else {
				$('.archive #results-filter, .search #results-filter').css({'display' : 'none'})
			}
		}
	}
	$(window).scroll(fixDiv);
	fixDiv();

	function fixTax() {
		var mq = window.matchMedia( '(min-width: 480px)' );
		if (mq.matches) {
			var $cache = $('#pintax');
			var $topper = $('#toppertax');
			if ($(window).scrollTop() > 350) {
				$cache.css({
					'position': 'fixed',
					'top': '95px',
					'width' : '100%',
					'z-index' : '998',
					'min-height' : '105px',
				});
				$topper.css({'margin-top' : '105px'});
			} else {
				$cache.css({
				'position': 'relative',
				'top': 'auto'
				});
				$topper.css({'margin-top' : '0px'});
			}
		} else {
			// we do this for mobile
		}
	}
	$(window).scroll(fixTax);
	fixTax();
	
	$(function() {
	  $('.slideto').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	      var target = $(this.hash);
	      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	      if (target.length) {
			  var tar = target.offset().top;
	        $('html, body').animate({
	          scrollTop: target[0].offsetTop -129
	        }, 1000);
	        return false;
	      }
	    }
	  });
	});

});

