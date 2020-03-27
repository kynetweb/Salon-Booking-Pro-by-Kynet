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
				plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
				},
				
				defaultDate: '2020-02-12',
				navLinks: true, // can click day/week names to navigate views
				businessHours: true, // display business hours
				editable: true,
				events: [
					{
						title: 'Business Lunch',
						start: '2020-02-03T13:00:00',
						constraint: 'businessHours'
					},
					{
						title: 'Meeting',
						start: '2020-02-13T11:00:00',
						constraint: 'availableForMeeting', // defined below
						color: '#257e4a'
					},
					{
						title: 'Conference',
						start: '2020-02-18',
						end: '2020-02-20'
					},
			
					// areas where "Meeting" must be dropped
					{
						groupId: 'availableForMeeting',
						start: '2020-02-11T10:00:00',
						end: '2020-02-11T16:00:00',
						rendering: 'background'
					},
					{
						groupId: 'availableForMeeting',
						start: '2020-02-13T10:00:00',
						end: '2020-02-13T16:00:00',
						rendering: 'background'
					},
	
					// red areas where no events can be dropped
					{
						start: '2020-02-24',
						end: '2020-02-28',
						overlap: false,
						rendering: 'background',
						color: '#ff9f89'
					},
					{
						start: '2020-02-06',
						end: '2020-02-08',
						overlap: false,
						rendering: 'background',
						color: '#ff9f89'
					}
				],
				eventDrop: function(info) {
					alert(info.event.title + " was dropped on " + info.event.start.toISOString());
				
					if (!confirm("Are you sure about this change?")) {
					  info.revert();
					}
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

