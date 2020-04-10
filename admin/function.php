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
function calculatetime($startTime,$duration){
$startTime = date("Y-m-d H:i:s");
//adding 1 hour and 30 minutes to time
$cenvertedTime = date('Y-m-d H:i:s',strtotime('+1 hour +30 minutes',strtotime($startTime)));
return $cenvertedTime;
}


?>