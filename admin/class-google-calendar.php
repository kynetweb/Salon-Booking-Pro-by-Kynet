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
    
    public function create_event($post_id,$emp_calendar_id,$event) {
        try {
            $event = $this->calendarService->events->insert($emp_calendar_id, $event);
            if(!empty($post_id)){
                update_post_meta( $post_id, '_sbprok_booking_event_id', $event->id );
            }
        } catch (Google_Service_Exception $e) {
           // print_r($e->getErrors()[0]['message']); 
        }

    }
 
    public function update_event($update_data) {
        $event = $this->calendarService->events->get($update_data['calendar_id'], $update_data['event_id']);

        $event->setSummary($update_data['summary']);
        $start = new Google_Service_Calendar_EventDateTime();
        $start->setDateTime($update_data['start_date']);
        $event->setStart($start);
        $end = new Google_Service_Calendar_EventDateTime();
        $end->setDateTime($update_data['end_date']);
        $event->setEnd($end);
        try{
            $updatedEvent = $this->calendarService->events->update($update_data['calendar_id'], $event->getId(), $event);
        } catch (Google_Service_Exception $e) {    
            // print_r($e->getErrors()[0]['message']);    
        }
        

    }

    public function delete_event($emp_calendar_id,$event_id) {
        try {
            $event = $this->calendarService->events->delete($emp_calendar_id, $event_id);
        } catch (Google_Service_Exception $e) {
          //  print_r($e->getErrors()[0]['message']); 
        }
    }
}
