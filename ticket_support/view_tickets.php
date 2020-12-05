<?php  
  if(isset($_SESSION['seller_user_name'])){
    $login_seller_user_name = $_SESSION['seller_user_name'];
    $select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
    $row_login_seller = $select_login_seller->fetch();
    $login_seller_id = $row_login_seller->seller_id;
    $login_seller_email = $row_login_seller->seller_email;
    $login_seller_user_name = $row_login_seller->seller_user_name;
  }
  
  $query = "select * from support_tickets where sender_id = $login_seller_id order by 1 DESC";
  $stmt = $db->query($query);
  $support_tickets = $stmt->fetchAll();
  
  if($lang_dir == "right"){
    $floatRight = "float-right";
  }else{
    $floatRight = "float-left";
  }

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
  <title><?= $site_name; ?> - <?= $lang["titles"]["customer_support"]; ?></title>
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
  <link href="styles/categories_nav_styles.css" rel="stylesheet">
  <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
  <link href="styles/sweat_alert.css" rel="stylesheet">
  <script type="text/javascript" src="js/ie.js"></script>
  <script type="text/javascript" src="js/sweat_alert.js"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/sweat_alert.js"></script>
  <?php if(!empty($site_favicon)){ ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>
</head>
<body class="is-responsive">

  <?php require_once("includes/header.php"); ?>

  <div class="container pb-4"><!-- Container starts -->

    <div class="row customer-support mt-4" style="<?=($lang_dir == "right" ? 'direction: rtl;':'')?>">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header text-center make-white">
          <h2>My Tickets List</h2>          
          </div>
          <div class="card-body">            
            <div class="table-responsive">
              <table class="table table-bordered table-hover tickets-list">
                <thead>
                  <tr>                    
                    <th><?= $lang['th']['ticket_number']; ?></th>
                    <th><?= $lang['th']['subject']; ?></th>
                    <th><?= $lang['th']['message']; ?></th>
                    <th><?= $lang['th']['order_number']; ?></th>
                    <th><?= $lang['th']['rule']; ?></th>
                    <th><?= $lang['th']['status']; ?></th>
                    <th><?= $lang['th']['action']; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($support_tickets as $ticket):
                    $message = strlen($ticket->message) > 100 ? substr($ticket->message, 0, 50).'....': $ticket->message;
                  ?>
                    <tr>
                      <td>#<?= $ticket->number; ?></td>
                      <td><?= $ticket->subject; ?></td>
                      <td><?= $message; ?></td>
                      <td><?= $ticket->order_number; ?></td>
                      <td><?= $ticket->order_rule; ?></td>
                      <td><?= ucfirst($ticket->status); ?></td>
                      <td><a class="btn btn-success" href="<?= $site_url."/support?view_conversation&ticket_id=".$ticket->ticket_id; ?>">View</a></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- Container ends -->

<?php require_once("includes/footer.php"); ?>

</body>
</html>