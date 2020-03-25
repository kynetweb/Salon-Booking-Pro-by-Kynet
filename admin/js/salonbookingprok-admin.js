(function( $ ) {
	'use strict';
	//
	$(window).load(function(){
        /**** display date and time */
		var exclude = ["16-11-2015", "17-11-2015", "18-11-2015", "19-11-2015", "20-11-2015", "26-03-2020"];
		$('#_sbprok_day').datepicker({
			beforeShowDay: function(date) {
				var day = jQuery.datepicker.formatDate('dd-mm-yy', date);
				return [!~$.inArray(day, exclude) && (date.getDay() != 0)];
			  },
			changeMonth: true,
			changeYear: true,
			minDate:new Date()
		});
		$('#_sbprok_time').timepicker({
			timeFormat: 'h:mm p',
			interval: 30,
			minTime: '09',
			maxTime: '6:00pm',
			startTime: '09:00',
			dynamic: false,
			dropdown: true,
			scrollbar: true
		});
		/**** */
		
		$('#_salonbookingprok_employees').select2();

		/**** create new button */
		$('.js-toggle').click(function() {
			$('.hidden_fields').toggle();
		  });
	    });
		/**** */

		/**** Calendar */
		document.addEventListener('DOMContentLoaded', function() {
			var calendarEl = document.getElementById('calendar');
		
			var calendar = new FullCalendar.Calendar(calendarEl, {
			  plugins: [ 'dayGrid', 'timeGrid', 'list', 'interaction' ],
			  header: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
			  },
			  defaultDate: '2020-02-12',
			  navLinks: true, // can click day/week names to navigate views
			  editable: true,
			  eventLimit: true, // allow "more" link when too many events
			  events: [
				{
				  title: 'All Day Event',
				  start: '2020-02-01',
				},
				{
				  title: 'Long Event',
				  start: '2020-02-07',
				  end: '2020-02-10'
				},
				{
				  groupId: 999,
				  title: 'Repeating Event',
				  start: '2020-02-09T16:00:00'
				},
				{
				  groupId: 999,
				  title: 'Repeating Event',
				  start: '2020-02-16T16:00:00'
				},
				{
				  title: 'Meeting',
				  start: '2020-02-12T10:30:00',
				  end: '2020-02-12T12:30:00'
				},
				{
				  title: 'Meeting',
				  start: '2020-02-12T14:30:00'
				},
				{
				  title: 'Happy Hour',
				  start: '2020-02-12T17:30:00'
				},
				{
				  title: 'Click for Google',
				  url: 'http://google.com/',
				  start: '2020-02-28'
				}
			  ]
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

