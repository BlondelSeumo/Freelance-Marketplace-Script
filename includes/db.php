<?php

@session_start();
require_once("config.php");
if(isset($_SESSION['sessionStart'])){
	$_SESSION['seller_user_name'] = $_SESSION['sessionStart'];
	unset($_SESSION['sessionStart']);
}

if(empty(DB_HOST) and empty(DB_USER) and empty(DB_NAME)){
	echo "<script>window.open('install','_self'); </script>";
	exit();
}else{
	
	define('ROOTPATH',str_replace(array("includes"),'',__DIR__));
	$dir = str_replace(array("includes"),'',__DIR__);

	require_once "$dir/libs/database.php";
	require_once "$dir/libs/input.php";
	require_once "$dir/libs/validator.php";
	require_once "$dir/libs/flash.php";
	require_once "$dir/functions/Core.php";
	require_once "commonFunctions.php";
	require_once "s3-config.php";

	$core = new Core;
	$paymentGateway = $core->checkPlugin("paymentGateway","site");
	$videoPlugin = $core->checkPlugin("videoPlugin","site");
	$notifierPlugin = $core->checkPlugin("notifierPlugin","site");

	$db->query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");

	if(!isset($_SESSION['siteLanguage'])){
		$_SESSION['siteLanguage'] = $db->select("languages",["default_lang" =>1])->fetch()->id;
	}

	$siteLanguage = $_SESSION['siteLanguage'];
	
	$get_general_settings = $db->select("general_settings");   
	$row_general_settings = $get_general_settings->fetch();
	$site_url = $row_general_settings->site_url;
	$site_email_address = $row_general_settings->site_email_address;
	$site_name = $row_general_settings->site_name;
	$site_desc = $row_general_settings->site_desc;
	$site_keywords = $row_general_settings->site_keywords;
	$site_author = $row_general_settings->site_author;
	$enable_mobile_logo = $row_general_settings->enable_mobile_logo;
	
	$site_favicon = getImageUrl2("general_settings","site_favicon",$row_general_settings->site_favicon);
	$site_logo_type = $row_general_settings->site_logo_type;
	$site_logo_text = $row_general_settings->site_logo_text;
	$site_logo_image = getImageUrl2("general_settings","site_logo_image",$row_general_settings->site_logo_image);
	$site_logo = getImageUrl2("general_settings","site_logo",$row_general_settings->site_logo);
	$site_mobile_logo = getImageUrl2("general_settings","site_mobile_logo",$row_general_settings->site_mobile_logo);
	$site_timezone = $row_general_settings->site_timezone;
	$tinymce_api_key = $row_general_settings->tinymce_api_key;
	$enable_social_login = $row_general_settings->enable_social_login;
	$fb_app_id = $row_general_settings->fb_app_id;
	$fb_app_secret = $row_general_settings->fb_app_secret;
	$g_client_id = $row_general_settings->g_client_id;
	$g_client_secret = $row_general_settings->g_client_secret;
	$site_currency = $row_general_settings->site_currency;
	$currency_position = $row_general_settings->currency_position;
	$currency_format = $row_general_settings->currency_format;
	$make_phone_number_required = $row_general_settings->make_phone_number_required;
	$enable_maintenance_mode = $row_general_settings->enable_maintenance_mode;
	$enable_referrals = $row_general_settings->enable_referrals;
	$language_switcher = $row_general_settings->language_switcher;
	$google_analytics = $row_general_settings->google_analytics;
	$site_watermark = $row_general_settings->site_watermark;
	$jwplayer_code = $row_general_settings->jwplayer_code;
	$edited_proposals = $row_general_settings->edited_proposals;
	$enable_websocket = $row_general_settings->enable_websocket;
	$websocket_address = $row_general_settings->websocket_address;

	$get_currencies = $db->select("currencies",array("id" => $site_currency));
	$row_currencies = $get_currencies->fetch();
	$s_currency_name = $row_currencies->name;
	$s_currency = $row_currencies->symbol;

	$get_smtp_settings = $db->select("smtp_settings");
	$row_smtp_settings = $get_smtp_settings->fetch();
	$mail_library = $row_smtp_settings->library;
	$enable_smtp = $row_smtp_settings->enable_smtp;
	$s_host = $row_smtp_settings->host;
	$s_port = $row_smtp_settings->port;
	$s_secure = $row_smtp_settings->secure;
	$s_username = $row_smtp_settings->username;
	$s_password = $row_smtp_settings->password;

	$get_api_settings = $db->select("api_settings");
	$row_api_settings = $get_api_settings->fetch();
	$enable_s3 = $row_api_settings->enable_s3;

	$get_bar = $db->select("announcement_bar",['language_id'=>$siteLanguage]);
	$row_bar = $get_bar->fetch();
	$enable_bar = $row_bar->enable_bar;
	$bg_color = $row_bar->bg_color;
	$text_color = $row_bar->text_color;
	$bar_text = $row_bar->bar_text;
	$bar_last_updated = $row_bar->last_updated;

	$get_api = $db->select("currency_converter_settings");
	$row_api = $get_api->fetch();
	$enable_converter = $row_api->enable;

	$get_payment_settings = $db->select("payment_settings");
	$row_payment_settings = $get_payment_settings->fetch();
	$paypal_client_id = $row_payment_settings->paypal_app_client_id;
	$paypal_currency_code = $row_payment_settings->paypal_currency_code;

	date_default_timezone_set($site_timezone);

	$row_language = $db->select("languages",array("id"=>$siteLanguage))->fetch();
	$lang_dir = $row_language->direction;
	$template_folder = $row_language->template_folder;
	require($dir."languages/".strtolower($row_language->title).".php");

	require_once "$dir/screens/detect.php";
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	if($deviceType == "phone"){
		$proposals_stylesheet = '<link href="styles/mobile_proposals.css" rel="stylesheet">'; 
	}else{
		$proposals_stylesheet = '<link href="styles/desktop_proposals.css" rel="stylesheet">'; 
	}

	if(isset($_SESSION['seller_user_name'])){
		$login_seller_user_name = $_SESSION['seller_user_name'];
		$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
		$count_seller_login = $select_login_seller->rowCount();
		if($count_seller_login == 0){
			echo "<script>window.open('$site_url/logout','_self');</script>";
		}else{
			$row_login_seller = $select_login_seller->fetch();
			$login_seller_id = $row_login_seller->seller_id;
		}
	}

	if(!isset($_SESSION['admin_email'])){
		if($enable_maintenance_mode == "yes"){ 
			echo "<script>window.open('$site_url/maintenance','_self');</script>";
		}
	}

	if($lang_dir == "right"){
		$floatRight = "float-right";
		$textRight = "text-right";
	}else{
		$floatRight = "float-left";
		$textRight = "text-left";
	}

}