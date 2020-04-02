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
		wp_enqueue_script( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/salonbookingprok-admin.js', array( 'jquery-ui-datepicker','jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
		wp_enqueue_script( $this->plugin_name.'-timepickar', plugin_dir_url( __FILE__ ) . 'js/Timepicker.min.js', array( 'jquery' ), $this->version, true );
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
		
	}

	function get_ajax_posts() {
		$services      = array(
							'post_type' => array('sbprok_appoints'),
							'posts_per_page' => 40,
							'nopaging' => true,
							'order' => 'DESC',
							'orderby' => 'date'
						 );
		
		$ajaxposts     = get_posts( $services );
		$array         = array(
							'ID'=>$name[0],
							'post_type'=>'sbprok_services'
							);
		$service_names = get_posts($array);

		foreach($ajaxposts as $ajaxpost){
			$service_id  = get_post_meta( $ajaxpost->ID, "_sbprok_services", true );
				foreach($service_id as $id){
					$service_ids['id'] = $id;
				}
			$date_time  = get_post_meta( $ajaxpost->ID, "_sbprok_appt_schedule", true );
			$schedule[] = array_merge($service_ids,$date_time);
		}
		echo json_encode( array($schedule,$service_names) );
			exit; 
		}

}
