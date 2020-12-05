<?php
@session_start();

if(!isset($_SESSION['admin_email'])){
  
echo "<script>window.open('login','_self');</script>";
  
}else{
    
$single_request = $input->get('single_request');

$get_support_tickets = $db->select("support_tickets",array("ticket_id" => $single_request));
$row_support_tickets = $get_support_tickets->fetch();
$ticket_id = $row_support_tickets->ticket_id;
$number = $row_support_tickets->number;
$sender_id = $row_support_tickets->sender_id;
$subject = $row_support_tickets->subject;
$status = $row_support_tickets->status;
$enquiry_id = $row_support_tickets->enquiry_id;
$message = $row_support_tickets->message;
$attachment = $row_support_tickets->attachment;
$order_number = $row_support_tickets->order_number;
$order_rule = $row_support_tickets->order_rule;
$date = $row_support_tickets->date;

$select_sender = $db->select("sellers",array("seller_id" => $sender_id));
$row_sender = $select_sender->fetch();
$sender_user_name = $row_sender->seller_user_name;
$sender_email = $row_sender->seller_email;
$sender_phone = $row_sender->seller_phone;

$get_enquiry_types = $db->select("enquiry_types",array("enquiry_id" => $enquiry_id));
$enquiry_title = $get_enquiry_types->fetch()->enquiry_title;

$support_conversations = $db->select("support_conversations",['ticket_id'=>$ticket_id])->fetchAll();

if(isset($_POST['new_message']) && !empty($_POST['new_message'])){   

    $new_message = $input->post('new_message');

    $file = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    
    $allowed = array('jpeg','jpg','gif','png','tif','avi','mpeg','mpg','mov','rm','3gp','flv','mp4','zip','rar','mp3','wav','docx','csv','xls','xlsx','pptx','pdf','txt','psd','xd','txt');

    $file_extension = pathinfo($file, PATHINFO_EXTENSION);

    if(!in_array($file_extension,$allowed) & !empty($file)){
      echo "<script>alert('{$lang['alert']['extension_not_supported']}')</script>";
    }else{

        if(!empty($file)){
          $file = pathinfo($file, PATHINFO_FILENAME);
          $file = $file."_".time().".$file_extension";
          uploadToS3("ticket_files/$file",$file_tmp);
        }else{
          $file = "";
        }

        $isS3 = $enable_s3;
        $date = date("h:i M d, Y");
        $insert_support_ticket = $db->insert("support_conversations",array("ticket_id" => $ticket_id, "admin_id" => $admin_id, "sender_id" => 0,"message" => $new_message,"attachment" => $file,"date" => $date));
        if($insert_support_ticket){          

            $data = [];
            $data['template'] = "ticket_reply";
            $data['to'] = $sender_email;
            $data['subject'] = "$site_name: We just responded to your ticket.";
            $data['user_name'] = $sender_user_name;
            $data['ticket_number'] = $number;
            $data['response'] = $new_message;
            send_mail($data);

            if($notifierPlugin == 1){ 

                $smsText = str_replace('{number}',$number,$lang['notifier_plugin']['ticket_reply']);
                sendSmsTwilio("",$smsText,$sender_phone);

            }

            $date = date("F d, Y");

            $insert_notification = $db->insert("notifications",array("receiver_id" => $sender_id,"sender_id" => "admin_$admin_id","order_id" => $ticket_id,"reason" => "ticket_reply","date" => $date,"status" => "unread"));

            echo "<script>alert_success('Message submitted successfully.','index?single_request=$ticket_id');</script>"; 

        }
    }
}
  
$allowed = array('jpeg','jpg','gif','png');

?>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-phone-square"></i> Support Settings</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Single Request</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<div class="container">

    <div class="row"><!--- 2 row Starts --->

        <div class="col-lg-12"><!--- col-lg-12 Starts --->

            <div class="card"><!--- card Starts --->

                <div class="card-header"><!--- card-header Starts --->

                    <h4 class="h4">View Single Request</h4>

                </div><!--- card-header Ends --->

                <div class="card-body"><!--- card-body Starts --->

                    <p>

                        <b> Ticket Number : </b> #<?= $number; ?>

                    </p>

                    <p>

                        <b> Enquiry Type : </b><?= $enquiry_title; ?>

                    </p>

                    <p>

                        <b> Sender Username : </b><?= $sender_user_name; ?>

                    </p>

                    <p>

                        <b> Sender Email Address : </b><?= $sender_email; ?>

                    </p>

                    <p>

                        <b> Subject : </b><?= $subject; ?>

                    </p>

                    <p>
                        <b> Message : </b><?= $message; ?>
                    </p>

                    <?php if(!empty($order_rule)){ ?>

                    <p>
                        <b> Order Rule : </b><?= $order_rule; ?>
                    </p>

                    <?php } ?>


                    <?php if(!empty($order_number)){ ?>

                    <p>
                        <b> Order Number : </b><?= $order_number; ?>
                    </p>

                    <?php } ?>


                    <?php if(!empty($attachment)){ ?>
                    <p>

                        <b> Attachment : </b>
                        <a href="<?= getImageUrl("support_tickets",$attachment); ?>" download>
                            <?= $attachment; ?>        
                        </a>

                    </p>

                    <?php } ?>

                    <p>
                        <b> Request Created Date : </b> <?= $date; ?>
                    </p>

                    <p>

                        <b> Request Status : </b> <?= ucwords($status); ?> &nbsp;&nbsp;

                        <a href="#" class="btn btn-success" data-toggle="collapse" data-target="#status">
                           Change Status
                        </a>

                    </p>

                    <form id="status" class="collapse form-inline" method="post"><!--- collapse form-inline Starts --->

                     <p class="text-muted mb-3">

                        <span class="text-danger">Note : </span> If you solve this issue, change the text from "OPEN" to "CLOSE". By doing this, this ticket will disappear.

                     </p>

                     <div class="form-group"><!--- form-group Starts --->

                         <label class="mb-2 mr-sm-2 mb-sm-0"> Change Status : </label>

                         <input type="text" name="status" class="form-control mb-2 mr-sm-2 mb-sm-0" value="<?= $status; ?>">
                         <input type="submit" name="update_status" class="form-control btn btn-success" value="Change Status">

                     </div><!--- form-group Ends --->

                     <?php

                     if(isset($_POST['update_status'])){

                        $status = $input->post('status');

                        $update_support_ticket = $db->update("support_tickets",["status" => $status],["ticket_id"=>$single_request]);
                        if($update_support_ticket){
                            
                            $insert_log = $db->insert_log($admin_id,"support_request",$ticket_id,$status);
                            if($status == "close"){
                                $data = [];
                                $data['template'] = "ticket_closed";
                                $data['to'] = $sender_email;
                                $data['subject'] = "$site_name: your ticket has been closed.";
                                $data['user_name'] = $sender_user_name;
                                send_mail($data);
                            }
                            echo "<script>alert('Ticket has been changed successfully.');</script>";
                            echo "<script>window.open('index?single_request=$ticket_id','_self');</script>"; 
                        
                        }

                     }

                     ?>

               </form><!--- collapse form-inline Ends --->
            </div><!--- card-body Ends --->

         </div><!--- card Ends --->

        <div class="card"><!--- card Starts --->

        <div class="card-header"><!--- card-header Starts --->

            <h4 class="h4">Ticket Conversations</h4>

        </div><!--- card-header Ends --->

        <div class="card-body"> <!--- card-body Starts --->

            <?php if(empty($support_conversations)){ ?>
                Currenty do not have any converstation
            <?php } ?>

            <?php 

            foreach($support_conversations as $conversation){ 

            if(!empty($conversation->admin_id)){
                $admin = $db->query("select admin_image,admin_user_name from admins where admin_id = ".$conversation->admin_id)->fetch();
                $sender_user_name = $admin->admin_user_name;
                $sender_image = getImageUrl("admins",$admin->admin_image);
            }else{
                $select_sender = $db->select("sellers",array("seller_id" => $conversation->sender_id));
                $row_sender = $select_sender->fetch();
                $sender_user_name = $row_sender->seller_user_name;
                $sender_image = getImageUrl2("sellers","seller_image",$row_sender->seller_image);
            }

            ?>

            <div class="message-div mb-3">

                <img class="message-image img-fluid img-rounded" src="<?= $sender_image; ?>" width="60" height="60">

                <h5 class="mb-1"><?= $sender_user_name; ?></h5>
                
                <p class="message-desc mb-1"><?= $conversation->message; ?></p>

                <p class="message-desc">

                    <?php if(!empty($conversation->attachment)){ ?>

                    <?php if(in_array(pathinfo($conversation->attachment,PATHINFO_EXTENSION),$allowed)){ ?>
                    <img src="<?= getImageUrl("support_conversations",$conversation->attachment); ?>" class="img-thumbnail" width="100"/>
                    <?php } ?>

                    <a href="<?= getImageUrl("support_conversations",$conversation->attachment); ?>" target="_blank" class="d-block mt-2">
                        <i class="fa fa-download"></i> <?= $conversation->attachment; ?>
                    </a>
                    <?php } ?>

                </p>

                <p class="text-muted text-right mb-0"><?= $conversation->date; ?></p>
            
            </div>    
            <?php } ?>

        </div><!--- card-body Ends --->

        </div><!--- card Ends --->

        <?php if($status == 'open'){ ?>
            <div class="card"><!--- card Starts --->            
                <div class="card-body"><!--- card-body Starts --->
                    <form id="new_message" method="post" enctype="multipart/form-data">
                        <div class="form-group"><!--- form-group Starts --->                        
                            <label class="col-form-label-lg">
                                New Response Message to <?= $sender_user_name; ?>
                            </label>
                            <textarea placeholder="Enter Your message" class="form-control" name="new_message" id="new_message" rows="5"></textarea>
                        </div><!--- form-group Ends --->                
                        <div class="form-group">
                            <label class="<?= $floatRight ?>">Upload File</label>
                            <input type="file" class="form-control" name="file">
                        </div>
                        <input type="submit" name="submit" class="btn btn-success" value="Submit">
                    </form>                    
                </div>
            </div>
        <?php }else{ ?>
            <div class="card">
                <div class="card-body">
                    You Can't Send Message Now Because Ticket status is closed now.                     
                </div>
            </div>
        <?php } ?>

      </div><!--- col-lg-12 Ends --->

   </div><!--- 2 row Ends --->
</div>
<?php } ?>