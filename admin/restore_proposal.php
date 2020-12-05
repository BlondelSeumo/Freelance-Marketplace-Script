<?php


@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	
?>


<?php 
    
    if(isset($_GET['restore_proposal'])){
        
        $restore_id = $input->get('restore_proposal');
        $page = (isset($_GET['page']))?"=".$input->get('page'):"";
        $update_proposal = $db->update("proposals",array("proposal_status"=>'active'),array("proposal_id"=>$restore_id));

        if($update_proposal){
            
        $insert_log = $db->insert_log($admin_id,"proposal",$restore_id,"restored");

            echo "<script>
      
                  swal({
                  type: 'success',
                  text: 'Listing Restored successfully!',
                  timer: 3000,
                  onOpen: function(){
                  swal.showLoading()
                  }
                  }).then(function(){
                  
                  // Read more about handling dismissals
                  window.open('index?view_proposals_trash','_self')

                })

            </script>";
        }
        
    }



?>


<?php } ?>