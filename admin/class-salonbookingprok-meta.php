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
                'id'        => 'salonbookingprok_service_details',
                'title'     => 'Service Details',
                'post_type' => 'sbprok_services',
                'context'   => 'normal',
                'args'      => array(
                                'field' => 'multiple_fields',
                                'fields' => array(
                                    array(
                                        'id' 	=> 'salonbookingprok_price',
                                        'desc'	=> 'Price',
                                        'type' 	=>	'textfield'
                                    ),
                                    array(
                                        'id' 	=> 'salonbookingprok_duration',
                                        'desc'	=> 'Duration',
                                        'type' 	=> 'textfield'
                                    )

                                )
                            )
                ),
            array(
                'id'        => 'salonbookingprok_Price',
                'title'     => 'Price',
                'post_type' => 'sbprok_services',
                'context'   => 'normal',
                'args'      => array(
                                'desc'  => 'Price',
                                'field' => 'textfield',
                                )
                ),
            array(
                 'id'        => 'salonbookingprok_duration',
                 'title'     => 'Duration',
                 'post_type' => 'sbprok_services',
                 'context'   => 'side',
                 'args'      => array(
                                  'desc'  => 'Duration',
                                  'field' => 'textfield',
                                )
                ),
            array(
                 'id'        => 'salonbookingprok_employees',
                 'title'     => 'Employees',
                 'post_type' => 'sbprok_services',
                 'desc'     => 'Select the buildings affected',
                 'context'   => 'side',
                 'args'      => array(
                                  'desc'  => 'Employees',
                                  'field' => 'dropdown',
                                    )
                ),
            array(
                 'id'        => 'salonbookingprok_min_capacity',
                 'title'     => 'Min capacity',
                 'post_type' => 'sbprok_services',
                 'context'   => 'side',
                 'args'      => array(
                                  'desc'  => 'Min capacity',
                                  'field' => 'numeric',
                                    )
                ),
            array(
                 'id'        => 'salonbookingprok_max_capacity',
                 'title'     => 'Max capacity',
                 'post_type' => 'sbprok_services',
                 'context'   => 'side',
                 'args'      => array(
                                  'desc'  => 'Max capacity',
                                  'field' => 'numeric',
                                    ) 
                ),
            array(
                 'id'        => 'salonbookingprok_show_service',
                 'title'     => 'Show service on site',
                 'post_type' => 'sbprok_services',
                 'context'   => 'side',
                 'type'      => 'switch',
                 'style'     => 'rounded',
                 'on_label'  => 'Yes',
                 'off_label' => 'No',
                 'args'      => array(
                                  'desc'  => 'Show service on site',
                                  'field' => 'switch',
                                    ) 
                   ),	

            );

        $this->meta_helper->create_meta($args,'_salonbookingprok_meta_box', '_salonbookingprok_meta_box_nouce' );
    }
    /**
    * Save meta Data
    * @since: 1.0
    */
    public function save_service_meta_boxes( $post_id ){
        
        
        // verify meta box nonce
        if ( !isset( $_POST['_salonbookingprok_meta_box_nouce'] ) || !wp_verify_nonce( $_POST['_salonbookingprok_meta_box_nouce'], '_salonbookingprok_meta_box' ) ){
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

        if ( isset( $_POST['_salonbookingprok_price'] ) || isset( $_POST['_salonbookingprok_duration'] ) ) {
            $details = array(
                '_salonbookingprok_price' => !empty( $_POST['_salonbookingprok_price'] ) ? sanitize_text_field( $_POST['_salonbookingprok_price'] ) : '',
                '_salonbookingprok_duration' => !empty( $_POST['_salonbookingprok_duration'] ) ? sanitize_text_field( $_POST['_salonbookingprok_duration'] ) : '',

            );
            update_post_meta( $post_id, '_salonbookingprok_service_details', $details );
            
        }
        
        
    }
}