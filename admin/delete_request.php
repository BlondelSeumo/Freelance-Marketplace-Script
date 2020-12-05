<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php 

if(isset($_GET['delete_request'])){
	
$request_id = $input->get('delete_request');

$delete_request = $db->delete("buyer_requests",array("request_id"=>$request_id)); 
	
if($delete_request){

$delete_send_offers = $db->delete("send_offers",array('request_id' => $request_id)); 
	
$insert_log = $db->insert_log($admin_id,"request",$request_id,"deleted");

echo "<script>alert('One request has been deleted successfully.');</script>";
	
echo "<script>window.open('index?buyer_requests','_self')</script>";
	
}

}

?>

<?php } ?>