<?php

include('db.php');

$seller_user_name = $_SESSION['seller_user_name'];
$get_seller = $db->select("sellers",array("seller_user_name" => $seller_user_name));
$row_seller = $get_seller->fetch();
$seller_id = $row_seller->seller_id;

$last_activity = date("Y-m-d H:i:s");

$update_seller = $db->update("sellers",array("seller_activity" => $last_activity),array("seller_id" => $seller_id));

?>