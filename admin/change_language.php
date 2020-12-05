<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

if(isset($_GET['change_language'])){
  
if($_SERVER["HTTP_REFERER"]){

$id = $input->get('change_language');

$_SESSION['adminLanguage'] = $id;

echo "<script>window.open('{$_SERVER["HTTP_REFERER"]}','_self');</script>";

}

}

} 

?>