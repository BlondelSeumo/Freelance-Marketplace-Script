<?php

session_start();
require_once("includes/db.php");
require_once("functions/mailer.php");

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();    
$coinpayments_merchant_id = $row_payment_settings->coinpayments_merchant_id;
$coinpayments_ipn_secret = $row_payment_settings->coinpayments_ipn_secret;
$coinpayments_public_key = $row_payment_settings->coinpayments_public_key;
$coinpayments_private_key = $row_payment_settings->coinpayments_private_key;

// Fill these in with the information from your CoinPayments.net account.
$cp_merchant_id = $coinpayments_merchant_id;
$cp_ipn_secret = $coinpayments_ipn_secret;
$cp_debug_email = $site_email_address;

//These would normally be loaded from your database, the most common way is to pass the Order ID through the 'custom' POST field.

function errorAndDie($error_msg) {
    global $cp_debug_email;
    if (!empty($cp_debug_email)) {
        $report = 'Error: '.$error_msg."\n\n";
        $report .= "POST Data\n\n";
        foreach ($_POST as $k => $v) {
            $report .= "|$k| = |$v|\n";
        }
        mail($cp_debug_email, 'CoinPayments IPN Error', $report);
    }
    die('IPN Error: '.$error_msg);
}

if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
    errorAndDie('IPN Mode is not HMAC');
}

if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
    errorAndDie('No HMAC signature sent.');
}

$request = file_get_contents('php://input');
if ($request === FALSE || empty($request)) {
    errorAndDie('Error reading POST data');
}

if (!isset($_POST['merchant']) || $_POST['merchant'] != trim($cp_merchant_id)) {
    errorAndDie('No or incorrect Merchant ID passed');
}

$hmac = hash_hmac("sha512", $request, trim($cp_ipn_secret));
if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
//if ($hmac != $_SERVER['HTTP_HMAC']) { <-- Use this if you are running a version of PHP below 5.6.0 without the hash_equals function
    errorAndDie('HMAC signature does not match');
}

// HMAC Signature verified at this point, load some variables.

$ipn_type =  $input->post('ipn_type');
$txn_id = $input->post('txn_id');
$item_name = $input->post('item_name');
$item_number = $input->post('item_number');
$amount1 = floatval($input->post('amount1'));
$amount2 = floatval($input->post('amount2'));
$currency1 = $input->post('currency1');
$currency2 = $input->post('currency2');
$status = intval($input->post('status'));
$status_text = $input->post('status_text');

// $message = json_encode($input->post());
// $insert = $db->insert("temp_orders",array("content_id"=>$item_number,"message"=>$message));

$get_order = $db->select("temp_orders",['reference_no'=>$item_number,'method'=>'coinpayments']);
$row_order = $get_order->fetch();
$count_order = $get_order->rowCount();

if($count_order != 0){

    // $insert = $db->insert("temp_orders",array("content_id"=>$item_number,"message"=>"$status --- $status_text"));

    $reference_no = $row_order->reference_no;
    $buyer_id = $row_order->buyer_id;
    $content_id = $row_order->content_id;
    $qty = $row_order->qty;
    $price = $row_order->price;
    $total = $row_order->total;
    $delivery_id = $row_order->delivery_id;
    $revisions = $row_order->revisions;
    $minutes = $row_order->minutes;
    $extras = $row_order->extras;
    $currency = $row_order->currency;
    $type = $row_order->type;

    if($type == "proposal"){
      $p_url = "$site_url/crypto_order?reference_no=$reference_no";
    }elseif($type == "cart"){
      $p_url = "$site_url/crypto_order?reference_no=$reference_no";
    }elseif($type == "request_offer"){
      $p_url = "$site_url/crypto_order?reference_no=$reference_no";
    }elseif($type == "message_offer"){
      $p_url = "$site_url/crypto_order?reference_no=$reference_no";
    }elseif($type == "featured_listing"){
      $p_url = "$site_url/crypto_order?reference_no=$reference_no";
    }elseif($type == "orderTip"){
      $p_url = "$site_url/orderIncludes/charge/order/coinpayments?reference_no=$reference_no&orderTip=1";
    }elseif($type == "orderExtendTime"){
      $p_url = "$site_url/plugins/videoPlugin/extendTime/charge/order/coinpayments?reference_no=$reference_no&extendTime=1";
    }

    $select_buyer = $db->select("sellers",array("seller_id" => $buyer_id));
    $row_buyer = $select_buyer->fetch();
    $buyer_user_name = $row_buyer->seller_user_name;
    $buyer_email = $row_buyer->seller_email;

    // if($ipn_type != 'button'){ // Advanced Button payment
    //     die("IPN OK: Not a button payment");
    // }

    //depending on the API of your system, you may want to check and see if the transaction ID $txn_id has already been handled before at this point

    // Check the original currency to make sure the buyer didn't change it.
    if ($currency1 != $currency) {

        $data = [];
        $data['template'] = "dusupay_order";
        $data['to'] = $buyer_email;
        $data['subject'] = "$site_name: You Payment Currency Is Different.";
        $data['user_name'] = $buyer_user_name;
        $data['message'] = '
        Your coinpayments payment is currently on hold.<br />
        Reason: Order currency is different from the your sent payment currency.
        <br/> Order Currency is 
        <strong>'.$currency.'
        </strong> while the your sent payment currency is 
        <strong>'.$currency1.' </strong><br />
        <strong>Transaction ID:</strong> '.$txn_id.' | 
        <strong>Transaction Number:</strong>'.$item_number;
        send_mail($data);

        errorAndDie('Original currency mismatch!');
    }

    // Check amount against order total
    if ($amount1 < $total){
        errorAndDie('Amount is less than order total!');
    }
 
    if ($status >= 100 or $status == 2) {

        /// payment is complete or queued for nightly payout, success
        $data = [];
        $data['template'] = "dusupay_order_completed";
        $data['to'] = $buyer_email;
        $data['subject'] = "$site_name: Your coinpayments payment has been confirmed.";
        $data['user_name'] = $buyer_user_name;
        $data['message'] = 'Thank you for shopping with us. Your coinpayments payment has been confirmed.';
        $data['link_url'] = $p_url;
        send_mail($data);

        $update_order = $db->update("temp_orders",["status"=>'completed'],["reference_no"=>$reference_no]);

    }else if($status < 0) {
        
        /// payment error, this is usually final but payments will sometimes be reopened if there was no exchange rate conversion or with seller consent
        $update_order = $db->update("temp_orders",["status"=>'error'],["reference_no"=>$reference_no]);

    }else{

        /// payment is pending, you can optionally add a note to the order page
        $update_order = $db->update("temp_orders",["status"=>'pending'],["reference_no"=>$reference_no]);

    }

}

die('IPN OK');