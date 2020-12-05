<?php

@session_start();
require_once("../db.php");

$seller_id = $input->post('seller_id');
$search = $input->post('search');
$data = array();

$get_p = $db->query("select * from proposals where proposal_title Like :search AND proposal_status='active' LIMIT 0,6",["search"=>"%$search%"]);
$data['count_proposals'] = $get_p->rowCount();
$i = 0;
while($row = $get_p->fetch()){
   $i++;
   $get_seller = $db->select("sellers",array("seller_id" => $row->proposal_seller_id));
   $row_seller = $get_seller->fetch();
   $seller_user_name = $row_seller->seller_user_name;

   $title = str_ireplace("$search",ucwords("<b>$search</b>"),$row->proposal_title);
   $data['proposals'][$i]['title'] = $title;
   $data['proposals'][$i]['url'] = "$site_url/proposals/".$seller_user_name."/".$row->proposal_url;
}

$get_s = $db->query("select * from sellers where seller_user_name Like :search AND NOT seller_status='block-ban' LIMIT 0,6",["search"=>"%$search%"]);
$data['count_sellers'] = $get_s->rowCount();
$i = 0;
while($row = $get_s->fetch()){
   $i++;
   $user_name = str_ireplace("$search",ucwords("<b>$search</b>"),$row->seller_user_name);
   $data['sellers'][$i]['name'] = $user_name;
   $data['sellers'][$i]['url'] = "$site_url/".$row->seller_user_name;
}

$data['no_results'] = $lang['no_results'];

echo json_encode($data);