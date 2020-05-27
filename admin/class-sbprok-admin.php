<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Sbprok
 * @subpackage Sbprok/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sbprok
 * @subpackage Sbprok/admin
 * @author     Kynet Web Solutiosn <contact@kynetweb.com>
 */
class Sbprok_Admin {

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
		$loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles' );
		$loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );

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
		 * defined in Sbprok_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sbprok_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-datatables', plugin_dir_url( __FILE__ ) . 'css/datatables.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-datepickr', plugin_dir_url( __FILE__ ) . 'css/datepic.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-timepickr', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-timepickr_min', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.min.css.map', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name.'-timepickr2', plugin_dir_url( __FILE__ ) . 'css/timepicker.min.css.map', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css1', plugin_dir_url( __FILE__ ) . 'css/fullcalendar.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css2', plugin_dir_url( __FILE__ ) . 'css/calendr_css/core/main.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css3', plugin_dir_url( __FILE__ ) . 'css/calendr_css/daygrid/main.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css4', plugin_dir_url( __FILE__ ) . 'css/calendr_css/timegrid/main.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css5', plugin_dir_url( __FILE__ ) . 'css/calendr_css/list/main.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css6', plugin_dir_url( __FILE__ ) . 'css/calendr_css/bootstrap/main.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css6', plugin_dir_url( __FILE__ ) . 'css/calendr_css/bootstrap/main.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-css7', plugin_dir_url( __FILE__ ) . 'css/timepicker.css', array(), $this->version, 'all' );
		wp_enqueue_style('thickbox');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sbprok-admin.css', array(), $this->version, 'all' );

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
		 * defined in Sbprok_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sbprok_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name.'-mediabtn', plugin_dir_url( __FILE__ ) . 'js/sbprok-media.js', array( 'jquery', 'media-upload','thickbox' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-timepickar', plugin_dir_url( __FILE__ ) . 'js/Timepicker.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-timepickarmain', plugin_dir_url( __FILE__ ) . 'js/timepicker.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-datatables', plugin_dir_url( __FILE__ ) . 'js/datatables.js', array( 'jquery' ), $this->version, true );
		
		// full calendar
		if(isset($_GET["page"])) {
			if($_GET["page"] == "sbprok_calendar") {
			wp_enqueue_script( $this->plugin_name.'-calendar1', plugin_dir_url( __FILE__ ) . 'js/fullcalendar.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-moment', plugin_dir_url( __FILE__ ) . 'js/moment.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-platform', plugin_dir_url( __FILE__ ) . 'js/platform.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-client', plugin_dir_url( __FILE__ ) . 'js/client.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-js1', plugin_dir_url( __FILE__ ) . 'js/packages/core/main.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-js2', plugin_dir_url( __FILE__ ) . 'js/packages/interaction/main.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-js3', plugin_dir_url( __FILE__ ) . 'js/packages/daygrid/main.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-js4', plugin_dir_url( __FILE__ ) . 'js/packages/timegrid/main.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-js5', plugin_dir_url( __FILE__ ) . 'js/packages/list/main.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-js6', plugin_dir_url( __FILE__ ) . 'js/packages/interaction/main.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-js7', plugin_dir_url( __FILE__ ) . 'js/packages/bootstrap/main.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-js8', plugin_dir_url( __FILE__ ) . 'js/packages/sweetalert/sweetalert.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-js9', plugin_dir_url( __FILE__ ) . 'js/packages/google-calendar/main.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name.'-calendarmain', plugin_dir_url( __FILE__ ) . 'js/full-calendar-init.js', array( 'jquery' ), $this->version, true );
			}
		}	
			
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sbprok-admin.js', array( 'jquery-ui-datepicker','jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'sbprokAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
		
	}

}
