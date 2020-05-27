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
		$loader->add_action('before_delete_post', $this,'delete_calendar_events');
		$loader->add_filter( 'the_title', $this, 'booking_title', 10, 2 );
		$loader->add_filter( 'manage_sbprok_bookings_posts_columns', $this, 'filter_bookings_columns' );
		$loader->add_action( 'manage_sbprok_bookings_posts_custom_column', $this, 'bookings_column', 10, 2);
		$loader->add_filter( 'manage_sbprok_services_posts_columns', $this, 'filter_services_columns' );
		$loader->add_action( 'manage_sbprok_services_posts_custom_column', $this, 'services_column', 10, 2);
		$loader->add_action( 'post_submitbox_misc_actions', $this, 'display_google_calendar_status' );
		
		/** actions for adding featured image in taxonomy */
		$loader->add_action( 'sbprok_category_add_form_fields', $this, 'add_category_image', 10, 2 );
		$loader->add_action( 'created_sbprok_category', $this, 'save_category_image', 10, 2 );
		$loader->add_action( 'sbprok_category_edit_form_fields', $this, 'update_category_image', 10, 2 );
		$loader->add_action( 'edited_sbprok_category', $this, 'updated_category_image', 10, 2 );
		$loader->add_action( 'admin_enqueue_scripts', $this,  'load_media' );
		$loader->add_action( 'admin_footer', $this, 'add_script' );

        /**----------end-----------*/
        


	}
	 /**
	 * Load the required Helper's for this plugin.
	 * @since    1.0.0
	 */
	private function load_Helper() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-google-calendar.php';

		$this->google_calendar = new Sbprok_Google_Calendar($this->plugin_name, $this->version);

	}
	 /**
	 * Save Booking to google calendar
	 *
	 *
	 * @since    1.0.0
	 */
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

	/**
	 * display_google_calendar_status
	 *
	 * @since    1.0.0
	 */
    function display_google_calendar_status($post){

    $screen = get_current_screen();
		 if( 'sbprok_bookings' == $screen->post_type ) {
			$post_meta       = get_post_meta( $post->ID);

			if(array_key_exists("_sbprok_booking_event_id",$post_meta) && !empty($post_meta['_sbprok_booking_event_id'][0])){
			 echo "<p style='padding: 2px 13px;'><span>Booking is synchronized with Google Calendar.</span></p>";
			}else{
			 echo "<p style='padding: 2px 13px;'><span>Booking is not synchronized with Google Calendar.</span></p>";
			}
		 } 
    }

    /**
	 * delete_calendar_events.
	 *
	 * @since    1.0.0
	*/
	public function delete_calendar_events($post_id){
		if(get_post_type( $post_id ) == 'sbprok_bookings')
		{
		$post_meta       = get_post_meta( $post_id);
		$employee_meta   = get_user_meta($post_meta['_sbprok_employee'][0]);
		$emp_calendar_id = $employee_meta['calendar_id'][0];
		$event_id        = get_post_meta($post_id, '_sbprok_booking_event_id', true);
		$this->google_calendar->delete_event($emp_calendar_id,$event_id);
	   }
	}
	
	/**
	 * booking_title.
	 *
	 * @since    1.0.0
	*/
	public function booking_title($title, $post_id) {
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
    * Booking Post columns
    * @since: 1.0
    */
    function filter_bookings_columns( $columns ) {  
        $columns = array(
            'cb' => $columns['cb'],
			'title' => $columns['title'],
			'employee' => __( 'Assigned to' ),
            'bookingdate' => __( 'Date' ),
			'time' => __( 'Time' ),
			'date' => __( 'Created On' )    
        );
        return $columns;
    }
    function bookings_column(  $column, $post_id ) {  
		$schedule = get_post_meta($post_id, "_sbprok_booking_schedule", true );
        if($column == "employee") {	
			$empid  = get_post_meta($post_id, "_sbprok_employee", true );
			$emp 	= 	get_user_by( 'id', $empid );
			echo $emp->display_name ;
        } elseif($column == "bookingdate"){
            echo $schedule['_date'];
		} elseif($column == "time"){
			echo $schedule['_time'];	
		}
	}
	/**
    * Services Post columns
    * @since: 1.0
    */
    function filter_services_columns( $columns ) {  
        $columns = array(
            'cb' => $columns['cb'],
			'title' => $columns['title'],
			'price' => __( 'Price' ),
            'duration' => __( 'Duration' ),
			'date' => $columns['date']  
        );
        return $columns;
    }
    function services_column(  $column, $post_id ) {  
		$service  = get_post_meta($post_id, "_sbprok_service_details", true );
        if($column == "price") {	
			echo $service['_price'];
        } elseif($column == "duration"){
            echo $service['_duration'];
		} 
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
	
	 /*
    * load_media
    * @since 1.0.0
    */
	public function load_media() {
		wp_enqueue_media();
	   }

   /*
    * Add a form field in the new category page
    * @since 1.0.0
    */
	public function add_category_image ( $taxonomy ) { ?>
		<div class="form-field term-group">
		<label for="category-image-id"><?php _e('Image', 'hero-theme'); ?></label>
		<input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
		<div id="category-image-wrapper"></div>
		<p>
			<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'hero-theme' ); ?>" />
			<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
		</p>
		</div>
	<?php
	}
  
	/*
	* Save the form field
	* @since 1.0.0
	*/
	public function save_category_image ( $term_id, $tt_id ) {
		if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
		$image = $_POST['category-image-id'];
		add_term_meta( $term_id, 'category-image-id', $image, true );
		}
	}
  
	/*
	* Edit the form field
	* @since 1.0.0
	*/
	public function update_category_image ( $term, $taxonomy ) { ?>
		<tr class="form-field term-group-wrap">
		<th scope="row">
			<label for="category-image-id"><?php _e( 'Image', 'hero-theme' ); ?></label>
		</th>
		<td>
			<?php $image_id = get_term_meta ( $term -> term_id, 'category-image-id', true ); ?>
			<input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo $image_id; ?>">
			<div id="category-image-wrapper">
			<?php if ( $image_id ) { ?>
				<?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
			<?php } ?>
			</div>
			<p>
			<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'hero-theme' ); ?>" />
			<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
			</p>
		</td>
		</tr>
	<?php
	}
 
	/*
	* Update the form field value
	* @since 1.0.0
	*/
	public function updated_category_image ( $term_id, $tt_id ) {
		if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
		$image = $_POST['category-image-id'];
		update_term_meta ( $term_id, 'category-image-id', $image );
		} else {
		update_term_meta ( $term_id, 'category-image-id', '' );
		}
	}
 
	/*
	* Add script
	* @since 1.0.0
	*/
	public function add_script() { ?>
		<script>
		jQuery(document).ready( function($) {
			function ct_media_upload(button_class) {
			var _custom_media = true,
			_orig_send_attachment = wp.media.editor.send.attachment;
			$('body').on('click', button_class, function(e) {
				var button_id = '#'+$(this).attr('id');
				var send_attachment_bkp = wp.media.editor.send.attachment;
				var button = $(button_id);
				_custom_media = true;
				wp.media.editor.send.attachment = function(props, attachment){
				if ( _custom_media ) {
					$('#category-image-id').val(attachment.id);
					$('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
					$('#category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
				} else {
					return _orig_send_attachment.apply( button_id, [props, attachment] );
				}
				}
			wp.media.editor.open(button);
			return false;
			});
		}
		ct_media_upload('.ct_tax_media_button.button'); 
		$('body').on('click','.ct_tax_media_remove',function(){
			$('#category-image-id').val('');
			$('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
		});

		$(document).ajaxComplete(function(event, xhr, settings) {
			var queryStringArr = settings.data.split('&');
			if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
			var xml = xhr.responseXML;
			$response = $(xml).find('term_id').text();
			if($response!=""){
				$('#category-image-wrapper').html('');
			}
			}
		});
		});
	</script>
	<?php }
 
   
 }
?>