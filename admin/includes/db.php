<?php

$dir = __DIR__;
$dir = str_replace("admin/includes", '',$dir);
$dir = str_replace("admin\includes", '',$dir);

include("$dir/includes/config.php");

if(empty(DB_HOST) and empty(DB_USER) and empty(DB_NAME)){
	echo "<script>window.open('../install.php','_self'); </script>";
	exit();
}else{

   include $dir.'libs/database.php';
   include $dir.'libs/input.php';
   include $dir.'libs/validator.php';
   include $dir.'libs/flash.php';

   $db->query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");

   $get_general_settings = $db->select("general_settings");   
   $row_general_settings = $get_general_settings->fetch();
   $site_email_address = $row_general_settings->site_email_address;
   $site_email_address = $row_general_settings->site_email_address;
   $site_url = $row_general_settings->site_url;
   $tinymce_api_key = $row_general_settings->tinymce_api_key;
   $site_name = $row_general_settings->site_name;
   $site_keywords = $row_general_settings->site_keywords;
   $site_author = $row_general_settings->site_author;
   $site_desc = $row_general_settings->site_desc;
   $site_logo_image = $row_general_settings->site_logo_image;
   $site_currency = $row_general_settings->site_currency;
   $currency_position = $row_general_settings->currency_position;
   $currency_format = $row_general_settings->currency_format;
   $site_timezone = $row_general_settings->site_timezone;

   $get_currencies = $db->select("currencies",array( "id" => $site_currency));
   $row_currencies = $get_currencies->fetch();
   $s_currency_name = $row_currencies->name;
   $s_currency = $row_currencies->symbol;

   $get_smtp_settings = $db->select("smtp_settings");
   $row_smtp_settings = $get_smtp_settings->fetch();
   $enable_smtp = $row_smtp_settings->enable_smtp;
   $s_host = $row_smtp_settings->host;
   $s_port = $row_smtp_settings->port;
   $s_secure = $row_smtp_settings->secure;
   $s_username = $row_smtp_settings->username;
   $s_password = $row_smtp_settings->password;

   $get_api_settings = $db->select("api_settings");
   $row_api_settings = $get_api_settings->fetch();
   $enable_s3 = $row_api_settings->enable_s3;

   include("$dir/includes/s3-config.php");

   if(!isset($_SESSION['adminLanguage'])){
      $_SESSION['adminLanguage'] = $db->select("languages",["default_lang" =>1])->fetch()->id;
   }

   $sel_language = $db->select("languages",array( "id" => $_SESSION['adminLanguage']))->fetch();
   $template_folder = $sel_language->template_folder; 
   require($dir."languages/".strtolower($sel_language->title).".php");


   $site_favicon = getImageUrl2("general_settings","site_favicon",$row_general_settings->site_favicon);
   $site_logo_image = getImageUrl2("general_settings","site_logo_image",$row_general_settings->site_logo_image);
   $site_logo = getImageUrl2("general_settings","site_logo",$row_general_settings->site_logo);

}

require_once("$dir/includes/commonFunctions.php");
