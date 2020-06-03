<?php 
/* 
 * PayPal and database configuration 
 */ 
  
// PayPal configuration 
define('PAYPAL_ID', 'pallukapoor92@gmail.com'); 
define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE 
 
define('PAYPAL_RETURN_URL', 'http://localhost/kynet_plugin/sucess'); 
define('PAYPAL_CANCEL_URL', 'http://localhost/kynet_plugin/cancel'); 
define('PAYPAL_NOTIFY_URL', 'http://localhost/kynet_plugin/ipn'); 
define('PAYPAL_CURRENCY', 'USD'); 
 
// Database configuration 
// define('DB_HOST', 'localhost'); 
// define('DB_USERNAME', 'root'); 
// define('DB_PASSWORD', ''); 
// define('DB_NAME', 'kynet_plugin'); 
 
// Change not required 
define('PAYPAL_URL', (PAYPAL_SANDBOX == true)?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.paypal.com/cgi-bin/webscr");