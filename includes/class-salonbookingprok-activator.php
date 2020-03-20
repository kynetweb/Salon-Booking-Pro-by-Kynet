<?php

/**
 * Fired during plugin activation
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Salonbookingprok
 * @subpackage Salonbookingprok/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Salonbookingprok
 * @subpackage Salonbookingprok/includes
 * @author     kynet Web <contact@kynetweb.com>
 */
class Salonbookingprok_Activator {

	/**
	 * Short Description. This function fires after plugin activation
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	
	public static function activate() {
		add_role('salonbookingprok_employee', __(
			'Employees','salonbookingprok'),
			array(
				'read'            => true, // Allows a user to read
				'create_posts'      => false, // Allows user to create new posts
				'edit_posts'        => false, // Allows user to edit their own posts
				'edit_others_posts' => false, // Allows user to edit others posts too
				'publish_posts' => false, // Allows the user to publish posts
				'manage_categories' => false, // Allows user to manage post categories
				)
		 );
		 add_role('salonbookingprok_customer', __(
			'Customers','salonbookingprok'),
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
