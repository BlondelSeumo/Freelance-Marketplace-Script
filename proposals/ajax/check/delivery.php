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


	$file = $_FILES["file"]["name"];
	$allowed = array('jpeg','jpg','gif','png','tif','avi','mpeg','mpg','mov','rm','3gp','flv','mp4','zip','rar','mp3','wav','docx','csv','xls','pptx','pdf','txt');		
	$file_extension = pathinfo($file, PATHINFO_EXTENSION);
	if(in_array($file_extension,$allowed) & !empty($file)){
		echo json_encode(true);
		die();
	}

	$data = $input->post();
	$data['watermark'] = isset($data['enable_watermark']) ? $data['enable_watermark']: 0;
	$data['enable'] = isset($data['enable']) ? $data['enable']: 0;
	unset($data['enable_watermark']);
	$query = $db->select("instant_deliveries", array('proposal_id' => $input->post('proposal_id')));
	$get_delivery = $query->fetch(PDO::FETCH_ASSOC);	
	$array = array(
		'enable' => $get_delivery['enable'],
		'message' => $get_delivery['message'],
		'watermark' => $get_delivery['watermark']		
	);
	$array = array_diff_assoc($array, $data);
	if(!empty($array)){
		$diff = true;		
	}else{
		$diff = false;
	}	
}
echo json_encode($diff);
die();
?>