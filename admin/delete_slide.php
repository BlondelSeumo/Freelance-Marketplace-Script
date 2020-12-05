<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	

if(isset($_GET['delete_slide'])){
	
$delete_id = $input->get('delete_slide');
	
$delete_slide = $db->delete("slider",array('slide_id' => $delete_id));

if($delete_slide){

$insert_log = $db->insert_log($admin_id,"slide",$delete_id,"deleted");

echo "<script>alert('One Slide has been Deleted.');</script>";

echo "<script>window.open('index?view_slides','_self');</script>";

}


}

?>

<?php } ?>