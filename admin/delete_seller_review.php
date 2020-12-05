<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	
?>

<?php

if(isset($_GET['delete_seller_review'])){
	
$review_id = $input->get('delete_seller_review');
		
$delete_seller_review = $db->delete("seller_reviews",array('review_id' => $review_id));
	
if($delete_seller_review){

$insert_log = $db->insert_log($admin_id,"seller_review",$review_id,"deleted");

echo "<script>alert('Review deleted successfully.');</script>";
	
echo "<script>window.open('index?view_seller_reviews','_self');</script>";

}
	
	
}

?>

<?php } ?>