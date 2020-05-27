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

class Sbprok_Ajax {
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
	public function __construct( $plugin_name, $version, $loader ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->load_dependencies();
		$this->load_Helper();
        $loader->add_action( 'wp_ajax_get_bookings',$this, 'get_bookings');
		$loader->add_action( 'wp_ajax_get_availbility',$this, 'get_availbility');
		$loader->add_action( 'wp_ajax_add_google_calendar_events',$this, 'add_google_calendar_events');
		$loader->add_action( 'wp_ajax_get_service_employees',$this, 'get_service_employees');
		$loader->add_action( 'wp_ajax_get_cat_service',$this, 'get_cat_service');
		$loader->add_action( 'wp_ajax_get_posts_metadata',$this, 'get_posts_metadata');
		$loader->add_action( 'wp_ajax_get_ajax_data_requests',$this, 'get_ajax_data_requests');
		$loader->add_action( 'wp_ajax_get_disabled_days',$this, 'get_disabled_days');
		
	}
	private function load_dependencies() {
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/functions.php';
	}

	/**
	 * Load the required Helper's for this plugin.
	 * @since    1.0.0
	 */
	private function load_Helper() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-google-calendar.php';

		$this->google_calendar = new Sbprok_Google_Calendar($this->plugin_name, $this->version);

	}

    /**
	 * get bookings
	 *
	 * @since    1.0.0
	 */
    function get_bookings() {
		$services      = array(
							'post_type' => array('sbprok_bookings'),
							'posts_per_page' => 40,
							'nopaging' => true,
							'order' => 'DESC',
							'orderby' => 'date'
						 );
		
		$ajaxposts     = get_posts( $services );
		$array         = array(
							'post_type'=>'sbprok_services'
							);
		$service_names = get_posts($array);
      
		foreach($ajaxposts as $ajaxpost){
		 $service_id  = get_post_meta( $ajaxpost->ID, "_sbprok_services", true );
				foreach($service_names as $service_name){
					if($service_id == $service_name->ID){
					  $titles[]          = $service_name->post_title;
					  $durations         = get_post_meta( $service_name->ID, "_sbprok_service_details", true );
					  $final_durations[] = $durations["_duration"];
					}
				}

			$service_idds['name']       = implode(",",$titles);
			$service_idds['id']         = $service_id;
			$service_idds['duration']   = implode(",",$final_durations);
			$service_idds['posts_id']   = $ajaxpost->ID;
			$date_time                  = get_post_meta( $ajaxpost->ID, "_sbprok_booking_schedule", true );
			$customer_name              = get_userdata($date_time['_customer']);
			$date_time['_customer']     = $customer_name->display_name;
			$schedule[]                 = array_merge($service_idds,$date_time);
			$titles                     = (array) null;
			$durations                  = (array) null;
			$final_durations            = (array) null;
		}
			echo json_encode( array($schedule,$service_id) );
			exit; 
		}
    /**
	 * get availbility
	 *
	 * @since    1.0.0
	 */
	function get_availbility() {
		$services  = array(
						'post_type' => array('sbprok_appoints'),
						'posts_per_page' => 40,
						'nopaging' => true,
						'order' => 'DESC',
						'orderby' => 'date'
				     );
		$ajaxposts = get_posts($services);

		foreach($ajaxposts as $ajaxpost){
			$date_time      = get_post_meta( $ajaxpost->ID, "_sbprok_booking_schedule", true );
				foreach($date_time as $date_times){
					$date         = $date_time["_date"];
					$time[$date]  = $date_time["_time"];
				}
				$date_time_array[]   = $time;
				$time                = (array) null;
			}
			$date_array  = [3, 4];
			
		   echo json_encode(array($date_time_array,$date_array));
			exit; 
		}
    /**
	 * get employees related to a service
	 *
	 * @since    1.0.0
	 */
	function get_service_employees(){
		
        if(!empty($_POST['service_id'])) {
            $employees  = get_post_meta( $_POST['service_id'], "_sbprok_employees", true );
            if(count($employees) > 0) {
                $all_users = get_users( array( 
                    'fields' => array( 'display_name','id','user_email' ),
                    'role__in'     => array('sbprok_employee'),
                    'include'       =>  $employees
                     )
                );
                echo json_encode($all_users);
                exit;
            }
		 
        }
	} 

	/**
	* get services from category
	*
	* @since    1.0.0
	*/
	function get_posts_metadata(){
		$posts = get_posts([
			'post_type' => 'sbprok_bookings',
			'post_status' => 'publish',
			'numberposts' => -1
		  ]);
		  foreach($posts as $p){
			$service                      = get_post_meta($p->ID,"_sbprok_services",true);
			$service_post                 = get_post($service);  
			$service_details              = get_post_meta($service_post->ID,"_sbprok_service_details",true);
			$booking_details              = get_post_meta($p->ID,"_sbprok_booking_schedule",true);
			$end_time                     = calculate_end_time($booking_details['_date'].$booking_details['_time'], $service_details['_duration']);
			$long[$p->ID.'_'.$service]    = $booking_details;
			$long['end_time']             = date('h:ia', strtotime($end_time));
		}
		echo json_encode($long);
			exit;
	}

    /**
	 * get services from category
	 *
	 * @since    1.0.0
	 */
	function get_cat_service() {
		if($_POST['cat_id'] != ''){
			$args = [
				'post_type' => 'sbprok_services',
				'tax_query' => [
					[
						'taxonomy' => 'sbprok_category',
						'terms' => $_POST['cat_id'],
						'include_children' => true // Remove if you need posts from term 7 child terms
					],
				],
			];
			$services = get_posts($args);
			echo json_encode($services);
			exit;
		 } 
	}

	/**
	 * get_ajax_data_requests
	 *
	 * @since    1.0.0
	 */
    function get_ajax_data_requests(){
			$posts_id        = $_POST['posts_id'];
			$title           = $_POST['title'];
			$start           = $_POST['start_date'];
			$time            = $_POST['start_time'];
			$end_time        = $_POST['end_time'];
		    $details         = array(
									'_date' => !empty($start ) ? $start : '',
									'_time' => !empty($time ) ? $time : '',
								);
			$start_date      = datetime_conversion($start.$time);
			$end_time        = calculate_end_time($start.$time);
			$post_meta       = get_post_meta( $posts_id);
			$employee_meta   = get_user_meta($post_meta['_sbprok_employee'][0]);
			$emp_calendar_id = $employee_meta['calendar_id'][0];
			$event_id        = get_post_meta($posts_id, '_sbprok_booking_event_id', true);
			$update_data     = array(
									'calendar_id' => $emp_calendar_id,
									'event_id' => $event_id,
									'summary' => $title,
									'start_date' => $start_date,  
									'end_date' => $end_time
									);

			$this->google_calendar->update_event($update_data);		
		    update_post_meta($posts_id, '_sbprok_booking_schedule', $details);
		}

	/**
	 * get_disabled_days
	 *
	 * @since    1.0.0
	 */	

	function get_disabled_days(){
		$week_array    = array("monday", "tuesday", "wednesday","thursday","friday","saturday","sunday");
		$availble_days = get_option('sbprok_availbility');
		$availble_days = array_keys ($availble_days);
		$exclude       = array_diff($week_array,$availble_days);
		$days = [];
		foreach($exclude as $val){
			if ($val == 'sunday'){
			 $days[] = 0;
			}elseif ($val == 'monday'){
			 $days[] = 1;
			}elseif ($val == 'tuesday'){
		     $days[] = 2;
			}elseif ($val == 'wednesday'){
		     $days[] = 3;
			}elseif ($val == 'thursday'){
			 $days[] = 4;
			}elseif ($val == 'friday'){
			 $days[] = 5;
			}elseif ($val == 'saturday'){
			 $days[] = 6;
			} 
		  }
		echo json_encode($days);
		exit;
		}
	
}