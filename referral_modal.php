<?php

session_start();
require_once("includes/db.php");

$proposal_id = $input->post('proposal_id');

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

$select_proposal = $db->select("proposals",array("proposal_id" => $proposal_id));
$row_proposal = $select_proposal->fetch();
$proposal_referral_money = $row_proposal->proposal_referral_money;
$proposal_referral_code = $row_proposal->proposal_referral_code;

?>

<div id="referral-modal" class="modal">

<div class="modal-dialog" role="document">
 
 <div class="modal-content">
	
<div class="modal-header">

Referral Link <button class="close" data-dismiss="modal"> <span> &times;</span></button>

</div>

<div class="modal-body">

<h6>If anyone buys this proposal with your unique referral link, you will get <?= $proposal_referral_money; ?>% from every purchase.</h6>

<input class="form-control mb-1" disabled value="<?= $site_url . "/referral?proposal_id=$proposal_id&referral_code=" . $proposal_referral_code . "&referrer_id=$login_seller_id"; ?>">

</div>

</div>

</div>

</div>

<script>

$(document).ready(function(){
	
	$("#referral-modal").modal("show");
	
	$(".close").click(function(){
		$("#referral-modal").hide();
		$(".modal-backdrop").hide();
	});

	$(".modal-backdrop").click(function(){
		$("#referral-modal").hide();
		$(".modal-backdrop").remove();
	});

});
	
</script>	
	