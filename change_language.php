<?php

session_start();
require_once("includes/db.php");

if(isset($_GET['id'])){
  
	if($_SERVER["HTTP_REFERER"]){
		$id = $input->get('id');
		$_SESSION['siteLanguage'] = $id;
		echo "<script>window.open('{$_SERVER["HTTP_REFERER"]}','_self');</script>";
	}

}