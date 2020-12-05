<?php 
  require_once("db.php"); 
  require_once("extra_script.php");
  if(!isset($_SESSION['error_array'])){ $error_array = array(); }else{ $error_array = $_SESSION['error_array']; }
  if(isset($_SESSION['seller_user_name'])){
  require_once("seller_levels.php");
  $seller_user_name = $_SESSION['seller_user_name'];
  $get_seller = $db->select("sellers",array("seller_user_name" => $seller_user_name));
  $row_seller = $get_seller->fetch();
  $seller_id = $row_seller->seller_id;
  $seller_email = $row_seller->seller_email;
  $seller_verification = $row_seller->seller_verification;
  $seller_image = getImageUrl2("sellers","seller_image",$row_seller->seller_image);
  $count_cart = $db->count("cart",array("seller_id" => $seller_id));
  $select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $seller_id));
  $row_seller_accounts = $select_seller_accounts->fetch();
  $current_balance = $row_seller_accounts->current_balance;
  $get_general_settings = $db->select("general_settings");   
  $row_general_settings = $get_general_settings->fetch();
  $enable_referrals = $row_general_settings->enable_referrals;
  $count_active_proposals = $db->count("proposals",array("proposal_seller_id"=>$seller_id,"proposal_status"=>'active'));
  }
  function get_real_user_ip(){
  	/// This is to check ip from shared internet network
  	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
  	$ip = $_SERVER['HTTP_CLIENT_IP'];
  	}
  	/// This is to check ip if it is passing from proxy network
  	elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
  	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  	}
  	else{
  	$ip = $_SERVER['REMOTE_ADDR'];
  	}
  	return $ip;
  }
  $ip = get_real_user_ip();
  
  if(!isset($_COOKIE['close_announcement'])){
    include("comp/announcement_bar.php");
  }

$get_general_settings = $db->select("general_settings");
$row_general_settings = $get_general_settings->fetch();
$site_color = $row_general_settings->site_color;
$site_hover_color = $row_general_settings->site_hover_color;
$site_border_color = $row_general_settings->site_border_color;
?>

<link href="<?= $site_url; ?>/styles/scoped_responsive_and_nav.css" rel="stylesheet">
<link href="<?= $site_url; ?>/styles/vesta_homepage.css" rel="stylesheet">

<div id="gnav-header" class="gnav-header global-nav clear gnav-3">
  <header id="gnav-header-inner" class="gnav-header-inner clear apply-nav-height col-group has-svg-icons body-max-width">
    <div class="col-xs-12">
      <div id="gigtodo-logo" class="apply-nav-height gigtodo-logo-svg gigtodo-logo-svg-logged-in <?php if(isset($_SESSION["seller_user_name"])){echo"loggedInLogo";} ?>">
        <a href="<?= $site_url; ?>">
          <?php if($site_logo_type == "image"){ ?>
            <img class="desktop" src="<?= $site_logo_image; ?>" width="150">
          <?php }else{ ?>
            <span class="desktop text-logo"><?= $site_logo_text; ?></span>
          <?php } ?>
          <?php if($enable_mobile_logo == 1){ ?>
            <img class="mobile" src="<?= $site_mobile_logo; ?>" height="25">
          <?php } ?>
        </a>
      </div>
      <button id="mobilemenu" class="unstyled-button mobile-catnav-trigger apply-nav-height icon-b-1 tablet-catnav-enabled <?= ($enable_mobile_logo == 0)?"left":""; ?>">
        <span class="screen-reader-only"></span>
        <div class="text-gray-lighter text-body-larger">
          <span class="gigtodo-icon hamburger-icon nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M20,6H4A1,1,0,1,1,4,4H20A1,1,0,0,1,20,6Z" />
              <path d="M20,13H4a1,1,0,0,1,0-2H20A1,1,0,0,1,20,13Z" />
              <path d="M20,20H4a1,1,0,0,1,0-2H20A1,1,0,0,1,20,20Z" />
            </svg>
          </span>
        </div>
      </button>
      <div class="catnav-search-bar search-browse-wrapper with-catnav">
        <div class="search-browse-inner">
          <form id="gnav-search" class="search-nav expanded-search apply-nav-height" method="post">
            <div class="gnav-search-inner clearable">
              <label for="search-query" class="screen-reader-only">Search for items</label>
              <div class="search-input-wrapper text-field-wrapper">
                <input id="search-query" class="rounded" name="search_query"
                  placeholder="<?= $lang['search']['placeholder']; ?>" value="<?= @$_SESSION["search_query"]; ?>"  autocomplete="off">
              </div>
              <div class="search-button-wrapper hide">
                <button class="btn " name="search" type="submit" value="Search" style="background-color: <?= $site_color;?>;color: #FFF;border-top-left-radius: 0;
                        border-bottom-left-radius: 0;
                        line-height: 17px;
                        height: 34px;
                        padding: 7px 15px 8px;">
                <?= $lang['search']['button']; ?>
                </button>
              </div>
            </div>
            <ul class="search-bar-panel d-none"></ul>
          </form>
        </div>
      </div>
      <?php
        if (isset($_POST['search'])) {
            $search_query = $input->post('search_query');
            $_SESSION['search_query'] = $search_query;
            echo "<script>window.open('$site_url/search.php','_self')</script>";
        }
      ?>
      <ul class="account-nav apply-nav-height">
        <?php 
          require_once("comp/UserMenu.php");
        ?>
      </ul>
    </div>
  </header>
</div>

<div class="clearfix"></div>
<?php include("comp/mobile_menu.php"); ?>

<?php if(isset($_GET['not_available'])) { ?>
<div class="alert alert-danger text-center mb-0 h6">
<!-- Alert to show wrong url or unregistered account start -->
<i class="fa fa-exclamation-circle"></i> The page or user you searched for is no longer available.
</div>
<!-- Alert to show wrong url or unregistered account end -->
<?php } ?>
<?php if(isset($_SESSION['seller_user_name'])) { 
if($seller_verification != "ok"){
?>
<div class="alert alert-warning clearfix activate-email-class mb-0">
<div class="float-left mt-2">
<i style="font-size: 125%;" class="fa fa-exclamation-circle"></i> 
<?php 
$message = $lang['popup']['email_confirm']['text'];
$message = str_replace('{seller_email}', $seller_email, $message);
$message = str_replace('{link}', "$site_url/customer_support", $message);
echo $message;
?>
</div>
<div class="float-right">
<button id="send-email" class="btn btn-success btn-sm float-right text-white"><?= $lang["popup"]["email_confirm"]['button']; ?></button>
</div>
</div>
<script>
  $(document).ready(function(){
    $("#send-email").click(function(){
      $("#wait").addClass('loader');
      $.ajax({
        method: "POST",
        url: "<?= $site_url; ?>/includes/send_email",
        success:function(){
          $("#wait").removeClass('loader');
          $("#send-email").html("Resend Email");
          swal({
            type: 'success',
            text: '<?= $lang['alert']['confirmation_email']; ?>',
          });
        }
      });
    });
  });
</script>
<?php  } } ?>
<?php require_once("external_stylesheet.php"); ?>