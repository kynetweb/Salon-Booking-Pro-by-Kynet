<?php 
/**
 * The file that defines the core plugin functions
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Sbprok
 * @subpackage Sbprok/includes
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
 * @package    Sbprok
 * @subpackage Sbprok/includes
 * @author     kynet Web <contact@kynetweb.com>
 */

function datetime_conversion($datetime){
	return date(DATE_ISO8601, strtotime($datetime));
}

function calculate_end_time($startTime){
    $cenvertedTime = date(DATE_ISO8601,strtotime('+1 hour',strtotime($startTime)));
    return $cenvertedTime;
}


?>