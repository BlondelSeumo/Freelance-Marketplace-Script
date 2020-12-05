<?php
if($enable_social_login == "yes"){
	if(!empty($fb_app_id) and !empty($fb_app_secret)){
		require_once("fb-config.php");
		$redirectURL = "$site_url/fb-callback";
		$permissions = ['email'];
		$fLoginURL = $helper->getLoginUrl($redirectURL, $permissions);
	}

	if(!empty($g_client_id) and !empty($g_client_secret)){
		require_once("g-config.php");
		$gLoginURL = $gClient->createAuthUrl();
	}
}

?>