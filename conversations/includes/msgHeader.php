<?php
	session_start();
	require_once("../../includes/db.php");
	require_once("../../functions/functions.php");
	if(!isset($_SESSION['seller_user_name'])){
		echo "<script>window.open('../../login','_self')</script>";
	}

	$login_seller_user_name = $_SESSION['seller_user_name'];
	
	$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
	$row_login_seller = $select_login_seller->fetch();
	$login_seller_id = $row_login_seller->seller_id;


	$message_group_id = $input->post('message_group_id');

	$get_inbox_sellers = $db->select("inbox_sellers",array("message_group_id"=>$message_group_id));
	$row_inbox_sellers = $get_inbox_sellers->fetch();
	$offer_id = $row_inbox_sellers->offer_id;
	$sender_id = $row_inbox_sellers->sender_id;
	$receiver_id = $row_inbox_sellers->receiver_id;
	$message_status = $row_inbox_sellers->message_status;
	
	if($login_seller_id == $sender_id){
	
		$seller_id = $receiver_id;
	
	}else{
	
		$seller_id = $sender_id;
	
	}

	$select_seller = $db->select("sellers",array("seller_id"=>$seller_id));
	$row_seller = $select_seller->fetch();
	$seller_image = @$row_seller->seller_image;
	$seller_user_name = @$row_seller->seller_user_name;
	$seller_status = @$row_seller->seller_status;

	if(check_status($seller_id) == "Online"){
		$statusClass = "class='text-success font-weight-bold'";
	}else{	
		$statusClass = "style='color:#868e96; font-weight:bold;'"; 
	}

	$date = date("M d, h:i A");

	$select_starred = $db->select("starred_messages",array("seller_id"=>$login_seller_id,"message_group_id"=>$message_group_id));
	$count_starred = $select_starred->rowCount();

	if($count_starred == 1){ 
		$star = "unstar"; 
		$star_i = "fa-star"; 
	}else{ 
		$star = "star"; 
		$star_i = "fa-star-o"; 
	}

	$select_unread = $db->select("unread_messages",array("seller_id"=>$login_seller_id,"message_group_id"=>$message_group_id));

	$count_unread = $select_unread->rowCount();

	if($count_unread == 1){ 
		$unread = "read"; 
		$unread_i = "fa-envelope-o";
	}else{ 
		$unread = "unread";
		$unread_i = "fa-envelope-open-o";
	}


	$select_archived = $db->select("archived_messages",array("seller_id"=>$login_seller_id,"message_group_id"=>$message_group_id));

	$count_archived = $select_archived->rowCount();

	if($count_archived == 1){ 

		$archive = "unarchive"; 
		$archive_i = "fa-upload";

	}else{ 

		$archive = "archive"; 
		$archive_i = "fa-download";

	}


?>

<p class="float-left pb-0 mb-0">
	
	<strong class="ml-0 pl-0"><?= ucfirst(strtolower($seller_user_name)); ?></strong>
	
	<br>
	
	<span class="text-muted">

	<span <?= $statusClass; ?>><?= check_status($seller_id); ?></span> 
	
	| Local Time <i class="fa fa-clock-o"></i> <?= $date; ?>
	
	</span>

</p>

<p class="float-right">

<?php if($message_status != "empty"){ ?>

	<a href="inbox<?= "?$star=$message_group_id"; ?>" class="btn <?=$star;?>" data-toggle="tooltip" data-placement="bottom" title="<?= ucfirst($star); ?>">

		<i class="fa <?=$star_i;?>"></i>
	
	</a>

	<a href="inbox<?= "?$unread=$message_group_id"; ?>" class="btn unread" data-toggle="tooltip" data-placement="bottom" title="Mark As <?= ucfirst($unread); ?>">

		<i class="fa <?=$unread_i;?>"></i>

	</a>

	<a href="inbox<?= "?$archive=$message_group_id"; ?>" class="btn <?=$archive;?>" data-toggle="tooltip" data-placement="bottom" title="<?= ucfirst($archive); ?>">

		<i class="fa <?=$archive_i;?>"></i>
		
	</a>

	<a href="inbox?hide_seller=<?= $seller_id; ?>" class="btn" data-toggle="tooltip" data-placement="bottom" title="<?= $lang['inbox']['delete']; ?>">
		<i class="fa fa-trash-o"></i>
	</a>

	<?php } ?>
	
	<div class="dropdown float-right d-block d-sm-block d-md-none mt-2">
		
		<a class="dropdown-toggle closeMsgIcon" href="#" role="button" data-toggle="dropdown">
			<i class="mr-3 fa fa-2x fa-ellipsis-v"></i>
		</a>

		<div class="dropdown-menu pt-1 pb-1" style="margin-right: 15px; max-width: 30px !important; min-width: 150px !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-126px, 38px, 0px);" x-placement="bottom-start">
			
			<a href="inbox?hide_seller=<?= $sender_id; ?>" class="dropdown-item">
			<i class="fa fa-trash-o"></i> <?= $lang['inbox']['delete']; ?>
			</a>

			<a href="#" class="dropdown-item closeMsg">
			<i class="fa fa-times"></i> <?= $lang['button']['close']; ?>
			</a>

		</div>
	</div>
</p>

<script>
	
	$('[data-toggle="tooltip"]').tooltip();

</script>