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
class Sbprok_Pages {
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
		$this->version = $version;
		$loader->add_action( 'admin_menu', $this, 'menu_pages'); 
		$loader->add_action('admin_init', $this, 'saloon_setting_options');

	}
    /**
	 * Register the menu pages.
	 *
	 * @since    1.0.0
	 */
	function menu_pages(){
		add_menu_page(__('Salon Booking Pro', 'sbprok'), __('Salon Booking Pro', 'sbprok'), 'manage_options', 'sbprok', array($this, 'saloon_main_menu') );
  	    add_submenu_page('sbprok', __('Employees', 'sbprok'), 'Employees', 'manage_options', 'sbprok_employee_list',  array($this, 'employees_list')  );
		add_submenu_page('sbprok', _('Add Employee', 'sbprok'), _('Add Employee', 'sbprok'), 'manage_options', 'sbprok_add_employee',  array($this, 'add_employee')  );
		add_submenu_page( 'sbprok', __('Services', 'sbprok'), __('Services', 'sbprok'), 'manage_options', 'edit.php?post_type=sbprok_services', NULL );
		add_submenu_page( 'sbprok', __('Service Categories', 'sbprok'), __('Service Categories', 'sbprok'), 'manage_options', 'edit-tags.php?taxonomy=sbprok_category', NULL );
		add_submenu_page( 'sbprok', __('Bookings', 'sbprok'), __('Bookings', 'sbprok'), 'manage_options', 'edit.php?post_type=sbprok_bookings', NULL );
		add_submenu_page('sbprok', __('Calendar', 'sbprok'), __('Calendar', 'sbprok'), 'manage_options', 'sbprok_calendar',  array($this, 'calendar_sub_menu')  );
		add_submenu_page('sbprok', __('Settings', 'sbprok'), __('Settings', 'sbprok'), 'manage_options', 'saloon-setting-options',  array($this, 'saloon_setting_page')  );
		remove_submenu_page('sbprok','sbprok');
	}

   /**
	 * callback calendar form sub menu functions.
	 *
	 * @since    1.0.0
	 */

	function calendar_sub_menu() { ?>
		<style>
	 	body {
			margin: 40px 10px;
			padding: 0;
			font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
			font-size: 14px;
	  }
	  #calendar {
		max-width: 900px;
		margin: 0 auto;
	  }
	  </style>
	  <div id='loading'></div>
	  <div id='calendar'></div>
		<?php 
		}

	/**
	 * callback main menu settings functions.
	 *
	 * @since    1.0.0
	 */
	function saloon_setting_options() { 
		//general tab register setting
		register_setting('sbprok_general', 'sbprok_general');

		//Display tab register setting
		register_setting('sbprok_display', 'sbprok_display');

		//availabiltiy tab register setting
		register_setting('sbprok_availbility','sbprok_availbility');

		//general tab setion and fields
		add_settings_section( 'general_tab_section_id', 'Company Information', array($this, 'general_tab_section_callback'), 'sbprok_general' );
        add_settings_field( 'company_name_id', 'Company Name', 'company_name_callback', 'sbprok_general', 'general_tab_section_id' );
		add_settings_field( 'company_logo', 'Company Logo', 'company_logo_callback', 'sbprok_general', 'general_tab_section_id' );
		add_settings_field( 'company_email', 'Company Email', 'company_email_callback', 'sbprok_general', 'general_tab_section_id' );
		add_settings_field( 'company_phn', 'Company Phone No.', 'company_phn_callback', 'sbprok_general', 'general_tab_section_id' );
		add_settings_field( 'company_address', 'Company Address', 'company_address_callback', 'sbprok_general', 'general_tab_section_id' );

		//availability tab section and fields
		add_settings_section( 'availability_tab_section_id', 'Availability Information', array($this, 'availability_tab_section_callback'), 'sbprok_availbility' );
		add_settings_field( 'monday_id', 'Monday', 'monday_callback', 'sbprok_availbility', 'availability_tab_section_id' );
		add_settings_field( 'tuesday_id', 'Tuesday', 'tuesday_callback', 'sbprok_availbility', 'availability_tab_section_id' );
		add_settings_field( 'wednesday_id', 'Wednesday', 'wednesday_callback', 'sbprok_availbility', 'availability_tab_section_id' );
		add_settings_field( 'thursday_id', 'Thursday', 'thursday_callback', 'sbprok_availbility', 'availability_tab_section_id' );
		add_settings_field( 'friday_id', 'Friday', 'friday_callback', 'sbprok_availbility', 'availability_tab_section_id' );
		add_settings_field( 'saturday_id', 'Saturday', 'saturday_callback', 'sbprok_availbility', 'availability_tab_section_id' );
		add_settings_field( 'sunday_id', 'Sunday', 'sunday_callback', 'sbprok_availbility', 'availability_tab_section_id' );
	}
    function ch_essentials_header_callback() { 
		echo '<p>Header Display Options:</p>'; 
	}

	/** General tab section callback function */
	function general_tab_section_callback() {
			
	}

	/** availability tab section callback function */
	function availability_tab_section_callback(){
		
	}

	function saloon_setting_page() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/sbprok-setting-page.php';
	}

	/**
	 * callback employee form sub menu functions.
	 *
	 * @since    1.0.0
	*/
	function add_employee() {
		if($_POST) {
			global $wpdb;
			$username        = $_POST['user_name'];
			$email           = $_POST['user_email'];
			$password        = $_POST['user_password'];
			$confirmpassword = $_POST['user_confirm_password'];
			if(username_exists($username)) {
				$message = __( '<b>Error: </b>Username already registered. Please try some other username.<br>', 'sbprok' );
			}
			if(email_exists($email)) {
				$message .= __( '<b>Error: </b>Email already registered. Please try some other Email.<br>', 'sbprok' );
			}
			if(strcmp($password, $confirmpassword)!==0) {
				$message .= __( '<b>Error: </b>Password did not match.<br>', 'sbprok' );
			}
			if(empty($message)){
				$userdata = array(
					'user_login'    =>   $username,
					'user_email'    =>   $email,
					'user_pass'     =>   $password,
				);
				$user_id = wp_insert_user( $userdata );

				if(isset($_POST['calendar_id'])){
					$employee_calendar_id = $_POST['calendar_id'];
					update_user_meta( $user_id, 'calendar_id', $employee_calendar_id );
				}

				if(isset($_POST['employee_address'])){
					$employee_address = $_POST['employee_address'];
					update_user_meta( $user_id, 'employee_address', $employee_address );
				}
				if(isset($_POST['employee_phone'])){
					$employee_phone = $_POST['employee_phone'];
					update_user_meta( $user_id, 'employee_phone', $employee_phone );
				}
				if(isset($_POST['image_attachment_id'])){
					$profile_image = $_POST['image_attachment_id'];
					update_user_meta( $user_id, 'image_attachment_id', $profile_image );
			   }
				if(isset($_POST['active_status'])){
					$active_status = $_POST['active_status'];
					update_user_meta( $user_id, 'active_status', $active_status );
				}
				$user_id_role = new WP_User($user_id);
				$user_id_role->set_role('sbprok_employee');
				$class = 'notice notice-success';
				$message = __( 'User created successfully.', 'sbprok' );
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
			}
			else{
				printf( '<div class="notice notice-error"><p>%1$s</p></div>', $message ); 
			}
		}
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/sbprok-employee-form.php';
	}
	/**
	 * callback employee list form sub menu functions.
	 *
	 * @since    1.0.0
	 */
	function employees_list(){
		$args = array(
			'role'    => 'sbprok_employee',	
		);
		$users = get_users( $args );
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/sbprok-employees-list.php';
	}

	 
}