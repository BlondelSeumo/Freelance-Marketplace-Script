<?php

session_start();

if(!isset($_SESSION['seller_user_name'])){
	header("Location: ../login");
}

if(!isset($_GET["order_id"])){
	header("Location: ../index");
}

if(!isset($_GET["c_id"])){
	header("Location: ../index");
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

include("../includes/db.php");

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

$order_id = $input->get('order_id');
$c_id = $input->get('c_id');

$get_order = $db->query("select * from orders where order_id='$order_id' AND (buyer_id='$login_seller_id' OR seller_id='$login_seller_id')");
$count_order = $get_order->rowCount();

$sel_c = $db->select("order_conversations",array("c_id" => $c_id));
$count_c = $sel_c->rowCount();

if($count_order != 0 AND $count_c != 0){

	$row_order = $get_order->fetch();
	$order_status = $row_order->order_status;

	$row_c = $sel_c->fetch();
	$watermark = $row_c->watermark;
	$watermark_file = $row_c->watermark_file;
	$file = $row_c->file;
	$isS3 = $row_c->isS3;
	$status = $row_c->status;

	if($watermark == 1 AND $order_status != "completed"){ 
		$file_name = $watermark_file;
		$d_file = getImageUrl("order_conversations",$watermark_file,'watermark_file');
	}else{
		$file_name = $file;
		$d_file = getImageUrl("order_conversations",$file);
	}

	header("Content-Disposition: attachment; filename=$file_name");

	if($isS3 == 1){

		$downloadFile = "order_files/$file_name";

		$s3->doesObjectExist($config["bucket"],$downloadFile);
		$result = $s3->getObject([
		 'Bucket' => $config["bucket"],
		 'Key'    => $downloadFile
		]);
		echo $result['Body'];
		exit;

	}else{
		$localFile = "../order_files/$file_name";
		if(file_exists($localFile)){
			readfile($localFile);
			exit;
		}
	}

}else{

	header("Location: ../index");

}