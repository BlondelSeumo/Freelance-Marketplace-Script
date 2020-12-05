<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

$proposal_id = $input->get('submit_modification');
$page = (isset($_GET['page']))?"=".$input->get('page'):"";
$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
$row_proposals = $select_proposals->fetch();
$proposal_title = $row_proposals->proposal_title;
$proposal_seller_id = $row_proposals->proposal_seller_id;

$get_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
$row_seller = $get_seller->fetch();
$seller_user_name = $row_seller->seller_user_name;
$seller_email = $row_seller->seller_email;
$seller_phone = $row_seller->seller_phone;

$last_update_date = date("F d, Y");

?>


<div class="breadcrumbs">

<div class="col-sm-4">
<div class="page-header float-left">
<div class="page-title">
<h1><i class="menu-icon fa fa-table"></i> Proposal / Modification Request</h1>
</div>
</div>
</div>

<div class="col-sm-8">
<div class="page-header float-right">
<div class="page-title">
<ol class="breadcrumb text-right">
<li class="active">Submit Proposal/Service For Modification</li>
</ol>
</div>
</div>
</div>

</div>

<div class="container">
    
    <div class="row pt-2">
        
        <div class="col-lg-12">
            
            <div class="card">
                
                <div class="card-header">
                    
                <h4>Insert Reason For Modification Request</h4>
                    
                </div>
                
                <div class="card-body">
                    
                    <form action="" method="post">
                        
                        <div class="form-group row">
                            
                            <label class="col-md-3 control-label">Proposal Title</label>
                            
                            <div class="col-md-6">
                                
                                <p class="mt-2"><?= $proposal_title; ?></p>
                            
                            </div>
                        
                        </div>
                        
                        
                         <div class="form-group row">
                            
                            <label class="col-md-3 control-label">Describe Modification</label>
                            
                            <div class="col-md-6">
                                
                            <textarea name="proposal_modification" class="form-control"></textarea>
                            
                            </div>

                        </div>
                        
                        
                         <div class="form-group row">
                            
                            <label class="col-md-3 control-label"></label>
                            
                            <div class="col-md-6">
                                
                            <input type="submit" name="submit" class="btn btn-success form-control" value="Send Modification Request">
                            
                            </div>
                        
                        </div>
                    
            
                    </form>
                

                </div>
        
            </div>
        
        </div>
    
    </div>

</div>

<?php
    
  if(isset($_POST['submit'])){
      
    $proposal_modification = $input->post('proposal_modification');
    
    $insert_modification = $db->insert("proposal_modifications",array("proposal_id"=>$proposal_id,"modification_message" => $proposal_modification));

    $update_proposal = $db->update("proposals",array("proposal_status"=>'modification'),array("proposal_id"=>$proposal_id));

    if($update_proposal){

    $data = [];
    $data['template'] = "proposal_modification";
    $data['to'] = $seller_email;
    $data['subject'] = "$site_name: Admin Has Sent Modification To Your Proposal.";
    $data['user_name'] = $seller_user_name;
    send_mail($data);

    if($notifierPlugin == 1){
        $smsText = $lang['notifier_plugin']['proposal_modification'];
        sendSmsTwilio("",$smsText,$seller_phone);
    }

    $insert_notification = $db->insert("notifications",array("receiver_id" => $proposal_seller_id,"sender_id" => "admin_$admin_id","order_id" => $proposal_id,"reason" => "modification","date" => $last_update_date,"status" => "unread"));

    echo "<script>

        swal({
            type: 'success',
            text: 'Modification request sent!',
            timer: 3000,
            onOpen: function(){
                swal.showLoading()
            }
        }).then(function(){

            // Read more about handling dismissals
            window.open('index?view_proposals$page','_self');
      
        });

    </script>";

    }
   
    }  

?>

<?php } ?>