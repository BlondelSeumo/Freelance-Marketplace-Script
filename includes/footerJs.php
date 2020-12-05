<?php
if(isset($_SESSION['seller_user_name'])){
  $login_seller_user_name = $_SESSION['seller_user_name'];
  $select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
  $row_login_seller = $select_login_seller->fetch();
  $login_seller_id = $row_login_seller->seller_id;
  $login_seller_enable_sound = $row_login_seller->enable_sound;
  $login_seller_enable_notifications = $row_login_seller->enable_notifications;
}
?>

<div id="wait"></div>

<?php if(!empty($google_analytics)){ ?>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $google_analytics; ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?= $google_analytics; ?>');
  </script>
<?php } ?>
<script src="<?= $site_url; ?>/js/msdropdown.js"></script>
<script type="text/javascript" src="<?= $site_url; ?>/js/jquery.sticky.js"></script>

<script 
type="text/javascript" 
id="custom-js" 
src="<?= $site_url; ?>/js/customjs.js" 
data-logged-id="<?= (isset($_SESSION['seller_user_name']))?$login_seller_id:''; ?>" 
data-base-url="<?= $site_url; ?>" 
data-enable-sound="<?= (isset($_SESSION['seller_user_name']))?$login_seller_enable_sound:''; ?>"
data-enable-notifications="<?= (isset($_SESSION['seller_user_name']))?$login_seller_enable_notifications:'0'; ?>"
data-disable-messages="<?= (isset($disable_messages))?$disable_messages:'0'; ?>"
>
</script>

<script type="text/javascript" src="<?= $site_url; ?>/js/categoriesProposal.js"></script>
<script type="text/javascript" src="<?= $site_url; ?>/js/popper.min.js"></script>
<script type="text/javascript" src="<?= $site_url; ?>/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="<?= $site_url; ?>/js/bootstrap.js"></script>
<script type="text/javascript" src="<?= $site_url; ?>/js/summernote.js"></script>