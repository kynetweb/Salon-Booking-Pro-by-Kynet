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
class Salonbookingprok_Meta {
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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_Helper();

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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/helper/class-salonbookingprok-metaboxes.php';

		$this->meta_helper = new Salonbookingprok_Metaboxes();

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
                    'field' => 'multiple_fields',
                    'fields' => array(
                        array(
                            'id' 	=> 'sbprok_price',
                            'title' =>	'Price',
                            'type' 	=>	'textfield',
                            'desc'	=>  'Price',
                            'type' 	=>	'textfield'
                        ),
                        array(
                            'id' 	=> 'sbprok_duration',
                            'title' => 'Duration',
                            'desc'	=> 'Duration',
                            'type' 	=> 'textfield'
                        ),
                        array(
                            'id' 	=> 'sbprok_max_capacity',
                            'title' => 'Maximum Capacity',
                            'desc'	=> 'maximum allowed person per booking',
                            'type' 	=> 'numeric'
                        )

                    )
                )               
            ),
            array(
                'id' 	    =>  'sbprok_show',
                'title'     =>	'Show service on site',
                'post_type' => 'sbprok_services',
                'type' 	    =>	'checkbox',
                'context'   => 'normal',
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
                'context'   => 'normal',
                'args'      => array(
                                'field' => 'checkbox',
                                'desc'	=>  'Yes',
                                'type' 	=>	'checkbox'
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
        
        // store details
       
       
        if ( isset( $_POST['_sbprok_price'] ) || isset( $_POST['_sbprok_duration'] ) || isset( $_POST['_sbprok_max_capacity'])) {
            $details = array(
                '_sbprok_price' => !empty( $_POST['_sbprok_price'] ) ? sanitize_text_field( $_POST['_sbprok_price'] ) : '',
                '_sbprok_duration' => !empty( $_POST['_sbprok_duration'] ) ? sanitize_text_field( $_POST['_sbprok_duration'] ) : '',
                '_sbprok_max_capacity' => !empty( $_POST['_sbprok_max_capacity'] ) ? sanitize_text_field( $_POST['_sbprok_max_capacity'] ) : ''

            );
            update_post_meta( $post_id, '_sbprok_service_details', $details );
            
        }
        
        if ( isset( $_POST['_sbprok_show']) ){
            $my_data = $_POST['home_slider_display'] ? true : false;
            update_post_meta( $post_id, '_sbprok_show');
        }
      
        if (isset( $_POST['_sbprok_payment_mode'])){
            $my_data = $_POST['home_slider_display'] ? true : false;
            update_post_meta( $post_id, '_sbprok_payment_mode');
        }
        
    }

    /*** apointment meta boxes ***/
    public function appointment_meta_boxes(){
        $args = array(
            array(
                'id'        => 'sbprok_appointment',
                'title'     => 'Appointment Details',
                'post_type' => 'sbprok_appoints',
                'context'   => 'normal',
                'args'      => array(
                    'field' => 'multiple_fields',
                    'fields' => array(
                        array(
                            'id' 	=> 'sbprok_day',
                            'title' =>	'Day',
                            'desc'	=>  'Day',
                            'type' 	=>	'textfield'
                        ),
                        array(
                            'id' 	=> 'sbprok_time',
                            'title' => 'Time',
                            'desc'	=> 'Time',
                            'type' 	=> 'textfield'
                        ),

                    )
                )               
            ),
            array(
                'id' 	    =>  'sbprok_customers',
                'title'     =>	'Customer',
                'post_type' => 'sbprok_appoints',
                'type'    	=>	'customer_dropdown',
                'context'   => 'normal',
                'args'      => array(
                                'field' => 'multiple_fields',
                                'fields' => array(
                                    array(
                                        'id' 	=> 'sbprok_customer',
                                        'title' =>	'Customer',
                                        'desc'	=>  'Customer',
                                        'type' 	=>	'customer_dropdown'
                                    ),
                               )
                            )
            ),  
            array(
                'id' 	    =>  'sbprok_services',
                'title'     =>	'Service Name',
                'post_type' =>  'sbprok_appoints',
                'type'    	=>	'appointment_dropdown',
                'context'   =>  'normal',
                'args'      => array(
                                'field' => 'appointment_dropdown',
                                'type' 	=> 'appointment_dropdown'
                               )
            ),  
            array(
                'id' 	    =>  'sbprok_employee',
                'title'     =>	'Employee',
                'post_type' =>  'sbprok_appoints',
                'type'    	=>	'appointment_dropdown',
                'context'   =>  'normal',
                'args'      => array(
                                'field' => 'appointment_dropdown',
                                'type' 	=> 'appointment_dropdown'
                               )
            ),  
   
        );
        
        $this->meta_helper->create_meta($args,'_sbprok_appoint_meta_box', '_sbprok_meta_box_nouce' );
    }

    /**
    * Save meta Data
    * @since: 1.0
    */
    public function save_appointment_meta_boxes( $post_id ){
        
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

    // store appointment meta boxes details
       
       
    if ( isset( $_POST['_sbprok_day'] ) || isset( $_POST['_sbprok_time'] )) {
        $details = array(
            '_sbprok_day' => !empty( $_POST['_sbprok_day'] ) ?  $_POST['_sbprok_day'] : '',
            '_sbprok_time' => !empty( $_POST['_sbprok_time'] ) ? $_POST['_sbprok_time'] : ''
        );
        update_post_meta( $post_id, '_sbprok_appointment', $details );
        
    }

    if ( isset( $_POST['_sbprok_customer'] ) || isset( $_POST['_sbprok_first_name'] ) || isset( $_POST['_sbprok_last_name']) || isset( $_POST['_sbprok_email']) || isset( $_POST['_sbprok_phone']) || isset( $_POST['_sbprok_address'])) {
        $details = array(
            '_sbprok_customer' => !empty( $_POST['_sbprok_customer'] ) ? sanitize_text_field( $_POST['_sbprok_customer'] ) : '',
            '_sbprok_first_name' => !empty( $_POST['_sbprok_first_name'] ) ? sanitize_text_field( $_POST['_sbprok_first_name'] ) : '',
            '_sbprok_last_name' => !empty( $_POST['_sbprok_last_name'] ) ? sanitize_text_field( $_POST['_sbprok_last_name'] ) : '',
            '_sbprok_email' => !empty( $_POST['_sbprok_email'] ) ? sanitize_text_field( $_POST['_sbprok_email'] ) : '',
            '_sbprok_phone' => !empty( $_POST['_sbprok_phone'] ) ? sanitize_text_field( $_POST['_sbprok_phone'] ) : '',
            '_sbprok_address' => !empty( $_POST['_sbprok_address'] ) ? sanitize_text_field( $_POST['_sbprok_address'] ) : ''
        );
        update_post_meta( $post_id, '_sbprok_service_details', $details );
        
    }
    
    if ( isset( $_POST['_sbprok_services']) ){
        update_post_meta( $post_id, '_sbprok_services', $_POST['_sbprok_services'] );
    }
  
    if (isset( $_POST['_sbprok_employee'])){
        update_post_meta( $post_id, '_sbprok_employee', $_POST['_sbprok_employee'] );
     }
    
   }

 
}