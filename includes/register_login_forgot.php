<?php

require_once("$dir/functions/email.php");
require_once("$dir/functions/mailer.php");

$get_general_settings = $db->select("general_settings");   
$row_general_settings = $get_general_settings->fetch();
$site_email_address = $row_general_settings->site_email_address;
$site_name = $row_general_settings->site_name;
$signup_email = $row_general_settings->signup_email;
$referral_money = $row_general_settings->referral_money;

if(isset($_POST['register'])){
	$rules = array(
	"name" => "required",
	"u_name" => "required",
	"email" => "email|required",
	"pass" => "required",
	"con_pass" => "required");

	$messages = array("name" => "Full Name Is Required.","u_name" => "User Name Is Required.","pass" => "Password Is Required.","con_pass" => "Confirm Password Is Required.");
	$val = new Validator($_POST,$rules,$messages);

	if($val->run() == false){
		$_SESSION['error_array'] = array();
		Flash::add("register_errors",$val->get_all_errors());
		Flash::add("form_data",$_POST);
		echo "<script>window.open('index','_self')</script>";
	}else{
		$error_array = array();
		$name = strip_tags($input->post('name'));
		$name = strip_tags($name);
		// $name = ucfirst(strtolower($name));
		
		$name = $name;

		$_SESSION['name']= $name;
		$u_name = strip_tags($input->post('u_name'));
		$u_name = strip_tags($u_name);
		$_SESSION['u_name']= $u_name;
		$email = strip_tags($input->post('email'));
		$email = strip_tags($email);
		$_SESSION['email']=$email;

		$phone = strip_tags($input->post('phone'));
		$phone = strip_tags($phone);
		$_SESSION['phone']=$phone;

		$country_code = strip_tags($input->post('country_code'));
		$country_code = strip_tags($country_code);
		$_SESSION['country_code']=$country_code;

		$phone = $country_code." ".$phone;

		$pass = strip_tags($input->post('pass'));
		$con_pass = strip_tags($input->post('con_pass'));
		$referral = strip_tags($input->post('referral'));
		$geoplugin = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
		$country = $geoplugin['geoplugin_countryName'];
		if(empty($country)){ $country = ""; }
		$regsiter_date = date("F d, Y");
		$date = date("F d, Y");
	
		$check_seller_username = $db->count("sellers",array("seller_user_name" => $u_name));
		$check_seller_email = $db->count("sellers",array("seller_email" => $email));
		if(preg_match('/[اأإء-ي]/ui', $input->post('u_name'))){
		  array_push($error_array, "Foreign characters are not allowed in username, Please try another one.");
		}
		if($check_seller_username > 0 ){
		  array_push($error_array, "Opps! This username has already been taken. Please try another one");
		}

		if(strpbrk($input->post('u_name'), ' ') !== false){
			array_push($error_array, "Spaces Are Not Allowed In Username. Please Remove The Spaces.");
		}

		if($check_seller_email > 0){
		  array_push($error_array, "Email has already been taken. Try logging in instead.");
		}
		if($pass != $con_pass){
     	  array_push($error_array, "Passwords don't match. Please try again.");
		}
    
		if(empty($error_array)){

			$referral_code = mt_rand();
			if($signup_email == "yes"){
				$verification_code = mt_rand();
			}else{
				$verification_code = "ok";
			}
			$encrypted_password = password_hash($pass, PASSWORD_DEFAULT);
			$seller_activity = date("Y-m-d H:i:s");
			
			// This is just an example. In application this will come from Javascript (via an AJAX or something)
			$timezone_offset_minutes = $input->post('timezone');  // $_GET['timezone_offset_minutes']
			// Convert minutes to seconds and get timezone
			$timezone = timezone_name_from_abbr("", $timezone_offset_minutes*60, false);
			
			$insert_seller = $db->insert("sellers",array("seller_name" => $name,"seller_user_name" => $u_name,"seller_email" => $email,"seller_phone" => $phone,"seller_pass" => $encrypted_password,"seller_country"=>$country,"seller_level" => 1,"seller_recent_delivery" => 'none',"seller_rating" => 0,"seller_offers" => 10,"seller_referral" => $referral_code,"seller_ip" => $ip,"seller_verification" => $verification_code,"seller_vacation" => 'off',"seller_register_date" => $regsiter_date,"seller_activity"=>$seller_activity,"seller_timezone"=>$timezone,"seller_status" => 'online'));
					
			$regsiter_seller_id = $db->lastInsertId();
			if($insert_seller){
			  $_SESSION['seller_user_name'] = $u_name;
				$insert_seller_account = $db->insert("seller_accounts",array("seller_id" => $regsiter_seller_id));
				if($paymentGateway == 1){
					$insert_seller_settings = $db->insert("seller_settings",array("seller_id" => $regsiter_seller_id));
				}
				if($insert_seller_account){
					if(!empty($referral)){
				    $sel_seller = $db->select("sellers",array("seller_referral" => $referral));		
						$row_seller = $sel_seller->fetch();
						$seller_id = $row_seller->seller_id;	
						$seller_ip = $row_seller->seller_ip;
						if($seller_ip == $ip){
							echo "<script>alert('You Cannot Referral Yourself To Make Money.');</script>";
						}else{
							$count_referrals = $db->count("referrals",array("ip" => $ip));	
							if($count_referrals == 1){
						    echo "<script>alert('You are trying to referral yourself more then one time.');</script>";
							}else{
								$insert_referral = $db->insert("referrals",array("seller_id" => $seller_id,"referred_id" => $regsiter_seller_id,"comission" => $referral_money,"date" => $date,"ip" => $ip,"status" => 'pending'));
							}
						}	
					}
					if($signup_email == "yes"){
						userSignupEmail($email);
				  	}
					echo "
					<script>
						swal({
							type: 'success',
							text: '".str_replace("{name}",$name,$lang['alert']['successfully_registered'])."',
							timer: 6000,
							onOpen: function(){
								swal.showLoading()
							}
						}).then(function(){
							// Read more about handling dismissals
							window.open('$site_url','_self')
						});
					</script>";
					$_SESSION['name'] = "";
					$_SESSION['u_name']="";
					$_SESSION['email']= "";
					$_SESSION['error_array'] = array();
				}
			}
		}
		if(!empty($error_array)){
			$_SESSION['error_array'] = $error_array;
			echo "
			<script>
				swal({
					type: 'warning',
					html: $('<div>').text('{$lang['alert']['errors']}'),
					animation: false,
					customClass: 'animated tada'
				}).then(function(){
					window.open('index','_self')
				});
			</script>";
		}
	}
}

if(isset($_POST['login'])){
	
	$rules = array(
	"seller_user_name" => "required",
	"seller_pass" => "required"
	);
	$messages = array("seller_user_name" => "Username Is Required.","seller_pass" => "Password Is Required.");

	$val = new Validator($_POST,$rules,$messages);

	if($val->run() == false){
		Flash::add("login_errors",$val->get_all_errors());
		Flash::add("form_data",$_POST);
		echo "<script>window.open('index','_self')</script>";
	}else{

		$seller_user_name = $input->post('seller_user_name');
		$seller_pass = $input->post('seller_pass');

		// $select_seller = $db->query("select * from sellers where seller_user_name=:u_name",array(":u_name"=>$seller_user_name));
		// $select_seller = $db->query("select * from sellers where seller_user_name=:u_name OR seller_email=:u_email",array(":u_name"=>$seller_user_name,":u_email"=>$seller_user_name));

		$select_seller = $db->query("select * from sellers where binary seller_user_name like :u_name OR seller_email=:u_email",array(":u_name"=>$seller_user_name,":u_email"=>$seller_user_name));
		$row_seller = $select_seller->fetch();
		@$hashed_password = $row_seller->seller_pass;
		@$seller_user_name = $row_seller->seller_user_name;
		@$seller_status = $row_seller->seller_status;
		$decrypt_password = password_verify($seller_pass,$hashed_password);
		
		if($decrypt_password == 0){
		
			echo "
			<script>
	        swal({
	          type: 'warning',
	          html: $('<div>').text('{$lang['alert']['incorrect_login']}'),
	          animation: false,
	          customClass: 'animated tada'
	        })
		    </script>";

		}else{
			if($seller_status == "block-ban"){
				echo "
				<script>
	            swal({
	              type: 'warning',
	              html: $('<div>').text('{$lang['alert']['blocked']}'),
	              animation: false,
	              customClass: 'animated tada'
	            })
		    	</script>";
			}elseif($seller_status == "deactivated"){
				echo "
				<script>
					swal({
					  type: 'warning',
					  html: $('<div>').text('{$lang['alert']['deactivated']}'),
					  animation: false,
					  customClass: 'animated tada'
					})
				</script>";
			}else{

				$select_seller = $db->query("select * from sellers where seller_email=:u_email OR seller_user_name=:u_name AND seller_pass=:u_pass",array("u_email"=>$seller_user_name,"u_name"=>$seller_user_name,"u_pass"=>$hashed_password));
	    		$row_seller = $select_seller->fetch();
				if($select_seller){
					
					$_SESSION['sessionStart'] = $row_seller->seller_user_name;
					if(isset($_SESSION['sessionStart']) and $_SESSION['sessionStart'] === $row_seller->seller_user_name){

						$update_seller_status = $db->update("sellers",array("seller_status"=>'online',"seller_ip"=>$ip),array("seller_user_name"=>$row_seller->seller_user_name,"seller_pass"=>$hashed_password));
//						$seller_user_name = ucfirst(strtolower($row_seller->seller_user_name));
						$seller_user_name = ucfirst($row_seller->seller_user_name);
						$url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

						echo "
						<script>
							swal({
								type: 'success',
								text: '".str_replace('{seller_user_name}',$seller_user_name,$lang['alert']['successfully_login'])."',
								timer: 2000,
								onOpen: function(){
									swal.showLoading()
								}
							}).then(function(){
								window.open('$url','_self')
							});
						</script>";
						
					}
				}
			}
	  	}
			
	}

}


if(isset($_POST['forgot'])){
	
	$forgot_email = $input->post('forgot_email');
	$select_seller_email = $db->select("sellers",array("seller_email" => $forgot_email));
	$count_seller_email = $select_seller_email->rowCount();
	if($count_seller_email == 0){
		
		echo "
		<script>
        swal({
          type: 'warning',
          text: '{$lang['alert']['no_email']}',
       })
     	</script>";
		
	}else{
		
		$site_email_address = $row_general_settings->site_email_address;

		$row_seller_email = $select_seller_email->fetch();
		$seller_user_name = $row_seller_email->seller_user_name;
		$seller_pass = $row_seller_email->seller_pass;

		$data = [];
		$data['template'] = "forgot_password";
		$data['to'] = $forgot_email;
		$data['subject'] = "$site_name: Password Reset";
		$data['user_name'] = $seller_user_name;
		$data['forgot_link'] = "$site_url/change_password?username=$seller_user_name&code=$seller_pass";

		if(send_mail($data)){
			echo "
	        <script>
	         swal({
		         type: 'success',
		         text: '{$lang['alert']['successfully_forgot_pass']}',
	         });
	      </script>";
		}

	}
	
}