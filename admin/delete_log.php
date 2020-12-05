<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php 

if(isset($_GET['delete_log'])){
	
$id = $input->get('delete_log');

$delete_log = $db->delete("admin_logs",array("id"=>$id)); 
	
if($delete_log){

echo "<script>alert('One admin log has been deleted successfully.');</script>";
	
echo "<script>window.open('index?admin_logs','_self')</script>";
	
}

}

?>

<?php } ?>