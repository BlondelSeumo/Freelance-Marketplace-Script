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

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
<title><?= $site_name; ?> - All Your Purchases.</title>
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
<div class="container">
  <div class="row">
    <div class="col-md-12 mt-5">
      <h2 class="mb-5 <?=($lang_dir == "right" ? 'text-right':'')?>"> <?= $lang["titles"]["purchases"]; ?> </h2>
      <div class="table-responsive box-table">
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
        $get_purchases = $db->select("purchases",array("seller_id" => $login_seller_id),"DESC");
        $count_purchases = $get_purchases->rowCount();
        while($row_purchases = $get_purchases->fetch()){
        $order_id = $row_purchases->order_id;
        $reason = $row_purchases->reason;
        $amount = $row_purchases->amount;
        $date = $row_purchases->date;
        $method = $row_purchases->method;
        if($reason == "featured_listing" or $method == "featured_proposal_declined") {
          $select_proposals = $db->select("proposals",array("proposal_id"=>$order_id));
          $row_proposals = $select_proposals->fetch();
          $proposal_title = $row_proposals->proposal_title;
          $proposal_url = $row_proposals->proposal_url;
        }

        if($reason == "order"){
          $text = "Proposal/Service purchased with <b>".ucwords(str_replace("_"," ",$method))."</b>";
          $link = "(<a target='_blank' href='order_details?order_id=$order_id' class='text-success'>View Order</a>)";
        }elseif($reason == "order_tip"){
          $text = "Order Tip Payment with <b>".ucwords(str_replace("_"," ",$method))."</b>";
          $link = "(<a target='_blank' href='order_details?order_id=$order_id' class='text-success'>View Order</a>)";
        }elseif($reason == "featured_listing"){
          $text = "Featured Listing Payment with <b>".ucwords(str_replace("_"," ",$method))."</b>";
          $link = "(<a target='_blank' href='proposals/$login_seller_user_name/$proposal_url' class='text-success'>View Proposal</a>)";
        }

        ?>
        <tr>
          <td> <?= $date; ?> </td>
          <td> 
            <?php if($method == "featured_proposal_declined"){ ?>
            
              Your featured proposal is declined so its feature listing fee is refunded to your shopping balance.
              (<a href="<?= $site_url; ?>/view_proposals.php" class="text-success"> View Proposals </a>)
            
            <?php }elseif($method == "order_cancellation"){ ?>
            
              Canceled order payment refunded to your shopping  balance
            
            <?php }else{ ?>
              
              <?= "$text $link"; ?>

            <?php } ?>

          </td>
          <td class="text-danger"> 
          <?php 
            if($method == "order_cancellation" or $method == "featured_proposal_declined"){
            echo "<span class='text-success'>+$s_currency$amount.00</span>";
            }else{
            echo "-$s_currency$amount.00";
            }
          ?> 
          </td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
        <?php
        if($count_purchases == 0){
          echo "<center>
          <h3 class='pb-4 pt-4'>
            <i class='fa fa-meh-o'></i> {$lang['purchases']['no_purchases']} 
          </h3>
          </center>";
        }
        ?>
      </div>
    </div>
  </div>
</div>
<?php require_once("includes/footer.php"); ?>
</body>
</html>