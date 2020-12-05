<?php


@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

  if(isset($_GET['pause_proposal'])){
      
      $pause_id = $input->get('pause_proposal');

      $update_proposal = $db->update("proposals",array("proposal_status"=>'admin_pause'),array("proposal_id"=>$pause_id));
      
      if($update_proposal){

        $insert_log = $db->insert_log($admin_id,"proposal",$pause_id,"paused");

        echo "<script>
    
              swal({                  
              
              type: 'success',
              text: 'Proposal Paused Successfully!',
              timer: 3000,
              onOpen: function(){
              swal.showLoading()
              }
              }).then(function(){
              
              if(window.open('index?view_proposals_paused','_self')){
                console.log('Proposal paused successfully');
              }
            
          });

        </script>";
      }
      
  }

} 

?>