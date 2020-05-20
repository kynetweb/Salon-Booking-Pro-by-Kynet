<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    sbprok 
 * @subpackage sbprok/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    sbprok
 * @subpackage sbprok/admin
 * @author     kynet Web <contact@kynetweb.com>
 */
class Sbprok_Posttypes {

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
	 * Google calendar
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $google_calendar;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $loader ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->load_Helper();
		$loader->add_action( 'init', $this, 'register_posttypes' );
		$loader->add_action( 'save_post', $this,'save_booking_to_google');
		$loader->add_filter( 'the_title', $this, 'name_post_title', 10, 2 );
		?>
		
		<?php
	}

	 /**
	 * Load the required Helper's for this plugin.
	 *
	 *
	 * @since    1.0.0
	 */
	private function load_Helper() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-google-calendar.php';

		$this->google_calendar = new Sbprok_Google_Calendar($this->plugin_name, $this->version);

	}
	
	public function save_booking_to_google($post_id) {
		
		if ( 'sbprok_bookings' == get_post_type() ) 
		{
			if(isset($_POST['_sbprok_employee'])){
				$employee_meta   = get_user_meta($_POST['_sbprok_employee']);
			    $emp_calendar_id = $employee_meta['calendar_id'][0];
			}

			if($emp_calendar_id != ''){
				if ( isset( $_POST['_sbprok_services']) ){
					$service_post    = get_post($_POST['_sbprok_services']);
					$service         = $service_post->post_title;
				}
				if (isset($_POST['_sbprok_booking_schedule']['_customer'])) {
					$customer_meta   = get_user_meta($_POST['_sbprok_booking_schedule']['_customer']);
					$customer_name   = $customer_meta['first_name'][0].' '.$customer_meta['last_name'][0];
				}
				if(isset($_POST['_sbprok_booking_schedule']['_date']) && isset($_POST['_sbprok_booking_schedule']['_time'])){ 
					$start_date      = datetime_conversion($_POST['_sbprok_booking_schedule']['_date'].$_POST['_sbprok_booking_schedule']['_time']);
					$end_time        = calculate_end_time($_POST['_sbprok_booking_schedule']['_date'].$_POST['_sbprok_booking_schedule']['_time']);
				}

				$post_meta       = get_post_meta($post_id);
				$event_id        = get_post_meta($post_id, '_sbprok_booking_event_id', true);
				$event           = new Google_Service_Calendar_Event(array(
									'summary' => $service.': '.$customer_name,
									'start' => array(
										'dateTime' => $start_date  
									),
									'end' => array(
										'dateTime' => $end_time
									),
								 ));
				$update_data    = array(
										'calendar_id' => $emp_calendar_id,
										'event_id' => $event_id,
										'summary' => $service.': '.$customer_name,
										'start_date' => $start_date,  
										'end_date' => $end_time
										);
            if(!empty($event_id)){
			    $this->google_calendar->update_event($update_data); 
			}else{
				$return = $this->google_calendar->create_event($post_id,$emp_calendar_id,$event);
				if( is_wp_error( $return ) ) {
					echo $return->get_error_message();
					exit();
				}
			}

			}
		}
		
	}

	public function name_post_title($title, $post_id) {

	if(get_post_type( $post_id ) == 'sbprok_bookings')
    {
		$new_title     = get_post_meta( $post_id, '_sbprok_services', true);
		$customer      = get_post_meta( $post_id, "_sbprok_booking_schedule", true );
		$array         = array(
			'post_type'=>'sbprok_services'
			);
		$service_names = get_posts($array);
		foreach($service_names as $service_name){	
				if($service_name->ID == $new_title){
					foreach($customer as $customers){
						$customer_id = $customer["_customer"];
						$user_info = get_userdata($customer_id);
						return $service_name->post_title."-".$user_info->display_name;
					}
				}			
        }
   
	}
	return $title;
    }

	/**
	 * Register post type for Services.
	 *
	 * @since    1.0.0
	*/
	function register_posttypes() {
		$labels = array(
			'name'                  => _x( 'Service', 'post type general name', 'sbprok' ),
			'singular_name'         => _x( 'Services', 'post type singular name', 'sbprok' ),
			'menu_name'             => _x( 'Services', 'admin menu', 'sbprok' ),
			'name_admin_bar'        => _x( 'Services', 'add new on admin bar', 'sbprok' ),
			'add_new'               => _x( 'Add New', 'Services', 'sbprok' ),
			'add_new_item'          => __( 'Add New Services', 'sbprok' ),
			'new_item'              => __( 'New Services', 'sbprok' ),
			'edit_item'             => __( 'Edit Services', 'sbprok' ),
			'view_item'             => __( 'View Services', 'sbprok' ),
			'view_items'            => __('View %s', 'sbprok'),
			'all_items'             => __( 'All Services', 'sbprok' ),
			'search_items'          => __( 'Search Services', 'sbprok' ),
			'parent_item_colon'     => __( 'Parent Services:', 'sbprok' ),
			'not_found'             => __( 'No Services found.', 'sbprok' ),
			'not_found_in_trash'    => __( 'No Services found in Trash.', 'sbprok' ),
			'archives'              =>  __('%s Archives', 'sbprok'),
			'attributes'            =>  __('%s Attributes', 'sbprok'),
			'update_item'           =>  __('Update %s', 'sbprok'),
			'featured_image'        =>  __( 'Featured image', 'sbprok' ),
            'set_featured_image'    =>  __( 'Set featured image', 'sbprok' ),
            'remove_featured_image' =>  __( 'Remove featured image', 'sbprok' ),
            'use_featured_image'    =>  __( 'Use as featured image', 'sbprok' ),
            'items_list'            =>  __('%s list', 'sbprok'),
			'items_list_navigation' =>  __('%s list navigation', 'sbprok'),
			'description'           => __( 'Demo', 'sbprok' ),
            'filter_items_list'     =>  __('Filter %s list', 'sbprok')
		);

	 
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false, //<--- HERE
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'sbprok_services' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail')
		);
	 
		register_post_type( 'sbprok_services', $args );

		$tax_labels = array(
			'name'              => _x( 'Services Category', 'taxonomy general name','sbprok' ),
			'singular_name'     => _x( 'Services Category', 'taxonomy singular name','sbprok' ),
			'search_items'      =>  __( 'Search Services Category','sbprok' ),
			'all_items'         => __( 'All Services Category','sbprok' ),
			'parent_item'       => __( 'Parent Services Category','sbprok' ),
			'parent_item_colon' => __( 'Parent Services Category:' ),
			'edit_item'         => __( 'Edit Services Category','sbprok' ), 
			'update_item'       => __( 'Update Services Category','sbprok' ),
			'add_new_item'      => __( 'Add New Services Category','sbprok' ),
			'new_item_name'     => __( 'New Services Category Name','sbprok' ),
			'menu_name'         => __( 'Services Categories','sbprok' ),
		  );    
		  register_taxonomy('sbprok_category', array('sbprok_services'), array(
			'hierarchical'      => true,
			'labels'            => $tax_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'sbprok_category' ),
			));
			$supports = array ('');
			$labels = array(
			'name'                  => _x( 'Bookings', 'post type general name', 'sbprok' ),
			'singular_name'         => _x( 'Booking', 'post type singular name', 'sbprok' ),
			'menu_name'             => _x( 'Bookings', 'admin menu', 'sbprok' ),
			'name_admin_bar'        => _x( 'Bookings', 'add new on admin bar', 'sbprok' ),
			'add_new'               => _x( 'Add New', 'Booking', 'sbprok' ),
			'add_new_item'          => __( 'Add New Bookings', 'sbprok' ),
			'new_item'              => __( 'New Bookings', 'sbprok' ),
			'edit_item'             => __( 'Edit Booking', 'sbprok' ),
			'view_item'             => __( 'View Bookings', 'sbprok' ),
			'view_items'            => __('View %s', 'sbprok'),
			'all_items'             => __( 'All Bookings', 'sbprok' ),
			'search_items'          => __( 'Search Bookings', 'sbprok' ),
			'parent_item_colon'     => __( 'Parent Bookings:', 'sbprok' ),
			'not_found'             => __( 'No Bookings found.', 'sbprok' ),
			'not_found_in_trash'    => __( 'No Bookings found in Trash.', 'sbprok' ),
			'archives'              => __('%s Archives', 'sbprok'),
			'attributes'            => __('%s Attributes', 'sbprok'),
			'update_item'           => __('Update %s', 'sbprok'),
			'featured_image'        => __( 'featured image', 'sbprok' ),
			'set_featured_image'    => __( 'Set featured image', 'sbprok' ),
			'remove_featured_image' => __( 'Remove featured image', 'sbprok' ),
			'use_featured_image'    => __( 'Use as featured image', 'sbprok' ),
			'items_list'            => __('%s list', 'sbprok'),
			'items_list_navigation' => __('%s list navigation', 'sbprok'),
			'filter_items_list'     => __('Filter %s list', 'sbprok')
			);
			
			$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false, //<--- HERE
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'sbprok_appoints' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => $supports
			);
			register_post_type( 'sbprok_bookings', $args );
	}
}
?>