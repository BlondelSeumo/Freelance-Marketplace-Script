<?php 
   session_start();   
   include("includes/db.php");
   include("../functions/mailer.php");
   if(!isset($_SESSION['admin_email'])){
      echo "<script>window.open('login','_self');</script>";
   }

   if((time() - $_SESSION['loggedin_time']) > 9800){
      echo "<script>window.open('logout.php?session_expired','_self');</script>";
   }   
   
   $site_logo = getImageUrl2("general_settings","site_logo",$row_general_settings->site_logo);

   $data['template'] = $input->get('template');   
   $data['lang'] = @$input->get('lang');
   
   $data['user_name'] = "{receiver username}";
   $data['forgot_link'] = "$site_url/Reset_Password_Link";
   $data['verification_link'] = "$site_url/{verification link}";

   $data['sender_user_name'] = "Message Sender";
   $data['message_date'] = date("h:i: F d, Y");
   $data['message'] = "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout";
   $data['message_group_id'] = "";

   $data['seller_user_name'] = "{Seller Username}";
   $data['seller_email'] = "user@gmail.com";
   $data['enquiry_title'] = "user@gmail.com";
   $data['subject'] = "user@gmail.com";
   $data['subject'] = "Order Support";
   $data['attachment'] = "Fake.jpg";

   $data['proposal_title'] = "Sample Proposal Title";
   $data['qty'] = "1";
   $data['duration'] = "1";
   $data['amount'] = "11";

   $data['buyer_user_name'] = "{Buyer Username}";

   $data['order_id'] = "";

   $data['proposal_url'] = "";

   $data['admin_pass'] = "";

   $data['cat_title'] = "Graphic Design";
   $data['proposal_status'] = "Active";

   $data['item_type'] = "Proposal";
   $data['author'] = "{User}";
   $data['date'] = date("h:i: F d, Y");
   $data['item_link'] = "{Item Link}";

   $data['order_number'] = "71509409";


   $file = "main.php";

   echo load_view($file,$data);