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

$proposal_id = $input->post('proposal_id');
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
	$rules = array(
	"proposal_id" => "required",
	"attribute_name" => "required");

	$val = new Validator($_POST,$rules);

	if($val->run() == false){
	}else{
		$diff = true;
	}		
}
echo json_encode($diff);
die();
?>
