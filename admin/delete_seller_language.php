<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	

?>

<?php

if(isset($_GET['delete_seller_language'])){
	
	$language_id = $input->get('delete_seller_language');

	$delete_languages_relation = $db->delete("languages_relation",array('language_id' => $language_id));
	$delete_seller_language = $db->delete("seller_languages",array('language_id' => $language_id));

	if($delete_seller_language){

		$insert_log = $db->insert_log($admin_id,"seller_language",$language_id,"deleted");

		echo "<script>alert('One language has been deleted successfully.');</script>";
		echo "<script>window.open('index?view_seller_languages','_self');</script>";

	}
	
}

?>

<?php } ?>