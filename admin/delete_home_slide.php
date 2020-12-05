<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	

if(isset($_GET['delete_home_slide'])){
	
$delete_id = $input->get('delete_home_slide');
	
$delete_slide = $db->delete("home_section_slider",array('slide_id' => $delete_id));
		
if($delete_slide){
	
$insert_log = $db->insert_log($admin_id,"home_slide",$delete_id,"deleted");
	
echo "<script>alert('One Slide has been Deleted.');</script>";
	
echo "<script>window.open('index?layout_settings','_self');</script>";
	
}


}

?>

<?php } ?>