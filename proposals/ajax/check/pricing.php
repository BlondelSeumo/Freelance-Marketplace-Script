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

// echo $edited_proposals;

$proposal_id = strip_tags($input->post('proposal_id'));
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

	// Proposals
	if(isset($_POST['proposal_price'])){
		$proposal_price = $input->post('proposal_price');
		$proposal_revisions = $input->post('proposal_revisions');
		$delivery_id = $input->post('delivery_id');
		$query = "select proposal_price, delivery_id, proposal_revisions from proposals where proposal_id = ?";
		$stmt = $db->con->prepare($query);
		$stmt->execute(array($proposal_id));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$data_proposal = array(
			'proposal_price' => $input->post('proposal_price'),
			'proposal_revisions' => $input->post('proposal_revisions'),
			'delivery_id' => $input->post('delivery_id')
		);
		$array = array_diff_assoc($result, $data_proposal);	  

		if(!empty($array)){
			$diff = true;
		}else{
			$diff = false;
		}		
	}

	// Proposal Packages
	if(isset($_POST['proposal_packages'])){

		$packages = $input->post('proposal_packages');
		$packages = array_values($packages);
		$query = "select package_id, description, revisions, delivery_time,price from proposal_packages where proposal_id = ?";
		$stmt = $db->con->prepare($query);
		$stmt->execute(array($proposal_id));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);	

		foreach($result as $key => $res){
			$array = array_diff_assoc($result[$key], $packages[$key]);
			if(!empty($array)){
				$diff = true;
			}else{
				if($diff != true){
					$diff = false;
				}
			}
		}
	}

	echo json_encode($diff);

	die();
	
}
?>