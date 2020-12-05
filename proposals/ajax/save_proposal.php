<?php

session_start();

require_once("../../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){

echo "<script>window.open('../login','_self')</script>";

}


if(isset($_POST["proposal_id"])){

	$status = $_POST['change_status'];
	$proposal_id = $_POST['proposal_id'];
	if($status == 'true'){
		$update_status = $db->update("proposals", array('proposal_status' => 'pending') ,array("proposal_id"=>$proposal_id));
	}
	

	function removeJava($html,$allowed_tags){
	  
		$attrs = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
		  
		$dom = new DOMDocument;
		@$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$nodes = $dom->getElementsByTagName('*');//just get all nodes, 

		foreach($nodes as $node){
			foreach ($attrs as $attr){
				if($node->hasAttribute($attr)){ 
					$node->removeAttribute($attr);  
				}
			}
		}

		return strip_tags($dom->saveHTML(),$allowed_tags);

	}


	if($videoPlugin == 1){
		require_once("$dir/plugins/videoPlugin/proposals/checkVideo2.php");
	}

	$error = "";
	$proposal_id = strip_tags($input->post('proposal_id'));
	$data = $input->post();
	unset($data['proposal_id']);
	unset($data['change_status']);	


	if(isset($_POST['proposal_title'])){

		include("../sanitize_url.php");

		$proposal_title = $input->post('proposal_title');
		$sanitize_url = proposalUrl($proposal_title);

		$select_proposal = $db->query("select * from proposals where proposal_seller_id='$login_seller_id' AND proposal_url='$sanitize_url' AND NOT proposal_id='$proposal_id'");
		$count_proposal = $select_proposal->rowCount();

		if($count_proposal ==  1){
			$error = "error_title";
		}else{
			$data['proposal_url'] = $sanitize_url;
		}

 	}

	if(isset($_POST['proposal_desc'])){

		$data['proposal_desc'] = removeJava($_POST['proposal_desc'],"<div><table><tbody><thead><tr><th><td><iframe><br><a><b><i><u><span><img><h1><h2><h3><h4><h5><h6><p><ul><ol><li>");

	}

	if(isset($_POST['proposal_video'])){

		if(!empty($_POST['proposal_video'])){
			$decode = htmlspecialchars_decode($_POST['proposal_video']);
			$data['proposal_video'] = removeJava($decode,"<iframe>");
		}

	}

	if(isset($_POST['proposal_desc']) and @empty($_POST['proposal_desc'])){

		$error = "error";

	}

	if(isset($_POST['proposal_img1']) and @empty($_POST['proposal_img1'])){

		unset($data['proposal_img1']);
		$error = "error_img";

	}

	if(empty($error)){

		$update_proposal = $db->update("proposals",$data,array("proposal_id"=>$proposal_id));

		if($videoPlugin == 1){

			if(isset($data["proposal_cat_id"]) and isset($data["proposal_child_id"])){
				$checkVideo = checkVideo($data["proposal_cat_id"],$data["proposal_child_id"]);
				if($checkVideo){
					$error = "video";
					$db->update("instant_deliveries",["enable"=>0],["proposal_id"=>$proposal_id]);
				}else{
					$error = "not-video";
					$db->update("proposal_videosettings",["enable"=>0],["proposal_id"=>$proposal_id]);
				}
			}
			
		}

	}

	echo $error;

}