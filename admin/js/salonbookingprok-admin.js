(function( $ ) {
	'use strict';
		$(document).ready(function(){
		var exclude = ["16-11-2015", "17-11-2015", "18-11-2015", "19-11-2015", "20-11-2015", "26-03-2020"];
		//datepicker
		$('input[data-sbprok="datepicker"]').datepicker({
			beforeShowDay: function(date) {
				var day = jQuery.datepicker.formatDate('dd-mm-yy', date);
				return [!~$.inArray(day, exclude) && (date.getDay() != 0)];
			  },
			changeMonth: true,
			changeYear: true,
			minDate:new Date()
		});
		//timepicker
		$('input[data-sbprok="timepicker"]').timepicker({
			timeFormat: 'h:mm p',
			interval: 30,
			minTime: '09',
			maxTime: '6:00pm',
			startTime: '09:00',
			dynamic: false,
			dropdown: true,
			scrollbar: true
		});
		//select2
		$('select[data-sbprok="select2"]').select2();

		/**** create new button */
		$('#sbprok-form-toggle').click(function() {
			$('#sbprok-cust-form').toggle();
		  });
		/**** */
	    });
		/**** Calendar */
		document.addEventListener('DOMContentLoaded', function() {
			var calendarEl = document.getElementById('calendar');
	
			var calendar = new FullCalendar.Calendar(calendarEl, {
				plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
				},
				
				defaultDate: '2020-03-12',
				navLinks: true, // can click day/week names to navigate views
				businessHours: true, // display business hours
				selectable: true,
				editable: true,
				events: function(info, successCallback, failureCallback) { //include the parameters fullCalendar supplies to you!

					var events = [];
					$.ajax({
					  url: myAjax.ajaxurl,
					  type: 'POST',
					  dataType: "json",
					  data: { action : 'get_ajax_posts' },
					  success: function (response) {					
						$.each( response, function( key, value ) {
							if (value  != null) {
								  events.push({
									title: value.post_title,
									start: value.post_date
								  });
								}
						});
						successCallback(events)
					  }
					});
				  },
				
				eventDrop: function(info) {
					var now = new Date();
					if (info.event.start <= now){
						alert("Cannot Drag to past date.");
						info.revert();
					}else{
						if (!confirm("Are you sure about this change?")) {
							info.revert();
						  }else{
							alert(info.event.title + " was dropped on " + info.event.start.toISOString());
							Update(id, start, end);
						  }
					 }
				  },
				  
				eventClick: function(info) {
					/*Open Sweet Alert*/
						swal({
						  title: info.event.title,//Event Title
						  text: "Start From : "+info.event.start.toISOString(),//Event Start Date
						  icon: "success",
						});
					}
			});
	
			calendar.render();
		});
		
	
	/**
	 * All of the code for your admin-facing JavaScript source
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

})( jQuery );

