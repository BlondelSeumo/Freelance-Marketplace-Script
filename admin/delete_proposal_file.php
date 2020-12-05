<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php

if(isset($_GET['delete_proposal_file'])){
	
$file = $input->get('delete_proposal_file');
	
$path = "../proposals/proposal_files/$file";	

if(unlink($path)){
	
$insert_log = $db->insert_log($admin_id,"proposal_file","","deleted");

echo "<script>alert('$file Has been deleted successfully.');</script>";
	
echo "<script>window.open('index?view_proposals_files','_self');</script>";

	
}

	
}

?>

<?php } ?>