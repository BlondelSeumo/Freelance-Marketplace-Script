<div class="container-fluid"><!-- Container-fluid Starts -->
<div class="row"><!--- row Starts --->
<div class="col-md-12"><!--- col-md-12 Starts -->

<?php if($proposal_enable_referrals == "yes"){ ?>
<?php if($proposal_seller_id != @$login_seller_id){ ?>	
<div class="card mb-3 referral-box">
<div class="card-header">Referral Link</div>
<div class="card-body">
	<h6 class="line-height-full font-weight-normal mb-3">
	Support the seller and earn <?= $proposal_referral_money; ?>% of each sale made using link below. <a href="<?= $site_url; ?>/terms_and_conditions" class="strike">Terms apply.</a>
	</h6>
	<?php if(isset($_SESSION['seller_user_name'])){ ?>
	<input class="form-control" readonly="" value="<?= $site_url."/referral.php?proposal_id=$proposal_id&referral_code=".$proposal_referral_code."&referrer_id=$login_seller_id"; ?>">
	<?php }else{ ?>
	<button class="btn btn-order referral" data-toggle="modal" data-target="#login-modal">Get referral link</button>
	<?php } ?>
</div>
</div>
<?php } ?>
<?php } ?>
<center class="mb-3">
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<div class="addthis_inline_share_toolbox_d0jy"></div>
</center>

</div><!---  col-md-12 Ends -->
</div><!-- Row Ends -->
</div><!-- Container-fluid Ends -->