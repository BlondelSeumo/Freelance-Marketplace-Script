<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
   
echo "<script>window.open('login','_self');</script>";
   
}else{

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$dusupay_api_key = $row_payment_settings->dusupay_api_key;
$dusupay_secret_key = $row_payment_settings->dusupay_secret_key;
$dusupay_sandbox = $row_payment_settings->dusupay_sandbox;

$country = @$input->post('country');
$for = @$input->post('for');
$method = @$input->post('method');

?>

  <div class="breadcrumbs">
      <div class="col-sm-4">
          <div class="page-header float-left">
            <div class="page-title">
              <h1><i class="menu-icon fa fa-cog"></i> Settings / Payment Settings</h1>
            </div>
          </div>
      </div>
      <div class="col-sm-8">
          <div class="page-header float-right">
              <div class="page-title">
                  <ol class="breadcrumb text-right">
                      <li class="active">View Dusupay Provider Ids</li>
                  </ol>
              </div>
          </div>
      </div>
  </div>

<div class="container pt-3">

   <div class="row"><!--- 2 row Starts --->

     <div class="col-lg-12"><!--- col-lg-12 Starts --->

         <div class="card mb-4"><!--- card mb-5 Starts --->

             <div class="card-header"><!--- card-header Starts --->

                 <h4 class="h4">

                    <i class="fa fa-money fa-fw"></i> View Dusupay Provider Ids

                 </h4>

             </div><!--- card-header Ends --->

            <div class="card-body"><!--- card-body Starts --->

               <form action="" method="post"><!--- form Starts --->

                <div class="form-group row"><!--- form-group row Starts --->
                  <label class="col-md-3 control-label"> Country Code : </label>
                  <div class="col-md-6">
                    <input type="text" name="country" class="form-control" value="<?= $country; ?>" required="">
                    <small class="text-muted">
                      Click Here To Get Supported
                      <a class="text-success" target="_blank" href="https://dashboard.dusupay.com/docs/#supported-countries">Countries Codes</a>
                    </small>
                  </div>
                </div><!--- form-group row Ends --->


                <div class="form-group row"><!--- form-group row Starts --->
                  <label class="col-md-3 control-label"> For : </label>
                  <div class="col-md-6">
                    <select name="for" class="form-control" required="">
                      <option value="collection"> Buying </option>
                      <option value="payout" <?= ($for == "payout")?'selected':''; ?>> User Payout/Withdraw Money </option>
                    </select>
                  </div>
                </div><!--- form-group row Ends --->

                <div class="form-group row"><!--- form-group row Starts --->
                  <label class="col-md-3 control-label"> Payment Method : </label>
                  <div class="col-md-6">
                    <select name="method" class="form-control" required="">
                      <option value="MOBILE_MONEY"> Mobile Money </option>
                      <option value="CARD" <?= ($method == "CARD")?'selected':''; ?>> Card </option>
                      <option value="BANK" <?= ($method == "BANK")?'selected':''; ?>> Bank </option>
                      <option value="CRYPTO" <?= ($method == "CRYPTO")?"selected":""; ?>>Crypto</option>
                    </select>
                  </div>
                </div><!--- form-group row Ends --->

                 <div class="form-group row"><!--- form-group row Starts --->

                    <label class="col-md-3 control-label"></label>

                    <div class="col-md-6">
                      <input type="submit" name="get_provider" value="View Provider Ids" class="btn btn-success form-control"/>
                    </div>

                 </div><!--- form-group row Ends --->

               </form><!--- form Ends --->

            </div><!--- card-body Ends --->

         </div><!--- card mb-5 Ends --->

        <?php

        if(isset($_POST['get_provider'])){

          // $dusupay_api_key = "";
          // $dusupay_secret_key = "";

          $for = $input->post("for");
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

          if ($err) {
            echo "cURL Error #:" . $err;
          }else{

            $data = json_decode($response, TRUE);
            echo "<div class='alert alert-success mb-4'><pre>";
              print_r($data);
            echo "</pre></div>";

          }

        }

        ?>

      </div><!--- col-lg-12 Ends --->

   </div><!--- 2 row Ends --->

</div><!--- container Ends --->

<?php } ?>