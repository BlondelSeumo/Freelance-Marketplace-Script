<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	

?>

<?php

if(isset($_GET['seller_login'])){
	
   $seller_user_name = $input->get('seller_login');
   $_SESSION['seller_user_name']=$seller_user_name;

   echo "<script>alert('You are about to log into $seller_user_name account.');</script>";
   echo "<script>window.open('../index.php','_self');</script>";
	
}

?>

<?php } ?>