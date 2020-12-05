<?php

session_start();

unset($_SESSION['admin_email']);

if(isset($_GET['session_expired'])){
	
echo "<script>window.open('login.php?session_expired','_self');</script>";
	
}else{

echo "<script>window.open('login','_self');</script>";

}

?>