<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php

if(isset($_GET['delete_order_file'])){
	
$file = $input->get('delete_order_file');
	
$path = "../order_files/$file";	

if(unlink($path)){

$insert_log = $db->insert_log($admin_id,"inbox_file","","deleted");

echo "<script>alert('$file has been deleted.');</script>";
	
echo "<script>window.open('index?view_order_files','_self');</script>";
	
}

	
}

?>

<?php } ?>