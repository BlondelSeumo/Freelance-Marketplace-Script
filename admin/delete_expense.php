<?php 
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}else{

	if(isset($_GET['delete_expense'])){
		$id = $input->get('delete_expense');
		$delete_expense = $db->delete("expenses",array('id' => $id));
		if($delete_expense){
			echo "<script>alert('One Expense has been Deleted.');</script>";
			echo "<script>window.open('index?sales','_self');</script>";
		}
	}

}
?>