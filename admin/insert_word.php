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
        <h1><i class="menu-icon fa fa-fax"></i> Restricted Words </h1>
    </div>
</div>
</div>
<div class="col-sm-8">
<div class="page-header float-right">
    <div class="page-title">
        <ol class="breadcrumb text-right">
            <li class="active">Restricted Words / Insert Word</li>
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

<h4 class="h4"> <i class="fa fa-money-bill-alt"></i> Insert Word </h4>

</div><!--- card-header Ends --->

<div class="card-body"><!--- card-body Starts --->

<form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->


<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"> Word Name : </label>

<div class="col-md-6">

<input type="text" name="name" class="form-control" required>

</div>

</div><!--- form-group row Ends --->


<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"></label>

<div class="col-md-6">

<input type="submit" name="insert" value="Insert Word" class="btn btn-success form-control">

</div>

</div><!--- form-group row Ends --->


</form><!--- form Ends --->

</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->

</div>

<?php

if(isset($_POST['insert'])){
	
$name = $input->post('name');


$insert_word = $db->insert("spam_words",array("word"=>$name));

if($insert_word){
	
echo "<script>alert_success('One Word Has Been Insertd.','index?view_words');</script>";
	
}

		
}

?>

<?php } ?>