<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Sbprok
 * @subpackage Sbprok/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sbprok
 * @subpackage Sbprok/Shortcode
 * @author     Kynet Web Solutiosn <contact@kynetweb.com>
 */
class Sbprok_Shortcode {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $loader ) {

		$this->plugin_name = $plugin_name;
        $this->version     = $version;
		add_shortcode( 'sbprok_booking_widget', array( $this, 'sbprok_shortcode' ) );
		add_shortcode( 'sbprok_paysucess_widget', array( $this, 'sbprok_sucess_shortcode' ) );
		add_shortcode( 'sbprok_paycancel_widget', array( $this, 'sbprok_sucess_shortcode' ) );
		add_shortcode( 'sbprok_payipn_widget', array( $this, 'sbprok_ipn_shortcode' ) );

    }

    /**
	 * Register shortcode
	 *
	 * @since    1.0.0
	 */
    function sbprok_shortcode() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/front-end-template.php';
	}
	
	function sbprok_ipn_shortcode() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/ipn.php';
	}

	function sbprok_sucess_shortcode() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/success.php';
	}
	
	function sbprok_cancel_shortcode() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/success.php';
	}

}
