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

		function service_cat(service_cat_sel=null){
			
			if(service_cat_sel == null){
				var service_cat_sel = $("#_sbprok_service_cat option:selected").text();
				var service_cat_id  = $("#_sbprok_service_cat option:selected").val();
				$('.sbprok_srvice').empty().append('<option>Select Service</option>');
			}
			if(service_cat_sel == ''){
				$('.sbprok_srvice').empty().append('<option>Select Service</option>');
			}
			
			$.ajax({
				url: sbprokAjax.ajaxurl,
				type: 'POST',
				dataType: "json",
				data: { action : 'get_cat_service',cat_id:service_cat_id },
				success: function (res) {
						$.each(res, function(index, value) {
										if(service_cat_sel != ''){
										$('.sbprok_srvice').append($( '<option value="'+ value.id +'" >'+ value.name+'</option>'));
										}
					});	
					var emp_opn_val = {};
					$("select[name='_sbprok_services'] > option").each(function () {
						if(emp_opn_val[this.text]) {
							$(this).remove();
						} else {
							emp_opn_val[this.text] = this.value;
						}
					}); 
				  }
			  });
		}

		function service_emp(service_sel=null){
			if(service_sel == null){
			var service_selected = $(".sbprok_srvice option:selected").text();
			$('.sbprok_employees').empty().append('<option>Select Employee</option>');
			}
			if(service_sel == ''){
			  $('.sbprok_employees').empty().append('<option>Select Employee</option>');
			}
			var sbprok_employee = $('.sbprok_employees').val();
			$.ajax({
				url: sbprokAjax.ajaxurl,
				type: 'POST',
				dataType: "json",
				data: { action : 'get_service_employees' },
				success: function (res) {
						$.each(res[0], function(index, value) {
							$.each(value, function(key, values) {
								$.each(res[1], function() {
									if(key == service_selected && this.id == values){ 
										$('.sbprok_employees').append($( '<option value="'+ this.id +'" >'+ this.display_name+'</option>'));
										}
								});
						});  
					});	
					var emp_opn_val = {};
					$("select[name='_sbprok_employee'] > option").each(function () {
						if(emp_opn_val[this.text]) {
							$(this).remove();
						} else {
							emp_opn_val[this.text] = this.value;
						}
					}); 
				  }
			  });
		}
		
		$(window).load(function() {
			var service_sel = $("#_sbprok_services option:selected").val();
			service_emp(service_sel);	
				var service_cat_sel = $("#_sbprok_services option:selected").val();
			service_cat(service_cat_sel);	
		});
		
		$('input[data-sbprok="datepicker"]').datepicker();
		$('input[data-sbprok="timepicker"]').timepicker();
			  
		 
		$(document).ready(function(){
		 var customer_to_insert;
		 var start_date_to_insert;
		 var end_date_to_insert;
		 var service_to_insert;
		 var date_to_insert;
		 var emp_email_to_insert;
		 var z = [];
		 var employee_calendar_date;
		 var key_for_update;
		 var date_to_update           = $('.datepic').val();
		 var time_to_update           = $('.time_pic').val();
		 var service_to_update        = $(".sbprok_srvice option:selected").text();
		 var customer_to_update       = $(".cst option:selected").text();
		 var end_time_to_update;
		 var emp_email_to_update;
		 var google_event_id;
		 var start_date_to_update;
		
		 // Developer Console, https://console.developers.google.com
		 var CLIENT_ID = '769528724818-n3vnf5u2tsoaueia22903vfcg805q9ij.apps.googleusercontent.com';
		 var SCOPES    = ["https://www.googleapis.com/auth/calendar"];
	
		 /**
		  * Check if current user has authorized this application.
		  */
		 function checkAuth() {
		   gapi.auth.authorize(
			 {
			   'client_id': CLIENT_ID,
			   'scope': SCOPES.join(' '),
			   'immediate': true
					   }, handleAuthResult);
		 }
	
		 /**
		  * Handle response from authorization server.
		  *
		  * @param {Object} authResult Authorization result.
		  */
		 function handleAuthResult(authResult) {
		   if (authResult && !authResult.error) {
			   if(key_for_update == 'enable'){
					update_google_events();
			   }else if(date_to_insert == null){
				makeApiCall();
			   }else{
				loadCalendarApi();
			   }
		   } 
		 }

		 		    
		//--------------------- Add a 0 to numbers
		function padNum(num) {
			if (num <= 9) {
				return "0" + num;
			}
			return num;
		}
		//--------------------- end    
			
		//--------------------- From 24h to Am/Pm
		function AmPm(num) {
			if (num <= 12) { return  "AM " + num; }
			return  "PM " + padNum(num - 12);
		}
		//--------------------- end    

		//--------------------- num Month to String
		function monthString(num) {
				if (num === "01") { return "Jan"; } 
			else if (num === "02") { return "Feb"; } 
			else if (num === "03") { return "Mar"; } 
			else if (num === "04") { return "Apr"; } 
			else if (num === "05") { return "May"; } 
			else if (num === "06") { return "Jun"; } 
			else if (num === "07") { return "Jul"; } 
			else if (num === "08") { return "Aug"; } 
			else if (num === "09") { return "Sep"; } 
			else if (num === "10") { return "Oct"; } 
			else if (num === "11") { return "Nov"; } 
			else if (num === "12") { return "Dec"; }
		}
		//--------------------- end

		//--------------------- from num to day of week
		function dayString(num){
				if (num == "1") { return "mon" }
			else if (num == "2") { return "tue" }
			else if (num == "3") { return "wed" }
			else if (num == "4") { return "thu" }
			else if (num == "5") { return "fri" }
			else if (num == "6") { return "sat" }
			else if (num == "0") { return "sun" }
		}


	  /**
       * Load Google Calendar client library. List upcoming events
       * once client library is loaded.
       */
      function loadCalendarApi() {
        gapi.client.load('calendar', 'v3', insertEvent);
	  }
	  function update_google_events(){
		var today          = new Date(); //today date
		var emp_selected   = $("#_sbprok_employee option:selected").val();
		$.ajax({
			url: sbprokAjax.ajaxurl,
			type: 'POST',
			dataType: "json",
			data: { action : 'get_service_employees' },
			success: function (res) {
				$.each(res[1], function() {
					if(this.id == emp_selected){ 
					    emp_email_to_update = this.user_email;
		                gapi.client.load('calendar', 'v3', function () {
							var request = gapi.client.calendar.events.list({
								'calendarId' : emp_email_to_update,
								'timeZone' : 'IST', 
								'singleEvents': true, 
								'timeMin': today.toISOString(), //gathers only events not happened yet
								'orderBy': 'startTime'});
						request.execute(function (resp) {
							
								for (var i = 0; i < resp.items.length; i++) {
									var li              = document.createElement('li');
									var item            = resp.items[i];
									google_event_id          = item.htmlLink;
									google_event_id          = google_event_id.substr(google_event_id.indexOf("=") + 1);
									var classes         = [];
									var allDay          = item.start.date? true : false;
									var startDT         = allDay ? item.start.date : item.start.dateTime;
									var dateTime        = startDT.split("T"); //split date from time
									var date            = dateTime[0].split("-"); //split yyyy mm dd
									var startYear       = date[0];
									var startMonth      = monthString(date[1]);
									var startDay        = date[2];
									var startDateISO    = new Date(startMonth + " " + startDay + ", " + startYear + " 00:00:00");
									var startDayWeek    = dayString(startDateISO.getDay());
									if( allDay == true){ //change this to match your needs
										var str = [
										'<font size="4" face="courier">',
										startDayWeek, ' ',
										startMonth, ' ',
										startDay, ' ',
										startYear, '</font><font size="5" face="courier"> @ ', item.summary , '</font><br><br>'
										];
									}
									else{
										var time      = dateTime[1].split(":"); //split hh ss etc...
										var startHour = AmPm(time[0]);
										var AmPms     = startHour.substring(0, 2);
										var startMin  = time[1]+' '+AmPms;
										startHour     = startHour.replace(/[^0-9.]/g, "");
										var str = [ //change this to match your needs
											'<font size="4" face="courier">',
											startDayWeek, ' ',
											startMonth, ' ',
											startDay, ' ',
											startYear, ' - ',
											startHour, ':', startMin, '</font><font size="5" face="courier"> @ ', item.summary , '</font><br><br>'
											];
									}
									
									li.innerHTML = str.join('');
									li.setAttribute('class', classes.join(' '));
									 var e_date      = startMonth+' '+startDay+', '+startYear;
									 var e_time      = startHour+':'+startMin;
									 var e_service   = item.summary;
									 console.log(e_time);
									 if(e_date == date_to_update && e_time == time_to_update && e_service == service_to_update){
										 console.log(google_event_id);
										 var date_to_updates           = $('.datepic').val();
										 var time_to_updates           = $('.time_pic').val();
										 var service_to_updates        = $(".sbprok_srvice option:selected").text();
										 customer_to_update       = $(".cst option:selected").text();
										 start_date_to_update     = new Date(date_to_updates+' '+time_to_updates).toISOString();
										 var duration_time        = "01:00";
										 var duration_time_arr    = duration_time.split(':');
										     end_time_to_update   = moment(time_to_updates, "hh:mm A")
																	.add(duration_time_arr[0], 'hour')
																	.add(duration_time_arr[1], 'minutes')
																	.format('LT');
										 end_time_to_update       = new Date(date_to_updates+' '+end_time_to_update).toISOString();
										 if (google_event_id) {
											console.log(google_event_id);  
											var event = {
												'summary': service_to_updates,
												'location': ' ',
												'description': service_to_updates+' Service is booked for '+customer_to_update,
												'start': {
													'dateTime': start_date_to_update, 
													'timeZone': 'America/Los_Angeles'
												},
												'end': {
													'dateTime': end_time_to_update,
													'timeZone': 'America/Los_Angeles'
												},
											  
											  
												'reminders': {
												  'useDefault': false,
												  'overrides': [
													{'method': 'email', 'minutes': 24 * 60},
													{'method': 'popup', 'minutes': 10}
												  ]
												}
											  };  
								
											var request = gapi.client.calendar.events.update({
												'calendarId': emp_email_to_update,
												'eventId': google_event_id,
												'resource': event
											});
											console.log(request);
											request.execute(function(event) {
												console.log(event);
											});
										}
									 }
								}
								
							});
						  });
					   }
					});	
			}
		});
	  }
	  function makeApiCall() {
		var today = new Date(); //today date
		var emp_selected     = $("#_sbprok_employee option:selected").val();
		var service_selected = $(".sbprok_srvice option:selected").text();
		$.ajax({
			url: sbprokAjax.ajaxurl,
			type: 'POST',
			dataType: "json",
			data: { action : 'get_service_employees' },
			success: function (res) {
				$.each(res[1], function() {
					if(this.id == emp_selected){ 
						var emp_email = this.user_email;
		                gapi.client.load('calendar', 'v3', function () {
							var request = gapi.client.calendar.events.list({
								'calendarId' : emp_email,
								'timeZone' : 'IST', 
								'singleEvents': true, 
								'timeMin': today.toISOString(), //gathers only events not happened yet
								'orderBy': 'startTime'});
						request.execute(function (resp) {
								for (var i = 0; i < resp.items.length; i++) {
									var li           = document.createElement('li');
									var item         = resp.items[i];
									var classes      = [];
									var allDay       = item.start.date? true : false;
									var startDT      = allDay ? item.start.date : item.start.dateTime;
									var dateTime     = startDT.split("T"); //split date from time
									var date         = dateTime[0].split("-"); //split yyyy mm dd
									var startYear    = date[0];
									var startMonth   = monthString(date[1]);
									var startDay     = date[2];
									var startDateISO = new Date(startMonth + " " + startDay + ", " + startYear + " 00:00:00");
									var startDayWeek = dayString(startDateISO.getDay());
									if( allDay == true){ //change this to match your needs
										var str = [
										'<font size="4" face="courier">',
										startDayWeek, ' ',
										startMonth, ' ',
										startDay, ' ',
										startYear, '</font><font size="5" face="courier"> @ ', item.summary , '</font><br><br>'
										];
									}
									else{
										var time      = dateTime[1].split(":"); //split hh ss etc...
										var startHour = AmPm(time[0]);
										var AmPms     = startHour.substring(0, 2);
										var startMin  = time[1]+' '+AmPms;
										startHour     = startHour.replace(/[^0-9.]/g, "");
										var str = [ //change this to match your needs
											'<font size="4" face="courier">',
											startDayWeek, ' ',
											startMonth, ' ',
											startDay, ' ',
											startYear, ' - ',
											startHour, ':', startMin, '</font><font size="5" face="courier"> @ ', item.summary , '</font><br><br>'
											];
									}
									
									li.innerHTML = str.join('');
									li.setAttribute('class', classes.join(' '));
									var x = [];
									x['date']     = startMonth+' '+startDay+', '+startYear;
									x['time']     = startHour+':'+startMin;
									x['service']  = item.summary;
									z.push(x);
									var x = [];
								}
								$('input[data-sbprok="datepicker"]').datepicker({
									onSelect: function(dateText, inst) {
									employee_calendar_date = dateText;
									// $.each(z, function() {
									//   if(this.date == dateText){ 	 
									// 	  $('.errorMsg').html('<span>We are unavailable at: '+ this.date+ '</span>');
									// 	  $('.errorMsg').css({"color":"#a94442","background-color": "#f2dede", "border-color": "#ebccd1","width": "250px", "height": "35px", "text-align": "center"});
									// 	  return false;
									//   }else{
									// 	  $('.errorMsg').html("");
									// 	  $('.errorMsg').css({"color":" ","background-color": " ", "border-color": " "});
									//   }
									// });
								  },
								  changeMonth: true,
								  changeYear: true,
								  minDate:new Date()
							  });

							/**** timepicker */
			$('input[data-sbprok="timepicker"]').timepicker({	
				change: function(dateText, inst){
					var time = $(this).val();
					$.each(z, function() {
						if (this.date == employee_calendar_date && time == this.time && this.service == service_selected) {
							$('.errorMsg').html('<span>This time for '+this.date+' is already booked.Please choose different time slot. </span>');
							$('.errorMsg').css({"color":"#a94442","background-color": "#f2dede", "border-color": "#ebccd1","width": "300px", "height": "35px", "text-align": "center"});
							$('input[data-sbprok="timepicker"]').val('');
							exit();
						}else{
							$('.errorMsg').css("display","none");
						}
					});
							
							// if(date == undefined){
							//    date = $(".datepic").val();
							//  }		
							// $('.errorMsgtime').css("display","none");
							// $.each(date_time_array, function(index, value) {
							// 	if (time == value[date]) {
							// 		$('.errorMsgtime').html('<span>This time is already booked.Please choose another time slot. </span>');
							// 		$('.errorMsgtime').css({"display":"block","color":"#a94442","background-color": "#f2dede", "border-color": "#ebccd1","width": "250px", "height": "35px", "text-align": "center"});
							// 		$('input[data-sbprok="timepicker"]').val('');
							// 	}
							// }); 
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
							});
						  });
					   }
					});	
			}
		});		
		
		};	
		
			$(".sync").click(function() {	
			    date_to_insert           = $('.datepic').val();
				var time                 = $('.time_pic').val();	
				service_to_insert        = $("#_sbprok_services option:selected").text();
				var employee_id          = $("#_sbprok_employee option:selected").val();
				var employee_name        = $("#_sbprok_employee option:selected").text();
				customer_to_insert       = $(".cst option:selected").text();
				start_date_to_insert     = new Date(date_to_insert+' '+time).toISOString();
				var variable             = "01:00";
				var time_arr             = variable.split(':');
				var end_time = moment(time, "hh:mm A")
								.add(time_arr[0], 'hour')
								.add(time_arr[1], 'minutes')
								.format('LT');
				end_date_to_insert = new Date(date_to_insert+' '+end_time).toISOString();
				$.ajax({
					url: sbprokAjax.ajaxurl,
					type: 'POST',
					dataType: "json",
					data: { action : 'get_service_employees' },
					success: function (res) {
						$.each(res[1], function() {
							if(this.id == employee_id){ 
							 emp_email_to_insert = this.user_email;
							}
						})
					}
				});
				checkAuth();
		});
		
		$(".update_sync").click(function() {
			key_for_update = "enable";
			checkAuth();	
	});	

		function insertEvent() {
			var event = {
									'summary': service_to_insert,
									'description': service_to_insert+' Service is booked for '+customer_to_insert,
									'start': {
										'dateTime': start_date_to_insert,
										'timeZone': 'America/Los_Angeles'
									},
									'end': {
										'dateTime': end_date_to_insert,
										'timeZone': 'America/Los_Angeles'
									},
									'recurrence': [
										'RRULE:FREQ=DAILY;COUNT=1'
									],
									'attendees': [
										{'email': 'abc@gmail.com'},
										{'email': 'def@gmail.com'}
									],
									'reminders': {
										'useDefault': false,
										'overrides': [
											{'method': 'email', 'minutes': 24 * 60},
											{'method': 'popup', 'minutes': 10}
										]
									}
								};
	
			var request = gapi.client.calendar.events.insert({
				'calendarId': emp_email_to_insert,
				'resource': event
			});
			
			request.execute(function(event) {
				$( "<strong>Success! Booking Added To Google Calendar.</strong>" ).insertAfter( ".sync" );
			});
		
		}

			$( ".sbprok_service_cat" ).change(function() {
				service_cat();
			 });

			$( ".sbprok_srvice " ).change(function() {
				service_emp();
			 });
			 $( ".sbprok_employees" ).change(function() {
				checkAuth();
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
				},
				{
					googleCalendarId: 'testlast17316@gmail.com'
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
			  eventClick: function(info) {
				var formDate   = moment(info.event.start).format('YYYY-MM-DD');
									var start_time = moment(info.event.start).format('hh:mm a');
									var end_time   = moment(info.event.end).format('hh:mm a');
									/*Open Sweet Alert*/
										swal({
										  title: info.event.title,
										  text: "Date : "+formDate+" \n"+
										        "Start From : "+start_time+" To "+end_time,
										  icon: "success",
										});
										info.jsEvent.preventDefault();
				// // opens events in a popup window
				// window.open(arg.event.url, 'gcalevent', 'width=700,height=600');
		
				// // prevent browser from visiting event's URL in the current tab
				
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
	
	