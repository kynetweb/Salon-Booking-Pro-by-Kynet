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
									var customer       = this._customer;
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
													title: name+': '+customer,
													start: dates,
													end: end_date
													});
								}
							
                                });
                                
								successCallback(events);					
							}
						});
                    },
                    eventOverlap: function(stillEvent, movingEvent) {
                        					var evts = $('#calendar').fullCalendar('clientEvents');
                        					for (i in evts) {
                        						if (evts[i].id != event.id){
                        							if (event.start.isBefore(evts[i].end) && event.end.isAfter(evts[i].start)){
                        								return true;
                        							}
                        						}
                        					}
                            				return false;
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
                        							  if(info.view.type == "dayGridMonth"){
                        								calendar.changeView('timeGridWeek');
                        								alert("Please update booking in Week or Day mode.");
                        							  }else{
                        								var posts_id = info.oldEvent.id;
                        								var name     = info.oldEvent.title;
                        								var time     = moment(info.event.start).format('hh:mm a');
                        								if(info.oldEvent.title == info.event.title && info.oldEvent.id == info.event.id){
                                                            var dates = info.event.start;
                        									dates = dates.toDateString();
                        									jQuery.ajax({
                        										type: "POST",
                        										url: sbprokAjax.ajaxurl,
                        										data: { action: "get_ajax_data_requests", posts_id:posts_id,title: info.event.title,start_date:dates,start_time:time},
                        										success: function(data) {
																	console.log(data);
                        											alert(info.event.title + " was dropped on " + info.event.start);
                        										}
                        									});		
                        								}
                        							  }
                        						  }
                        					 }
                        				  },  
                    eventClick: function(info) {
                        					var formDate   = moment(info.event.start).format('YYYY-MM-DD');
                        					var start_time = moment(info.event.start).format('hh:mm a');
                        					var end_time   = moment(info.event.end).format('hh:mm a');
                        						swal({
                        						  title: info.event.title,
                        						  text: "Date : "+formDate+" \n"+
                        						        "Start From : "+start_time+" To "+end_time,
                        						  icon: "success",
                        						});
                        					}
				});
				calendar.render();
			});
	
	
})( jQuery );