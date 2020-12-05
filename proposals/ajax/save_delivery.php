<?php

session_start();
require_once("../../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('../../login','_self')</script>";
}

if(isset($_POST["change_status"])){
	$status = $_POST['change_status'];
	$proposal_id = $_POST['proposal_id'];
	if($status == 'true' && isset( $_POST['proposal_id'] )){
		$update_status = $db->update("proposals", array('proposal_status' => 'pending') ,array("proposal_id"=>$proposal_id));
	}
}



function watermarkImage($image,$data){
	
	global $site_watermark;

	$fileType = pathinfo($image,PATHINFO_EXTENSION);
	if($fileType == "jpg" or $fileType == "jpeg" or $fileType == "png"){

		$to_image = imagecreatefromstring(file_get_contents($data));
		$stamp = imagecreatefromstring(file_get_contents("../../images/$site_watermark"));
		$spacing = 15;
		$spacing_double = $spacing  * 2;

		list($width,$height) = getimagesize($data);
		list($stamp_width,$stamp_height) = getimagesize("../../images/$site_watermark");

		$offsetX = ($width  - ($stamp_width + $spacing)) / 2;
		$offsetY = ($height - ($stamp_height + $spacing)) / 2;
		
		imagecopy($to_image, $stamp, $offsetX, $offsetY, 0, 0, $stamp_width, $stamp_height);

		ob_start();
		imagejpeg($to_image,null,100);
		$image_contents = ob_get_clean();
		imagedestroy($to_image);

		uploadToS3("$image","",$image_contents);

	}else{
		uploadToS3("$image",$data);
	}

}

if(isset($_POST["proposal_id"])){

	$proposal_id = $input->post("proposal_id");
	$data = $input->post();

	@$file = $_FILES["file"]["name"];
	@$file_tmp = $_FILES["file"]["tmp_name"];
	$allowed = array('jpeg','jpg','gif','png','tif','avi','mpeg','mpg','mov','rm','3gp','flv','mp4','zip','rar','mp3','wav','docx','csv','xls','pptx','pdf','txt');
		
	$file_extension = pathinfo($file, PATHINFO_EXTENSION);

	if(!in_array($file_extension,$allowed) & !empty($file)){
		
		$data["status"] = "error_file";
		
	}else{

		if(!empty($file)){
			$file = pathinfo($file, PATHINFO_FILENAME);

		   if(isset($_POST['enable_watermark'])){

				$watermark_file = $file."_".time()."_watermark.$file_extension";
				$file = $file."_".time().".$file_extension";

		   	watermarkImage("order_files/$watermark_file",$file_tmp);
		   	uploadToS3("order_files/$file",$file_tmp);

		   	$data['watermark'] = 1;
		   	$data['watermark_file'] = $watermark_file;

		   }else{
		   	$file = $file."_".time().".$file_extension";
		   	uploadToS3("order_files/$file",$file_tmp);
		   	$data['watermark'] = 0;
		   	$data['watermark_file'] = "";
		   }

			$data['file'] = $file;
			$data['isS3'] = $enable_s3;
		}else{
			$file = "";
		}

		if($input->post('enable')){
			$data['enable'] = 1;
		}else{
			$data['enable'] = 0;
		}

		unset($data['enable_watermark']);
		unset($data['change_status']);

		$update = $db->update("instant_deliveries",$data,["proposal_id"=>$proposal_id]);
		if($update){
			$data["status"] = "success";
		}

	}

	echo json_encode($data);

}