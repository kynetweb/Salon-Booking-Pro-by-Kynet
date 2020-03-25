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
		add_menu_page(__('Saloon', 'salonbookingprok'), 'Saloon', 'manage_options', 'saloon', array($this, 'saloon_main_menu') );
		add_submenu_page('saloon', __('Saloon Employee', 'salonbookingprok'), 'Employees', 'manage_options', 'sbprok_employee',  array($this, 'employees_sub_menu')  );
		add_submenu_page('saloon', __('Calendar', 'salonbookingprok'), 'Calendar', 'manage_options', 'sbprok_calendar',  array($this, 'calendar_sub_menu')  );
	}
	
    /**
	 * callback main menu function.
	 *
	 * @since    1.0.0
	 */
	function saloon_main_menu(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/salonbookingprok-main-menu.php';
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
  
	<?php }

	/**
	 * callback employee form sub menu functions.
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
			if(username_exists($username)) {
				$message = __( '<b>Error: </b>Username already registered. Please try some other username.<br>', 'salonbookingprok' );
			}
			if(email_exists($email)) {
				$message .= __( '<b>Error: </b>Email already registered. Please try some other Email.<br>', 'salonbookingprok' );
			}
			if(strcmp($password, $confirmpassword)!==0) {
				$message .= __( '<b>Error: </b>Password did not match.<br>', 'salonbookingprok' );
			}
			if(count($message) == 0){
				$userdata = array(
					'user_login'    =>   $username,
					'user_email'    =>   $email,
					'user_pass'     =>   $password,
				);
				$user_id = wp_insert_user( $userdata );
				if(isset($_POST['employee_address'])){
					$employee_address = $_POST['employee_address'];
					update_user_meta( $user_id, 'employee_address', $employee_address );
				}
				if(isset($_POST['employee_phone'])){
					$employee_phone = $_POST['employee_phone'];
					update_user_meta( $user_id, 'employee_phone', $employee_phone );
				}
				if(isset($_POST['active_status'])){
					$active_status = $_POST['active_status'];
					update_user_meta( $user_id, 'active_status', $active_status );
				}
				$user_id_role = new WP_User($user_id);
				$user_id_role->set_role('salonbookingprok_employee');
				$class = 'notice notice-success';
				$message = __( 'User created successfully.', 'salonbookingprok' );
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
			}
			else{
				printf( '<div class="notice notice-error"><p>%1$s</p></div>', $message ); 
			}
		}
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/salonbookingprok-employees-form.php';
	}


	/**
	 * user meta fields.
	 *
	 * @since    1.0.0
	 */

	function user_custom_fields( $user ) {
		$employee_address = esc_attr( get_the_author_meta( 'employee_address', $user->ID ) );
		$employee_phone = esc_attr( get_the_author_meta( 'employee_phone', $user->ID ) );
		$active_status = get_the_author_meta( 'active_status', $user->ID);
		?>
		<h3><?php _e('Additional Employee Information'); ?></h3>
		<table class="form-table">
			<tr>
				<th>
					<label for="employee_address"><?php _e('Address'); ?></label>
				</th>
				<td>
					<input type="text" name="employee_address" id="employee_address" value="<?php echo $employee_address; ?>"  /><br />
				</td>
			</tr>
			<tr>
				<th>
					<label for="employee_phone"><?php _e('Phone'); ?></label>
				</th>
				<td>
					<input type="text" name="employee_phone" id="employee_phone" value="<?php echo $employee_phone; ?>"  /><br />
				</td>
			</tr>
			<tr>
				<th>
					<?php _e('Active'); ?>
				</th>
				<td>
					<input type="checkbox" name="active_status" <?php if ($active_status == 'active' ) { ?>checked="checked"<?php } ?> value="active" /> 
				</td>
			</tr>
			
			
		</table>
	<?php 
	}
	function save_user_custom_fields( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) )
			return FALSE;
			update_usermeta( $user_id, 'employee_address', $_POST['employee_address'] );
			update_usermeta( $user_id, 'employee_phone', $_POST['employee_phone'] );
			update_usermeta( $user_id, 'active_status', $_POST['active_status'] );
		
	}
}