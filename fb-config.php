<?php
@session_start();

require_once "includes/db.php";
require_once "Facebook/autoload.php";

$FB = new \Facebook\Facebook([
   'app_id' => $fb_app_id,
   'app_secret' => $fb_app_secret,
   'default_graph_version' => 'v2.10',
]);

$helper = $FB->getRedirectLoginHelper();