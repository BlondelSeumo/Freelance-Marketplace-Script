<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
   
echo "<script>window.open('login','_self');</script>";
   
}else{

	if(isset($_GET['delete_currency'])){
	   
	   $delete_id = $input->get('delete_currency');
	   $delete = $db->delete("site_currencies",array('id' => $delete_id));
	         
	   if($delete){

	  		$insert_log = $db->insert_log($admin_id,"site_currency",$delete_id,"deleted");
	      echo "<script>alert('One Site Currency Has been deleted successfully.');</script>";
	      echo "<script>window.open('index?view_currencies','_self');</script>";

	   }

	}

} 

?>