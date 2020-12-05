<?php
@session_start();
require_once("../includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('../login','_self')</script>";
}
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$receiver_id = $input->post('receiver_id');
$message = $input->post('message');
$file = $input->post('file');
?>
<div id="send-offer-modal" class="modal fade"><!-- send-offer-modal modal fade Starts -->
<div class="modal-dialog"><!-- modal-dialog Starts -->
<div class="modal-content"><!-- modal-content Starts -->
<div class="modal-header"><!-- modal-header Starts -->
<h5 class="modal-title"> Select A Proposal/Service To Offer </h5>
<button class="close" data-dismiss="modal"><span>&times;</span></button>
</div><!-- modal-header Ends -->
<div class="modal-body p-0"><!-- modal-body p-0 Starts -->
<div class="request-proposals-list"><!--- request-proposals-list Starts --->
<?php
$get_proposals = $db->select("proposals",array("proposal_seller_id"=>$login_seller_id,"proposal_status"=>"active"));
while($row_proposals = $get_proposals->fetch()){
$proposal_id = $row_proposals->proposal_id;
$proposal_title = $row_proposals->proposal_title;
$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
?>
<div class="proposal-picture"><!--- proposal-picture Starts --->
<input type="radio" id="radio-<?= $proposal_id; ?>" class="radio-custom" name="proposal_id" value="<?= $proposal_id; ?>" required>
<label for="radio-<?= $proposal_id; ?>" class="radio-custom-label"> </label>
<img src="<?= $proposal_img1; ?>" width="50" height="50">
</div><!--- proposal-picture Ends --->
<div class="proposal-title"><!--- proposal-title Starts --->
<p><?= $proposal_title; ?></p>
</div><!--- proposal-title Ends --->
<hr>
<?php } ?>
</div><!--- request-proposals-list Ends --->
</div><!-- modal-body p-0 Ends -->
<div class="modal-footer"><!--- modal-footer Starts --->
<button class="btn btn-secondary" data-dismiss="modal"> Close </button>
<button id="submit-proposal" class="btn btn-success" data-toggle="modal" data-dismiss="modal" data-target="#submit-proposal-details">Go Next</button>
</div><!--- modal-footer Ends --->
</div><!-- modal-content Ends -->
</div><!-- modal-dialog Ends -->
</div><!-- send-offer-modal modal fade Ends -->
<div id="submit-proposal-details" class="modal fade"><!--- modal fade Starts --->
<div class="modal-dialog"><!--- modal-dialog Starts --->
</div><!--- modal-dialog Ends --->
</div><!--- modal fade Ends --->
<textarea id="message" class="d-none"><?= $message; ?></textarea>
<script>
$(document).ready(function(){
	$("#send-offer-modal").modal('show');
	$("#submit-proposal").attr("disabled", "disabled");
	$(".radio-custom-label").click(function(){
		$("#submit-proposal").removeAttr("disabled");
	});
   $("#submit-proposal").click(function(){
   proposal_id = document.querySelector('input[name="proposal_id"]:checked').value;	   
   receiver_id = "<?= $receiver_id; ?>";
   message = $("#message").val();
   file = "<?= $file; ?>";
   $.ajax({
		method: "POST",   
		url: "<?= $site_url; ?>/conversations/submit_proposal_details",
		data: { proposal_id: proposal_id, receiver_id: receiver_id, message: message, file: file}
		}).done(function(data){
		 $("#submit-proposal-details .modal-dialog").html(data);
		});
   });
});
</script>