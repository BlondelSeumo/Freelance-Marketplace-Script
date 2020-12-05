<?php
session_start();

require_once("../../../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){

echo "<script>window.open('../login','_self')</script>";

}

if($edited_proposals == 0){
	echo json_encode(false);
	die();
}

$diff = false;
if(isset($_POST["proposal_id"])){

	$proposal_id = $input->post('proposal_id');
	$query = "select proposal_status from proposals where proposal_id = ?";
	$stmt = $db->con->prepare($query);
	$stmt->execute(array($proposal_id));
	$result = $stmt->fetch(PDO::FETCH_OBJ);
	if($result->proposal_status == 'pending' or $result->proposal_status == 'draft' or $result->proposal_status == 'modification'){		
		echo json_encode(false);
		die();
	}
	
	$query = $db->select("proposals_faq", 
		array(			
			'proposal_id' => $input->post('proposal_id'),
			'id'	=> $_POST['id']
		));
	$get_faqs = $query->fetch(PDO::FETCH_ASSOC);	
	$array = array(
		'id' => $get_faqs['id'],
		'title' => $get_faqs['title'],
		'content' => $get_faqs['content'],
	);
	unset($_POST['proposal_id']);

	$array = array_diff_assoc($array, $_POST);
	if(!empty($array)){
		$diff = true;		
	}else{
		$diff = false;
	}	
}
echo json_encode($diff);
die();
?>