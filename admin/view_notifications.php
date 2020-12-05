<?php
@session_start();
if(!isset($_SESSION['admin_email']) & !isset($_SESSION['helper_email'])){
echo "<script>window.open('login.php','_self');</script>";
}else{
?>
<div class="breadcrumbs">
<div class="col-sm-4">
<div class="page-header float-left">
    <div class="page-title">
        <h1><i class="menu-icon fa fa-bell"></i> Alerts </h1>
    </div>
</div>
</div>
</div>

<div class="container">
<div class="row"><!--- 2 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
<div class="card"><!--- card Starts --->
<div class="card-header"><!--- card-header Starts --->
<i class="fa fa-money-bill-alt"></i> View Alerts
</div><!--- card-header Ends --->
<div class="card-body"><!--- card-body Starts --->
<table class="table table-bordered table-hover"><!--- table table-bordered table-hover Starts --->

<thead>
<tr>
<th>No:</th>
<th>Message:</th>
<th>Date:</th>
<th>Actions:</th>
</tr>
</thead>

<tbody><!--- tbody Starts --->
<?php
$i = 0;
$select_notofications = $db->select("admin_notifications order By 1 DESC");
while($row_notifications = $select_notofications->fetch()){
	$notification_id = $row_notifications->id;
	$sender_id = $row_notifications->seller_id;
	$content_id = $row_notifications->content_id;
	$proposal_id = $row_notifications->proposal_id;
	$reason = $row_notifications->reason;
	$date = $row_notifications->date;
	$status = $row_notifications->status;
	// Select Seller Details
	$select_seller = $db->select("sellers",array("seller_id" => $sender_id));
	$row_seller = $select_seller->fetch();
	$seller_user_name = $row_seller->seller_user_name;
	$seller_image = getImageUrl2("sellers","seller_image",$row_seller->seller_image);
	
	include("includes/notification_reasons.php");
	$i++;
?>
<tr>
	<td width="100"><?= $i; ?></td>
	<td width="550">
	<?php if(!empty($seller_image)){ ?>
	<img src="<?= $seller_image; ?>" width="40" class="rounded-circle img-fluid">
	<?php }else{ ?>
	<img src="../user_images/empty-image.png" width="40" class="rounded-circle img-fluid">
	<?php } ?>
	&nbsp;&nbsp; <a href="../<?= $seller_user_name; ?>"><?= $seller_user_name; ?></a>
	<?= @$message; ?>
	</td>
	<td width="190"><?= $date; ?></td>
	<td width="290">
	<a href="<?= $url; ?>">
		<i class="fa fa-eye"></i>
		<?php 
			if($reason == "message_spam" OR $reason == "order_spam"){
				echo "View Conversation";
			}elseif($reason == "payout_request"){
				echo "View Payout"; 
			}elseif($reason == "order" or $reason == "cancellation_request" or $reason == "decline_cancellation_request" or $reason == "accept_cancellation_request"){
				echo "View Order"; 
			}else{
				echo "View Report"; 
			}
		?>
	</a>
	&nbsp; | &nbsp;
	<a href="#" onclick="alert_confirm('Do you really want to delete this notification permanently.','index.php?delete_notification=<?= $notification_id; ?>');">
		<i class="fa fa-trash"></i> Delete
	</a>
	</td>
</tr>
<?php } ?>
</tbody><!--- tbody Ends --->
</table><!--- table table-bordered table-hover Ends --->
</div><!--- card-body Ends --->
</div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
</div>
<?php } ?>