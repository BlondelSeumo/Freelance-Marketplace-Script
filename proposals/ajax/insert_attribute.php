<?php

session_start();

require_once("../../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){

echo "<script>window.open('../login','_self')</script>";

}


if(isset($_POST["proposal_id"])){
	$status = $_POST['change_status'];
	$proposal_id = $_POST['proposal_id'];
	if($status == 'true'){
		$update_status = $db->update("proposals", array('proposal_status' => 'pending') ,array("proposal_id"=>$proposal_id));
	}

$rules = array(
"proposal_id" => "required",
"attribute_name" => "required");

$val = new Validator($_POST,$rules);

if($val->run() == false){

echo "error";

}else{

$proposal_id = strip_tags($input->post('proposal_id'));

$attribute_name = strip_tags($input->post('attribute_name'));


$data = array();

$i = 0;

$get_p = $db->select("proposal_packages",array("proposal_id"=>$proposal_id));

while($row = $get_p->fetch()){ 

$i++;

$package_id = $row->package_id;

$insert_attribute = $db->insert("package_attributes",array("proposal_id"=>$proposal_id,"package_id"=>$package_id,"attribute_name"=>$attribute_name));

$data[$i]['id'] = $db->lastInsertId();

$data[$i]['attribute_name'] = $attribute_name;

}

echo json_encode($data);

}

}

?>