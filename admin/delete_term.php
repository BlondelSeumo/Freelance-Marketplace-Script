<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php

if(isset($_GET['delete_term'])){
	
$delete_id = $input->get('delete_term');

$delete_term = $db->delete("terms",array('term_id' => $delete_id));
		
if($delete_term){

$insert_log = $db->insert_log($admin_id,"term",$delete_id,"deleted");

echo "<script>alert('One Term has been Deleted.');</script>";
	
echo "<script>window.open('index?view_terms','_self');</script>";	
	
}


}

?>

<?php } ?>