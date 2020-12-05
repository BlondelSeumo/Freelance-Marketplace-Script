<?php
	
	@session_start();
	require_once("../db.php");

	$get_payment_settings = $db->select("payment_settings");
	$row_payment_settings = $get_payment_settings->fetch();
	$dusupay_api_key = $row_payment_settings->dusupay_api_key;
	$dusupay_secret_key = $row_payment_settings->dusupay_secret_key;
	$dusupay_sandbox = $row_payment_settings->dusupay_sandbox;

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

		if($data['code'] == 200 AND $data['status'] == "success"){

			echo "success";

		}else{
			echo $data['message'];
		}

		// echo "<pre>";
		//   print_r($data);
		// echo "</pre>";

	}