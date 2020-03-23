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
		wp_enqueue_style('thickbox'); 

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/salonbookingprok-admin.js', array( 'jquery' ), $this->version, false );
		
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		

	}
	function my_profile_upload_js() { ?>
    
		<script type="text/javascript">
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

?>