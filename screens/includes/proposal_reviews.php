
<article id="all" class="proposal-reviews">
<ul class="reviews-list">
	<?php
	$select_buyer_reviews = $db->select("buyer_reviews",array("proposal_id" => $proposal_id),"DESC");
	$count_reviews = $select_buyer_reviews->rowCount();
	if($count_reviews == 0){
	$rtl = ($lang_dir == "right" ? 'text-right':'');
	echo "
	<li>
		<h3 class='$rtl' align='center'> 
			<i class='fa fa-frown-o'></i> {$lang['proposal']['no_reviews']}
		</h3>
	</li>";
	}
	while($row_buyer_reviews = $select_buyer_reviews->fetch()){
	$order_id = $row_buyer_reviews->order_id;
	$review_buyer_id = $row_buyer_reviews->review_buyer_id;
	$buyer_rating = $row_buyer_reviews->buyer_rating;
	$buyer_review = $row_buyer_reviews->buyer_review;
	$review_date = $row_buyer_reviews->review_date;
	$select_buyer = $db->select("sellers",array("seller_id" => $review_buyer_id));
	$row_buyer = $select_buyer->fetch();
	$buyer_user_name = $row_buyer->seller_user_name;
	$buyer_image = getImageUrl2("sellers","seller_image",$row_buyer->seller_image);
	$select_seller_review = $db->select("seller_reviews",array("order_id" => $order_id));
	$count_seller_review =  $select_seller_review->rowCount();
	$row_seller_review = $select_seller_review->fetch();
	$seller_rating = @$row_seller_review->seller_rating;
	$seller_review = @$row_seller_review->seller_review;
	?>
	<li class="star-rating-row"><!-- star-rating-row Starts -->
	<span class="user-picture" style="<?=($lang_dir == "right" ? 'left:auto;margin-left:6px;':'')?>"><!-- user-picture Starts -->
	<?php if(!empty($buyer_image)){ ?>
	<img src="<?= $buyer_image; ?>" width="60" height="60">
	<?php }else{ ?>
	<img src="../../user_images/empty-image.png" width="60" height="60">
	<?php } ?>
	</span><!-- user-picture Ends -->
	<h4><!-- h4 Starts -->
	<a class="text-success" href="#" class="mr-1"> <?= $buyer_user_name; ?> </a>
	<?php
	for($buyer_i=0; $buyer_i<$buyer_rating; $buyer_i++){
	echo " <img class='rating' src='../../images/user_rate_full.png' > ";
	}
	for($buyer_i=$buyer_rating; $buyer_i<5; $buyer_i++){
	echo " <img class='rating' src='../../images/user_rate_blank.png' > ";
	}
	?>
	</h4><!-- h4 Ends -->
	<div class="msg-body"><!-- msg-body Starts -->
	<?= $buyer_review; ?>
	</div><!-- msg-body Ends -->
	<span class="rating-date"> <?= $review_date; ?> </span>
	</li><!-- star-rating-row Ends -->
	<?php if(!$count_seller_review == 0){ ?>
	<li class="rating-seller"><!-- rating-seller Starts -->
	<h4><!-- h4 Starts -->
	<span class="mr-1"> Seller's Feedback </span>
	<?php
	for($seller_i=0; $seller_i<$seller_rating; $seller_i++){
	echo " <img class='rating' src='../../images/user_rate_full.png' > ";
	}
	for($seller_i=$seller_rating; $seller_i<5; $seller_i++){
	echo " <img class='rating' src='../../images/user_rate_blank.png' > ";
	}
	?>
	</h4><!-- h4 Ends -->
	<span class="user-picture"><!-- user-picture Starts -->
	<?php if(!empty($proposal_seller_image)){ ?>
	<img src="<?= getImageUrl2("sellers","seller_image",$proposal_seller_image); ?>" width="40" height="40">
	<?php }else{ ?>
	<img src="../../user_images/empty-image.png" width="40" height="40">
	<?php } ?>
	</span><!-- user-picture Ends -->
	<div class="msg-body"><!-- msg-body Starts -->
	<?= $seller_review; ?>
	</div><!-- msg-body Ends -->
	</li><!-- rating-seller Ends -->
	<?php } ?>
	<hr>
	<?php } ?>
</ul><!-- reviews-list Ends -->
</article>
<article id="good" class="proposal-reviews"><!-- proposal-reviews Starts -->
<ul class="reviews-list"><!-- reviews-list Starts -->
	<?php
	$select_buyer_reviews = $db->query("select * from buyer_reviews where proposal_id='$proposal_id' AND (buyer_rating='5' or buyer_rating='4') order by 1 DESC");
	$count_reviews = $select_buyer_reviews->rowCount();
	if($count_reviews == 0){
	$rtl = ($lang_dir == "right" ? 'text-right':'');
	echo "
	<li>
	<h3 class='$rtl' align='center'> 
	There is currently no positive review for this proposal/service.
	</h3>
	</li>";
	}
	while($row_buyer_reviews = $select_buyer_reviews->fetch()){
	$order_id = $row_buyer_reviews->order_id;
	$review_buyer_id = $row_buyer_reviews->review_buyer_id;
	$buyer_rating = $row_buyer_reviews->buyer_rating;
	$buyer_review = $row_buyer_reviews->buyer_review;
	$review_date = $row_buyer_reviews->review_date;
	$select_buyer = $db->select("sellers",array("seller_id" => $review_buyer_id));
	$row_buyer = $select_buyer->fetch();
	$buyer_user_name = $row_buyer->seller_user_name;
	$buyer_image = getImageUrl2("sellers","seller_image",$row_buyer->seller_image);
	$select_seller_review = $db->select("seller_reviews",array("order_id" => $order_id));
	$count_seller_review =  $select_seller_review->rowCount();
	$row_seller_review = $select_seller_review->fetch();
	$seller_rating = @$row_seller_review->seller_rating;
	$seller_review = @$row_seller_review->seller_review;
	?>
	<li class="star-rating-row"><!-- star-rating-row Starts -->
	<span class="user-picture"><!-- user-picture Starts -->
	<?php if(!empty($buyer_image)){ ?>
	<img src="<?= $buyer_image; ?>" width="60" height="60">
	<?php }else{ ?>
	<img src="../../user_images/empty-image.png" width="60" height="60">
	<?php } ?>
	</span><!-- user-picture Ends -->
	<h4><!-- h4 Starts -->
	<a href="#" class="mr-1 text-success"> <?= $buyer_user_name; ?> </a>
	<?php
	for($buyer_i=0; $buyer_i<$buyer_rating; $buyer_i++){
	echo " <img class='rating' src='../../images/user_rate_full.png' > ";
	}
	for($buyer_i=$buyer_rating; $buyer_i<5; $buyer_i++){
	echo " <img class='rating' src='../../images/user_rate_blank.png' > ";
	}
	?>
	</h4><!-- h4 Ends -->
	<div class="msg-body"><!-- msg-body Starts -->
	<?= $buyer_review; ?>
	</div><!-- msg-body Ends -->
	<span class="rating-date"> <?= $review_date; ?> </span>
	</li><!-- star-rating-row Ends -->
	<?php if(!$count_seller_review == 0){ ?>
	<li class="rating-seller"><!-- rating-seller Starts -->
	<h4><!-- h4 Starts -->
	<span class="mr-1"> Seller's Feedback </span>
	<?php
	for($seller_i=0; $seller_i<$seller_rating; $seller_i++){
	echo " <img class='rating' src='../../images/user_rate_full.png' > ";
	}
	for($seller_i=$seller_rating; $seller_i<5; $seller_i++){
	echo " <img class='rating' src='../../images/user_rate_blank.png' > ";
	}
	?>
	</h4><!-- h4 Ends -->
	<span class="user-picture"><!-- user-picture Starts -->
	<?php if(!empty($proposal_seller_image)){ ?>
	<img src="<?= getImageUrl2("sellers","seller_image",$proposal_seller_image); ?>" width="40" height="40">
	<?php }else{ ?>
	<img src="../../user_images/empty-image.png" width="40" height="40">
	<?php } ?>
	</span><!-- user-picture Ends -->
	<div class="msg-body"><!-- msg-body Starts -->
	<?= $seller_review; ?>
	</div><!-- msg-body Ends -->
	</li><!-- rating-seller Ends -->
	<?php } ?>
	<hr>
	<?php } ?>
</ul><!-- reviews-list Ends -->
</article><!-- proposal-reviews Ends -->
<article id="bad" class="proposal-reviews"><!-- proposal-reviews Starts -->
<ul class="reviews-list"><!-- reviews-list Starts -->
	<?php
	$select_buyer_reviews = $db->query("select * from buyer_reviews where proposal_id='$proposal_id' AND (buyer_rating='1' or buyer_rating='2' or buyer_rating='3') order by 1 DESC");
	$count_reviews = $select_buyer_reviews->rowCount();
	if($count_reviews == 0){
	$rtl = ($lang_dir == "right" ? 'text-right':'');
	echo "
	<li>
	<h3 align='center $rtl'> 
	<i class='fa fa-smile-o'></i> There is currently no negative review for this proposal/service.
	</h3>
	</li>";
	}
	while($row_buyer_reviews = $select_buyer_reviews->fetch()){
	$order_id = $row_buyer_reviews->order_id;
	$review_buyer_id = $row_buyer_reviews->review_buyer_id;
	$buyer_rating = $row_buyer_reviews->buyer_rating;
	$buyer_review = $row_buyer_reviews->buyer_review;
	$review_date = $row_buyer_reviews->review_date;
	$select_buyer = $db->select("sellers",array("seller_id" => $review_buyer_id));
	$row_buyer = $select_buyer->fetch();
	$buyer_user_name = $row_buyer->seller_user_name;
	$buyer_image = getImageUrl2("sellers","seller_image",$row_buyer->seller_image);
	$select_seller_review = $db->select("seller_reviews",array("order_id" => $order_id));
	$count_seller_review = $select_seller_review->rowCount();
	$row_seller_review = $select_seller_review->fetch();
	$seller_rating = @$row_seller_review->seller_rating;
	$seller_review = @$row_seller_review->seller_review;
	?>
	<li class="star-rating-row"><!-- star-rating-row Starts -->
	<span class="user-picture"><!-- user-picture Starts -->
	<?php if(!empty($buyer_image)){ ?>
	<img src="<?= $buyer_image; ?>" width="60" height="60">
	<?php }else{ ?>
	<img src="../../user_images/empty-image.png" width="60" height="60">
	<?php } ?>
	</span><!-- user-picture Ends -->
	<h4><!-- h4 Starts -->
	<a href="#" class="mr-1 text-success"> <?= $buyer_user_name; ?> </a>
	<?php
	for($buyer_i=0; $buyer_i<$buyer_rating; $buyer_i++){
	echo " <img class='rating' src='../../images/user_rate_full.png' > ";
	}
	for($buyer_i=$buyer_rating; $buyer_i<5; $buyer_i++){
	echo " <img class='rating' src='../../images/user_rate_blank.png' > ";
	}
	?>
	</h4><!-- h4 Ends -->
	<div class="msg-body"><!-- msg-body Starts -->
	<?= $buyer_review; ?>
	</div><!-- msg-body Ends -->
	<span class="rating-date"> <?= $review_date; ?> </span>
	</li><!-- star-rating-row Ends -->
	<?php if(!$count_seller_review == 0){ ?>
	<li class="rating-seller"><!-- rating-seller Starts -->
	<h4><!-- h4 Starts -->
	<span class="mr-1"> Seller's Feedback </span>
	<?php
	for($seller_i=0; $seller_i<$seller_rating; $seller_i++){
	echo " <img class='rating' src='../../images/user_rate_full.png' > ";
	}
	for($seller_i=$seller_rating; $seller_i<5; $seller_i++){
	echo " <img class='rating' src='../../images/user_rate_blank.png' > ";
	}
	?>
	</h4><!-- h4 Ends -->
	<span class="user-picture"><!-- user-picture Starts -->
	<?php if(!empty($proposal_seller_image)){ ?>
	<img src="<?= getImageUrl2("sellers","seller_image",$proposal_seller_image); ?>" width="40" height="40">
	<?php }else{ ?>
	<img src="../../user_images/empty-image.png" width="40" height="40">
	<?php } ?>
	</span><!-- user-picture Ends -->
	<div class="msg-body"><!-- msg-body Starts -->
	<?= $seller_review; ?>
	</div><!-- msg-body Ends -->
	</li><!-- rating-seller Ends -->
	<?php } ?>
	<hr>
	<?php } ?>
</ul><!-- reviews-list Ends -->
</article><!-- proposal-reviews Ends -->