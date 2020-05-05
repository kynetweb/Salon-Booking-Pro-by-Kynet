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
										if(service_cat_sel != ' '){
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
			var service_selected = $("#_sbprok_services").select2('data');
			var service_sel;
			$.each(service_selected, function() {
				service_sel = this.text;
			});
			service_emp(service_sel);	
				var service_cat_sel = $("#_sbprok_services option:selected").val();
			service_cat(service_cat_sel);	
		});
		
			  
		 
		$(document).ready(function(){
		 
			$(".sync").click(function() {	
				var date           = $('.datepic').val();
				var time           = $('.time_pic').val();	
				var service        = $("#_sbprok_services option:selected").text();
				var employee_id    = $("#_sbprok_employee option:selected").val();
				var employee_name  = $("#_sbprok_employee option:selected").text();
				var customer       = $(".cst option:selected").text();
				var start_date     = new Date(date+' '+time).toISOString();
				var variable = "01:00";
				var time_arr = variable.split(':');
				var end_time = moment(time, "hh:mm A")
								.add(time_arr[0], 'hour')
								.add(time_arr[1], 'minutes')
								.format('LT');
				var end_date = new Date(date+' '+end_time).toISOString();
				
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
          loadCalendarApi();
        } 
      }
 
      /**
       * Initiate auth flow in response to user clicking authorize button.
       *
       * @param {Event} event Button click event.
       */
	  	   checkAuth();
 
      /**
       * Load Google Calendar client library. List upcoming events
       * once client library is loaded.
       */
      function loadCalendarApi() {
        gapi.client.load('calendar', 'v3', insertEvent);
      }
   
      function insertEvent() {
				var event = {
										'summary': 'Customer: '+customer,
										'description': 'A chance to hear more about Google\'s developer products.',
										'start': {
											'dateTime': start_date,
											'timeZone': 'America/Los_Angeles'
										},
										'end': {
											'dateTime': end_date,
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
					'calendarId': 'smartwork1242@gmail.com',
					'resource': event
				});
				
				request.execute(function(event) {
					$( "<strong>Success! Booking Added To Google Calendar.</strong>" ).insertAfter( ".sync" );
				});
			
			}
		});	

			$( "#_sbprok_service_cat" ).change(function() {
				service_cat();
			 });

			$( "#_sbprok_services" ).change(function() {
				service_emp();
			 });
			 $( ".sbprok_employees" ).change(function() {
				var emp_selected = $("#_sbprok_employee option:selected").val();
				$.ajax({
					url: sbprokAjax.ajaxurl,
					type: 'POST',
					dataType: "json",
					data: { action : 'get_service_employees' },
					success: function (res) {
						$.each(res[1], function() {
							if(this.id == emp_selected){ 
								var email = this.user_email.substr(0, this.user_email.indexOf('@'));
								$('.show_calendar').append($('<iframe id="iFrameID" src="https://calendar.google.com/calendar/embed?src='+email+'%40gmail.com&ctz=Asia%2FKolkata" style="border: 0" width="700" height="400" frameborder="0" scrolling="no"></iframe>'));
								$("#iFrameID").contents().find("head")[0].appendChild($('#cssID')[0]);
							}
							});	
						}
				});		
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
					console.log(res);
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
	
	