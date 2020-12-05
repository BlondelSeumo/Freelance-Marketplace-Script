<?php

require_once("../includes/db.php");

if(isset($_FILES["file"]["name"])){

	$file = $_FILES["file"]["name"];
	$file_tmp = $_FILES["file"]["tmp_name"];

	$allowed = array('jpeg','jpg','gif','png','tif','avi','mpeg','mpg','mov','rm','3gp','flv','mp4', 'zip','rar','mp3','wav','docx','csv','xls','pptx','pdf','psd','xd','txt');
	$file_extension = pathinfo($file, PATHINFO_EXTENSION);
	
	if(!in_array($file_extension,$allowed)){
	
		$data = array();
		$data['message'] = $lang['alert']['extension_not_supported'];
	
		echo json_encode($data);

	}else{
	
		$file = pathinfo($file, PATHINFO_FILENAME);
		$file = $file."_".time().".$file_extension";
   
    	uploadToS3("proposal_files/$file",$file_tmp);
	
		$data = array();
		$data['name'] = $file;
		$data['message'] = "";

		if($enable_s3 == 1){
			$data['url'] = "$s3_domain/proposal_files/$file";
		}else{
			$data['url'] = "$site_url/proposals/proposal_files/$file";
		}
		
		echo json_encode($data);

	}

}