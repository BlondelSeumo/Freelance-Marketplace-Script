<?php

require_once("../includes/db.php");

if(isset($_POST["image"])){
	
	$data = $input->post("image");
	$name = $input->post("name");

	$image_array_1 = explode(";", $data);
	$image_array_2 = explode(",", $image_array_1[1]);

	$data = base64_decode($image_array_2[1]);
	$imageName = pathinfo($name, PATHINFO_FILENAME) . "_" . time() . '.png';
	$allowed = array('jpeg','jpg','gif','tiff','png','webp');
	$file_extension = pathinfo($name, PATHINFO_EXTENSION);

	if(!in_array($file_extension,$allowed)){
   	echo "";
	}else{

   	if(uploadToS3("proposal_files/$imageName","",$data)){ 
			
			$data = array();
			$data['name'] = $imageName;

   		if($enable_s3 == 1){
   			$data['url'] = "$s3_domain/proposal_files/$imageName";
   		}else{
   			$data['url'] = "$site_url/proposals/proposal_files/$imageName";
   		}
   		
   		echo json_encode($data);

   	}

	}

}