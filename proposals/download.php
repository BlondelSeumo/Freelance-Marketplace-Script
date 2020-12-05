<?php

@session_start();

if(!isset($_SESSION['seller_user_name'])){
   header("Location: ../login");
}

if(!isset($_GET["proposal_id"])){
   header("Location: ../index");
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

require_once("../includes/db.php");

$proposal_id = $input->get('proposal_id');

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

$get_p = $db->query("select * from proposals where proposal_id='$proposal_id' AND proposal_seller_id='$login_seller_id'");
$count_proposal = $get_p->rowCount();

$get_delivery = $db->select("instant_deliveries",['proposal_id'=>$proposal_id]);
$row_delivery = $get_delivery->fetch();
$file = $row_delivery->file;
$isS3 = $row_delivery->isS3;

if($count_proposal != 0 AND !empty($file)){

   header("Content-Disposition: attachment; filename=$file");

   if($isS3 == 1){

      $downloadFile = "order_files/$file";

      $s3->doesObjectExist($config["bucket"],$downloadFile);
      $result = $s3->getObject([
       'Bucket' => $config["bucket"],
       'Key'    => $downloadFile
      ]);
      echo $result['Body'];
      exit;

   }else{
      $localFile = "../order_files/$file";
      if(file_exists($localFile)){
         readfile($localFile);
         exit;
      }
   }

}else{
   header("Location: ../index");
}