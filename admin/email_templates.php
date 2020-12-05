<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
   
echo "<script>window.open('login','_self');</script>";
   
}else{

   $get_smtp_settings = $db->select("smtp_settings");
   $row_smtp_settings = $get_smtp_settings->fetch();
   $library = $row_smtp_settings->library;
   $enable_smtp = $row_smtp_settings->enable_smtp;
   $host = $row_smtp_settings->host;
   $port = $row_smtp_settings->port;
   $secure = $row_smtp_settings->secure;
   $username = $row_smtp_settings->username;
   $password = $row_smtp_settings->password;

   function get_file($file){
      global $template_folder;
      echo file_get_contents("../emails/templates/$template_folder/$file");
   }
?>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-cog"></i> Settings / Email Content</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Email Content</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container pt-3"><!--- container Starts --->

   <div class="row"><!---  3 row Starts --->

       <div class="col-lg-12"><!--- col-lg-12 Starts --->

           <div class="card mb-5"><!--- card mb-5 Starts -->
             
             <div class="card-header">

               <ul class="nav nav-tabs card-header-tabs">
                 
                 <li class="nav-item">
                   <a class="nav-link active" data-toggle="tab" href="#forgot">Forgot Password</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#email-confirmation">Email Confirmation</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#inbox-new-message">Inbox New Message</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#customer-support">Customer Support</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#ticket-closed">Ticket Closed Email</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#ticket-reply">Ticket Reply Email</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#order-receipt">Order Email Receipt To Buyer</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#order-email">Order Email</a>
                 </li>        

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#dusupay-order">Coinpayments/Dusupay Order Email</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#dusupay-order-completed">Coinpayments/Dusupay Completed Order Email</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#order-delivered">Order Delivered</a>
                 </li>   

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#revision_requested">Revision Requested</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#order_cancel_seller">Order Canceled Email To Seller</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#order_cancel_buyer">Order Canceled Email To Buyer</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#proposal-modification">Proposal Modification</a>
                 </li>
             
                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#proposal-declined">Proposal Declined</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#proposal-approved">Proposal Approved</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#admin-reset-pass">Admin Reset Password</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#new-proposal">Admin New Proposal</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#new-user">Admin New Seller</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#ban-seller">Admin Ban Seller</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#item-report">Admin Item Report</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#cancellation-request">Admin Order Cancellation Request</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#payout-request">Admin Payout Request</a>
                 </li>
                
                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#header">Email Header</a>
                 </li>

                 <li class="nav-item">
                   <a class="nav-link" data-toggle="tab" href="#footer">Email Footer</a>
                 </li>

               </ul>
             </div>

               <div class="card-body tab-content"><!--- card-body Starts --->

               
               <div class="tab-pane fade" id="header"><!--- tab-pane fade show active Starts --->

               <?php include("email_content/header.php"); ?>

               </div><!--- tab-pane fade show active Ends --->


               <div class="tab-pane fade" id="footer"><!--- tab-pane fade show active Starts --->

               <?php include("email_content/footer.php"); ?>

               </div><!--- tab-pane fade show active Ends --->


               <div class="tab-pane fade show active" id="forgot"><!--- tab-pane fade show active Starts --->

               <?php include("email_content/forgot_pass.php"); ?>

               </div><!--- tab-pane fade show active Ends --->


               <div class="tab-pane fade" id="email-confirmation"><!--- tab-pane fade Starts --->

               <?php include("email_content/email_confirmation.php"); ?>

               </div><!--- tab-pane fade Ends --->

               <div class="tab-pane fade" id="inbox-new-message"><!--- tab-pane fade Starts --->

               <?php include("email_content/new_message.php"); ?>

               </div><!--- tab-pane fade Ends --->


               <div class="tab-pane fade" id="customer-support"><!--- tab-pane fade Starts --->

               <?php include("email_content/customer_support.php"); ?>

               </div><!--- tab-pane fade Ends --->


               <div class="tab-pane fade" id="ticket-closed"><!--- tab-pane fade Starts --->

               <?php include("email_content/ticket_closed.php"); ?>

               </div><!--- tab-pane fade Ends --->

               <div class="tab-pane fade" id="ticket-reply"><!--- tab-pane fade Starts --->

               <?php include("email_content/ticket_reply.php"); ?>

               </div><!--- tab-pane fade Ends --->            


               <div class="tab-pane fade" id="order-receipt"><!--- tab-pane fade Starts --->

               <?php include("email_content/order_receipt.php"); ?>

               </div><!--- tab-pane fade Ends --->

               <div class="tab-pane fade" id="order-email"><!--- tab-pane fade Starts --->

               <?php include("email_content/order_email.php"); ?>

               </div><!--- tab-pane fade Ends --->           


               <div class="tab-pane fade" id="dusupay-order"><!--- tab-pane fade Starts --->

               <?php include("email_content/dusupay_order.php"); ?>

               </div><!--- tab-pane fade Ends --->           


               <div class="tab-pane fade" id="dusupay-order-completed"><!--- tab-pane fade Starts --->

               <?php include("email_content/dusupay_order_completed.php"); ?>

               </div><!--- tab-pane fade Ends --->           


               <div class="tab-pane fade" id="order-delivered"><!--- tab-pane fade Starts --->

               <?php include("email_content/order_delivered.php"); ?>

               </div><!--- tab-pane fade Ends --->


               <div class="tab-pane fade" id="revision_requested"><!--- tab-pane fade Starts --->

               <?php include("email_content/revision_requested.php"); ?>

               </div><!--- tab-pane fade Ends --->


               <div class="tab-pane fade" id="order_cancel_seller"><!--- tab-pane fade Starts --->

               <?php include("email_content/order_cancel_seller.php"); ?>

               </div><!--- tab-pane fade Ends --->

               <div class="tab-pane fade" id="order_cancel_buyer"><!--- tab-pane fade Starts --->

               <?php include("email_content/order_cancel_buyer.php"); ?>

               </div><!--- tab-pane fade Ends --->

               <div class="tab-pane fade" id="order_cancel_seller"><!--- tab-pane fade Starts --->

               <?php include("email_content/order_cancel_seller.php"); ?>

               </div><!--- tab-pane fade Ends --->


               <div class="tab-pane fade" id="proposal-modification"><!--- tab-pane fade Starts --->

               <?php include("email_content/proposal_modification.php"); ?>

               </div><!--- tab-pane fade Ends --->


               <div class="tab-pane fade" id="proposal-declined"><!--- tab-pane fade Starts --->

               <?php include("email_content/proposal_declined.php"); ?>

               </div><!--- tab-pane fade Ends --->


               <div class="tab-pane fade" id="proposal-approved"><!--- tab-pane fade Starts --->

               <?php include("email_content/proposal_approved.php"); ?>

               </div><!--- tab-pane fade Ends --->


               <div class="tab-pane fade" id="admin-reset-pass"><!--- tab-pane fade Starts --->

               <?php include("email_content/admin_reset_pass.php"); ?>

               </div><!--- tab-pane fade Ends --->


               <div class="tab-pane fade" id="new-proposal"><!--- tab-pane fade Starts --->

               <?php include("email_content/new_proposal.php"); ?>

               </div><!--- tab-pane fade Ends --->

               <div class="tab-pane fade" id="new-user"><!--- tab-pane fade Starts --->

               <?php include("email_content/new_user.php"); ?>

               </div><!--- tab-pane fade Ends --->

    
               <div class="tab-pane fade" id="ban-seller"><!--- tab-pane fade Starts --->

               <?php include("email_content/ban_seller.php"); ?>

               </div><!--- tab-pane fade Ends --->
    

               <div class="tab-pane fade" id="item-report"><!--- tab-pane fade Starts --->

               <?php include("email_content/item_report.php"); ?>

               </div><!--- tab-pane fade Ends --->
    

               <div class="tab-pane fade" id="cancellation-request"><!--- tab-pane fade Starts --->

               <?php include("email_content/cancellation_request.php"); ?>

               </div><!--- tab-pane fade Ends --->


               <div class="tab-pane fade" id="payout-request"><!--- tab-pane fade Starts --->

               <?php include("email_content/payout_request.php"); ?>

               </div><!--- tab-pane fade Ends --->
    

               </div><!--- card-body Ends --->

           </div><!--- card mb-5 Ends -->

       </div><!--- col-lg-12 Ends --->

   </div><!---  3 row Ends --->

</div><!--- container Ends --->

<script type="text/javascript">
  
  $(document).ready(function(){
      
   $(".preview-email").click(function(event) {
      
      var template = $(this).parent().parent().find('input').val().replace('.php','');

      console.log(template);
      var template_address = "template_preview?template="+template+"&lang=<?= $template_folder ?>";
      console.log(template_address);
      window.open(template_address,"_blank");
      
   });

  });

</script>

<?php

if(isset($_POST['update'])){
   $file = "../emails/templates/$template_folder/".$input->post('file_name');
   $content = $_POST['content'];   
   $handle = fopen($file, "w");

   if($handle){
      fwrite($handle, $content);
      fclose($handle);
      echo "<script>alert_success('Changes Has Been Saved Successfully.','index?email_templates');</script>";
   }

}

?>

<?php } ?>