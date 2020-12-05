<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php

if(isset($_GET['delete_all_logs'])){
	
$delete_logs = $db->delete("admin_logs",""); 
	
if($delete_logs){

echo "<script>alert('All admin logs has been deleted successfully.');</script>";
	
echo "<script>window.open('index?admin_logs','_self')</script>";
	
}

}

?>

<?php } ?>