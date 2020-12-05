<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{

  if(isset($_GET['approve_proposal'])){
    $proposal_id = $input->get('approve_proposal');
    $page = (isset($_GET['page']))?"=".$input->get('page'):"";
    $update_proposal = $db->update("proposals",array("proposal_status" => 'active'),array("proposal_id"=>$proposal_id));
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
        $featured_duration = $row_payment_settings->featured_duration;

        $end_date = date("F d, Y h:i:s", strtotime(" + $featured_duration days"));
        $insert_featured = $db->insert("featured_proposals",array("proposal_id"=>$proposal_id,"end_date"=>$end_date));
      }

      $get_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
      $row_seller = $get_seller->fetch();
      $seller_user_name = $row_seller->seller_user_name;
      $seller_email = $row_seller->seller_email;
      $seller_phone = $row_seller->seller_phone;

      $site_email_address = $row_general_settings->site_email_address;

      $data = [];
      $data['template'] = "approve_proporsal";
      $data['to'] = $seller_email;
      $data['subject'] = "$site_name: Your proposal/service has been successfully approved.";
      $data['user_name'] = $seller_user_name;
      $data['proposal_url'] = $proposal_url;
      send_mail($data);

      $last_update_date = date("F d, Y");
      $insert_notification = $db->insert("notifications",array("receiver_id" => $proposal_seller_id,"sender_id" => "admin_$admin_id","order_id" => $proposal_id,"reason" => "approved","date" => $last_update_date,"status" => "unread"));
      if($insert_notification){

        /// sendPushMessage Starts
        $notification_id = $db->lastInsertId();
        sendPushMessage($notification_id);
        /// sendPushMessage Ends

        if($notifierPlugin == 1){
          $smsText = $lang['notifier_plugin']['proposal_approved'];
          sendSmsTwilio("",$smsText,$seller_phone);
        }

        $insert_log = $db->insert_log($admin_id,"proposal",$proposal_id,"approved");
        echo "<script>
          swal({
            type: 'success',
            text: 'Proposal approved successfully!',
            timer: 3000,
            onOpen: function(){
              swal.showLoading()
            }
          }).then(function(){
            // Read more about handling dismissals
            window.open('index?view_proposals_active','_self')
          });
        </script>";
      }
    }
  }

}