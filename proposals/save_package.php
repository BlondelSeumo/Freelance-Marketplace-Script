<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){

echo "<script>window.open('../login','_self')</script>";

}

if(isset($_POST["package_id"])){

$rules = array(
"package_id" => "required",
"description" => "required",
"delivery_time" => "required",
"price" => "required");

$val = new Validator($_POST,$rules);

if($val->run() == false){

echo "error";

}else{

$package_id = strip_tags($input->post('package_id'));

$description = strip_tags($input->post('description'));

$delivery_time = strip_tags($input->post('delivery_time'));

$price = strip_tags($input->post('price'));

$update_package = $db->update("proposal_packages",array("description"=>$description,"delivery_time"=>$delivery_time,"price"=>$price),array("package_id"=>$package_id));

}

}

?>