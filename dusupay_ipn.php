<?php

session_start();
require_once("includes/db.php");
require_once("functions/mailer.php");

function isJSON($string){
   return is_string( $string ) && is_array(json_decode($string,true)) ? true : false;
}

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$enable_paystack = $row_payment_settings->enable_paystack;
$paystack_public_key = $row_payment_settings->paystack_public_key;
$paystack_secret_key = $row_payment_settings->paystack_secret_key;
$enable_dusupay = $row_payment_settings->enable_dusupay;
$dusupay_sandbox = $row_payment_settings->dusupay_sandbox;
$dusupay_currency_code = $row_payment_settings->dusupay_currency_code;
$dusupay_webhook_hash = $row_payment_settings->dusupay_webhook_hash;

if((strtoupper($_SERVER['REQUEST_METHOD'] ) != 'POST')){
   header('HTTP/1.1 400 POST Expected!');
   exit;
}

$body = @file_get_contents('php://input');

if(isJSON($body)){
   $_POST = (array) json_decode($body);
}

$order_id = null;
$dusupay_transactionReference = null;

if(empty($_SERVER['HTTP_WEBHOOK_HASH'])){
   header('HTTP/1.1 400 Missing Webhook hash!');
   exit();
}

if($_SERVER['HTTP_WEBHOOK_HASH'] != $dusupay_webhook_hash){
   header('HTTP/1.1 403 Invalid Webhook hash!');
   exit();
}

$ipn = file_get_contents('php://input');
@$ipn = json_decode($ipn);

if(empty($ipn)){
   header('HTTP/1.1 400 IPN is invalid');
   exit();
}

$reference_no = $ipn->merchant_reference;
   
if(empty($reference_no)) {
   header('HTTP/1.1 403 Invalid Request');
   exit();
}


$get_order = $db->select("dusupay_orders",['reference_no'=>$reference_no]);
$row_order = $get_order->fetch();
$count_order = $get_order->rowCount();

if($count_order != 0){

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
      $p_url = "$site_url/dusupay_order?reference_no=$reference_no";
   }elseif($type == "cart"){
      $p_url = "$site_url/dusupay_order?reference_no=$reference_no";
   }elseif($type == "request_offer"){
      $p_url = "$site_url/dusupay_order?reference_no=$reference_no";
   }elseif($type == "message_offer"){
      $p_url = "$site_url/dusupay_order?reference_no=$reference_no";
   }elseif($type == "featured_listing"){
      $p_url = "$site_url/dusupay_order?reference_no=$reference_no";
   }elseif($type == "orderTip"){
      $p_url = "$site_url/orderIncludes/charge/order/dusupay?reference_no=$reference_no&orderTip=1";
   }elseif($type == "orderExtendTime"){
      $p_url = "$site_url/plugins/videoPlugin/extendTime/charge/order/dusupay?reference_no=$reference_no&extendTime=1";
   }

   $select_buyer = $db->select("sellers",array("seller_id" => $buyer_id));
   $row_buyer = $select_buyer->fetch();
   $buyer_user_name = $row_buyer->seller_user_name;
   $buyer_email = $row_buyer->seller_email;

   if(in_array(strtoupper($ipn->transaction_status), ['COMPLETED'])){

      // check if the amount paid is equal to the order amount.
      if($ipn->request_amount < $total){

         $data = [];
         $data['template'] = "dusupay_order";
         $data['to'] = $buyer_email;
         $data['subject'] = "$site_name: You Have Not Paid The Full Amount.";
         $data['user_name'] = $buyer_user_name;
         $data['message'] = 'Thank you for shopping with us.Your payment was successful, but the amount paid is not the same as the total order amount.<br/>Your order is currently on-hold.<br/>Kindly contact us for more information regarding your order and payment status.';

         send_mail($data);

         exit();

      }

      // check if the currency paid is equal to the order currency.
      if($ipn->request_currency !== $currency) {

         // Add Admin Order Note
         $data = [];
         $data['template'] = "dusupay_order";
         $data['to'] = $buyer_email;
         $data['subject'] = "$site_name: You Payment Currency Is Different.";
         $data['user_name'] = $buyer_user_name;
         $data['message'] = '
         Your dusupay payment is currently on hold.<br />
         Reason: Order currency is different from the your sent payment currency.
         <br/> Order Currency is 
         <strong>'.$currency.'
         </strong> while the your sent payment currency is 
         <strong>'.$ipn->request_currency.' </strong><br />
         <strong>Transaction ID:</strong> '.$ipn->id.' | 
         <strong>Payment Reference:</strong>'.$ipn->internal_reference;

         send_mail($data);

         exit();

      }
      
      $data = [];
      $data['template'] = "dusupay_order_completed";
      $data['to'] = $buyer_email;
      $data['subject'] = "$site_name: Your dusupay payment has been confirmed.";
      $data['user_name'] = $buyer_user_name;
      $data['message'] = 'Thank you for shopping with us. Your dusupay payment has been confirmed.';
      $data['link_url'] = $p_url;
      send_mail($data);

      $update_order = $db->update("dusupay_orders",array("status"=>$ipn->transaction_status),array("reference_no"=>$reference_no));

   }elseif(in_array(strtoupper($ipn->transaction_status), ['FAILED', 'CANCELLED', 'CANCELED'])){
      
         $data = [];
         $data['template'] = "dusupay_order";
         $data['to'] = $buyer_email;
         $data['subject'] = "$site_name: You Payment Has Been {$ipn->transaction_status}";
         $data['user_name'] = $buyer_user_name;
         $data['message'] = '
            Thank you for shopping with us. But Your dusupay payment Has Been '.$ipn->transaction_status.'<br />
            Reason: '.$ipn->message.'
         ';
         send_mail($data);

         $update_order = $db->update("dusupay_orders",array("status"=>$ipn->transaction_status),array("reference_no"=>$reference_no));

   }else{

      $update_order = $db->update("dusupay_orders",array("status"=>$ipn->transaction_status),array("reference_no"=>$reference_no));

   }

}

header('HTTP/1.1 200 OK');