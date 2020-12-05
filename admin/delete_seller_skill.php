<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	
?>

<?php

if(isset($_GET['delete_seller_skill'])){
	
	$skill_id = $input->get('delete_seller_skill');

	$delete_skills_relation = $db->delete("skills_relation",array('skill_id' => $skill_id));

	$delete_seller_skill = $db->delete("seller_skills",array('skill_id' => $skill_id));
		
	if($delete_seller_skill){

		$insert_log = $db->insert_log($admin_id,"seller_skill",$skill_id,"deleted");
		
		echo "<script>alert('Seller skill deleted successfully.');</script>";
		echo "<script>window.open('index?view_seller_skills','_self');</script>";
			
	}
		
	
}

?>

<?php } ?>