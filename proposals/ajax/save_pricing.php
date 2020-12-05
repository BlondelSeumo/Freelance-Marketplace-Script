<?php

session_start();

require_once("../../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){

echo "<script>window.open('../login','_self')</script>";

}

if(isset($_POST["proposal_id"])){  

$proposal_id = strip_tags($input->post('proposal_id'));

$packages = $input->post('proposal_packages');

foreach ($packages as $key => $package) {
  
  $package_id = $package['package_id'];
  $description = $package['description'];
  $delivery_time = $package['delivery_time'];
  $revisions = $package['revisions'];
  $price = $package['price'];

  $update_package = $db->update("proposal_packages",array("description"=>$description,"delivery_time"=>$delivery_time,"revisions"=>$revisions,"price"=>$price),array("package_id"=>$package_id));
}



if(isset($_POST['package_attributes'])){

   $attrs = $input->post('package_attributes');

   foreach($attrs as $key => $attr) {
	  
	  $attribute_id = $attr['attribute_id'];
	  $attribute_value = $attr['attribute_value'];

	  $update_attr = $db->update("package_attributes",array("attribute_value"=>$attribute_value),array("attribute_id"=>$attribute_id));
	 	
    }

}


if(isset($_POST['proposal_price'])){

  $proposal_price = $input->post('proposal_price');
  $proposal_revisions = $input->post('proposal_revisions');
  $delivery_id = $input->post('delivery_id');

  $update_p = $db->update("proposals",["proposal_price"=>$proposal_price,"delivery_id"=>$delivery_id,"proposal_revisions"=>$proposal_revisions],["proposal_id"=>$proposal_id]);

}

if(isset($_POST["change_status"])){
	$status = $_POST['change_status'];
	$proposal_id = $_POST['proposal_id'];
	if($status == 'true' && isset( $_POST['proposal_id'] )){
		$update_status = $db->update("proposals", array('proposal_status' => 'pending') ,array("proposal_id"=>$proposal_id));
	}
}


}