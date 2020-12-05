<?php

if(isset($_GET['hide_seller'])){
  $hide_seller_id = $input->get('hide_seller');
  $hide_seller = $db->insert("hide_seller_messages",array("hider_id"=>$login_seller_id,"hide_seller_id"=>$hide_seller_id));
  if($hide_seller){
    echo "<script>window.open('inbox','_self')</script>";
  }
}
if(isset($_GET['star'])){
  $message_group_id = $input->get('star');
  $add = $db->insert("starred_messages",array("seller_id"=>$login_seller_id,"message_group_id"=>$message_group_id));
  echo "<script>window.open('inbox?single_message_id=$message_group_id','_self')</script>";
}
if(isset($_GET['unstar'])){
  $message_group_id = $input->get('unstar');
  $delete = $db->delete("starred_messages",array("seller_id"=>$login_seller_id,"message_group_id"=>$message_group_id)); 
  echo "<script>window.open('inbox?single_message_id=$message_group_id','_self')</script>";
}
if(isset($_GET['archive'])){
  $message_group_id = $input->get('archive');
  $add = $db->insert("archived_messages",array("seller_id"=>$login_seller_id,"message_group_id"=>$message_group_id));
  echo "<script>window.open('inbox?single_message_id=$message_group_id','_self')</script>";
}
if(isset($_GET['unarchive'])){
  $message_group_id = $input->get('unarchive');
  $delete = $db->delete("archived_messages",array("seller_id"=>$login_seller_id,"message_group_id"=>$message_group_id)); 
  echo "<script>window.open('inbox?single_message_id=$message_group_id','_self')</script>";
}
if(isset($_GET['unread'])){
  $message_group_id = $input->get('unread');
  $add = $db->insert("unread_messages",array("seller_id"=>$login_seller_id,"message_group_id"=>$message_group_id));
  echo "<script>window.open('inbox?single_message_id=$message_group_id','_self')</script>";
}
if(isset($_GET['read'])){
  $message_group_id = $input->get('read');
  $delete = $db->delete("unread_messages",array("seller_id"=>$login_seller_id,"message_group_id"=>$message_group_id)); 
  echo "<script>window.open('inbox?single_message_id=$message_group_id','_self')</script>";
}
if(isset($_POST['submit_report'])){
$message_group_id = $input->post('message_group_id');
$reason = $input->post('reason');
$additional_information = $input->post('additional_information');
$date = date("F d, Y");
$insert = $db->insert("reports",array("reporter_id" => $login_seller_id,"content_id" => $message_group_id,"content_type" => "message","reason" => $reason,"additional_information" => $additional_information,"date"=>$date));

$insert_notification = $db->insert("admin_notifications",array("seller_id" => $login_seller_id,"content_id" => $message_group_id,"reason" => "message_report","date" => $date,"status" => "unread"));

if($insert){
send_report_email("message","No Author",$message_group_id,$date);
echo "<script>alert('Your Report Has Been Successfully Submitted.')</script>";
echo "<script>window.open('inbox?single_message_id=$message_group_id','_self')</script>";
}
}
/// Time Ago Function Starts ///
function time_ago($timestamp){
  $time_ago = strtotime($timestamp);  
  $current_time = time();  
  $time_difference = $current_time - $time_ago;  
  $seconds = $time_difference;  
  $minutes      = round($seconds / 60 );             // value 60 is seconds  
  $hours           = round($seconds / 3600);        //value 3600 is 60 minutes * 60 sec  
  $days          = round($seconds / 86400);        //86400 = 24 * 60 * 60;  
  $weeks          = round($seconds / 604800);     // 7*24*60*60;  
  $months          = round($seconds / 2629440);  //((365+365+365+365+366)/5/12)*24*60*60  
  $years          = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60  
  if($seconds <= 60){  
    return "Just Now";  
  }else if($minutes <=60){  
   if($minutes==1) {  
     return "one minute ago";  
   }  else  {  
     return "$minutes minutes ago";  
   }  
  }  else if($hours <=24)  {  
   if($hours==1){  
     return "an hour ago";  
   }else{  
     return "$hours hrs ago";  
   }  
 }else if($days <= 7)  {  
   if($days==1)  {  
     return "yesterday";  
   }else{
     return "$days days ago";  
   }  
 }else if($weeks <= 4.3){ //4.3 == 52/12  
   if($weeks==1)  {  
     return "a week ago";  
   } else{
     return "$weeks weeks ago";  
   }  
 }else if($months <=12)  {  
   if($months==1) {  
     return "a month ago";  
   }else{  
     return "$months months ago";  
   }
 }else{
   if($years==1)  {  
     return "one year ago";  
   } else {  
     return "$years years ago";  
   }  
 }
}
/// Time Ago Function Ends ///