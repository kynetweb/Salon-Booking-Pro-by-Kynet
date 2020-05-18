<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Sbprok
 * @subpackage Sbprok/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sbprok
 * @subpackage Sbprok/admin
 * @author     Kynet Web Solutiosn <contact@kynetweb.com>
 */
class Sbprok_Google_Calendar {

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
	 * Calendar Scope
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
    private $scopes;

     /**
	 * Google Client
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
    private $googleClient;

     /**
	 * Calendar Service
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
    private $calendarService;
    
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/library/google-api-v2/vendor/autoload.php';
		$this->plugin_name = $plugin_name;
        $this->version = $version;
        putenv('GOOGLE_APPLICATION_CREDENTIALS='.__DIR__.'/library/google-api-v2/calender-project-274606-c0b516cd4b47.json');
        $this->googleClient = new Google_Client();
        $this->googleClient->setApplicationName("Google Calendar PHP API");
        $this->googleClient->useApplicationDefaultCredentials();
        $this->googleClient->setScopes(['https://www.googleapis.com/auth/calendar.events']);
        $this->calendarService = new Google_Service_Calendar($this->googleClient);
    }
    
    public function create_event($post_id,$emp_calendar_id,$start_date,$end_time,$service,$customer_name) {
        $event = new Google_Service_Calendar_Event(array(
            'summary' => $service.': '.$customer_name,
            'start' => array(
                'dateTime' => $start_date  
            ),
            'end' => array(
                'dateTime' => $end_time
            ),
        ));
        $id = $emp_calendar_id;

        try {
            $event = $this->calendarService->events->insert($id, $event);
            if(!empty($post_id)){
                update_post_meta( $post_id, '_sbprok_booking_event_id', $event->id );
            }
        } catch (Google_Service_Exception $e) {          
            print_r($e->getErrors()[0]['message']);   
        }

    }
 
    public function update_event($post_id,$emp_calendar_id,$event_id,$start_date,$end_time,$service,$customer_name) {
        $id    = $emp_calendar_id;
        $event = $this->calendarService->events->get($id, $event_id);

        $event->setSummary($service.': '.$customer_name);
        $start = new Google_Service_Calendar_EventDateTime();
        $start->setDateTime($start_date);
        $event->setStart($start);
        $end = new Google_Service_Calendar_EventDateTime();
        $end->setDateTime($end_time);
        $event->setEnd($end);
        try{
            $updatedEvent = $this->calendarService->events->update($id, $event->getId(), $event);
        } catch (Google_Service_Exception $e) {
           // $e->getMessage();           
            print_r($e->getErrors()[0]['message']);   
        }
        

    }
	

}
