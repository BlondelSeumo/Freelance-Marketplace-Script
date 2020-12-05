<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php

if(isset($_GET['delete_buyer_review'])){
	
$review_id = $input->get('delete_buyer_review');


$get_buyer_reviews = $db->select("buyer_reviews",array("review_id" => $review_id));

$row_buyer_reviews = $get_buyer_reviews->fetch();

$seller_id = $row_buyer_reviews->review_seller_id;

$proposal_id = $row_buyer_reviews->proposal_id;

$buyer_rating = $row_buyer_reviews->buyer_rating;
	

$get_seller = $db->select("sellers",array("seller_id" => $seller_id));

$row_seller = $get_seller->fetch();

$seller_rating = $row_seller->seller_rating;


if($buyer_rating == "5"){
	
$update_seller_rating = $db->query("update sellers set seller_rating=seller_rating-7 where seller_id='$seller_id'");

}elseif($buyer_rating == "4"){
	
$update_seller_rating = $db->query("update sellers set seller_rating=seller_rating-2 where seller_id='$seller_id'");
		
}elseif($buyer_rating == "3"){
	
if($seller_rating == "100"){
	
}else{
	
$update_seller_rating = $db->query("update sellers set seller_rating=seller_rating+3 where seller_id='$seller_id'");
			
}
	
}elseif($buyer_rating == "2"){
	
if($seller_rating == "100"){
	
}else{
	
$update_seller_rating = $db->query("update sellers set seller_rating=seller_rating+5 where seller_id='$seller_id'");
	
}

}elseif($buyer_rating == "1"){
	
if($seller_rating == "100"){
	
}else{
	
$update_seller_rating = $db->query("update sellers set seller_rating=seller_rating+7 where seller_id='$seller_id'");
		
}
	
}


$delete_buyer_review = $db->delete("buyer_reviews",array('review_id' => $review_id));	
	
if($delete_buyer_review){	

$ratings = array();
	
$select_buyer_reviews = $db->select("buyer_reviews",array("proposal_id" => $proposal_id));

$count_reviews = $select_buyer_reviews->rowCount();

while($row_buyer_reviews = $select_buyer_reviews->fetch()){
	
	$proposal_buyer_rating = $row_buyer_reviews->buyer_rating;
	
	array_push($ratings,$proposal_buyer_rating);
	
}

$total = array_sum($ratings);
	
$avg = $total/count($ratings);
	
$updated_propoasl_rating = substr($avg,0,1);
	

$update_proposal = $db->update("proposals",["proposal_rating"=>$updated_propoasl_rating],["proposal_id"=>$proposal_id]);
	
if($update_proposal){
	
$insert_log = $db->insert_log($admin_id,"buyer_review",$review_id,"deleted");

echo "<script>alert('One Buyer Review Has been Deleted Successfully.');</script>";

echo "<script>window.open('index?view_buyer_reviews','_self');</script>";
	
}


	
}
	
}

?>

<?php } ?>