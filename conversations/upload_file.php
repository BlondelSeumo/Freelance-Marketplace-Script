<?php

require_once("../includes/db.php");

if(isset($_FILES["file"]["name"])){
	$file = $_FILES["file"]["name"];
	$file_tmp = $_FILES["file"]["tmp_name"];

    $allowed = array('jpeg','jpg','gif','png','tif','avi','mpeg','mpg','mov','rm','3gp','flv','mp4','zip','rar','mp3','wav','docx','csv','xls','xlsx','pptx','pdf','txt','psd','xd','txt');

	$file_extension = pathinfo($file, PATHINFO_EXTENSION);
	if(!in_array($file_extension,$allowed)){
		echo $lang['alert']['extension_not_supported'];
	}else{
		$file = pathinfo($file, PATHINFO_FILENAME);
		$file = $file."_".time().".$file_extension";
      uploadToS3("conversations_files/$file",$file_tmp);
		echo $file;	
	}
}