<div class="card rounded-0 mb-3 welcome-box"><!-- card rounded-0 mb-3 welcome-box Starts -->
	<div class="card-body pb-2"><!-- card-body Starts -->
		<center>
		<?php if(!empty($seller_image)){ ?>
		<img src="<?= $seller_image; ?>" class="img-fluid rounded-circle mb-3">
		<?php }else{ ?>
		<img src="<?= $site_url; ?>/user_images/empty-image.png"  class="img-fluid rounded-circle mb-3">
		<?php } ?>
		</center>
		<h5><?= $lang['welcome']; ?>, <span class="text-success"><?= ucfirst($login_user_name); ?></span> </h5>
		<hr>
		<div class="row m-0"><!--- row Starts --->
			<div class="col-lg-6 m-0 p-0 pr-2 pb-lg-0 pr-lg-2 pb-md-2 pr-sm-2"><!--- col-md-6 Starts --->
				<h5><a href="<?= $site_url; ?>/dashboard"><?= $lang['menu']['dashboard']; ?></a></h5>
				<h5><a href="<?= $site_url; ?>/proposals/create_proposal"><?= $lang['user_home']['add_proposal']; ?></a></h5>
				<h5 class="mb-0"><a href="<?= $site_url; ?>/requests/post_request"><?= $lang['menu']['post_request']; ?></a></h5>
			</div><!--- col-md-6 Ends --->
			<div class="col-lg-6 m-0 p-0 pl-2 pt-lg-0 pl-lg-2 pl-md-0 pt-md-2 pl-sm-2"><!--- col-md-6 Starts --->
				<?php if(isset($count_active_proposals) AND @$count_active_proposals  > 0){ ?>
				<h5><a href="<?= $site_url; ?>/selling_orders"><?= $lang['user_home']['view_sales']; ?></a></h5>
				<?php }else{ ?>
				<h5><a href="<?= $site_url; ?>/buying_orders"><?= $lang['user_home']['view_purchases']; ?></a></h5>
				<?php } ?>
				<h5>
               <a href="<?= $site_url; ?>/settings?profile_settings">
                  <?= $lang['user_home']['edit_profile']; ?>
               </a>
            </h5>
				<h5 class="mb-0"><a href="<?= $site_url; ?>/settings"><?= $lang['menu']['settings']; ?></a></h5>
			</div><!--- col-md-6 Ends --->
		</div><!--- row Ends --->
		<hr>
		<h5>
         <a href="<?= $site_url; ?>/customer_support">
            <?= $lang["user_home"]['contact']; ?> <?= $site_name; ?>   
         </a>
      </h5>
	</div><!-- card-body Ends -->
</div><!-- card rounded-0 mb-3 welcome-box Ends -->
<div class="rounded-0 carosel_sec">
	<h3 class="buy_head <?=($lang_dir == "right" ? 'text-right':'')?>"><?= $lang['sidebar']['buy_it_again']; ?></h3>
	<?php 
		$select_orders = $db->query("select DISTINCT proposal_id from orders WHERE buyer_id='$login_seller_id' AND order_status='completed' AND EXISTS (SELECT * FROM proposals WHERE proposals.proposal_id = orders.proposal_id AND proposals.proposal_status='active')");
		$count_orders = $select_orders->rowCount();
		if($count_orders == 0){
			echo "<p class='text-muted'><i class='fa fa-frown-o'></i> {$lang['sidebar']['no_buy_it_again']} </p>";
		}else{ 
	?>
  <div id="demo" class="carousel slide" data-ride="carousel"><!-- The slideshow -->
		<div class="carousel-inner " role="listbox">
		<?php
			$i = 0;
			$select_orders = $db->query("select DISTINCT proposal_id from orders where buyer_id='$login_seller_id' AND order_status='completed' order by 1 DESC");
			while($row_orders = $select_orders->fetch()){
			$proposal_id = $row_orders->proposal_id;

			$get_proposals = $db->query("select * from proposals where proposal_id='$proposal_id' AND proposal_status='active'");
			$count_proposals = $get_proposals->rowCount();
			if($count_proposals == 1){
			$i++;
	    	$row_proposals = $get_proposals->fetch();
			$proposal_id = $row_proposals->proposal_id;
			$proposal_title = $row_proposals->proposal_title;
			$proposal_price = $row_proposals->proposal_price;
			if($proposal_price == 0){
			$get_p_1 = $db->select("proposal_packages",array("proposal_id" => $proposal_id,"package_name" => "Basic"));
			$proposal_price = $get_p_1->fetch()->price;
			}
			$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
			$proposal_video = $row_proposals->proposal_video;
			$proposal_seller_id = $row_proposals->proposal_seller_id;
			$proposal_rating = $row_proposals->proposal_rating;
			$proposal_url = $row_proposals->proposal_url;
			$proposal_featured = $row_proposals->proposal_featured;
			$proposal_enable_referrals = $row_proposals->proposal_enable_referrals;
			$proposal_referral_money = $row_proposals->proposal_referral_money;
			if(empty($proposal_video)){
				$video_class = "";
			}else{
				$video_class = "video-img";
			}
			$get_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
			$row_seller = $get_seller->fetch();
			$seller_user_name = $row_seller->seller_user_name;
			$seller_image = getImageUrl2("sellers","seller_image",$row_seller->seller_image);
			$seller_level = $row_seller->seller_level;
			$seller_status = $row_seller->seller_status;
			if(empty($seller_image)){
			$seller_image = "empty-image.png";
			}
			// Select Proposal Seller Level
			@$seller_level = $db->select("seller_levels_meta",array("level_id"=>$seller_level,"language_id"=>$siteLanguage))->fetch()->title;
			$proposal_reviews = array();
			$select_buyer_reviews = $db->select("buyer_reviews",array("proposal_id" => $proposal_id));
			$count_reviews = $select_buyer_reviews->rowCount();
			while($row_buyer_reviews = $select_buyer_reviews->fetch()){
				$proposal_buyer_rating = $row_buyer_reviews->buyer_rating;
				array_push($proposal_reviews,$proposal_buyer_rating);
			}
			$total = array_sum($proposal_reviews);
			@$average_rating = $total/count($proposal_reviews);

         $get_delivery = $db->select("instant_deliveries",['proposal_id'=>$proposal_id]);
         $row_delivery = $get_delivery->fetch();
         $enable_delivery = $row_delivery->enable;

			if($videoPlugin == 1){
				$proposal_videosettings =  $db->select("proposal_videosettings",array('proposal_id'=>$proposal_id))->fetch();
				$enableVideo = $proposal_videosettings->enable;
			}else{
				$enableVideo = 0;
			}
	   ?>
	   <div class="carousel-item <?= ($i == 1 ? "active" : "") ?>">
	   <div class="proposal-card-base mp-proposal-card"><!--- proposal-card-base mp-proposal-card Starts --->
	     <a href="<?= $site_url; ?>/proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>">
	     <img src="<?= $proposal_img1; ?>" class="img-fluid">
	     </a>
	     <div class="proposal-card-caption"><!--- proposal-card-caption Starts --->
	     <div class="proposal-seller-info"><!--- gig-seller-info Starts --->
	     <span class="fit-avatar s24">
	     <img src="<?= $seller_image; ?>" class="rounded-circle" width="32" height="32">
	     </span>
	     <div class="seller-info-wrapper">
	     <a href="<?= $site_url; ?>/<?= $seller_user_name; ?>" class="seller-name"><?= $seller_user_name; ?></a>
	     <div class="gig-seller-tooltip">
	     <?= $seller_level; ?>
	     </div>
	     </div>
	     </div><!--- gig-seller-info Ends --->
	     <a href="<?= $site_url; ?>/proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" class="proposal-link-main">
	     <h3><?= $proposal_title; ?></h3>
	     </a>
	     <div class="rating-badges-container">
	     <span class="proposal-rating">
	     <svg class="fit-svg-icon full_star" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
	      <path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"></path>
	  	 </svg>
	     <span>
	      <strong><?php if($proposal_rating == "0"){ echo "0.0"; }else{ printf("%.1f", $average_rating); } ?></strong>
	      (<?= $count_reviews; ?>)
	      </span>
	     </span>
	     </div>
	     </div><!--- proposal-card-caption Ends --->
	     <footer class="proposal-card-footer"><!--- proposal-card-footer Starts --->
	     	<div class="proposal-fav">
				<?php if($enableVideo == 1){ ?>
               <a class="icn-list" data-toggle="tooltip" data-placement="top" title="<?= $lang['proposals']['video']; ?>">
					<?php require("$dir/images/camera.svg"); ?>
					</a>
				<?php } ?>
   
            <?php if($enable_delivery == 1){ ?>
               <a class="icn-list" data-toggle="tooltip" data-placement="top" title="<?= $lang['proposals']['instant_delivery']; ?>">
                 <?php require("$dir/images/flash.svg"); ?>
               </a>
            <?php } ?>

			</div>
	      <div class="proposal-price">
		      <a class="js-proposal-card-imp-data">
		      	<small>Starting At</small><?= showPrice($proposal_price); ?>
		      </a>
	      </div>
	     </footer><!--- proposal-card-footer Ends --->
	  	</div><!--- proposal-card-base mp-proposal-card Ends --->
	  	</div>
	  <?php } ?>
	  <?php } ?>
	</div><!-- Left and right controls -->
		<a class="carousel-control-prev" href="#demo" data-slide="prev">
			<i class="fa fa-angle-left"></i>
		</a>
		<a class="carousel-control-next" href="#demo" data-slide="next">
			<i class="fa fa-angle-right"></i>
		</a>
		</div>
	<?php  } ?>
</div>
<div class="rounded-0 mb-3 carosel_sec mt-3">
<h3 class="buy_head <?=($lang_dir == "right" ? 'text-right':'')?>"><?= $lang['sidebar']['recently_viewed']; ?></h3>
  <?php
  $select_recent = $db->query("select * from recent_proposals WHERE seller_id='$login_seller_id' AND EXISTS (SELECT * FROM proposals WHERE proposals.proposal_id = recent_proposals.proposal_id AND proposals.proposal_status='active') order by 1 DESC LIMIT 0,4");
  $count_recent = $select_recent->rowCount();
  if($count_recent == 0){
  	echo "<p class='text-muted'> <i class='fa fa-frown-o'></i> {$lang['sidebar']['no_recently_viewed']} </p>";
	}else{
  ?>
  <div id="demo2" class="carousel slide" data-ride="carousel"><!-- The slideshow -->
  <div class="carousel-inner " role="listbox">
	<?php
	$i = 0;
  $select_recent = $db->query("select * from recent_proposals where seller_id='$login_seller_id' order by 1 DESC LIMIT 0,4");
  while($row_recent = $select_recent->fetch()){
  $proposal_id = $row_recent->proposal_id;
  $get_proposals = $db->query("select * from proposals where proposal_id='$proposal_id' AND proposal_status='active'");
  $count_proposals = $get_proposals->rowCount();
	if($count_proposals == 1){
	$i++;
  	$row_proposals = $get_proposals->fetch();
	$proposal_id = $row_proposals->proposal_id;
	$proposal_title = $row_proposals->proposal_title;
	$proposal_price = $row_proposals->proposal_price;
	if($proposal_price == 0){
	$get_p_1 = $db->select("proposal_packages",array("proposal_id" => $proposal_id,"package_name" => "Basic"));
	$proposal_price = $get_p_1->fetch()->price;
	}
	$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
	$proposal_video = $row_proposals->proposal_video;
	$proposal_seller_id = $row_proposals->proposal_seller_id;
	$proposal_rating = $row_proposals->proposal_rating;
	$proposal_url = $row_proposals->proposal_url;
	$proposal_featured = $row_proposals->proposal_featured;
	$proposal_enable_referrals = $row_proposals->proposal_enable_referrals;
	$proposal_referral_money = $row_proposals->proposal_referral_money;
	if(empty($proposal_video)){
		$video_class = "";
	}else{
		$video_class = "video-img";
	}
	$get_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
	$row_seller = $get_seller->fetch();
	$seller_user_name = $row_seller->seller_user_name;
	$seller_image = getImageUrl2("sellers","seller_image",$row_seller->seller_image);
	$seller_level = $row_seller->seller_level;
	$seller_status = $row_seller->seller_status;
	if(empty($seller_image)){
	$seller_image = "empty-image.png";
	}
	// Select Proposal Seller Level
	@$seller_level = $db->select("seller_levels_meta",array("level_id"=>$seller_level,"language_id"=>$siteLanguage))->fetch()->title;
	$proposal_reviews = array();
	$select_buyer_reviews = $db->select("buyer_reviews",array("proposal_id" => $proposal_id));
	$count_reviews = $select_buyer_reviews->rowCount();
	while($row_buyer_reviews = $select_buyer_reviews->fetch()){
		$proposal_buyer_rating = $row_buyer_reviews->buyer_rating;
		array_push($proposal_reviews,$proposal_buyer_rating);
	}
	$total = array_sum($proposal_reviews);
	@$average_rating = $total/count($proposal_reviews);


   $get_delivery = $db->select("instant_deliveries",['proposal_id'=>$proposal_id]);
   $row_delivery = $get_delivery->fetch();
   $enable_delivery = $row_delivery->enable;

	if($videoPlugin == 1){
		$proposal_videosettings =  $db->select("proposal_videosettings",array('proposal_id'=>$proposal_id))->fetch();
		$enableVideo = $proposal_videosettings->enable;
	}else{
		$enableVideo = 0;
	}

  ?>
<div class="carousel-item <?= ($i == 1 ? "active" : "") ?>"><!--- carousel-item Starts --->
<div class="proposal-card-base mp-proposal-card"><!--- proposal-card-base mp-proposal-card Starts --->
    <a href="proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>">
    <img src="<?= $proposal_img1; ?>" class="img-fluid">
    </a>
    <div class="proposal-card-caption"><!--- proposal-card-caption Starts --->
    <div class="proposal-seller-info"><!--- gig-seller-info Starts --->
    <span class="fit-avatar s24">
    <img src="<?= $seller_image; ?>" class="rounded-circle" width="32" height="32">
    </span>
    <div class="seller-info-wrapper">
    <a href="<?= $site_url; ?>/<?= $seller_user_name; ?>" class="seller-name"><?= $seller_user_name; ?></a>
    <div class="gig-seller-tooltip">
    	<?= $seller_level; ?>
    </div>
    </div>
    </div><!--- gig-seller-info Ends --->
    <a href="<?= $site_url; ?>/proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" class="proposal-link-main">
    <h3><?= $proposal_title; ?></h3>
    </a>
    <div class="rating-badges-container">
    <span class="proposal-rating">
    <svg class="fit-svg-icon full_star" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
    <path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"></path>
	</svg>
    <span>
    <strong><?php if($proposal_rating == "0"){ echo "0.0"; }else{ printf("%.1f", $average_rating); } ?></strong>
    (<?= $count_reviews; ?>)
    </span>
    </span>
    </div>
    </div><!--- proposal-card-caption Ends --->
    <footer class="proposal-card-footer"><!--- proposal-card-footer Starts --->

		<div class="proposal-fav">
		<?php if($enableVideo == 1){ ?>
			<a class="icn-list" data-toggle="tooltip" data-placement="top" title="This proposal allows video sessions">
			<?php require("$dir/images/camera.svg"); ?>
			</a>
		<?php } ?>

      <?php if($enable_delivery == 1){ ?>
         <a class="icn-list" data-toggle="tooltip" data-placement="top" title="<?= $lang['proposals']['instant_delivery']; ?>">
         <?php require("$dir/images/flash.svg"); ?>
         </a>
      <?php } ?>

		</div>

    <div class="proposal-price">
    <a class="js-proposal-card-imp-data">
    <small>Starting At</small><?= showPrice($proposal_price); ?>
    </a>
    </div>
    </footer><!--- proposal-card-footer Ends --->
	</div><!--- proposal-card-base mp-proposal-card Ends --->
  </div><!--- carousel-item Ends --->
	<?php } ?>
	<?php } ?>

  </div>
  <!-- Left and right controls -->
  <a class="carousel-control-prev" href="#demo2" data-slide="prev">
    <i class="fa fa-angle-left"></i>
  </a>
  <a class="carousel-control-next" href="#demo2" data-slide="next">
    <i class="fa fa-angle-right"></i>
  </a>
  </div>
  <?php } ?>
</div>
<div class="card rounded-0 sticky-start mb-3 card_user">
	<div class="card-body">
		<img src="images/sales.png" class="img-fluid center-block" alt="none">
		<h4><?= $lang['sidebar']['start_selling']['title']; ?></h4>
		<p><?= $lang['sidebar']['start_selling']['desc']; ?></p>
		<button onclick="location.href='start_selling'" class="btn get_btn"><?= $lang['sidebar']['start_selling']['button']; ?></button>
	</div>
</div>
<br>
<script>
$(document).ready(function(){
	// Sticky Code start //
	if($(window).width() < 767){
		// 
	}else{
		$(".sticky-start").sticky({
			topSpacing:20,
			zIndex:500,
			bottomSpacing:400,
		});
	}
	// Sticky code ends //
});
</script>