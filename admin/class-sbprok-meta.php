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
class Sbprok_Meta {
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
	protected $meta_helper;
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
        $loader->add_action( 'add_meta_boxes', $this,'service_meta_boxes');
        $loader->add_action( 'add_meta_boxes', $this,'booking_meta_boxes');
        $loader->add_action( 'save_post', $this,'save_service_meta_boxes', 0 );
		$loader->add_action( 'save_post', $this,'save_booking_meta_boxes', 1 );

    }
    /**
	 * Load the required Helper's for this plugin.
	 *
	 *
	 * @since    1.0.0
	 */
	private function load_Helper() {
		/**
		 * The helper class responsible for defining and crating the meta Fileds for all post types.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/helper/class-sbprok-metaboxes.php';

		$this->meta_helper = new sbprok_Metaboxes();

	}
    /**
	 * Add Metaboxes for Services.
	 *
	 * @since    1.0.0
	 */
	public function service_meta_boxes(){
        $args = array(
            array(
                'id'        => 'sbprok_service_details',
                'title'     => 'Service Details',
                'post_type' => 'sbprok_services',
                'context'   => 'normal',
                'args'      => array(
                    'field' => 'service_details'
                )               
            ),
            array(
                'id' 	    =>  'sbprok_show',
                'title'     =>	'Show service on site',
                'post_type' => 'sbprok_services',
                'type' 	    =>	'checkbox',
                'context'   => 'side',
                'args'      => array(
                                'field' => 'checkbox',
                                'desc'	=>  'Show',
                                'type' 	=>	'checkbox'
                               )  
            ),
            array(
                'id' 	    =>  'sbprok_payment_mode',
                'title'     =>	'Payment',
                'post_type' => 'sbprok_services',
                'type'    	=>	'checkbox',
                'context'   => 'side',
                'args'      => array(
                                'field' => 'checkbox',
                                'desc'	=>  'Yes',
                                'type' 	=>	'checkbox'
                               )
            ),
            array(
                'id' 	    =>  'sbprok_employees',
                'title'     =>	'Assign Employee',
                'post_type' =>  'sbprok_services',
                'type'    	=>	'employees_selection',
                'context'   =>  'normal',
                'args'      => array(
                                'field' => 'employees_selection',
                                'type' 	=> 'employees_selection'
                               )
            ),      
        );
        $this->meta_helper->create_meta($args, '_sbprok_meta_box', '_sbprok_meta_box_nouce' );
    }
    /*** apointment meta boxes ***/
    public function booking_meta_boxes(){
        $args = array(
            array(
                'id' 	    =>  'sbprok_service_cat',
                'title'     =>	'Select Service Category',
                'post_type' =>  'sbprok_bookings',
                'type'    	=>	'service_cat_selection',
                'context'   =>  'normal',
                'args'      => array(
                                'field' => 'service_cat_selection',
                                'type' 	=> 'service_cat_selection'
                               )
            ),  
            array(
                'id' 	    =>  'sbprok_services',
                'title'     =>	'Select Service',
                'post_type' =>  'sbprok_bookings',
                'type'    	=>	'service_selection',
                'context'   =>  'normal',
                'args'      => array(
                                'field' => 'service_selection',
                                'type' 	=> 'service_selection'
                               )
            ),  
            array(
                'id' 	    =>  'sbprok_employee',
                'title'     =>	'Assign Employee',
                'post_type' =>  'sbprok_bookings',
                'type'    	=>	'employee_selection',
                'context'   =>  'normal',
                'args'      => array(
                                'field' => 'employee_selection',
                                'type' 	=> 'employee_selection'
                               )
            ),
            array(
                'id'        => 'sbprok_booking_schedule',
                'title'     => 'Booking Schedule',
                'post_type' => 'sbprok_bookings',
                'context'   => 'normal',
                'args'      => array(
                        'desc'	=>  'Show',
                        'field' =>  'booking_schedule'
                )             
            ),
            
        );   
       $this->meta_helper->create_meta($args,'_sbprok_meta_box', '_sbprok_meta_box_nouce' );
    }
    /**
    * Save meta Data
    * @since: 1.0
    */
    public function save_service_meta_boxes( $post_id ){
        // verify meta box nonce
        if ( !isset( $_POST['_sbprok_meta_box_nouce'] ) || !wp_verify_nonce( $_POST['_sbprok_meta_box_nouce'], '_sbprok_meta_box' ) ){
            // return;
        }
       
        // return if autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
            return;
        }
        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ){
            return;
        }
        
        // store details
       
        if ( isset( $_POST['_sbprok_service_details'] ))  {

            $details = array(
                '_price' => !empty( $_POST['_sbprok_service_details']['_price'] ) ? sanitize_text_field( $_POST['_sbprok_service_details']['_price'] ) : '',
                '_duration' => !empty( $_POST['_sbprok_service_details']['_duration'] ) ? sanitize_text_field( $_POST['_sbprok_service_details']['_duration'] ) : '',
                '_max_capacity' => !empty( $_POST['_sbprok_service_details']['_max_capacity'] ) ? sanitize_text_field( $_POST['_sbprok_service_details']['_max_capacity'] ) : ''
            );
            update_post_meta( $post_id, '_sbprok_service_details', $details );
            
        }
        
        if ( isset( $_POST['_sbprok_show']) && ($_POST['_sbprok_show'] == "on" || $_POST['_sbprok_show'] == 1) ){
            update_post_meta( $post_id,'_sbprok_show', true);
        } else {
            update_post_meta( $post_id, '_sbprok_show', false);
        }
      
        if (isset( $_POST['_sbprok_payment_mode']) && (($_POST['_sbprok_payment_mode'] == "on" || $_POST['_sbprok_payment_mode'] == 1))){
            update_post_meta( $post_id, '_sbprok_payment_mode', true);
        } else {
            update_post_meta( $post_id, '_sbprok_payment_mode', false);
        }

        if ( isset( $_POST['_sbprok_employees']) ){
            update_post_meta( $post_id, '_sbprok_employees', $_POST['_sbprok_employees'] );
        }
        
    }
    /**
    * Save meta Data
    * @since: 1.0
    */
    public function save_booking_meta_boxes( $post_id ){
        
        // verify meta box nonce
        if ( !isset( $_POST['_sbprok_meta_box_nouce'] ) || !wp_verify_nonce( $_POST['_sbprok_meta_box_nouce'], '_sbprok_meta_box' ) ){
            return;
        } 
        // return if autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
            return;
        }
        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ){
            return;
        }

    // store booking meta boxes details
    if ( isset( $_POST['_sbprok_booking_schedule'] ) ) {
        
        $details = array(
            '_date' => !empty( $_POST['_sbprok_booking_schedule']['_date'] ) ?  $_POST['_sbprok_booking_schedule']['_date'] : '',
            '_time' => !empty( $_POST['_sbprok_booking_schedule']['_time']  ) ? $_POST['_sbprok_booking_schedule']['_time'] : '',
            '_customer' => !empty( $_POST['_sbprok_booking_schedule']['_customer']  ) ? $_POST['_sbprok_booking_schedule']['_customer'] : '',
            
        );

        update_post_meta( $post_id, '_sbprok_booking_schedule', $details );
        
    }

    if ( isset( $_POST['_sbprok_customer']) ) {
       //update_post_meta( $post_id, '_sbprok_customer', $_POST['_sbprok_customer'] );   
    }
    
    if ( isset( $_POST['_sbprok_services']) ){
        update_post_meta( $post_id, '_sbprok_services', $_POST['_sbprok_services'] );
    }
    if(isset($_POST['_sbprok_service_cat'])){
        update_post_meta( $post_id, '_sbprok_service_cat', $_POST['_sbprok_service_cat'] );
    }
  
    if (isset( $_POST['_sbprok_employee'])){
        update_post_meta( $post_id, '_sbprok_employee', $_POST['_sbprok_employee'] );
     }
    
   }

 
}