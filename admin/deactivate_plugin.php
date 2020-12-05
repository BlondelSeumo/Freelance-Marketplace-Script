<?php 

@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}else{

	if(isset($_GET['deactivate_plugin'])){
		$id = $input->get('deactivate_plugin');
		$delete_plugin = $db->update("plugins",['status'=>0],['id' => $id]);
		if($delete_plugin){
			echo "<script>alert('One Plugin has been Deactivated Successfully.');</script>";
			echo "<script>window.open('index?plugins','_self');</script>";
		}
	}

}