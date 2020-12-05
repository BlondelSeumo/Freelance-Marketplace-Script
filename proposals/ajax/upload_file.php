<?php

session_start();
require_once("../../includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('../login','_self')</script>";
}

if(isset($_FILES["file"]["name"])){
	
	$file = $_FILES["file"]["name"];
	$file_tmp = $_FILES["file"]["tmp_name"];
	
	$allowed = array('jpeg','jpg','gif','png','tif','avi','mpeg','mpg','mov','rm','3gp','flv','mp4', 'zip','rar','mp3','wav');
	$file_extension = pathinfo($file, PATHINFO_EXTENSION);

	if(!in_array($file_extension,$allowed)){
		echo "error";
	}else{
		$file_extension = pathinfo($file, PATHINFO_EXTENSION);
		$file = pathinfo($file, PATHINFO_FILENAME);
		$file = $file."_".time().".$file_extension";
		uploadToS3("proposal_files/$file",$file_tmp);
		echo $file;
	}

}