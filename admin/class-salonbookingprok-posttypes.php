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
class Salonbookingprok_Posttypes {

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
		wp_enqueue_style( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
		

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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/salonbookingprok-admin.js', array( 'jquery' ), $this->version, true );
	}
	/**
	 * Register post type for Services.
	 *
	 * @since    1.0.0
	 */

	function register_services() {
		$labels = array(
			'name'                  => _x( 'Service', 'post type general name', 'salonbookingprok' ),
			'singular_name'         => _x( 'Services', 'post type singular name', 'salonbookingprok' ),
			'menu_name'             => _x( 'Services', 'admin menu', 'salonbookingprok' ),
			'name_admin_bar'        => _x( 'Services', 'add new on admin bar', 'salonbookingprok' ),
			'add_new'               => _x( 'Add New', 'Services', 'salonbookingprok' ),
			'add_new_item'          => __( 'Add New Services', 'salonbookingprok' ),
			'new_item'              => __( 'New Services', 'salonbookingprok' ),
			'edit_item'             => __( 'Edit Services', 'salonbookingprok' ),
			'view_item'             => __( 'View Services', 'salonbookingprok' ),
			'view_items'            => __('View %s', 'salonbookingprok'),
			'all_items'             => __( 'All Services', 'salonbookingprok' ),
			'search_items'          => __( 'Search Services', 'salonbookingprok' ),
			'parent_item_colon'     => __( 'Parent Services:', 'salonbookingprok' ),
			'not_found'             => __( 'No Services found.', 'salonbookingprok' ),
			'not_found_in_trash'    => __( 'No Services found in Trash.', 'salonbookingprok' ),
			'archives'              =>  __('%s Archives', 'salonbookingprok'),
			'attributes'            =>  __('%s Attributes', 'salonbookingprok'),
			'update_item'           =>  __('Update %s', 'salonbookingprok'),
			'featured_image'        =>  __( 'Featured image', 'salonbookingprok' ),
            'set_featured_image'    =>  __( 'Set featured image', 'salonbookingprok' ),
            'remove_featured_image' =>  __( 'Remove featured image', 'salonbookingprok' ),
            'use_featured_image'    =>  __( 'Use as featured image', 'salonbookingprok' ),
            'items_list'            =>  __('%s list', 'salonbookingprok'),
			'items_list_navigation' =>  __('%s list navigation', 'salonbookingprok'),
			'description'           => __( 'Demo', 'salonbookingprok' ),
            'filter_items_list'     =>  __('Filter %s list', 'salonbookingprok')
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
	}

	function register_appointments() {
		$supports = array ('');
		$labels = array(
		'name'                  => _x( 'Bookings', 'post type general name', 'salonbookingprok' ),
		'singular_name'         => _x( 'Booking', 'post type singular name', 'salonbookingprok' ),
		'menu_name'             => _x( 'Bookings', 'admin menu', 'salonbookingprok' ),
		'name_admin_bar'        => _x( 'Bookings', 'add new on admin bar', 'salonbookingprok' ),
		'add_new'               => _x( 'Add New', 'Booking', 'salonbookingprok' ),
		'add_new_item'          => __( 'Add New Bookings', 'salonbookingprok' ),
		'new_item'              => __( 'New Bookings', 'salonbookingprok' ),
		'edit_item'             => __( 'Edit Booking', 'salonbookingprok' ),
		'view_item'             => __( 'View Bookings', 'salonbookingprok' ),
		'view_items'            => __('View %s', 'salonbookingprok'),
		'all_items'             => __( 'All Bookings', 'salonbookingprok' ),
		'search_items'          => __( 'Search Bookings', 'salonbookingprok' ),
		'parent_item_colon'     => __( 'Parent Bookings:', 'salonbookingprok' ),
		'not_found'             => __( 'No Bookings found.', 'salonbookingprok' ),
		'not_found_in_trash'    => __( 'No Bookings found in Trash.', 'salonbookingprok' ),
		'archives'              => __('%s Archives', 'salonbookingprok'),
		'attributes'            => __('%s Attributes', 'salonbookingprok'),
		'update_item'           => __('Update %s', 'salonbookingprok'),
		'featured_image'        => __( 'featured image', 'salonbookingprok' ),
		'set_featured_image'    => __( 'Set featured image', 'salonbookingprok' ),
		'remove_featured_image' => __( 'Remove featured image', 'salonbookingprok' ),
		'use_featured_image'    => __( 'Use as featured image', 'salonbookingprok' ),
		'items_list'            => __('%s list', 'salonbookingprok'),
		'items_list_navigation' => __('%s list navigation', 'salonbookingprok'),
		'filter_items_list'     => __('Filter %s list', 'salonbookingprok')
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
		
		register_post_type( 'sbprok_appoints', $args );
		}

	/**
	 * Register Taxonomy for Services.
	 *
	 * @since    1.0.0
	 */

	function service_hierarchical_taxonomy() {

		  $labels = array(
			'name'              => _x( 'Services Category', 'taxonomy general name','salonbookingprok' ),
			'singular_name'     => _x( 'Services Category', 'taxonomy singular name','salonbookingprok' ),
			'search_items'      =>  __( 'Search Services Category','salonbookingprok' ),
			'all_items'         => __( 'All Services Category','salonbookingprok' ),
			'parent_item'       => __( 'Parent Services Category','salonbookingprok' ),
			'parent_item_colon' => __( 'Parent Services Category:' ),
			'edit_item'         => __( 'Edit Services Category','salonbookingprok' ), 
			'update_item'       => __( 'Update Services Category','salonbookingprok' ),
			'add_new_item'      => __( 'Add New Services Category','salonbookingprok' ),
			'new_item_name'     => __( 'New Services Category Name','salonbookingprok' ),
			'menu_name'         => __( 'Services Categories','salonbookingprok' ),
		  );    
		 
		  register_taxonomy('sbprok_category', array('sbprok_services'), array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'sbprok_category' ),
		  ));
		 
	}
}

?>