<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Salonbookingprok 
 * @subpackage Salonbookingprok/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Salonbookingprok
 * @subpackage Salonbookingprok/admin
 * @author     kynet Web <contact@kynetweb.com>
 */
class Salonbookingprok_Ajax {
    /**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
    /**
	 * get bookings
	 *
	 * @since    1.0.0
	 */
    function get_bookings() {
		$services      = array(
							'post_type' => array('sbprok_appoints'),
							'posts_per_page' => 40,
							'nopaging' => true,
							'order' => 'DESC',
							'orderby' => 'date'
						 );
		
		$ajaxposts     = get_posts( $services );
		$array         = array(
							'ID'=>$name[0],
							'post_type'=>'sbprok_services'
							);
		$service_names = get_posts($array);

		foreach($ajaxposts as $ajaxpost){
			$service_id  = get_post_meta( $ajaxpost->ID, "_sbprok_services", true );
			foreach($service_id as $service_ids){
				foreach($service_names as $service_name){
					if($service_ids == $service_name->ID){
					  $titles[]          = $service_name->post_title;
					  $durations         = get_post_meta( $service_name->ID, "_sbprok_service_details", true );
					  $final_durations[] = $durations["_duration"];
					  
					}
				}
			}
			$service_idds['name']       = implode(",",$titles);
			$service_idds['id']         = implode(",",$service_id);
			$service_idds['duration']   = implode(",",$final_durations);
			$date_time                  = get_post_meta( $ajaxpost->ID, "_sbprok_appt_schedule", true );
			$schedule[]                 = array_merge($service_idds,$date_time);
			$titles                     = (array) null;
			$durations                  = (array) null;
			$final_durations            = (array) null;
		}
			echo json_encode( array($schedule,$service_id) );
			exit; 
		}

		function get_availbility() {
			$date_array = ["April 10, 2020", "April 18, 2020", "April 22, 2020", "April 28, 2020"];
			echo json_encode($date_array);
			exit; 
		 
		}
}