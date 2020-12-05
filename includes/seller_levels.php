<?php

$seller_user_name = $_SESSION['seller_user_name'];

$get_seller = $db->select("sellers",array("seller_user_name" => $seller_user_name));
$row_seller = $get_seller->fetch();
$seller_id = $row_seller->seller_id;
$seller_level = $row_seller->seller_level;
$seller_rating = $row_seller->seller_rating;


$count_orders = $db->count("orders",array("seller_id" => $seller_id, "order_status" => 'completed'));

$get_general_settings = $db->select("general_settings");   
$row_general_settings = $get_general_settings->fetch();
$level_one_rating = $row_general_settings->level_one_rating;
$level_one_orders = $row_general_settings->level_one_orders;
$level_two_rating = $row_general_settings->level_two_rating;
$level_two_orders = $row_general_settings->level_two_orders;
$level_top_rating = $row_general_settings->level_top_rating;
$level_top_orders = $row_general_settings->level_top_orders;


if($seller_level == 1){
	
	if($seller_rating >= $level_one_rating AND $count_orders >= $level_one_orders){	
	    
		$update_seller_level = $db->update("sellers",array("seller_level" => 2),array("seller_id" => $seller_id));
		$update_seller_proposals = $db->update("proposals",array("level_id" => 2),array("proposal_seller_id" => $seller_id));

		if($update_seller_proposals){

?>

<div id="level-one-modal" class="modal fade"><!-- level-one-modal modal fade Starts -->

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title"> Promoted To Level One </h5>

				<button class="close" data-dismiss="modal">
				  <span> &times; </span>
				</button>

			</div>

			<div class="modal-body text-center">

				<h2> Great </h2>

				<p class="lead">
				We Have Some Great News For You!<br> 
				You're now a level one seller.
				</p>

				<img src="<?= $site_url; ?>/images/level_badge_1.png" >

			</div>

			<div class="modal-footer">

			<button class="btn btn-secondary" data-dismiss="modal">Close</button>

			</div>

		</div>

	</div>

</div><!-- level-one-modal modal fade Ends -->

<script>
	$(document).ready(function(){
		$("#level-one-modal").modal('show');
	});
</script>

<?php

}

}

}

?>

<?php 

if($seller_level == 2 ){
	
    if($seller_rating >= $level_two_rating AND $count_orders >= $level_two_orders){	

	$update_seller_level = $db->update("sellers",array("seller_level" => 3),array("seller_id" => $seller_id));

	$update_seller_proposals = $db->update("proposals",array("level_id" => 3),array("proposal_seller_id" => $seller_id));

    if($update_seller_proposals){

?>

<div id="level-two-modal" class="modal fade"><!-- level-two-modal modal fade Starts -->

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title"> Promoted To Level Two</h5>

				<button class="close" data-dismiss="modal">
				  <span> &times; </span>
				</button>

			</div>

			<div class="modal-body text-center">

				<h2> Awesome </h2>

				<p class="lead">
				We Have Some Awesome News For You!<br> 
				You're now a level 2 seller. Good Job!
				</p>

				<img src="<?= $site_url; ?>/images/level_badge_2.png" >

			</div>

			<div class="modal-footer">

			<button class="btn btn-secondary" data-dismiss="modal">Close</button>

			</div>

		</div>

	</div>

</div><!-- level-two-modal modal fade Ends -->

<script>

$(document).ready(function(){
	
	$("#level-two-modal").modal('show');
	
});

</script>

<?php

}

}

}

?>

<?php 

    if($seller_level == 3 ){

    if($seller_rating >= $level_top_rating AND $count_orders >= $level_top_orders){	

	$update_seller_level = $db->update("sellers",array("seller_level" => 4),array("seller_id" => $seller_id));

	$update_seller_proposals = $db->update("proposals",array("level_id" => 4),array("proposal_seller_id" => $seller_id));

    if($update_seller_proposals){

?>

<div id="top-rated-modal" class="modal fade"><!-- top-rated-modal modal fade Starts -->

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title"> Top Rated Seller </h5>

				<button class="close" data-dismiss="modal">
				  <span> &times; </span>
				</button>

			</div>

			<div class="modal-body text-center">

				<h2> Splendid </h2>

				<p class="lead">
				We Have Some Splendid News For You!<br> 
				You're Now a Top Rated Seller. More Custmers Will Trust You. Great Job!
				</p>

				<img src="<?= $site_url; ?>/images/level_badge_3.png" >

			</div>

			<div class="modal-footer">

				<button class="btn btn-secondary" data-dismiss="modal">Close</button>

			</div>

		</div>

	</div>

</div><!-- top-rated-modal modal fade Ends -->

<script>

$(document).ready(function(){
	
	$("#top-rated-modal").modal('show');
	
});

</script>

<?php

}

}

}

?>
