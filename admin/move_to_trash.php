<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	
?>


<?php 
    
    if(isset($_GET['move_to_trash'])){
        
        $trash_id = $input->get('move_to_trash');
        $page = (isset($_GET['page']))?"=".$input->get('page'):"";
        $update_proposal = $db->update("proposals",array("proposal_status"=>'trash'),array("proposal_id"=>$trash_id));

        if($update_proposal){

        $insert_log = $db->insert_log($admin_id,"proposal",$trash_id,"trashed");

            echo "<script>
      
                   swal({
                 
                  type: 'success',
                  text: 'Proposal has been moved to trash.',
                  timer: 3000,
                  onOpen: function(){
                  swal.showLoading()
                  }
                  }).then(function(){

                    // Read more about handling dismissals
                    window.open('index?view_proposals_trash','_self');

                });

            </script>";
        }
        
    }



?>


<?php } ?>