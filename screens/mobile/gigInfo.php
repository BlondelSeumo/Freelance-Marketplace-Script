<section class="gig-info <?= $show; ?>"><!--- gig-info Starts --->
	<h1><?= ucfirst($proposal_title); ?></h1>
	<div class="gig-info-desc">
	<p><?= $proposal_desc; ?></p>
	<div>
	<?php if(strlen($proposal_desc) > 200){ ?>
	<span class="more"> <a href="#!" class="see-more">Read more</a> </span>
	<?php } ?>
	</div>
	</div>
</section><!--- gig-info Ends --->

<section class="gig-info card p-0 border-0"><!--- gig-info Starts --->
<?php if($proposal_price == 0){ include("gigInfo/proposal_packages.php"); } ?>
<div class="card-body tab-content"><!--- card-body Starts --->
<?php 
 if($proposal_seller_vacation == "on"){ 
  include("gigInfo/sellerVacationOn.php"); 
 }elseif($proposal_seller_vacation == "off"){
  include("gigInfo/sellerVacationOff.php");
 }
?>
</div>
</section><!--- gig-info Ends --->