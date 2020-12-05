<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
  
echo "<script>window.open('login.php','_self');</script>";
  
}else{

?>

<div class="breadcrumbs">
  <div class="col-sm-4">
  <div class="page-header float-left">
    <div class="page-title">
      <h1><i class="menu-icon fa fa-table"></i> Payouts</h1>
    </div>
  </div>
  </div>
  <div class="col-sm-8">
    <div class="page-header float-right">
      <div class="page-title">
        <ol class="breadcrumb text-right">
        <li class="active">Decline Payout</li>
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

<h4 class="h4"><!--- h4 Starts --->

<i class="fa fa-money fa-fw"></i> Decline Payout Request

</h4><!--- h4 Ends --->

</div><!--- card-header Ends ---> 

<div class="card-body"><!--- card-body Starts --->

<form action="" method="post"><!---  form Starts --->


<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"> Describe Reason </label>

<div class="col-md-6">

<textarea name="message" class="form-control"></textarea>

</div>

</div><!--- form-group row Ends --->


<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"></label>

<div class="col-md-6">

<input type="submit" name="submit" value="Decline Payout Request" class="btn btn-success form-control">

</div>

</div><!--- form-group row Ends --->


</form><!---  form Ends --->

</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->

</div>

<?php

if(isset($_POST['submit'])){
  
$id = $input->get('decline_payout');
$message = $input->post('message');


$update = $db->update("payouts",array("status"=>'declined',"message"=>$message,),array("id"=>$id));

if($update){

$date = date("F d, Y");

$get = $db->select("payouts",array('id'=>$id));
$row = $get->fetch();
$seller_id = $row->seller_id;
$amount = $row->amount;

$get_seller = $db->select("sellers",array("seller_id" => $seller_id));
$row_seller = $get_seller->fetch();
$seller_phone = $row_seller->seller_phone;

$insert_notification = $db->insert("notifications",array("receiver_id" => $seller_id,"sender_id" => "admin_$admin_id","order_id" => $id,"reason" => "withdrawal_declined","date" => $date,"status" => "unread"));

$update_seller_account = $db->query("update seller_accounts set current_balance=current_balance+:plus,withdrawn=withdrawn-:minus where seller_id='$seller_id'",array("plus"=>$amount,"minus"=>$amount));


if($notifierPlugin == 1){
  $smsText = $lang['notifier_plugin']['payout_declined'];
  sendSmsTwilio("",$smsText,$seller_phone);
}

echo "
<script>alert('One Payout Request Has Been Declined.');
window.open('index?payouts&status=declined','_self');</script>";
  
}

}

?>

<?php } ?>