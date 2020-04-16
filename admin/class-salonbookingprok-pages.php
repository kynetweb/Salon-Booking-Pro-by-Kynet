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
		add_menu_page(__('Saloon Booking Pro', 'salonbookingprok'), __('Saloon Booking Pro', 'salonbookingprok'), 'manage_options', 'salonbookingprok', array($this, 'saloon_main_menu') );
		add_submenu_page('salonbookingprok', __('Calendar', 'salonbookingprok'), __('Calendar', 'salonbookingprok'), 'manage_options', 'sbprok_calendar',  array($this, 'calendar_sub_menu')  );
		add_submenu_page( 'salonbookingprok', __('Services', 'salonbookingprok'), __('Services', 'salonbookingprok'), 'manage_options', 'edit.php?post_type=sbprok_services', NULL );
		add_submenu_page( 'salonbookingprok', __('Service Categories', 'salonbookingprok'), __('Service Categories', 'salonbookingprok'), 'manage_options', 'edit-tags.php?taxonomy=sbprok_category', NULL );
		add_submenu_page( 'salonbookingprok', __('Bookings', 'salonbookingprok'), __('Bookings', 'salonbookingprok'), 'manage_options', 'edit.php?post_type=sbprok_appoints', NULL );
		add_submenu_page('salonbookingprok', __('Employees', 'salonbookingprok'), __('Employees', 'salonbookingprok'), 'manage_options', 'sbprok_employee',  array($this, 'employees_sub_menu')  );
		add_submenu_page('salonbookingprok', __('Saloon Employee List', 'salonbookingprok'), 'Employees List', 'manage_options', 'sbprok_employee_list',  array($this, 'employeeslist_sub_menu')  );
		add_submenu_page('salonbookingprok', __('Settings', 'salonbookingprok'), __('Settings', 'salonbookingprok'), 'manage_options', 'saloon-setting-options',  array($this, 'saloon_main_menu')  );
		remove_submenu_page('salonbookingprok','salonbookingprok');
	}
	/**
	 * callback main menu functions.
	 *
	 * @since    1.0.0
	 */
	/* setting Page Options Section */
	function saloon_setting_options() { 
		//general tab register setting
		register_setting('sbprok_general', 'sbprok_general');
		//availabiltiy tab register setting
		register_setting('sbprok_availbility','sbprok_availbility');
		//general tab setion and fields
		add_settings_section( 'general_tab_section_id', 'Company Information', 'general_tab_section_callback', 'sbprok_general' );
		add_settings_field( 'company_name_id', 'Company Name', 'company_name_callback', 'sbprok_general', 'general_tab_section_id' );
		add_settings_field( 'company_logo', 'Company Logo', 'company_logo_callback', 'sbprok_general', 'general_tab_section_id' );
		add_settings_field( 'company_email', 'Company Email', 'company_email_callback', 'sbprok_general', 'general_tab_section_id' );
		add_settings_field( 'company_phn', 'Company Phone No.', 'company_phn_callback', 'sbprok_general', 'general_tab_section_id' );
		add_settings_field( 'company_address', 'Company Address', 'company_address_callback', 'sbprok_general', 'general_tab_section_id' );
		//availability tab section and fields
		add_settings_section( 'availability_tab_section_id', 'Availability Information', 'availability_tab_section_callback', 'sbprok_availbility' );
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
	function saloon_main_menu(){?>
		<div class="wrap">  
        <div id="icon-themes" class="icon32"></div>  
        <h2>Settings</h2>  
        <?php settings_errors(); ?>  
		<?php  
            $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';  
        ?>  
		<h2 class="nav-tab-wrapper">  
            <a href="?page=saloon-setting-options&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>  
            <a href="?page=saloon-setting-options&tab=display" class="nav-tab <?php echo $active_tab == 'display' ? 'nav-tab-active' : ''; ?>">Display</a>  
			<a href="?page=saloon-setting-options&tab=availability" class="nav-tab <?php echo $active_tab == 'availability' ? 'nav-tab-active' : ''; ?>">Availability</a>
		</h2>  
		<form method="post" action="options.php">  
			<?php 
            if( $active_tab == 'general' ) {  // general tab function.
                settings_fields( 'sbprok_general' );
           		do_settings_sections( 'sbprok_general' );
            } else if( $active_tab == 'display' ) { // display tab function.
                settings_fields( 'sbprok_display' );
                do_settings_sections( 'sbprok_display' ); 
			}
			else if( $active_tab == 'availability' ) { //availability
				settings_fields( 'sbprok_availbility' );
				do_settings_sections( 'sbprok_availbility' ); 
			}
            ?>             
            <?php submit_button(); ?>  
        </form> 
		</div> 
	<?php
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
				if(isset($_POST['image_attachment_id'])){
					$profile_image = $_POST['image_attachment_id'];
					update_user_meta( $user_id, 'image_attachment_id', $profile_image );
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
	 * callback employee list form sub menu functions.
	 *
	 * @since    1.0.0
	 */
	function employeeslist_sub_menu(){
		$args = array(
			'role'    => 'salonbookingprok_employee',
			
		);
		$users = get_users( $args );
		echo '<h2> Employees <span> <a href = "admin.php?page=sbprok_employee"><button class="sbprok-employee-form-button">Add New</button></a></span></h2>';
		echo '<table id="table_id" class="display sbprok-employeelist">
				<thead>
					<tr>
					<th> Sr. No. </th>
						<th>Username</th>
						<th>Email</th>
						<th>Edit/Update</th>
					</tr>
				</thead>
				<tbody>';
				$sr_no = 1;
				foreach ( $users as $user ) {
					$user_link = get_edit_user_link($user->ID);
					echo '<tr>';
					echo '<td>' . $sr_no++ . '</td>';
					echo '<td>' . $user->display_name . '</td>';
					echo "<td>" . $user->user_email .'</a></td>';
					echo "<td> <a href = $user_link>".'<input class = "sbprok-employee-form-button" type="button" value="Edit Employee"></td> </tr>';
				}
				echo '</tbody>
			</table>';
	}
	/**
	 * admin user page additional meta fields.
	 *
	 * @since    1.0.0
	 */

	function user_custom_fields( $user ) {
		$employee_address = esc_attr( get_the_author_meta( 'employee_address', $user->ID ) );
		$employee_phone = esc_attr( get_the_author_meta( 'employee_phone', $user->ID ) );
		$active_status = get_the_author_meta( 'active_status', $user->ID);
		$profile_image = esc_attr( get_the_author_meta( 'image_attachment_id', $user->ID ) );
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
			<tr>
            	<th>
					<?php _e('Profile Image'); ?>
				</th>
				<td>
					<img id='image-preview' src='<?php echo wp_get_attachment_url($profile_image ); ?>' height='100'>
					<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
					<input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo $profile_image; ?>'>
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
			update_usermeta( $user_id, 'image_attachment_id', $_POST['image_attachment_id'] );
	}
}
/** General tab section callback function */
function general_tab_section_callback() {
   
}
/** General tab fields callback function */
function company_name_callback() {
	$option = get_option( 'sbprok_general' );
	if(isset($option['company_name'])){
		$company_name   = esc_attr( $option['company_name'] );
	}
	else{$company_name = "";}
	echo "<input type='text' name='sbprok_general[company_name]' value='$company_name' />";

}
function company_logo_callback(){
	$option = get_option( 'sbprok_general' );
	if(isset($option['company_logo_id'])){
	$company_logo   = esc_attr( $option['company_logo_id'] );
	}else{$company_logo = "";}
	?>
	<img id='image-preview' src='<?php echo wp_get_attachment_url($company_logo); ?>' height='50'>
    <input id="upload_image_button" class ="sbprok-employee-form-button" type="button" class="button" value = "<?php _e( 'Upload image' ); ?>" />
    <input type='hidden' name='sbprok_general[company_logo_id]' id='image_attachment_id' value ='<?php echo $company_logo; ?>'><br></br><?php
}
function company_email_callback(){
	$option = get_option( 'sbprok_general' );
	if(isset($option['company_email'])){
		$company_email   = esc_attr( $option['company_email'] );
	}else{$company_email = "";}
	echo "<input type='email' name='sbprok_general[company_email]' value='$company_email' />";
}
function company_phn_callback(){
	$option = get_option( 'sbprok_general' );
	if(isset($option['company_phn'])){
		$company_phn   = esc_attr( $option['company_phn'] );
	}else{$company_phn = "";}
	echo "<input type='text' name='sbprok_general[company_phn]' value='$company_phn' />";
}
function company_address_callback(){
	$option = get_option( 'sbprok_general' );
	if(isset($option['company_phn'])){
		$company_address   = esc_attr( $option['company_address'] );
	}else{$company_address = "";}
	
	echo "<textarea name='sbprok_general[company_address]' />" .$company_address. "</textarea>";
	
}
/** availability tab section callback function */
function availability_tab_section_callback(){
	
}
/** availability tab fields callback function */
function monday_callback(){
	$option = get_option( 'sbprok_availbility' );
	if(isset($option['monday']) && $option['monday']){
		$checked = 'checked';
   }else{$checked = "";}
	echo "<input $checked type='checkbox' name='sbprok_availbility[monday]' />";
}
function tuesday_callback(){
	$option = get_option( 'sbprok_availbility' );
	if(isset($option['tuesday']) && $option['tuesday']){
		$checked = 'checked';
   }else{$checked = "";}
	echo "<input $checked type='checkbox' name='sbprok_availbility[tuesday]'  />";
}
function wednesday_callback(){
	$option = get_option( 'sbprok_availbility' );
	if(isset($option['wednesday']) && $option['wednesday']){
		$checked = 'checked';
   }else{$checked = "";}
	echo "<input $checked type='checkbox' name='sbprok_availbility[wednesday]'/>";
}
function thursday_callback(){
	$option = get_option( 'sbprok_availbility' );
	if(isset($option['thursday']) && $option['thursday']){
		$checked = 'checked';
   }else{$checked = "";}
	echo "<input $checked type='checkbox' name='sbprok_availbility[thursday]'  />";
}
function friday_callback(){
	$option = get_option( 'sbprok_availbility' );
	if(isset($option['friday']) && $option['friday']){
		$checked = 'checked';
   }else{$checked = "";}
	echo "<input $checked type='checkbox' name='sbprok_availbility[friday]' />";
}
function saturday_callback(){
	$option = get_option( 'sbprok_availbility' );
	
	if(isset($option['saturday']) && $option['saturday']){
		$checked = 'checked';
   }else{$checked = "";}
	echo "<input $checked type='checkbox' name='sbprok_availbility[saturday]'  />";
}
function sunday_callback(){
	$option = get_option( 'sbprok_availbility' );
	if(isset($option['sunday']) && $option['sunday']){
		 $checked = 'checked';
	}else{$checked = "";}
	echo "<input $checked type='checkbox' name='sbprok_availbility[sunday]'  />";
}