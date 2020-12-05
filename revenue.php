<?php
session_start();
require_once("includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('login','_self')</script>";
}
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_level = $row_login_seller->seller_level;

$login_seller_paypal_email = $row_login_seller->seller_paypal_email;
$login_seller_payoneer_email = $row_login_seller->seller_payoneer_email;
$login_seller_account_number = $row_login_seller->seller_m_account_number;
$login_seller_account_name = $row_login_seller->seller_m_account_name;
$login_seller_wallet = $row_login_seller->seller_wallet;
$seller_payouts = $row_login_seller->seller_payouts;

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$withdrawal_limit = $row_payment_settings->withdrawal_limit;
$enable_paypal = $row_payment_settings->enable_paypal;
$enable_stripe = $row_payment_settings->enable_stripe;
$enable_dusupay = $row_payment_settings->enable_dusupay;
$enable_coinpayments = $row_payment_settings->enable_coinpayments;
$enable_paystack = $row_payment_settings->enable_paystack;
$enable_payoneer = $row_payment_settings->enable_payoneer;
$wish_do_manual_payouts = $row_general_settings->wish_do_manual_payouts;

if($paymentGateway == 1){
	$enable_bank_transfer = $row_payment_settings->enable_bank_transfer;
	$enable_moneygram = $row_payment_settings->enable_moneygram;
}else{
	$enable_bank_transfer = "no";
	$enable_moneygram = "no";
}

$select = $db->select("seller_payment_settings",["level_id"=>$login_seller_level]);;
$row = $select->fetch();
$payout_day = $row->payout_day;
$payout_time = $row->payout_time;
$payout_anyday = $row->payout_anyday;
$payout_date = date("F $payout_day, Y")." $payout_time";
$payout_date = new DateTime($payout_date);
$count_payout = 0;

$select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $login_seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$current_balance = $row_seller_accounts->current_balance;
$used_purchases = $row_seller_accounts->used_purchases;
$pending_clearance = $row_seller_accounts->pending_clearance;
$withdrawn = $row_seller_accounts->withdrawn;

if($current_balance < $withdrawal_limit AND empty($payout_anyday) & $seller_payouts == 0){
$withdrawLimitText = <<<EOT
onclick="return alert('You must have a minimum of at least $s_currency$withdrawal_limit to withdraw.');"
EOT;
}else{
$withdrawLimitText = "";
}
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
	<title><?= $site_name; ?> - Revenue Earned</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= $site_desc; ?>">
	<meta name="keywords" content="<?= $site_keywords; ?>">
	<meta name="author" content="<?= $site_author; ?>">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
	<link href="styles/bootstrap.css" rel="stylesheet">
  <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link href="styles/styles.css" rel="stylesheet">
	<link href="styles/user_nav_styles.css" rel="stylesheet">
	<link href="font_awesome/css/font-awesome.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<?php if(!empty($site_favicon)){ ?>
  <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>
</head>
<body class="is-responsive">
<?php require_once("includes/user_header.php"); ?>
   <div class="container mt-5 mb-3">
   	<div class="row">
   		<div class="col-md-12">
			<h2 class="pull-left"><?= $lang["titles"]["revenue"]; ?></h2>
			<p class="lead pull-right">
			Available For Withdrawal: <span class="font-weight-bold text-success"> <?= showPrice($current_balance); ?> </span>
			</p>
		</div>
		<div class="col-md-12">

		<?php if($current_balance != 0 And empty($payout_anyday) And $seller_payouts == 0 And date("d") <= $payout_day){ ?>
		<div class="alert alert-success mt-2 text-center h6"> <!-- Alert starts -->
		<i class="fa fa-exclamation-circle"></i> <?php printf($lang["availRevenue"],$s_currency.$current_balance,$payout_date->format("F d, Y H:i A")); ?>
		</div> <!-- Alert ends -->
		<?php }else if($seller_payouts > 0 And empty($payout_anyday)){ ?>
		<div class="alert alert-success mt-2 text-center h6"> <!-- Alert starts -->
		<i class="fa fa-exclamation-circle"></i>
		You will be able to withdraw or send next payout request of (<?= $s_currency.$current_balance; ?>) on the 
		<?php
		$interval = new DateInterval('P1M');
		$payout_date->add($interval);
		echo $payout_date->format("F d, Y H:i A");
		?>
		</div> <!-- Alert ends -->
		<?php } ?>

			<div class="card mb-3 rounded-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-3 text-center border-box">
							<p> Withdrawals </p>
							<h2> <?= showPrice($withdrawn); ?></h2>
						</div>
						<div class="col-md-3 text-center border-box">
							<p> Used To Order Proposals/Services </p>
							<h2> <?= showPrice($used_purchases); ?></h2>
						</div>
						<div class="col-md-3 text-center border-box">
							<p> Pending Clearance </p>
							<h2> <?= showPrice($pending_clearance); ?></h2>
						</div>
						<div class="col-md-3 text-center border-box">
							<p> Available Income </p>
							<h2> <?= showPrice($current_balance); ?></h2>
						</div>
					</div>
				</div>
			</div>

			<?php if(($current_balance >= $withdrawal_limit AND !empty($payout_anyday)) OR ($current_balance >= $withdrawal_limit AND empty($payout_anyday) AND $seller_payouts == 0 & date("d") >= $payout_day)){ ?>

			<label class="lead pull-left mt-1"> Withdraw To: </label>
			<?php if($enable_paypal == "yes"){ ?>
			<button class="btn btn-success ml-2" data-toggle="modal" data-target="#paypal_withdraw_modal">
				<i class="fa fa-paypal"></i> Paypal Account
			</button>
			<?php } ?>
			<?php if($enable_payoneer == 1) { ?>
			<button class="btn btn-success ml-2" data-toggle="modal" data-target="#payoneer_withdraw_modal">
				<i class="fa fa-paper-plane-o"></i> Payoneer Account
			</button>
			<?php } ?>
			<?php if($enable_bank_transfer == "yes"){ ?>
			<button class="btn btn-success ml-2" data-toggle="modal" data-target="#bank_account_modal">
				<i class="fa fa-university"></i> Bank Account
			</button>
			<?php } ?>
			<?php if($enable_moneygram == "yes"){ ?>
			<button class="btn btn-success ml-2" data-toggle="modal" data-target="#moneygram_modal">
				<i class="fa fa-credit-card"></i> Moneygram
			</button>
			<?php } ?>
			<?php if($enable_dusupay == "yes"){ ?>
			<button class="btn btn-success ml-2" data-toggle="modal" data-target="#mobile_money_modal">
				<i class="fa fa-mobile"></i> Mobile Money 
			</button>
			<?php } ?>
			<?php if($enable_coinpayments == "yes"){ ?>
			<button class="btn btn-success ml-2" data-toggle="modal" data-target="#trx_wallet_modal">
				<i class="fa fa-bitcoin"></i> Bitcoin Wallet 
			</button>
			<?php } ?>

			<?php }else{ ?>

			<label class="lead pull-left mt-1"> Withdraw To: </label>
			<?php if($enable_paypal == "yes"){ ?>
			<button class="btn btn-default ml-2" <?= $withdrawLimitText; ?>>
			<i class="fa fa-paypal"></i> Paypal Account
			</button>
			<?php } ?>
			<?php if($wish_do_manual_payouts == 1 & $enable_payoneer == 1) { ?>
			<button class="btn btn-default ml-2" <?= $withdrawLimitText; ?>>
			<i class="fa fa-paypal"></i> Payoneer Account
			</button>
			<?php } ?>
			
			<?php if($enable_bank_transfer == "yes"){ ?>
			<button class="btn btn-default ml-2" <?= $withdrawLimitText; ?>>
				<i class="fa fa-university"></i> Bank Account
			</button>
			<?php } ?>

			<?php if($enable_moneygram == "yes"){ ?>
			<button class="btn btn-default ml-2" <?= $withdrawLimitText; ?>>
				<i class="fa fa-credit-card"></i> Moneygram
			</button>
			<?php } ?>

			<?php if($enable_dusupay == "yes"){ ?>
			<button class="btn btn-default ml-2" <?= $withdrawLimitText; ?>>
				<i class="fa fa-mobile"></i> Mobile Money 
			</button>
			<?php } ?>
			<?php if($enable_coinpayments == "yes"){ ?>
			<button class="btn btn-default ml-2" <?= $withdrawLimitText; ?>>
				<i class="fa fa-bitcoin"></i> Bitcoin Wallet 
			</button>
			<?php } ?>

			<?php } ?>

			<div class="table-responsive box-table mt-4">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><?= $lang['th']['date']; ?></th>
							<th><?= $lang['th']['for']; ?></th>
							<th><?= $lang['th']['amount']; ?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					$get_revenues = $db->select("revenues",array("seller_id"=>$login_seller_id),"DESC");
					while($row_revenues = $get_revenues->fetch()){
					$revenue_id = $row_revenues->revenue_id;
					$order_id = $row_revenues->order_id;
					$reason = $row_revenues->reason;
					$amount = $row_revenues->amount;
					$date = $row_revenues->date;
					$status = $row_revenues->status;

					if($reason == "order"){
						$text = "Order Revenue";
					}else{
						$text = "Order Tip Revenue";
					}

					?>
					<tr>
						<td><?= $date; ?></td>
						<td> 
							<?php if($status == "pending"){ ?>
								<?= $text; ?> Pending Clearance (<a href="order_details?order_id=<?= $order_id; ?>" target="blank" class="text-success"> View Order </a>)
							<?php }else{ ?>                             
								<?= $text; ?> (<a href="order_details?order_id=<?= $order_id; ?>" target="blank" class="text-success"> View Order </a>)
							<?php } ?>
						</td>
						<td class="text-success"> +<?= showPrice($amount); ?> </td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
			  </div>
		  </div>
   	 </div>
   </div>
	
    <div id="paypal_withdraw_modal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"> Withdraw/Transfer Funds To PayPal </h5>
						<button class="close" data-dismiss="modal"><span> &times; </span></button>
					</div>
					<div class="modal-body"><!-- modal-body Starts -->
						<center><!-- center Starts -->
                     <?php if(empty($login_seller_paypal_email)){ ?>
							<p class="lead">
								In order to transfer funds to your PayPal account, you will need to add your PayPal email in your
								<a href="<?= $site_url; ?>/settings?account_settings" class="text-success">account settings</a> tab.
							</p>
                     <?php }else{ ?>
							<p class="lead">
								Your revenue funds will be transferred to: 
								<br> <strong> <?= $login_seller_paypal_email; ?> </strong>
							</p>
							<form action="<?= ($wish_do_manual_payouts == 1)?"withdraw_manual":"paypal_adaptive"; ?>" method="post">
								<input type="hidden" name="method" value="paypal">
								<div class="form-group row">
									<label class="col-md-3 col-form-label font-weight-bold">Amount</label>
									<div class="col-md-8">
										<div class="input-group">
											<span class="input-group-addon font-weight-bold"> $ </span>
											<input type="number" name="amount" class="form-control input-lg" min="<?= $withdrawal_limit; ?>" max="<?= $current_balance; ?>" placeholder="<?= $withdrawal_limit; ?> Minimum" required >
										</div>
								    </div>
								</div>
								<div class="form-group row">
									<div class="col-md-8 offset-md-3">
									  <input type="submit" name="withdraw" value="Transfer" class="btn btn-success form-control" >
									</div>
								</div>
							</form>
                     <?php } ?>
						</center>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
       </div>

       <div id="payoneer_withdraw_modal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"> Withdraw/Transfer Funds To Payoneer </h5>
						<button class="close" data-dismiss="modal"><span>&times;</span></button>
					</div>
					<div class="modal-body"><!-- modal-body Starts -->
						<center><!-- center Starts -->
                        <?php if(empty($login_seller_payoneer_email)){ ?>
							<p class="lead">
								In order to transfer funds to your Payoneer account, you will need to add your payoneer email in your
								<a href="<?= $site_url; ?>/settings?account_settings" class="text-success">account settings</a> tab.
							</p>
                            <?php }else{ ?>
							<p class="lead">
								Your revenue funds will be transferred to : <br> <strong><?= $login_seller_payoneer_email; ?></strong>
							</p>
							<form action="withdraw_manual" method="post">
								<input type="hidden" name="method" value="payoneer">
								<div class="form-group row">
									<label class="col-md-3 col-form-label font-weight-bold">Amount</label>
									<div class="col-md-8">
										<div class="input-group">
											<span class="input-group-addon font-weight-bold"> $ </span>
											<input type="number" name="amount" class="form-control input-lg" min="<?= $withdrawal_limit; ?>" max="<?= $current_balance; ?>" placeholder="<?= $withdrawal_limit; ?> Minimum" required >
										</div>
								    </div>
								</div>
								<div class="form-group row">
									<div class="col-md-8 offset-md-3">
									  <input type="submit" name="withdraw" value="Transfer" class="btn btn-success form-control" >
									</div>
								</div>
							</form>
                            <?php } ?>
						</center>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
       </div>

		<?php
		if($paymentGateway == 1){ 
			include("plugins/paymentGateway/revenueModals.php");
		}
		?>

		<div id="mobile_money_modal" class="modal fade"><!-- mobile_money_modal modal fade Starts -->
		<div class="modal-dialog"><!-- modal-dialog Starts -->
		<div class="modal-content"><!-- modal-content Starts -->
		<div class="modal-header"><!-- modal-header Starts -->
		<h5 class="modal-title"> Withdraw To Mobile Money Account </h5>
		<button type="button" class="close" data-dismiss="modal">
		<span>&times;</span>
		</button>
		</div><!-- modal-header Ends -->
		<div class="modal-body text-center"><!-- modal-body Starts -->
		<?php if(empty($login_seller_account_number) or empty($login_seller_account_name)){ ?>
		<p class="modal-lead">
		For Withdraw Payments To Your Mobile Money Account Please Add Your Mobile Money Account Details In <a href="#" id="settings-link">Settings Tab</a>
		</p>
		<?php }else{ ?>
		<p class="modal-lead">
		Your Payments Will Be Sent To Following Mobile Money Account:
		<p class="mb-1"> <strong> Account Number: </strong> <?= $login_seller_account_number; ?> </p>
		<p> <strong> Account/Owner Name: </strong> <?= $login_seller_account_name; ?> </p>
		</p>
		<form action="<?= ($wish_do_manual_payouts == 1)?"withdraw_manual":"withdraw"; ?>" method="post"><!-- withdraw form Starts -->
		<input type="hidden" name="method" value="dusupay">
		<div class="form-group row"><!-- form-group Starts -->
		<label class="col-md-3 col-form-label"> Amount: </label>
		<div class="col-md-8">
		<div class="input-group">
		<span class="input-group-addon font-weight-bold"> $ </span>
		<input type="number" name="amount" class="form-control" min="<?= $withdrawal_limit; ?>" max="<?= $current_balance; ?>" placeholder="<?= $withdrawal_limit; ?> Minimum" required>
		<input type="hidden" name="withdraw_method" value="mobile_money">
		</div>
		</div>
		</div><!-- form-group Ends -->
		<div class="form-group row"><!-- form-group Starts -->
		<div class="col-md-8 offset-md-3">
		<input type="submit" name="withdraw" value="Withdraw" class="btn btn-success form-control">
		</div>
		</div><!-- form-group Ends -->
		</form><!-- withdraw form Ends -->
		<?php } ?>
		</div><!-- modal-body Ends -->
		</div><!-- modal-content Ends -->
		</div><!-- modal-dialog Ends -->
		</div><!-- mobile_money_modal modal fade Ends -->


	<div id="trx_wallet_modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			<h5 class="modal-title"> Withdraw/Transfer Funds To Bitcoin Wallet </h5>
			<button class="close" data-dismiss="modal"><span>&times;</span></button>
			</div>
			<div class="modal-body"><!-- modal-body Starts -->
			<center><!-- center Starts -->
				<?php if(empty($login_seller_wallet)){ ?>
				<p class="lead">
				In order to transfer funds to your bitcoin wallet, you will need to add your wallet address in your
				<a href="<?= $site_url; ?>/settings?account_settings" class="text-success">
				account settings 
				</a>
				tab.
				</p>
				<?php }else{ ?>
				<p class="lead">
				Your revenue funds will be transferred to:
				<br> <strong> <?= $login_seller_wallet; ?> </strong>
				</p>
				<form action="<?= ($wish_do_manual_payouts == 1)?"withdraw_manual":"withdraw_wallet"; ?>" method="post">
					<input type="hidden" name="method" value="bitcoin wallet">
					<div class="form-group row">
					<label class="col-md-3 col-form-label font-weight-bold">Amount</label>
						<div class="col-md-8">
							<div class="input-group">
							<span class="input-group-addon font-weight-bold"> $ </span>
							<input type="number" name="amount" class="form-control input-lg" min="<?= $withdrawal_limit; ?>" max="<?= $current_balance; ?>" placeholder="<?= $withdrawal_limit; ?> Minimum" required >
							</div>
					    </div>
					</div>
					<div class="form-group row">
						<div class="col-md-8 offset-md-3">
						 <input type="submit" name="withdraw" value="Transfer" class="btn btn-success form-control">
						</div>
					</div>
			 	</form>
      		<?php } ?>
			</center>
			</div>
			<div class="modal-footer">
			<button class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php require_once("includes/footer.php"); ?>
</body>
</html>