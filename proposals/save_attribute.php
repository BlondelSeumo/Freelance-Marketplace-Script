<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){

echo "<script>window.open('../login','_self')</script>";

}

if(isset($_POST["attribute_id"])){

$rules = array(
"attribute_value" => "required",
"attribute_id" => "required");

$val = new Validator($_POST,$rules);

if($val->run() == false){

echo "error";

}else{

$attribute_value = strip_tags($input->post('attribute_value'));

$attribute_id = strip_tags($input->post('attribute_id'));

$update_attribute = $db->update("package_attributes",array("attribute_value"=>$attribute_value),array("attribute_id"=>$attribute_id));

}

}

?>