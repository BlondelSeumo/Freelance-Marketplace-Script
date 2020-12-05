<?php

	@session_start();
	require_once("../../includes/db.php");
	if(!isset($_SESSION['seller_user_name'])){
		echo "<script>window.open('../../login','_self')</script>";
	}

	$login_seller_user_name = $_SESSION['seller_user_name'];
	$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
	$row_login_seller = $select_login_seller->fetch();
	$login_seller_id = $row_login_seller->seller_id;

   function make_url_link($text) {
     return preg_replace('%(https?|ftp)://([-A-Z0-9-./_*?&;=#]+)%i','<a target="blank" rel="nofollow" href="$0" target="_blank">$0</a>', $text);
   }

	if(isset($_POST["message_group_id"])){
		$message_group_id = $input->post("message_group_id");
 	}

	$get_inbox_messages = $db->select("inbox_messages",array("message_group_id" => $message_group_id));
	while($row_inbox_messages = $get_inbox_messages->fetch()){
	$message_id = $row_inbox_messages->message_id;
	$message_sender = $row_inbox_messages->message_sender;
	$message_desc = $row_inbox_messages->message_desc;
	$message_date = $row_inbox_messages->message_date;
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
	$sender_image = getImageUrl2("sellers","seller_image",$row_sender->seller_image);

	$sender_profile_link = $sender_user_name;
                
	if($login_seller_id == $message_sender){
		$sender_user_name = "Me";
	}

	$allowed = array('jpeg','jpg','gif','png');

	?>
	
	<li href="#" class="inboxMsg media inboxMsg">
		
		<a href="../<?= $sender_profile_link; ?>">
			<?php if(!empty($sender_image)){ ?>
		   	<img src="<?= $sender_image; ?>" class="rounded-circle mr-3" width="40">
			<?php }else{ ?>
				<img src="../user_images/empty-image.png"  class="rounded-circle mr-3" width="40">
			<?php } ?>
		</a>

	    <div class="media-body">
	      <h6 class="mt-0 mb-1">
	      	
	      	<a href="../<?= $sender_profile_link; ?>"><?= $sender_user_name; ?></a>
	      	<!-- <?= $sender_user_name; ?> -->

	      	<small class="text-muted"><?= $message_date; ?></small>

	      	<?php if($login_seller_id != $message_sender){ ?>
						<small>
							|
							<a href="#" data-toggle="modal" data-target="#report-modal" class="text-muted">
								<small><i class="fa fa-flag"></i> Report</small>
							</a> 
						</small>
					<?php } ?>

	      </h6>
	      <?= make_url_link($message_desc); ?>
	      <?php if(!empty($message_file)){ ?>
	      <?php if(in_array(pathinfo($message_file,PATHINFO_EXTENSION),$allowed)){ ?>
	      <br>
	      <img src="<?= getImageUrl("inbox_messages",$message_file); ?>" class="img-thumbnail" width="100"/>
	      <?php } ?>
				<a href="<?= getImageUrl("inbox_messages",$message_file); ?>" download class="d-block mt-2 ml-1">
					<i class="fa fa-download"></i> <?= $message_file; ?>
				</a>
				<?php } ?>
				<?php if(!$message_offer_id == 0){ ?>
				<div class="message-offer card mb-3"><!--- message-offer Starts --->
				<div class="card-header p-2">
			   <h6 class="mt-md-0 mt-2">
				<?= $proposal_title; ?>
				<span class="price float-right d-sm-block d-none"> <?= showPrice($amount); ?> </span>
				</h6>
			  </div>
			<div class="card-body p-2"><!--- card-body Starts --->
			<p> <?= $description; ?> </p>
			<p class="d-block d-sm-none"> <b> Price / Amount : </b> <?= showPrice($amount); ?> </p>
			<p> <b> <i class="fa fa-calendar"></i> Delivery Time : </b> <?= $delivery_time; ?> </p>
			<?php if($offer_status == "active"){ ?>
			<?php if($login_seller_id == $sender_id){ ?>
			<?php }else{ ?>
			<button id="accept-offer-<?= $message_offer_id; ?>" class="btn btn-success float-right">
				<?= $lang['button']['accept_offer']; ?>
			</button>
			<script>
			$("#accept-offer-<?= $message_offer_id; ?>").click(function(){
				single_message_id = "<?= $message_group_id; ?>";
				offer_id = "<?= $message_offer_id; ?>";
				$.ajax({
				method: "POST",
				url: "accept_offer_modal",
				data: {single_message_id: single_message_id, offer_id: offer_id}
				}).done(function(data){
					$("#accept-offer-div").html(data);
				});
			});
			</script>
			<?php } ?>
			<?php }elseif($offer_status == "accepted"){ ?>
			<button class="btn btn-success rounded-0 mt-2 float-right" disabled>
				<?= $lang['button']['offer_accepted']; ?>
			</button>
			<a href="../order_details?order_id=<?= $order_id; ?>" target="_blank" class="mt-3 mr-3 float-right text-success">
				<?= $lang['button']['view_order']; ?>
			</a>
			<?php } ?>
			</div><!--- card-body Ends --->
			</div><!--- message-offer Ends --->
		<?php } ?>
	  </div>
  </li>
<?php } ?>