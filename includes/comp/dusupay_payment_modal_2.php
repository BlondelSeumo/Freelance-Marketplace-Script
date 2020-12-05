<?php
	
	@session_start();
	require_once("../db.php");

	$get_payment_settings = $db->select("payment_settings");
	$row_payment_settings = $get_payment_settings->fetch();
	$dusupay_api_key = $row_payment_settings->dusupay_api_key;
	$dusupay_secret_key = $row_payment_settings->dusupay_secret_key;
	$dusupay_sandbox = $row_payment_settings->dusupay_sandbox;


	$action = $input->post('action');

	$for = "collection";
	$method = $input->post("method");
	$country = $input->post("country");

	$curl = curl_init();
	$url = ($dusupay_sandbox=="on"?'https://dashboard.dusupay.com/api-sandbox':'https://api.dusupay.com');
	$url = "$url/v1/payment-options/$for/$method/$country?api_key=$dusupay_api_key";

	curl_setopt_array($curl, array(
		CURLOPT_URL => "$url",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"content-type: application/json",
			"secret-key: $dusupay_secret_key"
		),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if($err){
		echo "cURL Error #:".$err;
	}else{

	$data = json_decode($response, TRUE);

?>

<div class="modal-content"><!--- modal-content Starts --->

   <div class="modal-header"><!-- modal-header Starts -->
      
      <h5 class="modal-title"> 
         <span class="float-left">Pay With Dusupay</span>
      </h5>

      <button class="closeExtendTimePayment close" data-dismiss="modal">
         <span>&times;</span>
      </button>

   </div><!-- modal-header Ends -->

   <div class="modal-body"><!--- modal-body Starts --->
   
      <form method="post" action="<?= $action; ?>" <?= (isset($extendTimePayment))?"target='_blank'":""; ?>><!--- form Starts --->
         
         <input type="hidden" name="country" value="<?= $country; ?>">
         <input type="hidden" name="method" value="<?= $method; ?>">

         <div class="form-group"><!--- form-group Starts --->
            <label>Select Your Payment Provider </label>
            <select name="provider_id" class="form-control" required="">

               <?php 

	               foreach ($data['data'] as $provider) {
	               	echo "<option value='{$provider['id']}'>{$provider['name']} ({$provider['transaction_currency']})</option>";
	               }

               ?>

            </select>
         </div><!--- form-group Ends --->

         <?php if($method == "MOBILE_MONEY"){ ?>
         <div class="form-group">
            <label>Mobile Money Account Number</label>
            <input type="text" name="account_number" placeholder="Enter Your Mobile Money Account Number" class="form-control" required=""/>
         </div>
      	<?php } ?>

         <div class="form-group voucher d-none">
            <label>Mobile Money Voucher</label>
            <input type="text" name="voucher" placeholder="Enter Your Mobile Money Voucher" class="form-control"/>
         </div>

         <hr>

         <div class="form-group mb-0 text-center"><!--- form-group Starts --->

            <button class="btn btn-success" name="dusupay" type="submit">Pay With Dusupay</button>

         </div><!--- form-group Ends --->

      </form><!--- form Ends --->

   </div><!--- modal-body Ends --->

</div><!--- modal-content Ends --->

<script>
	
$(document).ready(function(){
   
   $("select[name='provider_id']").change(function(){

      if($(this).val() == 'vodafone_gh'){
         $(".voucher").removeClass('d-none');
      	$(".voucher input*").attr("required","required");
      }else{
         $(".voucher").addClass('d-none');
      	$(".voucher input*").removeAttr("required");
      }

   });

});

</script>

<?php } ?>