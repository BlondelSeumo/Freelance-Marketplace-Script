<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	

?>

<?php

if(isset($_GET['delete_enquiry_type'])){
	
$enquiry_id = $input->get('delete_enquiry_type');
	
$delete_enquiry_type = $db->delete("enquiry_types",array('enquiry_id' => $enquiry_id));
	
if($delete_enquiry_type){

$insert_log = $db->insert_log($admin_id,"enquiry_type",$enquiry_id,"deleted");
	
echo "<script>alert('One Enquiry Type Has been Deleted.');</script>";
	
echo "<script>window.open('index?view_enquiry_types','_self');</script>";

}

	
}

?>

<?php } ?>