<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Account Confirmation</title>
<style type="text/css">
   body{
    background: #fafafa; padding: 0px;
    margin: 0px;  
   }
   .table{
      min-width:480px;margin-top: 20px;
      background: #969393;
   }
   .logo{
      display: block;
      padding-bottom: 10px;
      width: 140px;
      margin-top: 20px;
   }
   .banner{
      display:block;
      width:480px;
   }
   .heading{
      font-family:'Roboto',Arial,Helvetica,sans-serif;
      font-size:22px;
      font-weight:300;
      color:#333333;
      margin:0px!important;
      padding:0px!important;
      line-height:29px
   }
   .footer-p{
      font-family:'Roboto',Arial,Helvetica,sans-serif;font-size:12px;font-weight:normal;color:#8b8b8b;padding:0px!important;line-height:18px;
      margin-top: 5px;
   }

</style>
</head>
<body>
<?php include("$dir/emails/templates/{$lang}/".$data['template'].".php"); ?>

</body>
</html>