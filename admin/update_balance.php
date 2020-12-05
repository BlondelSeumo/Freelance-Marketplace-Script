<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{


$seller_id = $input->get('update_balance');

$get_seller = $db->select("sellers",array("seller_id" => $seller_id)); 
	
$row_seller = $get_seller->fetch();
	
$seller_user_name = $row_seller->seller_user_name;

$seller_email = $row_seller->seller_email;


$select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $seller_id));

$current_balance = $select_seller_accounts->fetch()->current_balance;

?>

	<div class="breadcrumbs">
	<div class="col-sm-4">
	<div class="page-header float-left">
	    <div class="page-title">
	        <h1><i class="menu-icon fa fa-users"></i> All Users </h1>
	    </div>
	</div>
	</div>
	<div class="col-sm-8">
	<div class="page-header float-right">
	    <div class="page-title">
	        <ol class="breadcrumb text-right">
	            <li class="active">View Users / Change Balance</li>
	        </ol>
	    </div>
	</div>
	</div>
	</div>

	<div class="container">

    <div class="row mt-2"><!--- 2 row Starts --->

        <div class="col-lg-12"><!--- col-lg-12 Starts --->

		<?php 

		$form_errors = Flash::render("form_errors");
		$form_data = Flash::render("form_data");

		if(is_array($form_errors)){

		?>

		<div class="alert alert-danger"><!--- alert alert-danger Starts --->
		  
		<ul class="list-unstyled mb-0">
		<?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
		<li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
		<?php } ?>
		</ul>

		</div><!--- alert alert-danger Ends --->

		<?php } ?>

            <div class="card"><!--- card Starts --->

                <div class="card-header"><!--- card-header Starts --->

                <h4 class="h4"><i class="fa fa-money"></i> Update Seller Balance</h4>

                </div><!--- card-header Ends --->

                <div class="card-body"><!--- card-body Starts --->

				<form action="" method="post">

				<div class="form-group row">

				<label class="col-md-3 control-label">Username</label>

				<div class="col-md-6">

				<p class="mt-2"><?= $seller_user_name; ?></p>

				</div>

				</div>

				<div class="form-group row">

				<label class="col-md-3 control-label">Email Address</label>

				<div class="col-md-6">

				<p class="mt-2"><?= $seller_email; ?></p>

				</div>

				</div>

				<div class="form-group row">

				<label class="col-md-3 control-label">Current Balance</label>

				<div class="col-md-6">

				<p class="mt-2"><?= showPrice($current_balance); ?></p>

				</div>

				</div>

				<div class="form-group row">

				<label class="col-md-3 control-label">New Balance</label>

				<div class="col-md-6">

				<input type="text" name="new_balance" placeholder="New Balance" class="form-control" required="">

				</div>

				</div>

				<div class="form-group row">

				<label class="col-md-3 control-label"></label>

				<div class="col-md-6">

				<input type="submit" name="submit" value="Update Balance" class="btn btn-success form-control" >

				</div>

				</div>


				</form>

            </div><!--- card-body Ends --->

            </div><!--- card Ends --->

        </div><!--- col-lg-12 Ends --->

    </div><!--- 3 row Ends --->

</div>

<?php
    
	if(isset($_POST['submit'])){

		$rules = array("new_balance" => "number");
		$val = new Validator($_POST,$rules);
		if($val->run() == false){

			Flash::add("form_errors",$val->get_all_errors());
			Flash::add("form_data",$_POST);
			echo "<script> window.open(window.location.href,'_self');</script>";

		}else{

			$balance = $input->post('new_balance');
			$update_balance = $db->update("seller_accounts",array("current_balance"=>$balance),array("seller_id"=>$seller_id));
			if($update_balance){
				$insert_log = $db->insert_log($admin_id,"seller_balance",$seller_id,"updated");
				echo "<script>alert_success('Congrats, Seller Balance Has Been Updated Successfully.','index?view_sellers')</script>";
			}

		}

	}

?>

<?php } ?>