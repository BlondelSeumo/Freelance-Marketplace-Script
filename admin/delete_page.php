<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}else{
	if(isset($_GET['delete_page'])){
		$id = $input->get('delete_page');
		$delete_page = $db->delete("pages",array('id' => $id));
		if($delete_page){

			$delete_meta = $db->delete("pages_meta",array("page_id" => $id));

			$insert_log = $db->insert_log($admin_id,"pages",$id,"deleted");
			echo "<script>alert('Page deleted successfully.');</script>";
			echo "<script>window.open('index?pages','_self');</script>";
		}
	}
?>
<?php } ?>