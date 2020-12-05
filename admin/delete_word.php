<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login.php','_self');</script>";
	
}else{

if(isset($_GET['delete_word'])){
	
$id = $input->get('delete_word');


$delete_word = $db->delete("spam_words",array("id"=>$id)); 

if($delete_word){

echo "<script>alert_success('One Word Has Been Deleted.','index?view_words');</script>";

}

	
}

?>

<?php } ?>