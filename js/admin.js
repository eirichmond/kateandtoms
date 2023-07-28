(function($){
	
	$(document).ready(function(){
		
		$.each(specials_object, function(key, value){
			//console.log( key + ':' + value );
			if (value != '1') {
				$('#special_offer-cmb-group-'+key+'-offer_date-cmb-field-0').css({'background-color': '#ff0505', 'color':'#fff'});
			}
		});
		
		
/*
		$('.field-item').each(function(i, specials_object) {
			
			
			console.log(typeof 4);

			var indexNumber = i;
			
			console.log(specials_object[indexNumber]);
			
			if (specials_object[i] === '1') {
				
				$(this).addClass('avail');
			} else {
				$(this).addClass('unavail');
			}
		});	
*/
			
		
		function makeKatDatePickers() {
			$('.acf-field-availability-repeater > div > div > table > tbody > tr').each(function( index ) {
				if ($(this).find('.selector').hasClass('hasDatepicker')) {
					return;
				}
				if ($(this).hasClass('acf-clone')) {
					return;
				}
				var selector = $(this).find('.acf-field-select select'),
					selected_date = selector.val(),
					//input_box_full = $(this).find('.full'),
					input_box_days = $(this).find('.acf-field-day-picker .days'),
					date = getDateFromSelection(selected_date),
					settings = {
					//dateFormat: 'y-m-d', 
					//altField: '#'+input_box_full.attr('id'),
					defaultDate: date,
					firstDay: 5,
					onSelect: function() {
						var dates = $(this).multiDatesPicker('getDates', 'object');
						    days = '';
						if (dates.length>0) {
							$.each(dates, function (index, value) {
								days += value.getDate() + ',';
							});
							days = days.slice(0, -1);
						}
						$(this).parent().parent().parent().find('.days').val(days);
					}
				};
				
				if (input_box_days.val().length>0) {
					addDates = getAddDates(input_box_days.val(), date);
					$.extend(settings, {addDates: addDates});
				}

				$(this).find('.selector').multiDatesPicker(settings);
	
			});
		}
		
		function getDateFromSelection(selected_date) {
			if (!selected_date) {
				return new Date();
			}
			else {
				var month = selected_date.substr(0,2)-1,
					year =  selected_date.substr(3,4);
				return new Date(year, month, 1);
			}
		}
		
		function getAddDates(daysString, date) {
			var days = daysString.split(','),
				addDates = [];
			$.each( days, function( index, value ){
				addDates.push(date.setDate(value));
			});
			return addDates;
		}
		
		function noticeChangesInSelect() {
			$('.acf-field-availability-repeater select').change(function(){
				var row        		= jQuery(this).parent().parent().parent(),
					datePicker 		= row.find('.hasDatepicker');
				
				datePicker.multiDatesPicker('destroy');
				makeKatDatePickers();
			});
		}
		
		makeKatDatePickers();
		noticeChangesInSelect();
		
		$('.acf-field-availability-repeater').on('click', 'a.acf-repeater-add-row', function(){
			setTimeout(function() {
				makeKatDatePickers();
				noticeChangesInSelect();
			}, 500);
		});
		
		// hacked and included in CMB clonedField method @todo: make this more efficient by exenting the original js object in the cmb.js file.
		function limit_characters() {
			// a hack to limit the charaters input in to the custom meta for special offers
			$('#special-offers .field-item input').unbind('keyup change input paste').bind('keyup change input paste',function(){
			    var $this = $(this);
			    var val = $this.val();
			    var valLength = val.length;
			    var maxCount = 105;
			    var limitReached = false;
			    if(valLength>maxCount){
				    $this.val($this.val().substring(0,maxCount));
			    }
			    
			}); 
		}
		
		limit_characters();
		
/*
		jQuery('.field-item > .cmb_metabox').after('<button class="cmb-insert-field">Insert Below</button>');
		jQuery('.cmb-insert-field').click(function(e){
			e.preventDefault();
			jQuery(this).parent().clone().insertAfter(jQuery(this).parent());
		});
*/

				
	});


})(jQuery);