<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	
	echo "<script>window.open('../login','_self')</script>";
	
}

$login_seller_user_name = $_SESSION['seller_user_name'];

$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));

$row_login_seller = $select_login_seller->fetch();

$login_seller_id = $row_login_seller->seller_id;


$proposal_id = $input->post('proposal_id');

$request_id = $input->post('request_id');



$get_requests = $db->select("buyer_requests",array("request_id" => $request_id));

$row_requests = $get_requests->fetch();

$request_title = $row_requests->request_title;

$request_description = $row_requests->request_description;

$request_seller_id = $row_requests->seller_id;



$select_request_seller = $db->select("sellers",array("seller_id" => $request_seller_id));

$row_request_seller = $select_request_seller->fetch();

$request_seller_image = $row_request_seller->seller_image;



$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));

$row_proposals = $select_proposals->fetch();

$proposal_title = $row_proposals->proposal_title;

?>


<div class="modal-content"><!--- modal-content Starts --->

<div class="modal-header"><!--- modal-header Starts --->

<h5 class="modal-title h5"> Specify Your Proposal Details </h5>

<button class="close" data-dismiss="modal"> &times; </button>

</div><!--- modal-header Ends --->

<div class="modal-body p-0"><!--- modal-body p-0 Starts --->

<div class="request-summary"><!--- request-summary Starts --->

<?php if(!empty($request_seller_image)){ ?>

<img src="<?= $site_url; ?>/user_images/<?= $request_seller_image; ?>" width="50" height="50" class="rounded-circle">

<?php }else{ ?>

<img src="<?= $site_url; ?>/user_images/empty-image.png" width="50" height="50" class="rounded-circle">

<?php } ?>

<div id="request-description"><!--- request-description Starts --->

<h6 class="text-success mb-1"> <?= $request_title; ?> </h6>

<p> <?= $request_description; ?> </p>

</div><!--- request-description Ends --->

</div><!--- request-summary Ends --->

<form id="proposal-details-form"><!--- proposal-details-form Starts --->

<div class="selected-proposal p-3"><!--- selected-proposal p-3 Starts --->

<h5> <?= $proposal_title; ?> </h5>

<hr>

<input type="hidden" name="proposal_id" value="<?= $proposal_id; ?>">

<input type="hidden" name="request_id" value="<?= $request_id; ?>">

<div class="form-group"><!--- form-group Starts --->

<label class="font-weight-bold"> Description :  </label>

<textarea name="description" class="form-control" required=""></textarea>

</div><!--- form-group Ends --->

<hr>

<div class="form-group"><!--- form-group Starts --->

<label class="font-weight-bold"> Delivery Time :  </label>

<select class="form-control float-right" name="delivery_time">

<?php 

$get_delivery_times = $db->select("delivery_times");

while($row_delivery_times = $get_delivery_times->fetch()){

$delivery_proposal_title = $row_delivery_times->delivery_proposal_title;
	
echo "<option value='$delivery_proposal_title'> $delivery_proposal_title </option>";
	
}

?>

</select>

</div><!--- form-group Ends --->

<hr>


<div class="form-group"><!--- form-group Starts --->

<label class="font-weight-bold"> Total Offer Amount :  </label>

<div class="input-group float-right">

<span class="input-group-addon font-weight-bold"> <?= $s_currency; ?> </span>

<input type="number" name="amount" class="form-control" min="5" placeholder="5 Minimum" required="">

</div>

</div><!--- form-group Ends --->


</div><!--- selected-proposal p-3 Ends --->

<div class="modal-footer"><!--- modal-footer Starts --->

<button type="button" class="btn btn-secondary" data-dismiss="modal" data-toggle="modal" data-target="#send-offer-modal">Back</button>

<button type="submit" class="btn btn-success">Submit Offer</button>

</div><!--- modal-footer Ends --->

</form><!--- proposal-details-form Ends --->

</div><!--- modal-body p-0 Ends --->

</div><!--- modal-content Ends --->

<div id="insert_offer"></div>


<script>

$(document).ready(function(){
	
$("#proposal-details-form").submit(function(event){

event.preventDefault();

description = $("textarea[name='description']").val();

delivery_time = $("select[name='delivery_time']").val();

amount = $("input[name='amount']").val();

if(description == "" | delivery_time == "" | amount == ""){

swal({
type: 'warning',
text: 'You Must Need To Fill Out All Fields Before Submitting Offer.'
});

}else{


$.ajax({
	
method: "POST",
url: "<?= $site_url; ?>/requests/insert_offer",
data: $('#proposal-details-form').serialize()

}).done(function(data){
	
$("#submit-proposal-details").modal('hide');

$("#insert_offer").html(data);
	
});
	

}

});
	
	
});

</script>