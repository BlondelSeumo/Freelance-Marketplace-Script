<?php
   
   require_once("$dir/functions/functions.php");
   // require_once("$dir/functions/processing_fee.php");

   // select payment_settings
   $get_payment_settings = $db->select("payment_settings");
   $row_payment_settings = $get_payment_settings->fetch();
   $days_before_withdraw = $row_payment_settings->days_before_withdraw;

   $order_id = $_SESSION['tipOrderId'];
   $amount = $_SESSION['tipAmount'];
   $message = $_SESSION['tipMessage'];
   $payment_method = $_SESSION['method'];
   $date = date("F d, Y");

   // Get Order Details
   $get_order = $db->select("orders",array("order_id"=>$order_id));
   $count_orders = $get_order->rowCount();
   $row_order = $get_order->fetch();
   $buyer_id = $row_order->buyer_id;
   $seller_id = $row_order->seller_id;

   /// Select Order Seller Details ///
   $select_seller = $db->select("sellers",array("seller_id" => $seller_id));
   $row_seller = $select_seller->fetch();
   $seller_user_name = $row_seller->seller_user_name;
   $seller_email = $row_seller->seller_email;
   $seller_phone = $row_seller->seller_phone;

   //// Select Order Buyer Details ///
   $select_buyer = $db->select("sellers",array("seller_id" => $buyer_id));
   $row_buyer = $select_buyer->fetch();
   $buyer_user_name = $row_buyer->seller_user_name;
   $buyer_email = $row_buyer->seller_email;

   $processing_fee = processing_fee($amount);
   $total_amount = $amount+$processing_fee;

   $insert_tip = $db->insert("order_tips",array("order_id"=>$order_id,"amount"=>$amount,"message"=>$message,"date"=>$date));

   if($insert_tip){

      if($payment_method == "shopping_balance"){
         $insert_purchase = $db->insert("purchases",array("seller_id"=>$buyer_id,"order_id"=>$order_id,"reason"=>"order_tip","amount"=>$amount,"date"=>$date,"method"=>$payment_method));
      }else{
         $insert_purchase = $db->insert("purchases",array("seller_id"=>$buyer_id,"order_id"=>$order_id,"reason"=>"order_tip","amount"=>$total_amount,"date"=>$date,"method"=>$payment_method));
      }

      // update seller account
      $update_seller_account = $db->query("update seller_accounts set pending_clearance=pending_clearance+:p_plus,month_earnings=month_earnings+:m_plus where seller_id='$seller_id'",array("p_plus"=>$amount,"m_plus"=>$amount));

      // insert seller revenue
      $revenue_date = date("F d, Y", strtotime(" + $days_before_withdraw days"));
      $end_date = date("F d, Y H:i:s", strtotime(" + $days_before_withdraw days"));

      $insert_revenue = $db->insert("revenues",array("seller_id" => $seller_id,"order_id" => $order_id,"reason" => "order_tip","amount" => $amount,"date" => $revenue_date,"end_date" => $end_date,"status" => "pending"));

      $insert_notification = $db->insert("notifications",array("receiver_id"=>$seller_id,"sender_id"=>$buyer_id,"order_id"=>$order_id,"reason"=>"order_tip","date"=>$date,"status"=>"unread"));

      /// sendPushMessage Starts
      $notification_id = $db->lastInsertId();
      sendPushMessage($notification_id);
      /// sendPushMessage Ends

      if($notifierPlugin == 1){ 

         $smsText = str_replace('{buyer_user_name}',$buyer_user_name,$lang['notifier_plugin']['order_tip']);
         sendSmsTwilio("",$smsText,$seller_phone);

      }

      // get admin profit - Insert sale
      if($payment_method == "shopping_balance"){
         $adminProfit = 0;
      }else{
         $adminProfit = $processing_fee;
      }
      $sale = array("buyer_id" => $buyer_id,"work_id" => $order_id,"payment_method" => $payment_method,"amount" => $amount,"profit"=> $adminProfit,"processing_fee"=>$adminProfit,"action"=>"order_tip","date"=>date("Y-m-d"));
      insertSale($sale);

      unset($_SESSION['tipOrderId']);
      unset($_SESSION['tipAmount']);
      unset($_SESSION['tipMessage']);
      unset($_SESSION['method']);

      echo "
      <script>
        window.open('$site_url/order_details?order_id=$order_id','_self');
      </script>";

   }