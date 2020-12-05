<?php
	@session_start();
	require_once "includes/db.php";
	require_once "GoogleAPI/vendor/autoload.php";
	$gClient = new Google_Client();
	$gClient->setClientId($g_client_id);
	$gClient->setClientSecret($g_client_secret);
	$gClient->setApplicationName("");
	$gClient->setRedirectUri("$site_url/g-callback");
	$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
