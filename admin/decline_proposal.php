<?php

@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{

function declineEmail(){
  global $db;
  global $site_name;
  global $proposal_seller_id;

  $select_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
  $row_seller = $select_seller->fetch();
  $seller_user_name = $row_seller->seller_user_name;
  $seller_email = $row_seller->seller_email;
  $seller_phone = $row_seller->seller_phone;

  $data = [];
  $data['template'] = "decline_proposal";
  $data['to'] = $seller_email;
  $data['subject'] = "$site_name: Your proposal/service has been declined.";
  $data['user_name'] = $seller_user_name;
  send_mail($data);

  if($notifierPlugin == 1){
    $smsText = $lang['notifier_plugin']['proposal_declined'];
    sendSmsTwilio("",$smsText,$seller_phone);
  }

}

if(isset($_GET['decline_proposal'])){
  $proposal_id = $input->get('decline_proposal');
  $page = (isset($_GET['page']))?"=".$input->get('page'):"";
  $update_proposal = $db->update("proposals",["proposal_status"=>'declined'],["proposal_id"=>$proposal_id]);
  if($update_proposal){
    $select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
    $row_proposals = $select_proposals->fetch();
    $proposal_title = $row_proposals->proposal_title;
    $proposal_seller_id = $row_proposals->proposal_seller_id;
    $proposal_url = $row_proposals->proposal_url;
    $proposal_featured = $row_proposals->proposal_featured;

    if($proposal_featured == "yes"){
      $get_payment_settings = $db->select("payment_settings");
      $row_payment_settings = $get_payment_settings->fetch();
      $featured_fee = $row_payment_settings->featured_fee;
      $update_balance = $db->query("update seller_accounts set used_purchases=used_purchases-:amount,current_balance=current_balance+:amount where seller_id='$proposal_seller_id'",array("amount"=>$featured_fee));
      $purchase_date = date("F d, Y");
      $update_proposal = $db->update("proposals",array("proposal_featured"=>'no'),array("proposal_id"=>$proposal_id));
      $insert_purchase = $db->insert("purchases",array("seller_id"=>$proposal_seller_id,"order_id"=>$proposal_id,"amount"=>$featured_fee,"date"=>$purchase_date,"method"=>"featured_proposal_declined"));
    }

    declineEmail();

    $last_update_date = date("F d, Y");
    $insert_notification = $db->insert("notifications",["receiver_id"=>$proposal_seller_id,"sender_id"=>"admin_$admin_id","order_id"=>$proposal_id,"reason"=>"declined","date"=>$last_update_date,"status"=>"unread"]);
    if($insert_notification){
      $insert_log = $db->insert_log($admin_id,"proposal",$proposal_id,"declined");
      echo "
      <script>
        swal({
        type: 'success',
        text: 'Proposal declined successfully!',
        timer: 3000,
        onOpen: function(){
          swal.showLoading()
        }
        }).then(function(){
          window.open('index?view_proposals_active','_self')
      })
      </script>";
    }
  }
}

?>
<?php } ?>