<?php 
/* 
 * Template Name: paypal-sucess-template
 * Template Post Type: post, page
 */

// Include configuration file 
require_once plugin_dir_path( dirname( __FILE__ ) ) . '/paypal-integration/config.php'; 

 global $wpdb;
// If transaction data is available in the URL 
if(!empty($_GET['item_number']) && !empty($_GET['tx']) && !empty($_GET['amt']) && !empty($_GET['cc']) && !empty($_GET['st'])){ 
    // Get transaction information from URL 
    $item_number    = $_GET['item_number'];  
    $txn_id         = $_GET['tx']; 
    $payment_gross  = $_GET['amt']; 
    $currency_code  = $_GET['cc']; 
    $payment_status = $_GET['st']; 
     
    // Check if transaction data exists with the same TXN ID. 
    $prevPaymentResult = $wpd->get_results("SELECT * FROM wp_payments WHERE txn_id = '".$txn_id."'"); 
 
    if($prevPaymentResult->num_rows > 0){ 
        $paymentRow     = $prevPaymentResult->fetch_assoc(); 
        $payment_id     = $paymentRow['payment_id']; 
        $payment_gross  = $paymentRow['payment_gross']; 
        $payment_status = $paymentRow['payment_status']; 
    }else{ 
        // Insert tansaction data into the database 
         $table_name = "wp_payments";        
         $wpdb->insert($table_name, array('item_number' => $item_number, 'txn_id' => $txn_id,'payment_gross' => $payment_gross, 'currency_code' => $currency_code,'payment_status' => $payment_status) ); 
    } 
} 
?>

<div class="container">
    <div class="status">
        <?php if(!empty($payment_id)){ ?>
            <h1 class="success">Your Payment has been Successful</h1>
			
            <h4>Payment Information</h4>
            <p><b>Reference Number:</b> <?php echo $payment_id; ?></p>
            <p><b>Transaction ID:</b> <?php echo $txn_id; ?></p>
            <p><b>Paid Amount:</b> <?php echo $payment_gross; ?></p>
            <p><b>Payment Status:</b> <?php echo $payment_status; ?></p>
		
        <?php }else{ ?>
            <h1 class="error">Your Payment has Failed</h1>
        <?php } ?>
    </div>
    <a href="#" class="btn-link">Back</a>
</div>