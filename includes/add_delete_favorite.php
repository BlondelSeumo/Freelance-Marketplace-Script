<?php

session_start();

require_once("db.php");

if(isset($_SESSION['seller_user_name'])){
	
	$seller_id = $input->post('seller_id');
	
	$proposal_id = $input->post('proposal_id');
	
	if($_POST['favorite'] == "add_favorite"){

	$count_favorite = $db->count("favorites",array("proposal_id" => $proposal_id,"seller_id" => $seller_id));
	
	if($count_favorite == 0){
		    
    $insert_favorite = $db->insert("favorites",array("seller_id" => $seller_id,"proposal_id" => $proposal_id));
			
	}
		
	}
	
	if($_POST['favorite'] == "delete_favorite"){
				
		$delete_favorite = $db->delete("favorites",array('proposal_id' => $proposal_id,"seller_id" => $seller_id));
				
	}
	
}


?>