<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
    
$proposal_id = $input->get('feature_proposal');
$page = (isset($_GET['page']))?"=".$input->get('page'):"";
$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
$row_proposals = $select_proposals->fetch();
$proposal_title = $row_proposals->proposal_title;
$proposal_seller_id = $row_proposals->proposal_seller_id;

?>

<div class="breadcrumbs">

<div class="col-sm-4">

<div class="page-header float-left">

<div class="page-title">

<h1><i class="menu-icon fa fa-table"></i> Proposal / Make Proposal Featured</h1>

</div>

</div>

</div>

</div>


<div class="container">

<div class="row pt-2">

<div class="col-lg-12">

<div class="card">

<div class="card-header">

<h4>Make Proposal Featured</h4>

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

<label class="col-md-3 control-label">Select Featured Duration</label>

<div class="col-md-6">

<select class="form-control" name="featured_duration">
  
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>

</select>

</div>

</div>


<div class="form-group row">

<label class="col-md-3 control-label"></label>

<div class="col-md-6">

<input type="submit" name="submit" class="btn btn-success form-control" >

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
    
  $featured_duration = $input->post('featured_duration');

  $update_proposal = $db->update("proposals",array("proposal_featured"=>'yes'),array("proposal_id"=>$proposal_id));

  if($update_proposal){

  $end_date = date("F d, Y h:i:s", strtotime(" + $featured_duration days"));
  
  $insert_featured = $db->insert("featured_proposals",array("proposal_id"=>$proposal_id,"end_date"=>"$end_date"));

  unset($_SESSION['proposal_id']);

  echo "<script>alert('Congrats, This Proposal Has Been Feature Listed On The Website Successfully.')</script>";

  echo "<script>window.open('index?view_proposals$page','_self');</script>";

  }

      
  }  

?>


<?php } ?>