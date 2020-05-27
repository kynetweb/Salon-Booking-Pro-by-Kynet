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

function calculate_end_time($startTime,$duration=null){
    if($duration == null){
       $duraton_add = '+1 hour';
    }else{
        $duration    = explode(':', $duration);
        $duraton_add = '+'.$duration[0].' hour'.' +'.$duration[1].' minutes';
    }
    $cenvertedTime = date(DATE_ISO8601,strtotime($duraton_add,strtotime($startTime)));
    return  $cenvertedTime;
}


?>