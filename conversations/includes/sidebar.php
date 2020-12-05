<div class="specfic col-md-3 p-md-0 <?=($lang_dir == "right" ? 'order-2 order-sm-1 border-left':'')?>">
<div class="card border-0 rounded-0 m-0">
  <div class="card-header bg-transparent inboxHeader">
	<div class="search-bar d-none"><!--- search-bar Starts --->
	<div class="input-group"><!--- input-group Starts --->
      <input type="text" class="form-control" placeholder="Search for a username">
      <span class="input-group-addon"> <a href="#">Close</a> </span>
    </div><!--- input-group Ends --->
	</div><!--- search-bar Ends --->
    <div class="dropdown float-left mt-1"><!--- dropdown float-left mt-1 Starts --->
			<a class="dropdown-toggle" href="#" data-toggle="dropdown">All Conversations</a>
			<div class="dropdown-menu">
				<a href="#" class="dropdown-item" id="all"><?= $lang['inbox']['all']; ?></a>
				<a href="#" class="dropdown-item" id="unread"><?= $lang['inbox']['unread']; ?></a>
				<a href="#" class="dropdown-item" id="starred"><?= $lang['inbox']['starred']; ?></a>
				<a href="#" class="dropdown-item" id="archived"><?= $lang['inbox']['archived']; ?></a>
			</div>
	 </div><!--- dropdown float-left mt-1 Ends --->
	<div class="float-right mb-1"><!--- float-right mb-1 Starts --->
	<a href="#" class="text-muted search-icon"> <i class="fa fa-lg fa-search"></i> </a>
	</div><!--- float-right mb-1 Ends --->
  </div>
  <div class="card-body p-0">
	<ul class="list-unstyled">
	<?php
	// $get_inbox_sellers = $db->query("select * from inbox_sellers where sender_id='$login_seller_id' AND NOT message_status='empty' or receiver_id='$login_seller_id' AND NOT message_status='empty' order by 1 DESC");

	$inboxQuery = "select * from inbox_sellers where (receiver_id=:r_id or sender_id=:s_id) AND NOT message_status='empty' order by time DESC";
	$select_inbox_sellers = $db->query($inboxQuery,array("r_id"=>$login_seller_id,"s_id"=>$login_seller_id));
	$count_inbox_sellers = $select_inbox_sellers->rowCount();
	while($row_inbox_sellers = $select_inbox_sellers->fetch()){
	$message_sender = $row_inbox_sellers->sender_id;
	$message_receiver = $row_inbox_sellers->receiver_id;
	$message_id = $row_inbox_sellers->message_id;
	$message_status = $row_inbox_sellers->message_status;
	$message_group_id = $row_inbox_sellers->message_group_id;
	if($login_seller_id == $message_sender){
		$sender_id = $message_receiver;
	}else{
		$sender_id = $message_sender;
	}

	$select_inbox_message = $db->select("inbox_messages",array("message_id" => $message_id));
	$row_inbox_message = $select_inbox_message->fetch();
	$message_file = $row_inbox_message->message_file;
	$message_desc = strip_tags($row_inbox_message->message_desc,"<img>");
	$message_date = $row_inbox_message->message_date;

 	$dateAgo = $row_inbox_message->dateAgo;
	if($message_desc == ""){
	  $message_desc = "Sent you an offer";  
	}
	$select_sender = $db->select("sellers",array("seller_id" => $sender_id));
	$row_sender = $select_sender->fetch();
	$sender_user_name = $row_sender->seller_user_name;
	$sender_image = getImageUrl2("sellers","seller_image",$row_sender->seller_image);
	if(empty($sender_image)){
		$sender_image = "empty-image.png";
	}
	$select_starred = $db->select("starred_messages",array("seller_id"=>$login_seller_id,"message_group_id"=>$message_group_id));
	$count_starred = $select_starred->rowCount();
	$starred = "";
	if($count_starred == 1){
	$starred = "starred";
	}
	$select_archived = $db->select("archived_messages",array("seller_id"=>$login_seller_id,"message_group_id"=>$message_group_id));
	$count_archived = $select_archived->rowCount();
	$archived = "";
	if($count_archived == 1){
	$archived = "archived";
	}
	$select_hide_seller_messages = $db->select("hide_seller_messages",array("hider_id"=>$login_seller_id,"hide_seller_id"=>$sender_id));
	$count_hide_seller_messages = $select_hide_seller_messages->rowCount();
	if($count_hide_seller_messages == 0){
	$selected="";
	if($login_seller_id == $message_receiver){
    if($message_status == "unread"){
    	$selected="unread selected";
    }
  	}
  	if($message_group_id == @$_GET['single_message_id']){
  	$selected = "selected";
  	}
	$select_unread = $db->select("unread_messages",array("seller_id"=>$login_seller_id,"message_group_id"=>$message_group_id));
	$count_unread = $select_unread->rowCount();
	if($count_unread == 1){ 
	$selected = "unread selected";
	}
	?>
	<a href="#" class="message-recipients media border-bottom <?= $selected; ?> <?= $starred; ?> <?= $archived; ?>" data-username="<?= $sender_user_name; ?>" data-id="<?= $message_group_id; ?>">
    <img src="<?= $sender_image; ?>" class="rounded-circle mr-3" width="50">
    <div class="media-body nowrap">
      <h6 class="mt-0 mb-1">
      	<?= $sender_user_name; ?><small class="float-right text-muted"><?= time_ago($dateAgo); ?></small>
      </h6>
      <?= $message_desc; ?>
    </div>
	</a>
	<?php }} ?>
	</ul>
	<?php
	$count_unread_inbox_messages = $db->count("inbox_messages",array("message_receiver"=>$login_seller_id,"message_status"=>'unread'));
	if($count_unread_inbox_messages == 0){
	?>
	<p class="lead mt-5 text-center d-none unreadMsg"><?= $lang['inbox']['no_unread']; ?></p>
	<?php } ?>
	<?php
	$count_starred = $db->count("starred_messages",array("seller_id" => $login_seller_id));
	if(@$count_starred == 0){
	?>
	<p class="lead mt-5 text-center d-none starredMsg"><?= $lang['inbox']['no_starred']; ?></p>
	<?php } ?>
	<?php
	$count_archived = $db->count("archived_messages",array("seller_id"=>$login_seller_id));
	if(@$count_archived == 0){
	?>
	<p class="lead mt-5 text-center d-none archivedMsg"><?= $lang['inbox']['no_archived']; ?></p>
	<?php } ?>	
	<?php
	if($count_inbox_sellers == 0){
	?>
	<p class="lead mt-5 text-center"><?= $lang['inbox']['no_all']; ?></p>
	<?php } ?>
  </div>
</div>
</div>