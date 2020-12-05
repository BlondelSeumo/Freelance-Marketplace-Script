<?php

if(isset($_POST['seller_id'])){

require_once("../db.php");

$seller_id = $input->post('seller_id');

}

$count_favourites = $db->count("favorites",array("seller_id" => $seller_id));

if($count_favourites > 0){ 

echo $count_favourites;

}

?>