<?php

	require_once "g-config.php";

	if(isset($_SESSION['access_token'])){
		$gClient->setAccessToken($_SESSION['access_token']);
	}else if(isset($_GET['code'])){
		$token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
		$_SESSION['access_token'] = $token;
	}else{
	  echo "<script> window.open('login','_self'); </script>";
		exit();
	}

	$oAuth = new Google_Service_Oauth2($gClient);
	$userData = $oAuth->userinfo_v2_me->get();

	$_SESSION['id'] = $userData['id'];
	$_SESSION['email'] = $userData['email'];
	$_SESSION['gender'] = $userData['gender'];
	$_SESSION['picture'] = $userData['picture'];
	$_SESSION['familyName'] = $userData['familyName'];
	$_SESSION['givenName'] = $userData['givenName'];

	echo "<script> window.open('g-register.php','_self'); </script>";

	exit();