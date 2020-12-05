<?php

$get_api_settings = $db->select("api_settings");
$row_api_settings = $get_api_settings->fetch();
$enable_s3 = $row_api_settings->enable_s3;
$s3_access_key = $row_api_settings->s3_access_key;
$s3_access_sceret = $row_api_settings->s3_access_sceret;
$s3_bucket = $row_api_settings->s3_bucket;
$s3_region = $row_api_settings->s3_region;
$s3_domain = $row_api_settings->s3_domain;

if(empty($s3_domain)){
	$s3_domain = "https://s3.$s3_region.amazonaws.com/$s3_bucket";
}

// Require the Composer autoloader.
require $dir.'/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

function randomName($file,$realExt="") {
	$ext = pathinfo($file, PATHINFO_EXTENSION);
	if (!empty($realExt)) {
		return mt_rand() . ".$realExt";
	} else {
		return mt_rand() . ".$ext";
	}
}

$config = array(
	"key" => $s3_access_key,
	"secret" => $s3_access_sceret,
	"bucket" => $s3_bucket,
	"region" => $s3_region
);

$allowedExtensions = array("jpg", "jpeg", "png", "gif", "webp", "eps", "svg", "pdf", "txt", "docx", "pptx", "zip", "rar", "tar");

$allowedImageExtensions = array("jpg", "jpeg", "png", "gif", "webp", "svg");

function getImageColumn($table){
	
	if($table == "sellers"){
		return "seller_image";
	}elseif($table == "admins"){
		return "admin_image";
	}elseif($table == "categories"){
		return "cat_image";
	}elseif($table == "post_categories"){
		return "cat_image";
	}elseif($table == "order_conversations"){
		return "file";
	}elseif($table == "instant_deliveries"){
		return "file";
	}elseif($table == "inbox_messages"){
		return "message_file";
	}elseif($table == "buyer_requests"){
		return "request_file";
	}elseif($table == "languages"){
		return "image";
	}elseif($table == "section_boxes"){
		return "box_image";
	}elseif($table == "home_cards"){
		return "card_image";
	}elseif($table == "slider"){
		return "slide_image";
	}elseif($table == "home_section_slider"){
		return "slide_image";
	}elseif($table == "posts"){
		return "image";
	}elseif($table == "support_tickets"){
		return "attachment";
	}elseif($table == "support_conversations"){
		return "attachment";
	}

}

function getFolderName($table){
	
	if($table == "admins"){
		return "admin/admin_images";
	}elseif($table == "general_settings"){
		return "images";
	}elseif($table == "sellers"){
		return "user_images";
	}elseif($table == "categories"){
		return "cat_images";
	}elseif($table == "post_categories"){
		return "blog_cat_images";
	}elseif($table == "proposals"){
		return "proposal_files";
	}elseif($table == "order_conversations"){
		return "order_files";
	}elseif($table == "instant_deliveries"){
		return "order_files";
	}elseif($table == "inbox_messages"){
		return "conversations_files";
	}elseif($table == "buyer_requests"){
		return "request_files";
	}elseif($table == "languages"){
		return "images";
	}elseif($table == "section_boxes"){
		return "box_images";
	}elseif($table == "home_cards"){
		return "card_images";
	}elseif($table == "home_section_slider"){
		return "home_slider_images";
	}elseif($table == "slider"){
		return "slides_images";
	}elseif($table == "support_tickets"){
		return "ticket_files";
	}elseif($table == "support_conversations"){
		return "ticket_files";
	}elseif($table == "knowledge_bank"){
		return "article_images";
	}elseif($table == "posts"){
		return "post_images";
	}

}

function getMainFolderName($folder,$table){

	if($folder == "proposal_files"){ 
      $main_folder = "proposals"; 
   }elseif($folder == "request_files"){ 
      $main_folder = "requests"; 
   }elseif($folder == "conversations_files"){ 
      $main_folder = "conversations"; 
   }elseif($folder == "images" AND $table == "languages"){ 
      $main_folder = "languages";
   }elseif($folder == "article_images"){ 
      $main_folder = "article";
   }elseif($folder == "admin_images"){ 
      $main_folder = "admin";
   }else{
      $main_folder = "";
   }

   return $main_folder;

}

function getImageUrl($table,$key,$column=''){

	global $db;
	global $s3_bucket;
	global $s3_domain;
	global $site_url;

	if(empty($column)){
		$column = getImageColumn($table);
	}
	$folder = getFolderName($table);
	
	$select_table = $db->select("$table",["$column"=>$key]);
	$row_table = $select_table->fetch();
	$isS3 = $row_table->isS3;

	if($isS3 == 1){

		if($table == "languages"){
			$folder = "languages_images";
		}
		if($table == "admins"){
			$folder = "admin_images";
		}
		return "$s3_domain/$folder/$key";

	}else{

		if(empty($key)){ $key = "empty-image.png"; }
		$main_folder = getMainFolderName($folder,$table);
		$key = rawurlencode($key);
		return "$site_url/"."$main_folder/$folder/$key";

	}

}

function getImageUrl2($table,$field,$key){

	global $db;
	global $s3_bucket;
	global $s3_domain;
	global $site_url;

	$field2 = $field.'_s3';
	$folder = getFolderName($table);

	if($field == "seller_cover_image"){
		$folder = "cover_images";
	}

	$select_table = $db->select("$table",["$field"=>$key]);
	$row_table = $select_table->fetch();
	$isS3 = $row_table->$field2;

	if($isS3 == 1){
		return "$s3_domain/$folder/$key";
	}else{

		if(empty($key)){ $key = "empty-image.png"; }
		
		$main_folder = getMainFolderName($folder,$table);

		$key = rawurlencode($key);

		return "$site_url/$main_folder/$folder/$key"; 

	}

}

// Connect to AWS
try {

$s3 = S3Client::factory(
	array(
		'credentials' => array(
			'key' => $config["key"],
			'secret' => $config["secret"]
		),
		'version' => 'latest',
		'region'  => $config['region']
	)
);

// $buckets = $s3->listBuckets();

} catch(Exception $error){
	return false;
}

function uploadToS3($KeyFile,$fileTmp,$fileContent="",$private=''){
	global $config;
	global $dir;
	global $s3;
	global $enable_s3;
	global $s3_access_key;
	global $s3_access_sceret;
	global $s3_bucket;

	if($enable_s3 == 1 and (!empty($s3_access_key) and !empty($s3_access_sceret) and !empty($s3_bucket))) {

      if(strpos($KeyFile,'order_files') !== false){
			$ACL = "private";
		}else{
			$ACL = "public-read";
		}

	   $object = array(
	    'Bucket' => $config["bucket"],
	    'Key' =>  $KeyFile,
	    'StorageClass' => 'STANDARD',
	    'ACL' => $ACL
		);

		if (empty($fileContent)) {
		 $object["SourceFile"] = $fileTmp;
		} else {
		 $object["Body"] = $fileContent;
		}

		// echo $enable_s3;
		try{
		  $s3->putObject($object);
		  return true;
		}catch(S3Exception $error) {
		  return false;
		}catch(Exception $error) {
		  return false;
		}

	}else{
		if(strpos($KeyFile, 'proposal_files') !== false){
			$folder = "proposals";
		}else{
			$folder = "admin_area";
		}

		$KeyFile = str_replace("languages_images","languages/images", $KeyFile);
		$sub_folder = explode("/",$KeyFile,2);
		$folder = getMainFolderName($sub_folder[0],"");

		if(empty($fileContent)){
			move_uploaded_file($fileTmp,"$dir/$folder/$KeyFile");
		} else {
			file_put_contents("$dir/$folder/$KeyFile", $fileContent);
		}
		return true;

	}

}

function deleteFromS3($KeyFile){
	global $config;
	global $s3;

	if ($s3->doesObjectExist($config["bucket"], $KeyFile)){
	 // Delete file from the s3 bucket.
	 $s3->deleteObject([
	   'Bucket' => $config["bucket"],
	   'Key' => $KeyFile
	 ]);
	 return true;
	}else{
	 unlink(ABS_PATH."/admin_area/$KeyFile");
	 return true;
	}
}

function getObjectUrl($key){
	global $config;
	global $s3;
	global $site_url;
	global $s3_domain;
	
	if($s3->doesObjectExist($config["bucket"], $key)){
		if(empty($s3_domain)){
			return $s3->getObjectUrl($config["bucket"], $key);
		} else {
			return "$s3_domain/$key";
		}
	}else{
		if(strpos($key, 'customer_images') !== false){
			$folder = "customer";
		}else{
			$folder = "admin_area";
		}
		return "$site_url/$folder/$key";
	}
}

?>