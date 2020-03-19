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
class Salonbookingprok_Pages {
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
		$this->version = $version;

	}
    /**
	 * Register the menu pages.
	 *
	 * @since    1.0.0
	 */
	function menu_pages(){
		add_menu_page('Saloon', 'Saloon', 'manage_options', 'saloon', array($this, 'saloon_main_menu') );
		add_submenu_page('saloon', 'sbprok_employee', 'Employees', 'manage_options', 'sbprok_employee',  array($this, 'employees_sub_menu')  );
	}
    /**
	 * callback main menu function.
	 *
	 * @since    1.0.0
	 */
	function saloon_main_menu(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/saloon_main_menu.php';
	}
	/**
	 * callback sub menu functions.
	 *
	 * @since    1.0.0
	 */
	function employees_sub_menu() {
		if($_POST) {
			global $wpdb;
			$username = $_POST['user_name'];
			$email = $_POST['user_email'];
			$password = $_POST['user_password'];
			$confirmpassword = $_POST['user_confirm_password'];
			$error = array();
			if(username_exists($username)) {
				$error= "<script>alert('Username Already Exists')</script>";
			}
			if(!is_email($email)) { 
				$error= "<script>alert('Email Invalid')</script>";
			}
			if(email_exists($email)) {
				echo "<script>alert('Email Already Exists')</script>";
			}
			if(strcmp($password, $confirmpassword)!==0) {
				$error= "<script>alert('Password did not match')</script>";
			}
			if(count($error) == 0){
				$userdata = array(
					'user_login'    =>   $username,
					'user_email'    =>   $email,
					'user_pass'     =>   $password,
				);
				$user_id = wp_insert_user( $userdata );
				$user_id_role = new WP_User($user_id);
				$user_id_role->set_role('salonbookingprok_employee');
				echo "<script type='text/javascript'>alert('user created successfully')</script>";
			}
			else{
				print_r($error);
			}
		}
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/custom_employees_form.php';
	}

}