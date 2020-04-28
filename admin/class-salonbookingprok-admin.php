<?php /**
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
class Salonbookingprok_Admin {

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
	 * The meta_helper that's responsible for maintaining and registering all meta fileds.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Salonbookingprok_Metaboxes    $loader    Maintains and registers all hooks for the plugin.
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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Salonbookingprok_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Salonbookingprok_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/salonbookingprok-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-datatables', plugin_dir_url( __FILE__ ) . 'css/datatables.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-datepickr', plugin_dir_url( __FILE__ ) . 'css/datepic.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-timepickr', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css1', plugin_dir_url( __FILE__ ) . 'css/fullcalendar.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css2', plugin_dir_url( __FILE__ ) . 'css/calendr_css/core/main.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css3', plugin_dir_url( __FILE__ ) . 'css/calendr_css/daygrid/main.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css4', plugin_dir_url( __FILE__ ) . 'css/calendr_css/timegrid/main.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css5', plugin_dir_url( __FILE__ ) . 'css/calendr_css/list/main.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css6', plugin_dir_url( __FILE__ ) . 'css/calendr_css/bootstrap/main.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css6', plugin_dir_url( __FILE__ ) . 'css/calendr_css/bootstrap/main.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Salonbookingprok_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Salonbookingprok_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */ 
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name.'-sc13', 'https://apis.google.com/js/platform.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc14', 'https://apis.google.com/js/client.js?onload=checkAuth', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-timepickar', plugin_dir_url( __FILE__ ) . 'js/Timepicker.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-datatables', plugin_dir_url( __FILE__ ) . 'js/datatables.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc1', plugin_dir_url( __FILE__ ) . 'js/moment.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc2', plugin_dir_url( __FILE__ ) . 'js/fullcalendar.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc3', plugin_dir_url( __FILE__ ) . 'js/packages/core/main.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc4', plugin_dir_url( __FILE__ ) . 'js/packages/interaction/main.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc5', plugin_dir_url( __FILE__ ) . 'js/packages/daygrid/main.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc6', plugin_dir_url( __FILE__ ) . 'js/packages/timegrid/main.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc7', plugin_dir_url( __FILE__ ) . 'js/packages/list/main.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc8', plugin_dir_url( __FILE__ ) . 'js/php/list/main.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc9', plugin_dir_url( __FILE__ ) . 'js/packages/interaction/main.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc10', plugin_dir_url( __FILE__ ) . 'js/packages/bootstrap/main.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc11', plugin_dir_url( __FILE__ ) . 'js/packages/sweetalert/sweetalert.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-sc12', plugin_dir_url( __FILE__ ) . 'js/packages/google-calendar/main.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) .'js/salonbookingprok-admin.js', array( 'jquery-ui-datepicker','jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'sbprokAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
		
	}
	function wpse33385_filter_title( $title, $post_id )
{
	if(get_post_type( $post_id ) == 'sbprok_appoints')
    {
		$new_title     = get_post_meta( $post_id, '_sbprok_services', true);
		$customer      = get_post_meta( $post_id, "_sbprok_appt_schedule", true );
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

	function my_profile_upload_js() { 
    	$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
		$my_saved_attachment_post_id_user = get_option( 'media_selector_attachment_id', 0 );
		?>
		<script type='text/javascript'>
			jQuery( document ).ready( function( $ ) {
				// Uploading files
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
				var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
				jQuery(document).find("input[id^='upload_image_button']").live('click', function(event){
					event.preventDefault();
					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						// Set the post ID to what we want
						file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
						// Open frame
						file_frame.open();
						return;
					} else { 
						// Set the wp.media post id so the uploader grabs the ID we want when initialised
						wp.media.model.settings.post.id = set_to_post_id;
					}
					// Create the media frame.
					file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
					});
					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						// We set multiple to false so only get one image from the uploader
						attachment = file_frame.state().get('selection').first().toJSON();
						// Do something with attachment.id and/or attachment.url here
						$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
						$( '#image_attachment_id' ).val( attachment.id );
						// Restore the main post ID
						wp.media.model.settings.post.id = wp_media_post_id;
					});
				// Finally, open the modal
				file_frame.open();
				});
				// Restore the main ID when the add media button is pressed
				jQuery( 'a.add_media' ).on( 'click', function() {
					wp.media.model.settings.post.id = wp_media_post_id;
				});
			});
			
			//user backend
			jQuery(document).ready(function() {
				var uploadID = ''; /*setup the var*/
				jQuery(document).find("input[id^='uploadimage']").live('click', function(){
					uploadID = jQuery(this).prev('input'); /*grab the specific input*/
					formfield = jQuery('.profile_image').attr('name');
					tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
					window.send_to_editor = function(html) {
						console.log("hello");
						imgurl = jQuery(html).attr('src');
						uploadID.val(imgurl); /*assign the value to the input*/
						tb_remove();
					};
				});
			});
		</script>
	<?php }
	
}
