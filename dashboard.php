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
  $login_seller_rating = $row_login_seller->seller_rating;
  $login_seller_recent_delivery = $row_login_seller->seller_recent_delivery;
  $login_seller_country = $row_login_seller->seller_country;
  $login_seller_register_date = $row_login_seller->seller_register_date;
  $login_seller_image = getImageUrl2("sellers","seller_image",$row_login_seller->seller_image);
  $login_seller_payouts = $row_login_seller->seller_payouts;

  if(empty($login_seller_country)){
    $login_seller_country = "&nbsp;";
  }

  $select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $login_seller_id));
  $row_seller_accounts = $select_seller_accounts->fetch();
  $current_balance = $row_seller_accounts->current_balance;
  $month_earnings = $row_seller_accounts->month_earnings;

  if(isset($_GET['n_id'])){
    $notification_id = $input->get('n_id');
    $get_notification = $db->select("notifications",["notification_id" => $notification_id,"receiver_id" => $login_seller_id]);
    if($get_notification->rowCount() == 0){
      echo "<script>window.open('dashboard','_self')</script>";
    }else{
      $row_notification = $get_notification->fetch();
      $order_id = $row_notification->order_id;
      $reason = $row_notification->reason;
      $update_notification = $db->update("notifications",["status" => 'read'],["notification_id" => $notification_id]);
      if($update_notification){
        if($reason == "modification" or $reason == "approved" or $reason == "declined"){
          echo "<script>window.open('proposals/view_proposals','_self');</script>";
        }else if($reason == "offer"){
          echo "<script>window.open('$site_url/requests/view_offers?request_id=$order_id','_self')</script>";
        }elseif($reason == "approved_request" or $reason == "unapproved_request"){
          echo "<script>window.open('requests/manage_requests','_self');</script>";
        }elseif($reason == "withdrawal_approved" or $reason == "withdrawal_declined"){
          echo "<script>window.open('withdrawal_requests?id=$order_id','_self');</script>";
        }elseif($reason == "referral_approved"){
          echo "<script>window.open('my_referrals','_self');</script>";
        }elseif($reason == "proposal_referral_approved"){
          echo "<script>window.open('proposal_referrals','_self');</script>";
        }elseif($reason == "ticket_reply"){
          echo "<script>window.open('support?view_conversation&ticket_id=$order_id','_self');</script>";
        }else{
          echo "<script>window.open('order_details?order_id=$order_id','_self');</script>";
        }
      }
    }
  }
  
  if(isset($_GET['delete_notification'])){
    $delete_id = $input->get('delete_notification');
    $delete_notification = $db->delete("notifications",['notification_id' => $delete_id,"receiver_id" => $login_seller_id]); 
    if($delete_notification->rowCount() == 1){
      echo "<script>alert('One notification has been deleted.')</script>";
      echo "<script>window.open('dashboard','_self')</script>";
    }else{ 
      echo "<script>window.open('dashboard','_self')</script>"; 
    }
  }

  $select = $db->select("seller_payment_settings",["level_id"=>$login_seller_level]);
  $row = $select->fetch();
  $payout_day = $row->payout_day;
  $payout_time = $row->payout_time;
  $payout_anyday = $row->payout_anyday;
  $payout_date = date("F $payout_day, Y")." $payout_time";
  $payout_date = new DateTime($payout_date);

  $p_date="";
  if(empty($payout_anyday) and $login_seller_payouts == 0 and date("d") <= $payout_day){
    $p_date = $payout_date->format("F d, Y H:i A");
  }else if($login_seller_payouts > 1){
    $interval = new DateInterval('P1M');
    $payout_date->add($interval);
    $p_date = $payout_date->format("F d, Y H:i A");
  }

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
  <title><?= $site_name; ?> - <?= ucfirst($lang["titles"]["dashboard"]); ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?= $site_desc; ?>">
  <meta name="keywords" content="<?= $site_keywords; ?>">
  <meta name="author" content="<?= $site_author; ?>">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
  <link href="styles/bootstrap.css" rel="stylesheet">
  <link href="styles/custom.css" rel="stylesheet">
  <!-- Custom css code from modified in admin panel --->
  <link href="styles/styles.css" rel="stylesheet">
  <link href="styles/user_nav_styles.css" rel="stylesheet">
  <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
  <link href="styles/owl.carousel.css" rel="stylesheet">
  <link href="styles/owl.theme.default.css" rel="stylesheet">
  <link href="styles/sweat_alert.css" rel="stylesheet">
  <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
  <script src="js/ie.js"></script>
  <script type="text/javascript" src="js/sweat_alert.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <?php if(!empty($site_favicon)){ ?>
  <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>
</head>
<body class="is-responsive">
<?php require_once("includes/user_header.php") ?>
<div class="container mt-4 mb-5" style="max-width: 1200px !important;">
<div class="row">
  <div class="col-md-4 <?=($lang_dir == "right" ? 'order-2 order-sm-1':'')?>">
    <?php require_once("includes/dashboard_sidebar.php"); ?>
  </div>
  <div class="col-md-8">
   <div class="card rounded-0">
      <div class="card-body p-0">
        <div class="row p-2">
          <div class="col-lg-3 col-sm-12 text-center pt-2">
            <?php if(!empty($login_seller_image)){ ?>
            <img src="<?= $login_seller_image; ?>" class="rounded-circle img-thumbnail" width="130">
            <?php }else{ ?>
            <img src="user_images/empty-image.png" class="rounded-circle img-thumbnail" width="130">
            <?php } ?>
          </div>
          <div class="col-lg-9 col-sm-12 text-lg-left text-center <?=($lang_dir == "right" ? 'order-1 order-sm-2':'')?>">
            <div class="row mb-2">
              <div class="col-6 col-lg-4 mt-3">
                <h6><i class="fa fa-globe pr-1"></i> <?= $lang["dashboard"]['country']; ?></h6>
                <h6><i class="fa fa-star pr-1"></i> <?= $lang["dashboard"]['positive_rating']; ?></h6>
              </div>
              <div class="col-6 col-lg-8 mt-3">
                <h6 class="text-muted"><?= $login_seller_country; ?></h6>
                <h6 class="text-muted"> <?= $login_seller_rating; ?>%</h6>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-sm-4">
                <h6><i class="fa fa-truck pr-1"></i> <?= $lang["dashboard"]['recent_delivery']; ?></h6>
                <h6><i class="fa fa-clock-o pr-1"></i> <?= $lang["dashboard"]['member_since']; ?></h6>
              </div>
              <div class="col-6 col-lg-8">
                <h6 class="text-muted"><?= $login_seller_recent_delivery; ?></h6>
                <h6 class="text-muted"><?= $login_seller_register_date; ?></h6>
              </div>
            </div>
            <?php if(empty($payout_anyday)){ ?>
            <div class="row mt-2">
              <div class="col-6 col-sm-4">
                <h6><i class="fa fa-money pr-1"></i> <?= $lang["dashboard"]['payout_date']; ?></h6>
              </div>
              <div class="col-6 col-lg-8">
                <h6 class="text-muted"><?= $p_date; ?></h6>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
        <hr>
        <div class="row pl-3 pr-3 pb-2 pt-2 mt-4">
          <div class="col-md-4 text-center border-box">
            <?php
              $count_orders = $db->count("orders",array("seller_id" => $login_seller_id, "order_status" => 'completed'));
              ?>
              <img width="" src="images/comp/completed.png" alt="completed">
            <h5 class="text-muted pt-2"> <?= $lang["dashboard"]['orders_completed']; ?></h5>
            <h3 class="text-success"><?= $count_orders; ?></h3>
          </div>
          <div class="col-md-4 text-center border-box">
            <?php $count_orders = $db->count("orders",array("seller_id"=>$login_seller_id,"order_status"=>'delivered')); ?>
            <img width="" src="images/comp/box.png" alt="box">
            <h5 class="text-muted pt-2"><?= $lang["dashboard"]['delivered_orders']; ?></h5>
            <h3 class="text-success"><?= $count_orders; ?></h3>
          </div>
          <div class="col-md-4 text-center border-box">
            <?php $count_orders = $db->count("orders",array("seller_id"=>$login_seller_id,"order_status"=>'cancelled'));?>
            <img width="" src="images/comp/cancellation.png" alt="cancellation">
            <h5 class="text-muted pt-2"><?= $lang["dashboard"]['orders_cancelled']; ?></h5>
            <h3 class="text-success"><?= $count_orders; ?></h3>
          </div>
        </div>
        <hr>
        <div class="row pl-3 pr-3 pb-2 pt-2">
          <div class="col-md-3 text-center border-box">
            <?php
              $count_orders = $db->count("orders",array("seller_id" => $login_seller_id, "order_active" => 'yes'));
            ?>
            <img width="" src="images/comp/debt.png" alt="debt">
            <h5 class="text-muted pt-2"> <?= $lang["dashboard"]['sales_in_queue']; ?></h5>
            <h3 class="text-success"><?= $count_orders; ?></h3>
          </div>
          <div class="col-md-3 text-center border-box">
            <?php $count_orders = $db->count("orders",array("buyer_id" => $login_seller_id, "order_active" => 'yes')); ?>
              <img width="" src="images/comp/shopping-bags.png" alt="shopping-bags">
            <h5 class="text-muted pt-2"> <?= $lang["dashboard"]['open_purchases']; ?></h5>
            <h3 class="text-success"><?= $count_orders; ?> </h3>
          </div>
          <div class="col-md-3 text-center border-box">
            <img width="" src="images/comp/accounting.png" alt="accounting">
            <h5 class="text-muted pt-2"> <?= $lang["dashboard"]['balance']; ?></h5>
            <h3 class="text-success"><?= showPrice($current_balance); ?></h3>
          </div>
          <div class="col-md-3 text-center border-box">
            <img width="" src="images/comp/financial.png" alt="financial">
            <h5 class="text-muted pt-2"> <?= $lang["dashboard"]['earnings']; ?> </h5>
            <h3 class="text-success"><?= showPrice($month_earnings); ?></h3>
          </div>
        </div>
      </div>
    </div>
    <div class="card rounded-0 mt-3 bottom-tabs-dash">
      <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
          <li class="nav-item">
            <?php $count_notifications = $db->count("notifications",array("receiver_id" => $login_seller_id));?>
            <a href="#notifications" data-toggle="tab" class="nav-link make-black active">
            <?= $lang['menu']['notifications']; ?> <span class="badge badge-success"><?= $count_notifications; ?> </span>
            </a>
          </li>
          <li class="nav-item">
            <?php
              $select_all_inbox_sellers = $db->query("select * from inbox_sellers where (receiver_id='$login_seller_id' or sender_id='$login_seller_id') AND NOT message_status='empty'");
              $count_all_inbox_sellers = $select_all_inbox_sellers->rowCount();
            ?>
            <a href="#inbox" data-toggle="tab" class="nav-link make-black">
            <?= $lang['menu']['messages']; ?> <span class="badge badge-success"><?= $count_all_inbox_sellers; ?></span>
            </a>
          </li>
        </ul>
      </div>
      <div class="card-body p-0">
        <div class="tab-content dashboard">
          <div id="notifications" class="tab-pane fade show active mt-3">
            <?php
              if($count_notifications == 0){
                echo "<h5 class='text-center mb-3'> {$lang["dashboard"]['no_notifications']} </h5>";
              }
              
              $get_notifications = $db->query("select * from notifications where receiver_id='$login_seller_id' order by 1 DESC limit 0,5");
              while($row_notifications = $get_notifications->fetch()){
              $notification_id = $row_notifications->notification_id;
              $sender_id = $row_notifications->sender_id;
              $order_id = $row_notifications->order_id;
              $reason = $row_notifications->reason;
              $date = $row_notifications->date;
              $status = $row_notifications->status;

              // Select Sender Details
              $select_sender = $db->select("sellers",array("seller_id" => $sender_id));
              $row_sender = $select_sender->fetch();
              $sender_user_name = @$row_sender->seller_user_name;
              $sender_image = @getImageUrl2("sellers","seller_image",$row_sender->seller_image);

              if(strpos($sender_id,'admin') !== false){
                $admin_id = trim($sender_id, "admin_");
                $sender_user_name = "Admin";
                $get_admin = $db->select("admins",array("admin_id" => $admin_id));
                $sender_image = @getImageUrl("admins",$get_admin->fetch()->admin_image);
              }

            ?>
            <div class="<?php if($status == "unread"){ echo "header-message-div-unread"; }else{ echo "header-message-div"; } ?>">
              <a href="dashboard?delete_notification=<?= $notification_id; ?>" class="float-right delete text-danger">
              <i class="fa fa-times-circle fa-lg"></i>	
              </a>
              <a href="dashboard?n_id=<?= $notification_id; ?>">
                <?php if(!empty($sender_image)){ ?>
                <?php if(strpos($sender_id, "admin_") !== false){ ?>
                  <img src="<?= $sender_image; ?>" width="50" height="50" class="rounded-circle">
                <?php }else{ ?>
                  <img src="<?= $sender_image; ?>" width="50" height="50" class="rounded-circle">
                <?php } ?>
                <?php }else{ ?>
                <img src="user_images/empty-image.png" width="50" height="50" class="rounded-circle">
                <?php } ?>
                <strong class="heading"><?= $sender_user_name; ?></strong>
                <p class="message"><?= include("includes/comp/notification_reasons.php"); ?></p>
                <p class="date text-muted"> <?= $date; ?></p>
              </a>
            </div>
            <?php } ?>
            <?php if($count_notifications > 0){ ?>
            <div class="p-3">
              <a href="<?= $site_url; ?>/notifications" class="btn btn-success btn-block">
              <?= $lang['see_all']; ?>
              </a>
            </div>
            <?php } ?>
          </div>
          <div id="inbox" class="tab-pane fade mt-3">
            <?php
            
            if($count_all_inbox_sellers == 0){
              echo "<h5 class='text-center mb-3'> {$lang["dashboard"]['no_messages']} </h5>";
            }

            $select_inbox_sellers = $db->query("select * from inbox_sellers where (receiver_id='$login_seller_id' or sender_id='$login_seller_id') AND NOT message_status='empty' order by 1 DESC LIMIT 0,4");
            while($row_inbox_sellers = $select_inbox_sellers->fetch()){

            $inbox_seller_id = $row_inbox_sellers->inbox_seller_id;
            $message_group_id = $row_inbox_sellers->message_group_id;
            $sender_id = $row_inbox_sellers->sender_id;
            $receiver_id = $row_inbox_sellers->receiver_id;
            $message_id = $row_inbox_sellers->message_id;

            /// Ids
            if($login_seller_id == $sender_id){
            $sender_id = $receiver_id;
            }else{
            $sender_id = $sender_id;
            }

            /// Select Sender Information
            $select_sender = $db->select("sellers",array("seller_id" => $sender_id));
            $row_sender = $select_sender->fetch();
            $sender_user_name = $row_sender->seller_user_name;
            $sender_image = getImageUrl2("sellers","seller_image",$row_sender->seller_image);

            $select_inbox_message = $db->select("inbox_messages",array("message_id" => $message_id));
            $row_inbox_message = $select_inbox_message->fetch();
            $message_desc = strip_tags($row_inbox_message->message_desc);
            $message_date = $row_inbox_message->message_date;
            $message_status = $row_inbox_message->message_status;

            if($message_desc == ""){
              $message_desc = "Sent you an offer";
            }

            if($message_status == 'unread'){ 
              if($login_seller_id == $receiver_id){
                $msgClass = "header-message-div-unread"; 
              }else{ 
                $msgClass = "header-message-div"; 
              } 
            }else{ 
              $msgClass = "header-message-div"; 
            }

            ?>
            <div class="<?= $msgClass; ?>"> 
              <a href="conversations/inbox?single_message_id=<?= $message_group_id; ?>">
                <?php if(!empty($sender_image)){ ?>
                <img src="<?= $sender_image; ?>" width="50" height="50" class="rounded-circle">
                <?php }else{ ?>
                <img src="user_images/empty-image.png" width="50" height="50" class="rounded-circle">
                <?php } ?>
                <strong class="heading"><?= $sender_user_name; ?></strong>
                <p class="message text-truncate"><?= $message_desc; ?></p>
                <p class="text-muted date"><?= $message_date; ?></p>
              </a>
            </div>
            <?php } ?>
            <?php if($count_all_inbox_sellers > 0){ ?>
            <div class="p-3">
              <a href="<?= $site_url; ?>/conversations/inbox" class="btn btn-success btn-block">
              <?= $lang['see_all']; ?>
              </a>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php require_once("includes/footer.php"); ?>
</body>
</html>