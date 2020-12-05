<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	
?>


<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-comments"></i> Inbox Messages / Chat</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Single Message</li>
                        </ol>
                    </div>
                </div>
            </div>
    
    </div>

<div class="container">
    
    <div class="card"><!--- card Starts --->


<div class="card-header"><!--- card-header Starts --->

<h4 class="h4">

View Single Conversations

</h4>

</div><!--- card-header Ends --->


<div class="card-body"><!--- card-body Starts ---> 

<?php

$single_messages_id = $input->get('single_inbox_message');


$get_inbox_messages = $db->select("inbox_messages",array("message_group_id" => $single_messages_id));

while($row_inbox_messages = $get_inbox_messages->fetch()){

$message_id = $row_inbox_messages->message_id;
$message_sender = $row_inbox_messages->message_sender;
$message_desc = $row_inbox_messages->message_desc;
$message_date = $row_inbox_messages->message_date;
$message_group_id = $row_inbox_messages->message_group_id;
$message_file = $row_inbox_messages->message_file;
$message_offer_id = $row_inbox_messages->message_offer_id;


if(!$message_offer_id == 0){

$select_offer = $db->select("messages_offers",array("offer_id" => $message_offer_id));  

$row_offer = $select_offer->fetch();

$sender_id = $row_offer->sender_id;
$proposal_id = $row_offer->proposal_id;
$description = $row_offer->description;
$order_id = $row_offer->order_id;
$delivery_time = $row_offer->delivery_time;
$amount = $row_offer->amount;
$offer_status = $row_offer->status;	

$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
$row_proposals = $select_proposals->fetch();
$proposal_title = $row_proposals->proposal_title;
$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);

}


$select_sender = $db->select("sellers",array("seller_id" => $message_sender));
$row_sender = $select_sender->fetch();
$sender_user_name = $row_sender->seller_user_name;
$sender_image = $row_sender->seller_image;

?>

<div class="message-div"><!--- message-div Starts --->

<?php 

if(!empty($sender_image)){

?>

<img src="../user_images/<?= $sender_image; ?>" class="message-image">

<?php }else{ ?>

<img src="../user_images/empty-image.png" class="message-image">

<?php } ?>

<h5><?= $sender_user_name; ?></h5>

<p class="message-desc">

<?= $message_desc; ?>

<?php if(!empty($message_file)){ ?>

<a href="../conversations/conversations_files/<?= $message_file; ?>" download class="d-block mt-2 ml-1 text-primary">

<i class="fa fa-download" ></i> <?= $message_file; ?>

</a>

<?php }else{ ?>

<?php } ?>

</p>

<?php if(!$message_offer_id == 0){ ?>

<div class="message-offer"><!--- message-offer Starts --->

<div class="row"><!--- row Starts --->

<div class="col-lg-2 col-md-3"><!--- col-lg-2 col-md-3 Starts --->

<img src="<?= $proposal_img1; ?>" class="img-fluid">

</div><!--- col-lg-2 col-md-3 Ends --->

<div class="col-lg-10 col-md-9"><!--- col-lg-10 col-md-9 Starts --->

<h5 class="mt-md-0 mt-2">

<?= $proposal_title; ?>

<span class="price float-right d-sm-block d-none"> <?= showPrice($amount); ?> </span>

</h5>

<p><?= $description; ?></p>

<p class="d-block d-sm-none"> <b> Price / Amount : </b> <?= showPrice($amount); ?> </p>

<p> <b> Delivery Time : </b> <?= $delivery_time; ?> </p>

<?php if($offer_status == "active"){ ?>

<button class="btn btn-success rounded-0 mt-2 float-right">

Offer has not been accepted yet.

</button>

<?php }elseif($offer_status == "accepted"){ ?>

<p class="float-right">

<a class="btn" href="../order_details.php?order_id=<?= $order_id; ?>" target="_blank">

View Order

</a>

<button class="btn btn-success rounded-0" disabled>

Offer Accepted

</button>

</p>

<?php } ?>

</div><!--- col-lg-10 col-md-9 Ends --->

</div><!--- row Ends --->

</div><!--- message-offer Ends --->

<?php } ?>

</div><!--- message-div Ends --->

<?php } ?>

</div><!--- card-body Ends ---> 


</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

<?php } ?>

<div class="clearfix"></div>
