<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php

if(isset($_GET['delete_user'])){
	
$delete_id = $input->get('delete_user');
	
$delete_admin = $db->delete("admins",array('admin_id' => $delete_id));
		
if($delete_admin){
	
echo "<script>alert('Admin has been deleted successfully.');</script>";

echo "<script>window.open('index?view_users','_self');</script>";

}

	
}

?>

<?php } ?>