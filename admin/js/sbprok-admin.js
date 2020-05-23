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
			  },
			  beforeShowDay:function (date) {
				return [exclude_days.indexOf(date.getDay()) > -1];
			  },
			  changeMonth: true,
			  changeYear: true,
			  minDate:new Date()
		});
		
		$('input[data-sbprok="timepicker"]').timepicker({
			change: function(dateText, inst){
				var time             = $(this).val();
				var service_selected = $(".sbprok_srvice option:selected").val();
				
				$.ajax({
					url: sbprokAjax.ajaxurl,
					type: 'POST',
					dataType: "json",
					data: { action : 'get_posts_metadata'},
					success: function (res) {
						$.each(res, function(index, value) {
							var service_id = index.substr(index.indexOf("_") + 1);
							if(employee_calendar_date == value._date && time == value._time && service_id == service_selected) {
								$('.errorMsg').html('<span>This time for '+employee_calendar_date+' is already booked.Please choose different time slot. </span>');
								$('.errorMsg').css({"color":"#a94442","background-color": "#f2dede", "border-color": "#ebccd1","width": "300px", "height": "35px", "text-align": "center"});
								$('input[data-sbprok="timepicker"]').val('');
								return false;
							}else{
								$('.errorMsg').html(' ');
								$('.errorMsg').css({"color":"#ffffff","background-color": "#ffffff", "border-color": "#ffffff"});
							}
						});
						
					  }
				  });
		
					},
			timeFormat: 'hh:mm p',
			interval: 30,
			minTime: '09',
			maxTime: '06:00pm',
			startTime: '09:00',
			dynamic: false,
			dropdown: true,
			scrollbar: true
		});
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
					$('.prop_loadmore').text( 'Loading...' ); 
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
						$('.loadmore').text( 'Loading...' ); 
					},
					success: function (res) {
							$.each(res, function(index, values) {
								$('.sbprok_employees').append($( '<option value="'+ values.id +'" >'+ values.display_name+'</option>'));
								$('.loadmore').text(' '); 	
							});
					  }
				  });
		});
		//DataTables
		$('#sbprok_emp_table').DataTable();
		
	});

	
	
})( jQuery );
