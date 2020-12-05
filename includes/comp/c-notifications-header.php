<?php

if(isset($_POST['seller_id'])){

require_once("../db.php");

$seller_id = $input->post('seller_id');

}

$count_unread_notifications = $db->count("notifications",array("receiver_id" => $seller_id,"status" => "unread"));

if($count_unread_notifications > 0){ 

echo $count_unread_notifications;

}