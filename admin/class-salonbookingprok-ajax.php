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
		$this->version     = $version;
		$this->load_dependencies();

	}
	private function load_dependencies() {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/function.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . '/vendor/autoload.php';
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
			$service_idds['posts_id']   = $ajaxpost->ID;
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
		$services  = array(
						'post_type' => array('sbprok_appoints'),
						'posts_per_page' => 40,
						'nopaging' => true,
						'order' => 'DESC',
						'orderby' => 'date'
				     );
		$ajaxposts = get_posts($services);

		foreach($ajaxposts as $ajaxpost){
			$date_time      = get_post_meta( $ajaxpost->ID, "_sbprok_appt_schedule", true );
				foreach($date_time as $date_times){
					$date         = $date_time["_date"];
					$time[$date]  = $date_time["_time"];
				}
				$date_time_array[]   = $time;
				$time                = (array) null;
			}
			$date_array  = [3, 4];
		   
		//    $ccc = calculatetime();
		   echo json_encode(array($date_time_array,$date_array));
			exit; 
		}

	function get_service_employees(){
		$all_users = get_users( array( 
            'fields' => array( 'display_name','id' ),
            'role__in'     => array('salonbookingprok_employee'),
             )
        );
		$services   = array(
							'post_type' => array('sbprok_services'),
							'posts_per_page' => 40,
							'nopaging' => true,
							'order' => 'DESC',
							'orderby' => 'date'
						  );
		$ajaxposts  = get_posts( $services );
		foreach($ajaxposts as $ajaxpost){
			$employees  = get_post_meta( $ajaxpost->ID, "_sbprok_employees", true );
			foreach($employees as $employee){
				//$user = get_userdata($employee );
				$x[$ajaxpost->post_title] = $employee;
				$a[] = $x;
			    $x =  (array) null;
			}
			
		 }
		 echo json_encode(array($a,$all_users));
			exit; 
		}
		function get_ajax_data_requests(){
			    $posts_id   = $_POST['posts_id'];
				$title      = $_POST['title'];
				$start      = $_POST['start_date'];
				$time       = $_POST['start_time'];
				$details = array(
					'_date' => !empty($start ) ? $start : '',
					'_time' => !empty($time ) ? $time : '',
				);
				update_post_meta($posts_id, '_sbprok_appt_schedule', $details);
		}
		function add_google_calendar_events(){
			$client = getClient();
			// $service = new Google_Service_Calendar($client);
			// $event = new Google_Service_Calendar_Event(array(
			// 	'summary' => 'Google I/O 2020',
			// 	'location' => '800 Howard St., San Francisco, C 94103',
			// 	'description' => 'A chance to hear more about Google\'s developer products.',
			// 	'start' => array(
			// 		'dateTime' => '2020-04-28T09:00:00-07:00',
			// 		'timeZone' => 'America/Los_Angeles'
			// 	),
			// 	'end' => array(
			// 		'dateTime' => '2020-04-28T17:00:00-07:00',
			// 		'timeZone' => 'America/Los_Angeles'
			// 	),
			// 	'recurrence' => array(
			// 		'RRULE:FREQ=DAILY;COUNT=2'
			// 	),
			// 	'attendees' => array(
			// 		array(
			// 			'email' => 'lpage@example.com'
			// 		),
			// 		array(
			// 			'email' => 'sbrin@example.com'
			// 		)
			// 	),
			// 	'reminders' => array(
			// 		'useDefault' => FALSE,
			// 		'overrides' => array(
			// 			array(
			// 				'method' => 'email',
			// 				'minutes' => 24 * 60
			// 			),
			// 			array(
			// 				'method' => 'popup',
			// 				'minutes' => 10
			// 			)
			// 		)
			// 	)
			// ));
			
			// $calendarId = 'primary';
			// $event      = $service->events->update($calendarId, $event);
			echo json_encode($client);
			exit;
		}
}