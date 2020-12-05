<?php  
  
if(isset($_SESSION['seller_user_name'])){
    $login_seller_user_name = $_SESSION['seller_user_name'];
    $select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
    $row_login_seller = $select_login_seller->fetch();
    $login_seller_id = $row_login_seller->seller_id;
    $login_seller_email = $row_login_seller->seller_email;
    $login_seller_user_name = $row_login_seller->seller_user_name;
}

$ticket_id = $input->get('ticket_id');

$stmt = $db->select("support_tickets",["ticket_id"=>$ticket_id]);
$support_ticket = $stmt->fetch();

$stmt = $db->select("support_conversations",["ticket_id"=>$ticket_id]);
$support_conversations = $stmt->fetchAll();

if($lang_dir == "right"){
    $floatRight = "float-right";
}else{
    $floatRight = "float-left";
}

if(isset($_POST['new_message']) && !empty($_POST['new_message'])){   

    $new_message = $input->post('new_message');

    $file = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];

    $allowed = array('jpeg','jpg','gif','png','tif','avi','mpeg','mpg','mov','rm','3gp','flv','mp4','zip','rar','mp3','wav','docx','csv','xls','xlsx','pptx','pdf','txt','psd','xd','txt');

    $file_extension = pathinfo($file, PATHINFO_EXTENSION);

    if(!in_array($file_extension,$allowed) & !empty($file)){
      echo "<script>alert('{$lang['alert']['extension_not_supported']}')</script>";
    }else{

        if(!empty($file)){
          $file = pathinfo($file, PATHINFO_FILENAME);
          $file = $file."_".time().".$file_extension";
          uploadToS3("ticket_files/$file",$file_tmp);
        }else{
          $file = "";
        }

        $isS3 = $enable_s3;

        $date = date("h:i M d, Y");
        $insert_support_ticket = $db->insert("support_conversations",array("ticket_id" => $ticket_id, "admin_id" => 0, "sender_id" => $login_seller_id,"message" => $new_message,"attachment" => $file,"date" => $date));
        if($insert_support_ticket){          

           echo "<script>
              alert('Message submitted successfully.');
              window.open('support?view_conversation&ticket_id=$ticket_id','_self')
            </script>";

        }
    }
}

$allowed = array('jpeg','jpg','gif','png');

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

      <div class="col-md-4"><!--- col-md-4 Starts --->

        <div class="card">

          <div class="card-header text-center make-white">
            <h3><?= $lang['single_ticket']['ticket_details']; ?></h3>          
          </div>
          <div class="card-body">
            <div class="widget-content-expanded">

                <p class="lead">
                    <span class="font-weight-bold"> <?= $lang['single_ticket']['ticket_number']; ?> </span> 
                    <small>#<?= $support_ticket->number; ?></small>
                </p>

                <p class="lead">
                    <span class="font-weight-bold"> <?= $lang['single_ticket']['subject']; ?> </span> 
                    <small><?= $support_ticket->subject; ?></small>
                </p>

                <p class="lead">
                    <span class="font-weight-bold"> <?= $lang['single_ticket']['message']; ?> </span> 
                    <small><?= $support_ticket->message; ?></small>
                </p>

                <p class="lead">
                    <span class="font-weight-bold"> <?= $lang['single_ticket']['order_number']; ?> </span> 
                    #<small><?= $support_ticket->order_number; ?></small>
                </p>

                <p class="lead">
                    <span class="font-weight-bold"> <?= $lang['single_ticket']['order_rule']; ?> </span> 
                    <small><?= $support_ticket->order_rule; ?></small>
                </p>

                <p class="lead">
                    <span class="font-weight-bold"> <?= $lang['single_ticket']['status']; ?> </span> 
                    <small style="font-weight: 900;"><?= ucfirst($support_ticket->status); ?></small>
                </p>

            </div>

          </div>
        </div>

      </div><!--- col-md-4 Ends --->

      <div class="col-md-8"><!--- col-md-8 Starts --->
        <div class="card">
          <div class="card-header text-center make-white">
            <h3><?= $lang['single_ticket']['all_conversation']; ?><?= $support_ticket->number; ?></h3>
          </div>
          <div class="card-body">

            <?php if(empty($support_conversations)){?>
                <center><?= $lang['single_ticket']['no_conversation']; ?></center>
            <?php } ?>

            <?php 

            foreach($support_conversations as $conversation){ 

                if(!empty($conversation->admin_id)){
                    $admin = $db->query("select admin_image,admin_user_name from admins where admin_id = ".$conversation->admin_id)->fetch();
                    $sender_user_name = $admin->admin_user_name;
                    $sender_image = getImageUrl("admins",$admin->admin_image);
                }else{
                    $select_sender = $db->select("sellers",array("seller_id" => $conversation->sender_id));
                    $row_sender = $select_sender->fetch();
                    $sender_user_name = $row_sender->seller_user_name;
                    $sender_image = getImageUrl2("sellers","seller_image",$row_sender->seller_image);
                }

            ?>
                <div class="message-div mb-3">
                    <img src="<?= $sender_image; ?>" class="message-image img-responsive img-rounded" width="70px" height="70px">                
                    <h5 class="mb-1"><?= $sender_user_name; ?></h5>
                    <p class="message-desc mb-2">
                        <?= $conversation->message; ?>
                    </p>
                    <p class="message-desc">

                    <?php if(!empty($conversation->attachment)){ ?>
                        
                    <?php if(in_array(pathinfo($conversation->attachment,PATHINFO_EXTENSION),$allowed)){ ?>
                        <img src="<?= getImageUrl("support_conversations",$conversation->attachment); ?>" class="img-thumbnail" width="100"/>
                    <?php } ?>

                    <a href="<?= getImageUrl("support_conversations",$conversation->attachment); ?>" target="_blank" class="d-block mt-2">
                        <i class="fa fa-download"></i> <?= $conversation->attachment; ?>
                    </a>

                    <?php } ?>

                    </p>
                    <p class="text-muted text-right mb-0"><?= $conversation->date; ?></p>
                </div>
                <?php } ?>
              </div><!--- card-body Ends --->
            </div><!--- card Ends --->

            <?php if($support_ticket->status == 'open'){ ?>
                <div class="card mt-3"><!--- card Starts --->            
                    <div class="card-body"><!--- card-body Starts --->
                        <form id="new_message" method="post" enctype="multipart/form-data">
                            <div class="form-group"><!--- form-group Starts --->                        
                                <label class="col-form-label-lg">
                                  <?= $lang['single_ticket']['reply_to_admin'] ?>
                                </label>
                                <textarea placeholder="<?= $lang['placeholder']['enter_message']; ?>" class="form-control" name="new_message" id="new_message" rows="5"></textarea>
                            </div><!--- form-group Ends --->                
                            <div class="form-group">
                                <label class="<?= $floatRight ?>"><?= $lang['label']['upload_file']; ?></label>
                                <input type="file" class="form-control" name="file">
                            </div>
                            <input type="submit" name="submit" class="btn btn-success" value="<?= $lang['button']['submut']; ?>">
                        </form>                 
                    </div>
                </div>
            <?php }else{ ?>
                <div class="card mt-3">
                    <div class="card-body">
                        <?= $lang['single_ticket']["can't_send"]; ?>                     
                    </div>
                </div>
            <?php } ?>

          </div><!--- col-md-8 Ends --->

        </div><!--- row Ends -->

    </div> <!-- container pb-4 Ends -->
    
<!-- Container ends -->
<?php require_once("includes/footer.php"); ?>
</body>
</html>