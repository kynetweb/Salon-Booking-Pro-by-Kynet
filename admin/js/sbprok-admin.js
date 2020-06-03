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

	$(document).ready(function(){
		var exclude_days;
		var employee_calendar_date;
		var time_ranges = [];

		/**** select2 */
		$('select[data-sbprok="select2"]').select2();

		// Booking meta ajax actions - Get Services
		$( ".sbprok_service_cat" ).change(function() {
				var service_cat_id  = $(this).children("option:selected").val();
				$('.sbprok_srvice').empty().append('<option value="">Select Service</option>');
				$('.sbprok_employees').empty().append('<option value="">Select Employee</option>');	
			$.ajax({
				url: sbprokAjax.ajaxurl,
				type: 'POST',
				dataType: "json",
				data: { action : 'get_cat_service',cat_id:service_cat_id },
				beforeSend : function ( xhr ) {
				  setTimeout(function(){ $('.prop_loadmore').text( 'Loading...' ); }, 2000); 	 
				},
				success: function (res) {
					$.each(res, function(index, value) {
							$('.sbprok_srvice').append($( '<option value="'+ value.ID +'" >'+ value.post_title+'</option>'));
					        $('.prop_loadmore').text(' '); 
						});	 
				  }
			  });
		});
		// Get Employees
		$(".sbprok_srvice").change(function() {
				var service_selected = $(".sbprok_srvice option:selected").val();
				$('.sbprok_employees').empty().append('<option value="">Select Employee</option>');
				$.ajax({
					url: sbprokAjax.ajaxurl,
					type: 'POST',
					dataType: "json",
					data: { action : 'get_service_employees', service_id: service_selected },
					beforeSend : function ( xhr ) {
						setTimeout(function(){ $('.loadmore').text( 'Loading...' ); }, 2000); 
					},
					success: function (res) {
							$.each(res, function(index, values) {
								$('.sbprok_employees').append($( '<option value="'+ values.id +'" >'+ values.display_name+'</option>'));
								$('.loadmore').text(' '); 	
							});
					  }
				  });
		});

		$.ajax({
			url: sbprokAjax.ajaxurl,
			type: 'POST',
			dataType: "json",
			data: { action : 'get_disabled_days'},
			success: function (res) {
				exclude_days = res;	
			  }
		  });
		
		$('input[data-sbprok="datepicker"]').datepicker({
            onSelect: function(dateText, inst) {
				employee_calendar_date = dateText;
				var service_selected = $(".sbprok_srvice option:selected").val();
				var selected_emp     = $('.sbprok_employees option:selected').val();
				$.ajax({
					url: sbprokAjax.ajaxurl,
					type: 'POST',
					dataType: "json",
					data: { action : 'get_posts_metadata',employee_calendar_date:employee_calendar_date},
					success: function (res) {
						$.each(res, function(index, value) { 
							var service_id  = index.substr(index.indexOf("_") + 1);
							if(employee_calendar_date == value._date && service_id == service_selected) {
								time_ranges.push([value._time, value.end_time]);
							}
						});
						
						$('input[data-sbprok="timepicker"]').timepicker({
							'minTime': '9:00am',
							'maxTime': '6:00pm',
							'step': function(i) {
								return 60;
							},
							'disableTimeRanges' :time_ranges
						});	
					  }
				  });
				  
		
			  },
			  beforeShowDay:function (date) {
				return [exclude_days.indexOf(date.getDay()) == -1];
			  },
			  changeMonth: true,
			  changeYear: true,
			  minDate:new Date()
		});
		
		//DataTables
		$('#sbprok_emp_table').DataTable();
		
	});

	
	
})( jQuery );
