<?php

   session_start();
   require_once("../../includes/db.php");
   require_once("$dir/functions/payment.php");
   @require_once("$dir/functions/functions.php");

   if(!isset($_SESSION['seller_user_name'])){
   	echo "<script>window.open('$site_url/login','_self')</script>";
   }

   $order_id = $_SESSION['tipOrderId'];
   $amount = $_SESSION['tipAmount'];

   // proposal
   $processing_fee = processing_fee($amount);

   $data = [];
   $data['type'] = "orderTip";
   $data['content_id'] = $_SESSION['tipOrderId'];
   $data['name'] = "Order Tip Payment";
   $data['desc'] = "";
   $data['qty'] = 1;
   $data['price'] = $amount;
   $data['sub_total'] = $amount;
   $data['processing_fee'] = $processing_fee;
   $data['total'] = $amount+$processing_fee;

   $data['redirect_url'] = "$site_url/orderIncludes/charge/order/stripe";
   $data['cancel_url'] = "$site_url/cancel_payment";

   $payment = new Payment();
   $payment->stripe($data);