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
		function service_emp(service_sel=null){
			if(service_sel == null){
			var service_selected = $("#_sbprok_services").select2('data');
			var service_sel;
			$.each(service_selected, function() {
				service_sel = this.text;
			});
	
			}
			
			var sbprok_employee = $("#_sbprok_employee li").contents().filter(function() {
				return this.nodeType == 3," ";
			}).text();
			sbprok_employee = sbprok_employee.replace('×','');
			var strArray    = service_sel.split("×");
			//$('.sbprok_employee').empty().append('<option>Select Employee</option>');
			$.ajax({
				url: sbprokAjax.ajaxurl,
				type: 'POST',
				dataType: "json",
				data: { action : 'get_service_employees' },
				success: function (res) {
						$.each(res[0], function(index, value) {
							$.each(value, function(key, values) {
							if ($.inArray(key, strArray) !== -1) {	
								$.each(res[1], function() {
									if(this.id == values){ 
										if(sbprok_employee == " "){
										$('.sbprok_employee').append($( '<button type="button" class="empls">'+ this.display_name+'</button>'));
										}else{
										$('.sbprok_employee').append($( '<button type="button" class="empls">'+ this.display_name+'</button>'));
										}
									}
								});
							}
						});  
					});	
					// var emp_opn_val = {};
					// $("select[name='_sbprok_employee'] > option").each(function () {
					// 	if(emp_opn_val[this.text]) {
					// 		$(this).remove();
					// 	} else {
					// 		emp_opn_val[this.text] = this.value;
					// 	}
					// }); 
					$( ".empls" ).click(function() {
						$('.sbprok_employee').append($('<iframe src="https://calendar.google.com/calendar/embed?src=testdemo256%40gmail.com&ctz=Asia%2FKolkata" style="border: 0" width="300" height="300" frameborder="0" scrolling="no"></iframe>'));
					  });
				  }
			  });
		}
		
        window.onLoadCallback = function(){
			var url = 'https://www.googleapis.com/calendar/v3/calendars/[testdemo256@gmail.com]/events?sendNotifications=false&access_token=[GOOGLE_API_TOKEN]';
            var data = { end: { dateTime: "2012-07-22T11:30:00-07:00" }
                            , start: { dateTime: "2012-07-22T11:00:00-07:00" }
                            , summary: "New Calendar Event from API"
                        };
						var ajax = $.ajax({
							url: url,
							contentType: "application/json",
							data: JSON.stringify(data),
							method : 'POST',
							});
		  }
		$(window).load(function() {
			var service_selected = $("#_sbprok_services").select2('data');
			var service_sel;
			$.each(service_selected, function() {
				service_sel = this.text;
			});
			service_emp(service_sel);	
		});
		
		$(document).ready(function(){	
			$.ajax({
				url: sbprokAjax.ajaxurl,
				type: 'POST',
				dataType: "json",
				data: { action : 'add_google_calendar_events' },
				success: function (res) {
					alert('test');
					console.log(res);
				  }
			  });
			$( "#_sbprok_services" ).change(function() {
				service_emp();
			 });
		   
			/**** datepicker */
			var date;
			var exclude;
			var date_time_array;
			$.ajax({
				url: sbprokAjax.ajaxurl,
				type: 'POST',
				dataType: "json",
				data: { action : 'get_availbility' },
				success: function (res) {
					date_time_array = res[0];
					exclude         = res[1];
				  }
			  });
	
			$('input[data-sbprok="datepicker"]').datepicker({
				  onSelect: function(dateText, inst) {
						date       = $(this).val();
					var startDate  = new Date(dateText);
					var selDay     = startDate.getDay();
					
					if($.inArray(selDay, exclude) > -1){ 	 
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
	
			/**** timepicker */
			$('input[data-sbprok="timepicker"]').timepicker({	
				change: function(dateText, inst){
							var time = $(this).val();
							if(date == undefined){
							   date = $(".datepic").val();
							 }		
							$('.errorMsgtime').css("display","none");
							$.each(date_time_array, function(index, value) {
								if (time == value[date]) {
									$('.errorMsgtime').html('<span>This time is already booked.Please choose another time slot. </span>');
									$('.errorMsgtime').css({"display":"block","color":"#a94442","background-color": "#f2dede", "border-color": "#ebccd1","width": "250px", "height": "35px", "text-align": "center"});
									$('input[data-sbprok="timepicker"]').val('');
								}
							}); 
						},
				timeFormat: 'h:mm p',
				interval: 30,
				minTime: '09',
				maxTime: '6:00pm',
				startTime: '09:00',
				dynamic: false,
				dropdown: true,
				scrollbar: true
			});
	
			/**** select2 */
			$('select[data-sbprok="select2"]').select2();
	
			/**** create new button */
			$('#sbprok-form-toggle').click(function() {
				$('#sbprok-cust-form').toggle();
			  });
			/**** */
	
			});
			$(document).ready( function () {
				$('#table_id').DataTable();
			} );
			
		/**** Calendar */
		
		document.addEventListener('DOMContentLoaded', function() {
			var calendarEl = document.getElementById('calendar');
		
			var calendar = new FullCalendar.Calendar(calendarEl, {
			  plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'googleCalendar' ],
			  now: new Date(),
			  editable: true, 
			  aspectRatio: 1.8,
			  scrollTime: '00:00', 
			  header: {
				left: 'today prev,next',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
			  },
			  defaultView: 'dayGridMonth',
	
			  googleCalendarApiKey: 'AIzaSyDSyoCqNhneUNTyz_ccmFe3Tht-RWCgohk',
		      
			  eventSources: [
				{
				  googleCalendarId: 'testdemo256@gmail.com',
				  className: 'gcal-event' // an option!
				},
				{
				  googleCalendarId: 'smartwork1242@gmail.com'
				}
			  ],
			  
			  eventDrop: function(info) {
									var now = new Date();
									if (info.event.start <= now){
										alert("Cannot Drag to past date.");
										info.revert();
									}else{
										if (!confirm("Are you sure about this change?")) {
											info.revert();
										  }else{
											  console.log(info.view.type);
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
																 alert(info.event.title + " was dropped on " + info.event.start);
																}
													});		
												}
											  }
										  }
									 }
								  },
			  eventClick: function(arg) {
				// opens events in a popup window
				window.open(arg.event.url, 'gcalevent', 'width=700,height=600');
		
				// prevent browser from visiting event's URL in the current tab
				arg.jsEvent.preventDefault();
			  }
			});
		
			calendar.render();
		  });
		
	
	// 	document.addEventListener('DOMContentLoaded', function() {
	// 		var calendarEl = document.getElementById('calendar');
	
	// 			var calendar = new FullCalendar.Calendar(calendarEl, {
	// 				plugins: [ 'dayGrid', 'timeGrid', 'list', 'interaction' ],
	// 				header: {
	// 					left: 'prev,next today',
	// 					center: 'title',
	// 					right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
	// 				},
					
	// 				defaultDate: new Date(),
	// 				navLinks: true, // can click day/week names to navigate views
	// 				businessHours: true, // display business hours
	// 				selectable: true,
	// 				editable: true,
	// 				disableResizing: true,
	// 				events: function(info, successCallback, failureCallback) { //include the parameters fullCalendar supplies to you!
	
	// 					var events = [];
	// 					$.ajax({
	// 					  url: sbprokAjax.ajaxurl,
	// 					  type: 'POST',
	// 					  dataType: "json",
	// 					  data: { action : 'get_bookings' },
	// 					  success: function (response) {
	// 							$.each(response[0], function(){
	// 							if(this._date != null){
	// 								var name           = this.name;
	// 								var final_time     = this._time;
	// 								var duration       = this.duration;
	// 								var posts_id       = this.posts_id;
	// 								var duration_ar    = duration.split(',');
	// 								for (var a in duration_ar)
	// 								{
	// 									var variable = duration_ar[a];
	// 									var time_arr = variable.split(':');
	// 									var end_time = moment(final_time, "hh:mm A")
	// 								                .add(time_arr[0], 'hour')
	// 												.add(time_arr[1], 'minutes')
	// 												.format('LT');
	// 								    var final_time = end_time;
	// 								}
									
	// 								var dates    = new Date(this._date+' '+this._time).toISOString();
	// 								var end_date = new Date(this._date+' '+end_time).toISOString();
	// 												events.push({
	// 												id:	posts_id,
	// 												title: name,
	// 												start: dates,
	// 												end: end_date
	// 												});
	// 							}
							
	// 							});
	// 							successCallback(events);					
	// 						}
	// 					});
	// 				},
					
	// 				eventOverlap: function(stillEvent, movingEvent) {
	// 					var evts = $('#calendar').fullCalendar('clientEvents');
	// 					for (i in evts) {
	// 						if (evts[i].id != event.id){
	// 							if (event.start.isBefore(evts[i].end) && event.end.isAfter(evts[i].start)){
	// 								return true;
	// 							}
	// 						}
	// 					}
	//     				return false;
	// 				  },
	// 				eventDrop: function(info) {
	// 					var now = new Date();
	// 					if (info.event.start <= now){
	// 						alert("Cannot Drag to past date.");
	// 						info.revert();
	// 					}else{
	// 						if (!confirm("Are you sure about this change?")) {
	// 							info.revert();
	// 						  }else{
	// 							  console.log(info.view.type);
	// 							  if(info.view.type == "dayGridMonth"){
	// 								calendar.changeView('timeGridWeek');
	// 								alert("Please update booking in Week or Day mode.");
	// 							  }else{
	// 								var posts_id = info.oldEvent.id;
	// 								var name     = info.oldEvent.title;
	// 								var time     = moment(info.event.start).format('hh:mm a');
		
	// 								if(info.oldEvent.title == info.event.title && info.oldEvent.id == info.event.id){
	// 									var dates = info.event.start;
	// 									dates = dates.toDateString();
	// 									jQuery.ajax({
	// 										type: "POST",
	// 										url: sbprokAjax.ajaxurl,
	// 										data: { action: "get_ajax_data_requests", posts_id:posts_id,title: info.event.title,start_date:dates,start_time:time},
	// 										success: function(data) {
	// 												 alert(info.event.title + " was dropped on " + info.event.start);
	// 												}
	// 									});		
	// 								}
	// 							  }
	// 						  }
	// 					 }
	// 				  },  
	// 				eventClick: function(info) {
	// 					var formDate   = moment(info.event.start).format('YYYY-MM-DD');
	// 					var start_time = moment(info.event.start).format('hh:mm a');
	// 					var end_time   = moment(info.event.end).format('hh:mm a');
	// 					/*Open Sweet Alert*/
	// 						swal({
	// 						  title: info.event.title,
	// 						  text: "Date : "+formDate+" \n"+
	// 						        "Start From : "+start_time+" To "+end_time,
	// 						  icon: "success",
	// 						});
	// 					}
	// 			});
	// 			calendar.render();
	// 		});
	
	 })( jQuery );
	
	