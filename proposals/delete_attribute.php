<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){

echo "<script>window.open('../login','_self')</script>";

}

if(isset($_POST["proposal_id"])){

$proposal_id = $input->post('proposal_id');

$attribute_name = $input->post('attribute_name');

$delete_attribute = $db->delete("package_attributes",array('attribute_name'=>$attribute_name,'proposal_id' => $proposal_id));

}

?>

<?php ?>