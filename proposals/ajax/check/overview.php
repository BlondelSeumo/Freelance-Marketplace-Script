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

	if(isset($_POST['buyer_instruction'])){
		$buyer_instruction = trim($_POST['buyer_instruction']);
		$query = "select buyer_instruction from proposals where proposal_id = ?";
		$stmt = $db->con->prepare($query);
		$stmt->execute(array($proposal_id));
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		if ($buyer_instruction !== $result->buyer_instruction){
			echo json_encode(true);
			die();
		}else{
			echo json_encode(false);
			die();
		}
	}


	if(isset($_POST['proposal_desc'])){		
		$proposal_desc = $_POST['proposal_desc'];
		$query = "select proposal_desc from proposals where proposal_id = ?";
		$stmt = $db->con->prepare($query);
		$stmt->execute(array($proposal_id));
		$result = $stmt->fetch(PDO::FETCH_OBJ);		
		if(trim($result->proposal_desc) !== trim($proposal_desc)){	
			echo json_encode(true);
			die();
		}else{
			echo json_encode(false);
			die();
		}
	}


	if(isset($_POST['proposal_img1'])){
		$query = "select proposal_img1, proposal_img1_s3, proposal_img2, proposal_img2_s3, proposal_img3, proposal_img3_s3, proposal_img4, proposal_video,proposal_video_s3, proposal_id from proposals where proposal_id = ?";
		$stmt = $db->con->prepare($query);
		$stmt->execute(array($proposal_id));		
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		$array = array(
			'proposal_img1' => $result->proposal_img1,
			'proposal_img1_s3' => $result->proposal_img1_s3,
			'proposal_img2' => $result->proposal_img2,
			'proposal_img2_s3' => $result->proposal_img2_s3,
			'proposal_img3' => $result->proposal_img3,
			'proposal_img3_s3' => $result->proposal_img3_s3,
			'proposal_img4' => $result->proposal_img4,
			'proposal_video' => !empty($result->proposal_video) ? trim($result->proposal_video):'',
			'proposal_video_s3' => $result->proposal_video_s3,
			'proposal_id' => $result->proposal_id,
		);
		
		$data = $input->post();
		$data['proposal_video'] = empty($data['proposal_video']) ? '' : trim($data['proposal_video']);
		$array = array_diff_assoc($array, $data);
		if(!empty($array)){
			echo json_encode(true);
		}else{
			echo json_encode(false);
		}
		die();
	}




	include("../../sanitize_url.php");
	$proposal_title = $input->post('proposal_title');
	$sanitize_url = proposalUrl($proposal_title);
	$data = $input->post();
	$query = $db->select("proposals", array('proposal_id' => $input->post('proposal_id')));
	$get_proposal = $query->fetch(PDO::FETCH_ASSOC);	
	$array = array(
		'proposal_title' => $get_proposal['proposal_title'],
		'proposal_cat_id' => $get_proposal['proposal_cat_id'],
		'proposal_child_id' => $get_proposal['proposal_child_id'],
		'delivery_id' => $get_proposal['delivery_id'],
		'proposal_enable_referrals' => $get_proposal['proposal_enable_referrals'],
		'proposal_referral_money' => $get_proposal['proposal_referral_money'],
		'proposal_tags' => $get_proposal['proposal_tags']
	);
	$array = array_diff_assoc($array, $data);
	if(!empty($array)){
		echo json_encode(true);
	}else{
		echo json_encode(false);
	}	
	die();
}
?>