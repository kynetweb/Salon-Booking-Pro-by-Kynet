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
(function( $ ) {
	'use strict';
		$(document).ready(function(){
		//datepicker
		var exclude;
		$.ajax({
			url: sbprokAjax.ajaxurl,
			type: 'POST',
			dataType: "json",
			data: { action : 'get_availbility' },
			success: function (res) {
				exclude = res;
			  }
		  });
		$('input[data-sbprok="datepicker"]').datepicker({
			  onSelect: function(dateText, inst) {
				var date = $(this).val();
				var startDate = new Date(dateText);
				var selDay = startDate.getDay();
				alert(selDay);
				if($.inArray(date, exclude) > -1){
					$('.errorMsg').html('<span>We are unavailable at: '+ date+ '</span>');
					$('.errorMsg').css({"color":"#a94442","background-color": "#f2dede", "border-color": "#ebccd1","width": "250px", "height": "35px", "text-align": "center"});
					return false;
				}else{
					$('.errorMsg').html("");
					$('.errorMsg').css({"color":" ","background-color": " ", "border-color": " "});
				}
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
					  url: sbprokAjax.ajaxurl,
					  type: 'POST',
					  dataType: "json",
					  data: { action : 'get_bookings' },
					  success: function (response) {
							$.each(response[0], function(){
							if(this._date != null){
								var name           = this.name;
								var final_time     = this._time;
								var duration       = this.duration;
								var duration_ar    = duration.split(',');
								for (var a in duration_ar)
								{
									var variable = duration_ar[a];
									var time_arr = variable.split(':');
									var end_time = moment(final_time, "hh:mm A")
								                .add(time_arr[0], 'hour')
												.add(time_arr[1], 'minutes')
												.format('LT');
								    var final_time = end_time;
								}
								
								var dates    = new Date(this._date+' '+this._time).toISOString();
								var end_date = new Date(this._date+' '+end_time).toISOString();
												events.push({
												title: name,
												start: dates,
												end: end_date
												});
							}
						
							});
							successCallback(events);
													
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
							alert(info.event.title + " was dropped on " + info.event.start);
							Update(id, start, end);
						  }
					 }
				  },
				  
				eventClick: function(info) {
					var formDate   = moment(info.event.start).format('YYYY-MM-DD');
					var start_time = moment(info.event.start).format('hh:mm a');
					var end_time   = moment(info.event.end).format('hh:mm a');
					/*Open Sweet Alert*/
						swal({
						  title: info.event.title,//Event Title
						  text: "Date : "+formDate+" \n"+
						        "Start From : "+start_time+" To "+end_time,//Event Start Date
						  icon: "success",
						});
					}
			});
	
			calendar.render();
		});

})( jQuery );

