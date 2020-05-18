<?php

/**
 * Fired during plugin activation
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Sbprok
 * @subpackage Sbprok/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sbprok
 * @subpackage Sbprok/includes
 * @author     Kynet Web Solutiosn <contact@kynetweb.com>
 */
class Sbprok_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_role('sbprok_employee', __(
			'Employee','sbprok'),
			array(
				'read'            => true, // Allows a user to read
				'create_posts'      => false, // Allows user to create new posts
				'edit_posts'        => false, // Allows user to edit their own posts
				'edit_others_posts' => false, // Allows user to edit others posts too
				'publish_posts' => false, // Allows the user to publish posts
				'manage_categories' => false, // Allows user to manage post categories
				)
		 );
		 add_role('sbprok_customer', __(
			'Customer','sbprok'),
			array(
				'read'            => true, // Allows a user to read
				'create_posts'      => false, // Allows user to create new posts
				'edit_posts'        => false, // Allows user to edit their own posts
				'edit_others_posts' => false, // Allows user to edit others posts too
				'publish_posts' => false, // Allows the user to publish posts
				'manage_categories' => false, // Allows user to manage post categories
				)
		 );

	}

}
