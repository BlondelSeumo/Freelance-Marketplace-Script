<?php
	
	session_start();
	require_once "fb-config.php";

	try {
		$accessToken = $helper->getAccessToken();
	} catch (\Facebook\Exceptions\FacebookResponseException $e) {
		echo "Response Exception: " . $e->getMessage();
		exit();
	} catch (\Facebook\Exceptions\FacebookSDKException $e) {
		echo "SDK Exception: " . $e->getMessage();
		exit();
	}
	if (!$accessToken) {
		echo "<script> window.open('login','_self'); </script>";
		exit();
	}

	$oAuth2Client = $FB->getOAuth2Client();
	if(!$accessToken->isLongLived())
		$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

	$response = $FB->get("/me?fields=id, first_name, last_name, email, picture.type(large)", $accessToken);
	$userData = $response->getGraphNode()->asArray();
	$_SESSION['userData'] = $userData;
	$_SESSION['access_token'] = (string) $accessToken;
	$email = $_SESSION['userData']['email'];

	echo "<script> window.open('fb-register','_self'); </script>";
	
	exit();