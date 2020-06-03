(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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

	$(document).on('click','.sbprok-inner-box', function(e){
		$(".common_1").addClass("sbprok-hide");
		if($(".Sub-Category").length){
		 $(".Sub-Category").removeClass("sbprok-hide");
		}else{
		 $(".common_2").removeClass("sbprok-hide");
		}
		
	});  

	$(document).on('click','.Sub-Category', function(e){
		$(".Sub-Category").addClass("sbprok-hide");
		$(".sub_common_2").removeClass("sbprok-hide");
	});

	$(document).on('click','.sbprok-inner-box-2', function(e){
		var id = $(this).find('.service_id').val();
		$(".service_list").addClass("sbprok-hide");
		$(".common_"+id).removeClass("sbprok-hide");
	});
	$(document).on('click','#sbprok-back', function(e){
		$(".common_2").addClass("sbprok-hide");
		$(".common_1").removeClass("sbprok-hide");
	});
	

})( jQuery );
