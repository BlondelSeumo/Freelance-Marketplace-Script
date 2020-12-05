<?php
if(strlen($proposal_desc) > 400){ 
	$show = ""; 
}else{ 
	$show = "show";
}
?>
<div class="mp-gig-wrapper js-mp-gig-wrapper"><!--- mp-gig-wrapper js-mp-gig-wrapper Starts --->
	<div class="mp-gig"><!--- mp-gig Starts --->
		<div class="gig-page-section-dummy" id="overview"></div>
		<div class="gig-page-section gig-page-section-overview">
			<div id="GigGallery-component"><?php include("includes/proposal_slider.php"); ?></div>
		</div>
		<div class="gig-page-section-dummy" id="seller-info"></div>
		<?php 
			include("mobile/sellerInfo.php"); 
			include("mobile/gigInfo.php"); 
			include("mobile/referralBox.php"); 
			include("mobile/faqs.php"); 
			include("mobile/reviews.php");
		?>
	</div><!--- mp-gig Ends --->
</div><!--- mp-gig-wrapper js-mp-gig-wrapper Ends --->
<?php include("mobile/javascript.php"); ?>