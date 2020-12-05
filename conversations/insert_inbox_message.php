<?php
@session_start();

require_once("../includes/db.php");
require_once("../functions/mailer.php");

if($notifierPlugin == 1){ 
	require_once("$dir/plugins/notifierPlugin/functions.php");
}

if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('../login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

function removeJava($html){
   $attrs = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $dom = new DOMDocument;
   // @$dom->loadHTML($html);
   @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
   $nodes = $dom->getElementsByTagName('*');//just get all nodes, 
   foreach($nodes as $node){
      foreach($attrs as $attr){ 
         if($node->hasAttribute($attr)){ 
            $node->removeAttribute($attr);
         }
      }
   }
   return strip_tags($dom->saveHTML(),"<div><img>");
}

$message_group_id = $input->post('single_message_id');
$get_inbox_sellers = $db->select("inbox_sellers",array("message_group_id" => $message_group_id));
$row_inbox_sellers = $get_inbox_sellers->fetch();
$sender_id = $row_inbox_sellers->sender_id;
$receiver_id = $row_inbox_sellers->receiver_id;

if($login_seller_id == $sender_id){
   $receiver_seller_id = $receiver_id;
}else{
   $receiver_seller_id = $sender_id;
}

$message = removeJava($_POST['message']);
$file = $input->post('file');
$message_date = date("h:i: F d, Y");
$dateAgo = date("Y-m-d H:i:s");
$message_status = "unread";
$time = time();

$insert_message = $db->insert("inbox_messages",array("message_sender" => $login_seller_id,"message_receiver" => $receiver_seller_id,"message_group_id" => $message_group_id,"message_desc" => $message,"message_file" => $file,"isS3"=>$enable_s3,"message_date" => $message_date,"dateAgo" => $dateAgo,"bell" => 'active',"message_status" => $message_status));
$last_message_id = $db->lastInsertId();

// Added by Pixinal Studio for inbox push notification
$mesg_push_url = "$site_url/api/v1/inbox-notification/".$last_message_id;
$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_URL => $mesg_push_url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET"
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
// End

$update_inbox_sellers = $db->update("inbox_sellers",array("sender_id" => $login_seller_id,"receiver_id" => $receiver_seller_id,"message_status" => $message_status,"time"=>$time,"message_id" => $last_message_id,'popup'=>'1'),array("message_group_id" => $message_group_id));

if($update_inbox_sellers){

	// New Spam Words Code Starts
	$n_date = date("F d, Y");
	$get_words = $db->select("spam_words");
	while($row_words = $get_words->fetch()){
		$name = $row_words->word;
		if(preg_match("/\b($name)\b/i", $message)){
			if($db->insert("admin_notifications",array("seller_id" => $login_seller_id,"content_id" => $message_group_id,"reason" => "message_spam","date" => $n_date,"status" => "unread"))){
				break;
			}
		}
	}
	// New Spam Words Code Ends

	$select_hide_seller_messages = $db->query("select * from hide_seller_messages where hider_id='$login_seller_id' AND hide_seller_id='$receiver_seller_id' or hider_id='$receiver_seller_id' AND hide_seller_id='$login_seller_id'");
	$count_hide_seller_messages = $select_hide_seller_messages->rowCount();
	if($count_hide_seller_messages == 1){
      $delete_hide_seller_messages = $db->query("delete from hide_seller_messages where hider_id='$login_seller_id' and hide_seller_id='$receiver_seller_id' or hider_id='$receiver_seller_id' AND hide_seller_id='$login_seller_id'");
	}

	$get_seller = $db->select("sellers",array("seller_id" => $receiver_seller_id));
	$row_seller = $get_seller->fetch();
	$seller_user_name = $row_seller->seller_user_name;
	$seller_email = $row_seller->seller_email;
	$seller_phone = $row_seller->seller_phone;

	// $data = [];
	// $data['template'] = "new_message";
	// $data['to'] = $seller_email;
	// $data['subject'] = "You've received a message from $login_seller_user_name";
	// $data['user_name'] = $seller_user_name;
	// $data['sender_user_name'] = $login_seller_user_name;
	// $data['message'] = $message;
	// $data['attachment'] = $file;
	// $data['message_date'] = $message_date;
	// $data['message_group_id'] = $message_group_id;
	// send_mail($data);

	if($notifierPlugin == 1){ 
	
		$smsText = str_replace('{seller_user_name}',$login_seller_user_name,$lang['notifier_plugin']['new_message']);
		// sendSmsTwilio("",$smsText,$seller_phone);

	}

	if(!empty($file)){
		$message_file = getImageUrl("inbox_messages",$file);
	}else{
		$message_file = "";
	}

	echo json_encode(array('message_date' => $message_date, 'message_file' => $message_file));

}