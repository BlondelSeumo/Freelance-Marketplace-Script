<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{

?>

<div class="breadcrumbs">
	<div class="col-sm-6">
		<div class="page-header float-left">
			<div class="page-title">
				<h1><i class="menu-icon fa fa-cog"></i> Settings / Verify Your Purchase</h1>
			</div>
		</div>
	</div>
</div>

<div class="container pt-3"><!--- container Starts --->
<div class="row"><!--- 2 row Starts --->
  <div class="col-lg-12"><!--- col-lg-12 Starts --->
	<div class="card mb-5"><!--- card mb-5 Starts --->
	  <div class="card-header"><!--- card-header Starts --->
		<h4 class="h4 mb-0"><i class="fa fa-money fa-fw"></i> Verify Your Purchase</h4>
	  </div><!--- card-header Ends --->
	  <div class="card-body"><!--- card-body Starts --->
		<form action="" method="post"><!--- form Starts --->

		<div class="form-group row"><!--- form-group row Starts --->

		<label class="col-md-3 control-label"> Purchase Code : </label>

		<div class="col-md-6">
			<input type="text" name="purchase_code" placeholder="Enter Your Purchase Code" class="form-control" required="">
			<small class="form-text text-muted">
				“In order to proceed with this update, you will have to add a purchase code. If you purchased from codecanyon, please use your CodeCanyon purchase code, however, if you purchased from either Codester, Alkanyx or Pixinal Store please <a href="https://tawk.to/chat/5eae3327203e206707f9075a/default" target="_blank" class="text-primary">click this link</a> to request a purchase code.”
			</small>
		</div>

		</div><!--- form-group row Ends --->

		<div class="form-group row"><!--- form-group row Starts --->

		<label class="col-md-3 control-label"></label>

		<div class="col-md-6">
			<input type="submit" name="verify" value="Verify Your Purchase" class="btn btn-success form-control">
		</div>

		</div><!--- form-group row Ends --->

		</form>

		<?php

		function verify_purchase($purchase_code,$site_url){
			return array('status'=>'valid','purchase_code'=>'purchase_code','license_type'=>'Standart','website'=>'domen.ru');
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://www.gigtodo.com/purchase-code-management-system/admin/verify_purchase/",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => array('purchase_code' => $purchase_code,'site_url' => $site_url),
			));

			$response = curl_exec($curl);
			curl_close($curl);
			return json_decode($response);
		}

		if(isset($_POST['verify'])){

			$purchase_code = $input->post('purchase_code');

			$verify_purchase = verify_purchase($purchase_code,$site_url);

			if($verify_purchase->status == "already_used"){

				echo "
				<script>
				swal({
				  type: 'error',
				  text: 'The Purchase Code That You Enter Is Already Used By You Or By Another User.',
				});
				</script>";

			}elseif($verify_purchase->status == "invalid"){

				echo "
				<script>
				swal({
				  type: 'error',
				  text: 'The Purchase Code That You Enter Is Not Valid.',
				});
				</script>";

			}elseif($verify_purchase->status == "valid"){

				$app_license = $db->update("app_license",[
					"purchase_code" => $verify_purchase->purchase_code,
					"license_type" => $verify_purchase->license_type,
					"website" => $verify_purchase->website,
				]);

				echo "
				<script>
					swal({
					  type: 'success',
					  text: 'Your Purchase Has Been Verified Successfully.',
					  timer: 6000,
					  onOpen: function(){
							swal.showLoading()
						}
					}).then(function(){
						window.open('$site_url/admin/index?dashboard','_self')
					});
				</script>";

			}

		}

		?>

	</div><!--- card-body Ends --->
	</div><!--- card mb-5 Ends --->
	</div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
</div><!--- container Ends --->
<?php } ?>