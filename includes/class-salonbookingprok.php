<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Salonbookingprok
 * @subpackage Salonbookingprok/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Salonbookingprok
 * @subpackage Salonbookingprok/includes
 * @author     kynet Web <contact@kynetweb.com>
 */
class Salonbookingprok {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Salonbookingprok_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SALONBOOKINGPROK_VERSION' ) ) {
			$this->version = SALONBOOKINGPROK_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'salonbookingprok';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Salonbookingprok_Loader. Orchestrates the hooks of the plugin.
	 * - Salonbookingprok_i18n. Defines internationalization functionality.
	 * - Salonbookingprok_Admin. Defines all hooks for the admin area.
	 * - Salonbookingprok_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-salonbookingprok-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-salonbookingprok-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-salonbookingprok-admin.php';

		/**
		 * The class responsible for all admin pages
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-salonbookingprok-pages.php';

		/**
		 * The class responsible for defining all custom post types
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-salonbookingprok-posttypes.php';

		/**
		 * The class responsible for defining all custom meta boxes in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-salonbookingprok-meta.php';

		/**
		 * The class responsible for defining admin ajax functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-salonbookingprok-ajax.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-salonbookingprok-public.php';

		$this->loader = new Salonbookingprok_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Salonbookingprok_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Salonbookingprok_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		//init admin classes
		$plugin_admin    = new Salonbookingprok_Admin( $this->get_plugin_name(), $this->get_version() );
		$admin_pages     = new Salonbookingprok_Pages( $this->get_plugin_name(), $this->get_version() );
		$admin_posttypes = new Salonbookingprok_Posttypes( $this->get_plugin_name(), $this->get_version() );
		$admin_meta      = new Salonbookingprok_Meta( $this->get_plugin_name(), $this->get_version() );
		$admin_ajax      = new Salonbookingprok_Ajax( $this->get_plugin_name(), $this->get_version() );

		// add admin pages
		$this->loader->add_action( 'admin_menu', $admin_pages, 'menu_pages'); 
		//setting tabs
		$this->loader->add_action('admin_init', $admin_pages, 'saloon_setting_options');
		
		// admin script and styles
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// script in head
		$this->loader->add_action( 'admin_head', $plugin_admin, 'my_profile_upload_js' );

		// custom post types & meta
		$this->loader->add_action( 'init', $admin_posttypes, 'register_services' );
		$this->loader->add_action( 'init', $admin_posttypes, 'register_appointments' );
		$this->loader->add_action( 'add_meta_boxes', $admin_meta,'service_meta_boxes');
		$this->loader->add_action( 'add_meta_boxes', $admin_meta,'appointment_meta_boxes');
		$this->loader->add_action( 'save_post', $admin_meta,'save_service_meta_boxes', 0 );
		$this->loader->add_action( 'save_post', $admin_meta,'save_appointment_meta_boxes', 1 );

		
		$this->loader->add_action( 'wp_ajax_get_bookings',$admin_ajax, 'get_bookings');
		$this->loader->add_action( 'wp_ajax_get_availbility',$admin_ajax, 'get_availbility');
		$this->loader->add_action( 'wp_ajax_get_service_employees',$admin_ajax, 'get_service_employees');
		$this->loader->add_action( 'wp_ajax_get_ajax_data_requests',$admin_ajax, 'get_ajax_data_requests');
		 
		// employee address 
		$this->loader->add_action('show_user_profile', $admin_pages, 'user_custom_fields' );
		$this->loader->add_action( 'edit_user_profile', $admin_pages, 'user_custom_fields' );
		$this->loader->add_action( 'personal_options_update', $admin_pages, 'save_user_custom_fields' );
		$this->loader->add_action('edit_user_profile_update', $admin_pages, 'save_user_custom_fields' );
		
		
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Salonbookingprok_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Salonbookingprok_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
