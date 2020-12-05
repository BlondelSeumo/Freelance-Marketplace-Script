<?php

@session_start();

$dir = str_replace(array("orderIncludes"), '',__DIR__);
require_once("$dir/includes/db.php");
require_once("$dir/functions/functions.php");
require_once("$dir/functions/mailer.php");

if(!isset($_SESSION['seller_user_name'])){
  echo "<script>window.open('login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

@$order_id = $input->get('order_id');

$site_email_address = $row_general_settings->site_email_address;  
$order_auto_complete = $row_general_settings->order_auto_complete;

$get_orders = $db->select("orders",array("order_id" => $order_id));
$row_orders = $get_orders->fetch();
$seller_id = $row_orders->seller_id;
$buyer_id = $row_orders->buyer_id;
$order_price = $row_orders->order_price;
$order_status = $row_orders->order_status;
$order_complete_time = new DateTime($row_orders->complete_time);

//// Get Order Tip  ////
$get_tip = $db->select("order_tips",array("order_id" => $order_id));
$row_tip = $get_tip->fetch();
$count_tip = $get_tip->rowCount();
if($count_tip > 0){
  $tip_amount = $row_tip->amount;
  $tip_message = $row_tip->message;
  $tip_date = $row_tip->date;
}


//// Select Order Buyer Details ///
$select_buyer = $db->select("sellers",array("seller_id" => $buyer_id));
$row_buyer = $select_buyer->fetch();
$buyer_user_name = $row_buyer->seller_user_name;
$buyer_image = getImageUrl2("sellers","seller_image",$row_buyer->seller_image);


$get_order_conversations =  $db->select("order_conversations",array("order_id" => $order_id));
while($row_order_conversations = $get_order_conversations->fetch()){

$c_id = $row_order_conversations->c_id;
$sender_id = $row_order_conversations->sender_id;
$message = $row_order_conversations->message;
$watermark = $row_order_conversations->watermark;
$watermark_file = $row_order_conversations->watermark_file;
$file = $row_order_conversations->file;
$date = $row_order_conversations->date;
$status = $row_order_conversations->status;

$select_seller = $db->select("sellers",array("seller_id" => $sender_id));
$row_seller = $select_seller->fetch();
$seller_user_name = $row_seller->seller_user_name;
$seller_image = getImageUrl2("sellers","seller_image",$row_seller->seller_image);

if($seller_id == $sender_id){
  $receiver_name = "Buyer";
}else{
  $receiver_name = "Seller";
}

if($seller_id == $login_seller_id){
  $receiver_id = $buyer_id;
}else{
  $receiver_id = $seller_id;
}

$last_update_date = date("h:i: M d, Y");
$n_date = date("F d, Y");

?>

<?php if($status == "message"){ ?>

<div class="

<?php 

if($sender_id == $login_seller_id){
	
echo "message-div-hover";
	
}else{
	
echo "message-div";
	
}

?>"><!--- message-div Starts --->
    
<?php if(!empty($seller_image)){ ?>

<img src="<?= $seller_image; ?>" width="50" height="50" class="message-image">

<?php }else{ ?>

<img src="<?= $site_url; ?>/user_images/empty-image.png" width="50" height="50" class="message-image">

<?php } ?>

<h5>

<a href="#" class="seller-buyer-name"> <?= $seller_user_name; ?> </a>

</h5>

<p class="message-desc">

<?= $message; ?>

<?php 

if(!empty($file)){

  if($watermark == 1 AND $order_status != "completed"){ 
    $file_name = $file;
    $d_file = getImageUrl("order_conversations",$watermark_file,'watermark_file');
  }else{
    $file_name = $file;
    $d_file = getImageUrl("order_conversations",$file);
  }

  echo "
    <a href='orderIncludes/download?order_id=$order_id&c_id=$c_id' class='d-block mt-2 ml-1' target='_blank'>
      <i class='fa fa-download'></i> $file_name
    </a>
  ";

}

?>

</p>

<p class="text-right text-muted mb-0" style="font-size: 14px;"> 

<?= $date; ?> 


<?php if($login_seller_id != $sender_id){ ?>

<?php if($login_seller_id == $buyer_id){ ?>

| <a href="#" data-toggle="modal" data-target="#report-modal" class="text-muted"><i class="fa fa-flag"></i> Report</a> 

<?php }else{ ?>

| <a href="#" data-toggle="modal" data-target="#report-modal" class="text-muted"><i class="fa fa-flag"></i> Report</a> 

<?php } ?>

<?php } ?>

</p>

</div><!--- message-div Ends --->


<?php }elseif($status == "delivered"){ ?>

<?php

$remain = $order_complete_time->diff(new DateTime());

if($remain->d < 1){ $remain->d = 1; }

?>

<div class="card mt-4">

 <div class="card-body">

 	<h5 class="text-center">
    <img src="images/svg/box.svg" class="order-icon"/> Order Delivered
  </h5>

  <?php if($seller_id == $login_seller_id){ ?>
  <p class="text-center font-weight-bold pb-0">The buyer has <?= $remain->d; ?> day(s) to complete/respond to this order, otherwise it will be automatically marked as completed.</p>
  <?php } else { ?>

   <p class="text-center font-weight-bold pb-0">You have <?= $remain->d; ?> day(s) to complete/respond to this order, otherwise it will be automatically marked as completed.</p>
  
  <?php } ?>

 </div>

</div>

<div class="

<?php 

if($sender_id == $login_seller_id){
	
echo "message-div-hover";
	
}else{
	
echo "message-div";
	
}

?>

"><!--- message-div Starts --->

<?php if(!empty($seller_image)){ ?>

    <img src="<?= $seller_image; ?>" width="50" height="50" class="message-image">

    <?php }else{ ?>

    <img src="<?= $site_url; ?>/user_images/empty-image.png" width="50" height="50" class="message-image">

<?php } ?>
    
<h5>

<a href="#" class="seller-buyer-name"> <?= $seller_user_name; ?> </a>

</h5>

<p class="message-desc">

<?= $message; ?>

<?php 

if(!empty($file)){

  if($watermark == 1 AND $order_status != "completed"){ 
    $file_name = $file;
    $d_file = getImageUrl("order_conversations",$watermark_file,'watermark_file');
  }else{
    $file_name = $file;
    $d_file = getImageUrl("order_conversations",$file);
  }

  echo "
    <a href='orderIncludes/download?order_id=$order_id&c_id=$c_id' class='d-block mt-2 ml-1' target='_blank'>
      <i class='fa fa-download'></i> $file_name
    </a>
  ";

}

?>

</p>

<p class="text-right text-muted mb-0"> <?= $date; ?> </p>

</div><!--- message-div Ends --->

<?php if($order_status == "delivered"){ ?>

<?php if($buyer_id == $login_seller_id){ ?>
<center class="pb-4 mt-4"><!-- mb-4 mt-4 Starts --->
<form method="post">
<button name="complete" type="submit" class="btn btn-success">
Accept & Review Order
</button>
&nbsp;&nbsp;&nbsp;
<button type="button" data-toggle="modal" data-target="#revision-request-modal" class="btn btn-success">
Request A Revision
</button>
</form>
<?php 
if(isset($_POST['complete'])){
  require_once("orderIncludes/orderComplete.php");
}
?>
</center><!-- mb-4 mt-4 Ends --->
<?php } ?>

<?php } ?>

<?php }elseif($status == "revision"){ ?>
<div class="card mt-4">
  <div class="card-body">
    <h5 class="text-center"><i class="fa fa-pencil-square-o"></i> Revison Requested By <?= $seller_user_name; ?> </h5>
  </div>
</div>
<div class="
<?php 

if($sender_id == $login_seller_id){
	
echo "message-div-hover";
	
}else{
	
echo "message-div";
	
}

?>"><!--- message-div Starts --->
<?php if(!empty($seller_image)){ ?>
  <img src="<?= $seller_image; ?>" width="50" height="50" class="message-image">
    <?php }else{ ?>
  <img src="<?= $site_url; ?>/user_images/empty-image.png" width="50" height="50" class="message-image">
<?php } ?>
    
<h5><a href="#" class="seller-buyer-name"> <?= $seller_user_name; ?> </a></h5>

<p class="message-desc">

<?= $message; ?>

<?php if(!empty($file)){ ?>

<a href="<?= "orderIncludes/download?order_id=$order_id&c_id=$c_id"; ?>" class="d-block mt-2 ml-1" target='_blank'>
  <i class="fa fa-download"></i> <?= $file; ?>
</a>

<?php }else{ ?>

<?php } ?>

</p>

<p class="text-right text-muted mb-0"> <?= $date; ?> </p>

</div><!--- message-div Ends --->


<?php }elseif($status == "cancellation_request"){ ?>

<div class="card mt-4">
  <div class="card-body">
    <h5 class="text-center">
      <img src="images/svg/cancellation.svg" class="order-icon"/>
      Cancellation Requested By <?= $seller_user_name; ?> 
    </h5>
  </div>
</div>


<div class="

<?php 

if($sender_id == $login_seller_id){
	
echo "message-div-hover";
	
}else{
	
echo "message-div";
	
}

?>"><!--- message-div Starts --->

<?php if(!empty($seller_image)){ ?>

    <img src="<?= $seller_image; ?>" width="50" height="50" class="message-image">

        <?php }else{ ?>

    <img src="<?= $site_url; ?>/user_images/empty-image.png" width="50" height="50" class="message-image">

<?php } ?>
    
<h5>

<a href="#" class="seller-buyer-name"> <?= $seller_user_name; ?> </a>

</h5>

<p class="message-desc">

<?= $message; ?>

<?php if(!empty($file)){ ?>

<a href="<?= "orderIncludes/download?order_id=$order_id&c_id=$c_id"; ?>" class="d-block mt-2 ml-1" target='_blank'>

<i class="fa fa-download"></i> <?= $file; ?>

</a>

<?php }else{ ?>


<?php } ?>

</p>

<?php if($sender_id == $login_seller_id){ ?>


<?php }else{ ?>

<form class="mb-2" method="post">

		<center>

			<button name="accept_request" class="btn btn-success btn-sm">Accept Request</button>

			<button name="decline_request" class="btn btn-success btn-sm">Decline Request</button>

	   </center>

	</form>

<?php

if(isset($_POST['accept_request'])){

  $data = [];
  $data['template'] = "order_cancel_seller";
  $data['to'] = $seller_email;
  $data['subject'] = "$site_name: Order Has Been Cancelled.";
  $data['user_name'] = $seller_user_name;
  $data['order_id'] = $order_id;
  send_mail($data);

  $data = [];
  $data['template'] = "order_cancel_buyer";
  $data['to'] = $buyer_email;
  $data['subject'] = "$site_name: Order Has Been Cancelled.";
  $data['user_name'] = $buyer_user_name;
  $data['order_id'] = $order_id;
  send_mail($data);

  $update_messages = $db->update("order_conversations",array("status"=>"accept_cancellation_request"),array("order_id"=>$order_id,"status"=>"cancellation_request"));

  $update_order = $db->update("orders",array("order_status"=>'cancelled',"order_active"=>'no'),array("order_id"=>$order_id));

  $insert_notification = $db->insert("notifications",array("receiver_id" => $receiver_id,"sender_id" => $login_seller_id,"order_id" => $order_id,"reason" => "accept_cancellation_request","date" => $n_date,"status" => "unread"));

  /// sendPushMessage Starts
  $notification_id = $db->lastInsertId();
  sendPushMessage($notification_id);
  /// sendPushMessage Ends

  $update_my_buyers = $db->update("my_buyers",array("completed_orders"=>'completed_orders-1',"amount_spent"=>"amount_spent-$order_price"),array("buyer_id"=>$buyer_id,"seller_id"=>$seller_id));

  $update_my_sellers = $db->update("my_sellers",array("completed_orders"=>'completed_orders-1',"amount_spent"=>"amount_spent-$order_price"),array("seller_id"=>$seller_id,"buyer_id"=>$buyer_id));

  $purchase_date = date("F d, Y");

  $insert_purchase = $db->insert("purchases",array("seller_id" => $buyer_id,"order_id" => $order_id,"amount" => $order_price,"date" => $purchase_date,"method" => "order_cancellation"));

  $update_seller_account = $db->query("update seller_accounts set used_purchases=used_purchases-:minus,current_balance=current_balance+:plus where seller_id='$buyer_id'",array("minus"=>$order_price,"plus"=>$order_price));

  echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";
  	
}


if(isset($_POST['decline_request'])){

  $update_messages = $db->update("order_conversations",array("status"=>"decline_cancellation_request"),array("order_id"=>$order_id,"status"=>"cancellation_request"));

  $update_order = $db->update("orders",array("order_status"=>'progress'),array("order_id"=>$order_id));

  $insert_notification = $db->insert("notifications",array("receiver_id" => $receiver_id,"sender_id" => $login_seller_id,"order_id" => $order_id,"reason" => "decline_cancellation_request","date" => $n_date,"status" => "unread"));

  echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";

}

?>

<?php } ?>

<p class="text-right text-muted mb-0"> <?= $date; ?> </p>

</div><!--- message-div Ends --->


<?php }elseif($status == "decline_cancellation_request"){ ?>


<div class="card mt-4">

  <div class="card-body">

    <h5 class="text-center">
      <img src="images/svg/cancellation.svg" class="order-icon"/>
      Cancellation Request Declined By <?= $seller_user_name; ?>
    </h5>

  </div>

</div>


<div class="<?php 

if($sender_id == $login_seller_id){
  echo "message-div-hover";
}else{
  echo "message-div";
}

?>"><!--- message-div Starts --->

<?php if(!empty($seller_image)){ ?>

  <img src="<?= $seller_image; ?>" width="50" height="50" class="message-image">

  <?php }else{ ?>

  <img src="<?= $site_url; ?>/user_images/empty-image.png" width="50" height="50" class="message-image">

<?php } ?>
    
<h5>

<a href="#" class="seller-buyer-name"> <?= $seller_user_name; ?> </a>

</h5>

<p class="message-desc">

<?= $message; ?>

<?php if(!empty($file)){ ?>

<a href="<?= "orderIncludes/download?order_id=$order_id&c_id=$c_id"; ?>" class="d-block mt-2 ml-1" target='_blank'>

<i class="fa fa-download"></i> <?= $file; ?>

</a>

<?php }else{ ?>


<?php } ?>

</p>


<p class="text-right text-muted mb-0"> <?= $date; ?> </p>

</div><!--- message-div Ends --->

<div class="order-status-message"><!--- order-status-message Starts --->

<i class="fa fa-times fa-3x text-danger"></i>

<h5 class="text-danger">

Cancellation Request Declined By <?= $receiver_name; ?>

</h5>

</div><!--- order-status-message Ends --->

<?php }elseif($status == "accept_cancellation_request"){ ?>

<div class="card mt-4">

   <div class="card-body">

   	<h5 class="text-center">
      <img src="images/svg/cancellation.svg" class="order-icon"/>
      Cancellation Request By <?= $seller_user_name; ?>
    </h5>

   </div>


</div>



<div class="

<?php 

if($sender_id == $login_seller_id){
	
echo "message-div-hover";
	
}else{
	
echo "message-div";
	
}

?>

"><!--- message-div Starts --->

<?php if(!empty($seller_image)){ ?>

  <img src="<?= $seller_image; ?>" width="50" height="50" class="message-image">

<?php }else{ ?>

  <img src="<?= $site_url; ?>/user_images/empty-image.png" width="50" height="50" class="message-image">

<?php } ?>
    
<h5>

<a href="#" class="seller-buyer-name"> <?= $seller_user_name; ?> </a>

</h5>

<p class="message-desc">

<?= $message; ?>

<?php if(!empty($file)){ ?>

<a href="<?= "orderIncludes/download?order_id=$order_id&c_id=$c_id"; ?>" class="d-block mt-2 ml-1" target='_blank'>
  <i class="fa fa-download"></i> <?= $file; ?>
</a>

<?php }else{ ?>


<?php } ?>

</p>


<p class="text-right text-muted mb-0"> <?= $date; ?> </p>

</div><!--- message-div Ends --->


<?php if($seller_id == $login_seller_id){ ?>

<div class="order-status-message"><!-- order-status-message Starts --->

<i class="fa fa-times fa-3x text-danger"></i>

<h5 class="text-danger"> Order Cancelled By Mutual Agreement. </h5>

<p>

Order Was Cancelled By A Mutual Agreement Between You and Your Buyer. <br>

Funds have been refunded to buyer's account.

</p>

</div><!-- order-status-message Ends --->

<?php }else{ ?>

<div class="order-status-message"><!-- order-status-message Starts --->

<i class="fa fa-times fa-3x text-danger"></i>

<h5 class="text-danger"> Order Cancelled By Mutual Agreement. </h5>

<p>

Order was cancelled by a mutual agreement between you and your seller.<br>

The order funds have been refunded to your Shopping Balance.

</p>

</div><!-- order-status-message Ends --->


<?php } ?>


<?php }elseif($status == "cancelled_by_customer_support"){ ?>


  <?php if($seller_id == $login_seller_id){ ?>

  <div class="order-status-message"><!-- order-status-message Starts --->

  <i class="fa fa-times fa-3x text-danger"></i>

  <h5 class="text-danger"> Order Cancelled By Admin. </h5>

  <p>
  Payment For This Order Was Refunded To Buyer's Shopping Balance. <br>
  For Any Further Assistance, Please Contact Our <a href="/customer_support" class="link">Customer Support.</a>
  </p>

  </div><!-- order-status-message Ends --->

  <?php }else{ ?>


  <div class="order-status-message"><!-- order-status-message Starts --->

  <i class="fa fa-times fa-3x text-danger"></i>

  <h5 class="text-danger"> Order Cancelled By Customer Support. </h5>

  <p>

  Payment For This Order Has Been Refunded To Your <a href="revenue" class="link"> Shopping balance. </a>.

  </p>

  </div><!-- order-status-message Ends --->

  <?php } ?>

  <?php } ?>

<?php } ?>

<?php if($count_tip > 0 AND $login_seller_id == $seller_id){ ?>
  <div class="card mt-4 mb-0">
    <div class="card-body">
      <center>
        <h4>  
          <img src="images/svg/tip.svg" class="order-icon"/> <?= $lang['order_details']['seller_tip']['title']; ?>
        </h4>
        <p class="text-muted"><?= $lang['order_details']['seller_tip']['desc']; ?></p>
        <h3 class="text-success mb-1"><?= showPrice($tip_amount); ?></h3>
      </center>
      <?php if(!empty($tip_message)){ ?>
      
        <div class="message-div mt-3"><!--- message-div Starts --->

          <?php if(!empty($buyer_image)){ ?>
            <img src="<?= $buyer_image; ?>" width="50" height="50" class="message-image">
          <?php }else{ ?>
            <img src="<?= $site_url; ?>/user_images/empty-image.png" width="50" height="50" class="message-image">
          <?php } ?>
              
          <h5><a href="#" class="seller-buyer-name"> <?= $buyer_user_name; ?> </a></h5>

          <p class="message-desc"><?= $tip_message; ?></p>
          <p class="text-right text-muted mb-0"> <?= $tip_date; ?> </p>

        </div><!--- message-div Ends --->

      <?php } ?>

    </div>
  </div>
<?php }else if($count_tip > 0 AND $login_seller_id == $buyer_id){ ?>

  <div class="card mt-4 mb-0">
    <div class="card-body text-center">
      <h4> 
        <img src="images/svg/tip.svg" class="order-icon" width="15" height="15"/>
        <?php  
          $t_amount = showPrice($tip_amount);
          echo str_replace("{amount}",$t_amount,$lang['order_details']['tip_given']); 
        ?>
      </h4>
    </div>
  </div>

  <?php if(!empty($tip_message)){ ?>
  
    <div class="message-div"><!--- message-div Starts --->

      <?php if(!empty($buyer_image)){ ?>
        <img src="<?= $buyer_image; ?>" width="50" height="50" class="message-image">
      <?php }else{ ?>
        <img src="<?= $site_url; ?>/user_images/empty-image.png" width="50" height="50" class="message-image">
      <?php } ?>
      <h5><a href="#" class="seller-buyer-name"> <?= $buyer_user_name; ?> </a></h5>
      <p class="message-desc"><?= $tip_message; ?></p>
      <p class="text-right text-muted mb-0"> <?= $tip_date; ?> </p>

    </div><!--- message-div Ends --->

  <?php } ?>

<?php } ?>