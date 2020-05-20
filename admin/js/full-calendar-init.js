(function( $ ) {
	'use strict';

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
	 * pr
	 * actising this, we should strive to set a better example in our own work.
	 */
		/**** Calendar */
			document.addEventListener('DOMContentLoaded', function() {
			var calendarEl = document.getElementById('calendar');
	
				var calendar = new FullCalendar.Calendar(calendarEl, {
					plugins: [ 'dayGrid', 'timeGrid', 'list', 'interaction' ],
					header: {
						left: 'prev,next today',
						center: 'title',
						right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
					},
					
					defaultDate: new Date(),
					navLinks: true, // can click day/week names to navigate views
					businessHours: true, // display business hours
					selectable: true,
					editable: true,
					disableResizing: true,
					events: function(info, successCallback, failureCallback) { //include the parameters fullCalendar supplies to you!
	
						var events = [];
						$.ajax({
						  url: sbprokAjax.ajaxurl,
						  type: 'POST',
						  dataType: "json",
						  data: { action : 'get_bookings' },
						  success: function (response) {
                            console.log(response);
								$.each(response[0], function(){
								if(this._date != null){
									var name           = this.name;
									var final_time     = this._time;
									var duration       = this.duration;
									var posts_id       = this.posts_id;
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
													id:	posts_id,
													title: name,
													start: dates,
													end: end_date
													});
								}
							
                                });
                                
								successCallback(events);					
							}
						});
					}
				});
				calendar.render();
			});
	
	
})( jQuery );