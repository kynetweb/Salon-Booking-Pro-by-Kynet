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

	}
    /**
	 * Register the menu pages.
	 *
	 * @since    1.0.0
	 */
	function menu_pages(){
		add_menu_page(__('Salon Booking Pro', 'sbprok'), __('Salon Booking Pro', 'sbprok'), 'manage_options', 'sbprok', array($this, 'saloon_main_menu') );
    	add_submenu_page('sbprok', __('Employees', 'sbprok'), 'Employees', 'manage_options', 'sbprok_employee_list',  array($this, 'employees_list')  );
		add_submenu_page('sbprok', __('Add New Employee', 'sbprok'), __('Add New Employee', 'sbprok'), 'manage_options', 'sbprok_add_employee',  array($this, 'add_employee')  );
		add_submenu_page( 'sbprok', __('Services', 'sbprok'), __('Services', 'sbprok'), 'manage_options', 'edit.php?post_type=sbprok_services', NULL );
		add_submenu_page( 'sbprok', __('Service Categories', 'sbprok'), __('Service Categories', 'sbprok'), 'manage_options', 'edit-tags.php?taxonomy=sbprok_category', NULL );
		add_submenu_page( 'sbprok', __('Bookings', 'sbprok'), __('Bookings', 'sbprok'), 'manage_options', 'edit.php?post_type=sbprok_bookings', NULL );
		add_submenu_page('sbprok', __('Calendar', 'sbprok'), __('Calendar', 'sbprok'), 'manage_options', 'sbprok_calendar',  array($this, 'calendar_sub_menu')  );
		add_submenu_page('sbprok', __('Settings', 'sbprok'), __('Settings', 'sbprok'), 'manage_options', 'saloon-setting-options',  array($this, 'saloon_main_menu')  );
		remove_submenu_page('sbprok','sbprok');
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