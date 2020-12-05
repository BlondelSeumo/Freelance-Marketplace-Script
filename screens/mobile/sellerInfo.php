<section class="seller-info"><!--- seller-info Starts --->
	<header class="cf"><!--- header Starts --->
	<?php if($proposal_seller_vacation == "off"){ ?>
	<a href="../../conversations/message?seller_id=<?= $proposal_seller_id; ?>" class="btn-standard rf">Contact Me</a>
	<?php if(!isset($_SESSION['seller_user_name'])){ ?>
	<a href="#" data-toggle="modal" data-target="#login-modal" class="rf favorite"> 
		<i class="fa fa-heart fa-2x"></i> 
	</a>
	<?php }else{ ?>
		<?PHP if($proposal_seller_user_name != @$_SESSION['seller_user_name']){ ?>
		<a href="#" id="<?= $show_favorite_id; ?>" class="rf favorite"> 
			<i class="fa fa-heart fa-2x <?= $show_favorite_class; ?>"></i> 
		</a>
		<?php } ?>
	<?php } ?>
	<?php } ?>
	<div class="user-pict-40">
	<a href="../../<?= $proposal_seller_user_name; ?>">
		<?php if(!empty($proposal_seller_image)){ ?>
		<img src="<?= getImageUrl2("sellers","seller_image",$proposal_seller_image);  ?>">
		<?php }else{ ?>
		<img src="../../user_images/empty-image.png">
		<?php } ?>
	</a>
	<?php if(check_status($proposal_seller_id) == "online"){ ?> <i class="fa fa-fw fa-circle"></i> <?php } ?>
	</div>
	<p class="m-b-5"><span class="username"><?= $proposal_seller_user_name; ?></span></p>
	<a href="#!" class="see-more">See more</a>
	</header><!--- header Ends --->
	<div class="seller-info-wrap dummy"><!--- seller-info-wrap Starts --->
	<?php if(!empty($proposal_seller_about)){ ?>
	<p class="seller-desc"><?= $proposal_seller_about; ?></p>
	<?php }else{ echo "<br>"; } ?>
	<ul>
	<li>
	<div><?= $proposal_seller_country; ?></div>
	<span><i class="fa fa-check pr-1"></i> From</span>
	</li>
	<li>
	<div>
	<?php
	
	$select_languages_relation = $db->query("select * from languages_relation where seller_id='$proposal_seller_id' LIMIT 2,1000");
	$count_languages_relation = $select_languages_relation->rowCount();
	
	$select_languages_relation = $db->query("select * from languages_relation where seller_id='$proposal_seller_id' LIMIT 0,2");

  	while($row_languages_relation = $select_languages_relation->fetch()){
	    $language_id = $row_languages_relation->language_id;

		$get_language = $db->select("seller_languages",array("language_id" => $language_id));
		$row_language = $get_language->fetch();
		$language_title = @$row_language->language_title;
	
	?>
	<span><?= $language_title; ?>,</span>
	<?php } ?>
	<?php if($count_languages_relation > 0){ ?>
	+ <?= $count_languages_relation; ?>
	<?php } ?>
	</div> <span><i class="fa fa-check pr-1"></i> Speaks</span>
	</li>
	<li>
	<div><?= $proposal_seller_rating; ?>%</div>
	<span><i class="fa fa-check pr-1"></i> Positive Rating</span>
	</li>
	<li>
	<div><?= $proposal_seller_recent_delivery; ?></div>
	<span><i class="fa fa-check pr-1"></i> Recent Delivery </span>
	</li>
	</ul>
	</div><!--- seller-info-wrap Ends --->
	</section><!--- seller-info Ends --->