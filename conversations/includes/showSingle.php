<?php
session_start();
require_once("../../includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('../../login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_image = getImageUrl2("sellers","seller_image",$row_login_seller->seller_image);

$message_group_id = $input->post('message_group_id');
$get_inbox_sellers = $db->select("inbox_sellers",array("message_group_id" => $message_group_id));
$row_inbox_sellers = $get_inbox_sellers->fetch();
$offer_id = $row_inbox_sellers->offer_id;
$sender_id = $row_inbox_sellers->sender_id;
$receiver_id = $row_inbox_sellers->receiver_id;
if($login_seller_id == $sender_id){
	$seller_id = $receiver_id;
}else{
	$seller_id = $sender_id;
}

$update_inbox_sellers = $db->update("inbox_sellers",array("message_status" => 'read'),array("receiver_id" => $login_seller_id,"message_status" => 'unread',"message_group_id" => $message_group_id));

$update_inbox_messages = $db->update("inbox_messages",array("message_status" => 'read'),array("message_receiver" => $login_seller_id,"message_status" => 'unread',"message_group_id" => $message_group_id));

$past_orders = $db->query("select * from orders where (seller_id='$seller_id' AND buyer_id='$login_seller_id') or (seller_id='$login_seller_id' AND buyer_id='$seller_id')");
$count_orders = $past_orders->rowCount();

$select_seller = $db->select("sellers",array("seller_id" => $seller_id));
$row_seller = $select_seller->fetch();
$seller_image = getImageUrl2("sellers","seller_image",$row_seller->seller_image);
$seller_user_name = $row_seller->seller_user_name;
$seller_level = $row_seller->seller_level;
$seller_vacation = $row_seller->seller_vacation;
$seller_country = $row_seller->seller_country;
$seller_recent_delivery = $row_seller->seller_recent_delivery;
$seller_status = $row_seller->seller_status;
$seller_rating = $row_seller->seller_rating;

@$level_title = $db->select("seller_levels_meta",array("level_id"=>$seller_level,"language_id"=>$siteLanguage))->fetch()->title;

$count_active_proposals = $db->count("proposals",array("proposal_seller_id"=>$login_seller_id,"proposal_status"=>'active'));

?>
<div class="col-md-8 <?=($lang_dir == "right" ? 'order-2 order-sm-1 pl-0 pr-3':'pr-lg-0 ')?>">
	<ul class="list-unstyled messages mb-0 <?=($lang_dir == "right" ? 'direction-rtl':'')?>">
		<?php require_once("display_messages.php"); ?>
	</ul>
	<?php require_once("sendMessage.php"); ?>
	<?php require_once("sendMessageJs.php"); ?>
</div>
<div class="col-md-4 <?=($lang_dir == "right" ? 'order-1 order-sm-2 pr-0 border-right':'pl-0 border-left')?>" id="msgSidebar">
	<h5 class="pt-3 p-2">Orders</h5>
	<div class="dropdown">
		<a class="lead text-muted p-2 pt-0" href="#" role="button" data-toggle="dropdown">Past Orders (<?= $count_orders; ?>)</a>
		<div class="dropdown-menu pt-1 pb-1">
			<a href="../buying_history?buyer_id=<?= $seller_id; ?>" class="dropdown-item">Buying History</a>
			<a href="../selling_history?seller_id=<?= $seller_id; ?>" class="dropdown-item">Selling History</a>
		</div>
	</div>
	<hr>
	<h5 class="pb-0 p-2">About</h5>
	<center class="mb-3">
		
		<a href="../<?= $seller_user_name; ?>">
			<?php if(!empty($seller_image)){ ?>
				<img src="<?= $seller_image; ?>" width="50" class="rounded-circle">
			<?php }else{ ?>
				<img src="../user_images/empty-image.png" width="50" class="rounded-circle">
			<?php } ?>
		</a>
		
		<a class="text-center" href="../<?= $seller_user_name; ?>">
			<h6 class="mb-0 mt-2"><?= ucfirst($seller_user_name); ?></h6>
		</a>
		
		<p class="text-muted text-center"><?= $level_title; ?></p>

	</center>
	<div class="row p-3">
		<div class="col-md-6">
			<p><i class="fa fa-star pr-1"></i> Rating </p>
			<p><i class="fa fa-globe pr-1"></i> From</p>
			<p><i class="fa fa-truck pr-1"></i> Last delivery</p>
			<?php
			$select_languages_relation = $db->select("languages_relation",array("seller_id"=>$seller_id));
			while($row_languages_relation = $select_languages_relation->fetch()){
				$language_id = $row_languages_relation->language_id;
				$get_languages = $db->select("seller_languages",array("language_id"=>$language_id));
				$row_languages = $get_languages->fetch();
				$language_title = @$row_languages->language_title;
			?>
			<p> <i class="fa fa-language pr-1"></i> <?= $language_title; ?></p>
			<?php } ?>
		</div>
		<div class="col-md-6 text-right">
			<p class="font-weight-bold"><?= $seller_rating; ?>%</p>
			<p class="font-weight-bold"><?= $seller_country; ?></p>
			<p class="font-weight-bold"><?= $seller_recent_delivery; ?></p>
			<?php
			$select_languages_relation = $db->select("languages_relation",array("seller_id"=>$seller_id));
			while($row_languages_relation = $select_languages_relation->fetch()){
			$language_level = $row_languages_relation->language_level;
			?>
			<p class="font-weight-bold"><?= ucfirst($language_level); ?></p>
			<?php } ?>
		</div>		
	</div>
</div>
<?php require_once("reportModal.php"); ?>