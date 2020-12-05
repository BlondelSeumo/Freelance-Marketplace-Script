<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	
	echo "<script>window.open('../login','_self')</script>";
	
}

$relevant_requests = $row_general_settings->relevant_requests;

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

$request_id = $input->post('request_id');

$get_requests = $db->select("buyer_requests",array("request_id" => $request_id));
$row_requests = $get_requests->fetch();
$request_title = $row_requests->request_title;
$request_description = $row_requests->request_description;
$child_id = $row_requests->child_id;
$request_seller_id = $row_requests->seller_id;

$select_request_seller = $db->select("sellers",array("seller_id" => $request_seller_id));
$row_request_seller = $select_request_seller->fetch();
$request_seller_image = $row_request_seller->seller_image;

?>

<div id="send-offer-modal" class="modal fade">


	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title">Select A Proposal/Service To Offer</h5>

				<button class="close" data-dismiss="modal"> <span> &times;</span></button>
				

			</div>

			<div class="modal-body p-0">

				<div class="request-summary">
                    
                <?php if(!empty($request_seller_image)){ ?>
                    
                     <img src="<?= $site_url; ?>/user_images/<?= $request_seller_image; ?>" width="50" height="50" class="rounded-circle">

				<?php }else{ ?>

                    <img src="<?= $site_url; ?>/user_images/empty-image.png" width="50" height="50" class="rounded-circle">

                <?php } ?>

				<div id="request-description">

					<h6 class="text-success mb-1"> <?= $request_title; ?> </h6>

					<p><?= $request_description; ?></p>

				</div>
					

				</div>


				<div class="request-proposals-list">
                    
               <?php


               if($relevant_requests == "yes"){
               	$get_proposals = $db->select("proposals",array("proposal_child_id"=>$child_id,"proposal_seller_id"=>$login_seller_id,"proposal_status"=>"active"));
               }else{
               	$get_proposals = $db->select("proposals",array("proposal_seller_id"=>$login_seller_id,"proposal_status"=>"active"));
               }
               
					while($row_proposals = $get_proposals->fetch()){

					$proposal_id = $row_proposals->proposal_id;

					$proposal_title = $row_proposals->proposal_title;

					$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);

               ?>

					<div class="proposal-picture">

					<input type="radio" id="radio-<?= $proposal_id; ?>" class="radio-custom" name="proposal_id" value="<?= $proposal_id; ?>" required>

					<label for="radio-<?= $proposal_id; ?>" class="radio-custom-label"></label>

					<img src="<?= $proposal_img1; ?>" width="50" height="50" style="border-radius: 2% !important;">

					</div> 

					<div class="proposal-title">

					<p><?= $proposal_title; ?></p>

					</div>

					<hr>
                    
                    <?php } ?>

				</div>

			</div>

			<div class="modal-footer">

				<button class="btn btn-secondary" data-dismiss="modal"> Close</button>

				<button class="btn btn-success" id="submit-proposal" data-toggle="modal" data-dismiss="modal" data-target="#submit-proposal-details" title="Choose an offer before clicking continue">Continue</button>

			</div>

		</div>

	</div>

</div>

<div id="submit-proposal-details" class="modal fade"> <!-- Continue's Code -->

<div class="modal-dialog">


</div>

</div> <!-- Continue end -->

<script>

$(document).ready(function(){
	
	$("#send-offer-modal").modal("show");
	
	$("#submit-proposal").attr("disabled", "disabled");
	
	$(".radio-custom-label").click(function(){
		
		$("#submit-proposal").removeAttr("disabled");
		
	});
	
 
   $("#submit-proposal").click(function(){
	   
		proposal_id = document.querySelector('input[name="proposal_id"]:checked').value;	   
		
		request_id = "<?= $request_id; ?>";
	   
		$.ajax({
		   
		method: "POST",   
		url: "<?= $site_url; ?>/requests/submit_proposal_details",
		data: { proposal_id: proposal_id, request_id: request_id }
		   
		}).done(function(data){
		   
		   $("#submit-proposal-details .modal-dialog").html(data);
		   
		});
	
   });
	
});

</script>