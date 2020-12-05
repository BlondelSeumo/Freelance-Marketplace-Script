<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	

?>

<?php

if(isset($_GET['delete_delivery_time'])){
	
$delivery_id = $input->get('delete_delivery_time');
	
$delete_delivery_time = $db->delete("delivery_times",array('delivery_id' => $delivery_id));
		
if($delete_delivery_time){

$insert_log = $db->insert_log($admin_id,"delivery_time",$delivery_id,"deleted");
	
echo "<script>alert('Delivery time deleted successfully.');</script>";
	
echo "<script>window.open('index?view_delivery_times','_self');</script>";
	
	
}
	
	
}

?>

<?php } ?>