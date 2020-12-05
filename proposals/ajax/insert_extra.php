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
  }

  $rules = array(
  "name" => "required",
  "price" => "required");

  $val = new Validator($_POST,$rules);

  if($val->run() == false){

  echo "error";

  }else{

  $data = $input->post();

  unset($data['change_status']);

  $insert_extra = $db->insert("proposals_extras",$data);

  $data['id'] = $db->lastInsertId();

  if(isset($_POST["proposal_id"])){
	    $status = $_POST['change_status'];
	    $proposal_id = $_POST['proposal_id'];
	    if($status == 'true'){
	      $update_status = $db->update("proposals", array('proposal_status' => 'pending') ,array("proposal_id"=>$proposal_id));
	    }
	  }

  echo json_encode($data);

  }