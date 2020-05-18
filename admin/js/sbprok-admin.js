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
		$('input[data-sbprok="datepicker"]').datepicker();
		$('input[data-sbprok="timepicker"]').timepicker();
		/**** select2 */
		$('select[data-sbprok="select2"]').select2();

		// Booking meta ajax actions - Get Services

		$( ".sbprok_service_cat" ).change(function() {
				var service_cat_id  = $(this).children("option:selected").val();
				$('.sbprok_srvice').empty().append('<option>Select Service</option>');
				$('.sbprok_employees').empty().append('<option>Select Employee</option>');	
			$.ajax({
				url: sbprokAjax.ajaxurl,
				type: 'POST',
				dataType: "json",
				data: { action : 'get_cat_service',cat_id:service_cat_id },
				success: function (res) {
					$.each(res, function(index, value) {
							$('.sbprok_srvice').append($( '<option value="'+ value.ID +'" >'+ value.post_title+'</option>'));
					});	 
				  }
			  });
		});
		// Get Employees
		$( ".sbprok_srvice " ).change(function() {
				var service_selected = $(".sbprok_srvice option:selected").val();
				$('.sbprok_employees').empty().append('<option>Select Employee</option>');
				$.ajax({
					url: sbprokAjax.ajaxurl,
					type: 'POST',
					dataType: "json",
					data: { action : 'get_service_employees', service_id: service_selected },
					success: function (res) {
						//console.log(res);
							$.each(res, function(index, values) {
								$('.sbprok_employees').append($( '<option value="'+ values.id +'" >'+ values.display_name+'</option>'));
											
							});
					  }
				  });
		});
	});
	
})( jQuery );
