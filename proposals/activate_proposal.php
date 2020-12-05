<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	
echo "<script>window.open('../login','_self')</script>";
	
}

if(isset($_GET['proposal_id'])){
	
   $proposal_id = $input->get('proposal_id');

   $select_proposal = $db->select("proposals",array("proposal_id"=>$proposal_id,"proposal_seller_id"=>$login_seller_id));

   if($select_proposal->rowCount() == 1){

      $status = $select_proposal->fetch()->proposal_status;

      if($status == "pause"){

         $update_proposal = $db->update("proposals",array("proposal_status"=>'active'),array("proposal_id"=>$proposal_id,"proposal_seller_id"=>$login_seller_id));
         echo "<script>alert('Your proposal has been successfully activated!');</script>";

      }elseif($status == "admin_pause") {

         $update_proposal = $db->update("proposals",array("proposal_status"=>'pending'),array("proposal_id"=>$proposal_id,"proposal_seller_id"=>$login_seller_id));
         echo "<script>alert('Proposal/Service submitted successfully for approval.');</script>";

      }  

      echo "<script>window.open('view_proposals','_self');</script>";

   }else{
      echo "<script>window.open('view_proposals','_self');</script>"; 
   }

}

?>