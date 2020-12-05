<li class="logged-in-link d-none d-sm-block d-md-block d-lg-block">
  <a class="menuItem" href="<?= $site_url; ?>/blog/" title="<?= $lang["blog"]['title']; ?>">
    <span class="gigtodo-icon nav-icon gigtodo-icon-relative">
      <i class="fa fa fa-rss fa-lg" style="font-size:1.4em;"></i>
    </span>
  </a>
</li>
<li class="logged-in-link">
  <a class="menuItem" href="<?= $site_url; ?>/freelancers" title="<?= $lang["freelancers_menu"]; ?>">
    <span class="gigtodo-icon nav-icon gigtodo-icon-relative">
      <img width="135" src="<?= $site_url; ?>/images/big-users.png" style="width: 35px;height: 35px;top: -10px;">
    </span>
  </a>
</li>
<li class="logged-in-link">
  <a class="bell menuItem" data-toggle="dropdown" title="<?= $lang['popup']['notifications']; ?>">
  <span class="gigtodo-icon nav-icon gigtodo-icon-relative"><?php include("{$dir}images/svg/notification.svg"); ?></span>
  <span class="total-user-count count c-notifications-header"></span>
  </a>
  <div class="dropdown-menu notifications-dropdown">
  </div>
</li>
<li class="logged-in-link">
  <a class="message menuItem" data-toggle="dropdown" title="<?= $lang['popup']['inbox']; ?>">
  <span class="gigtodo-icon nav-icon gigtodo-icon-relative"><?php include("{$dir}images/svg/email.svg"); ?></span>
  <span class="total-user-count count c-messages-header"></span>
  </a>
  <div class="dropdown-menu messages-dropdown">
  </div>
</li>
<li class="logged-in-link">
  <a href="<?= $site_url; ?>/favorites" class="heart menuItem" title="<?= $lang["menu"]["favorites"]; ?>">
  <span class="gigtodo-icon nav-icon gigtodo-icon-relative"><?php include("{$dir}images/svg/heart.svg"); ?> </span>
  <span>
  <span class="total-user-count count c-favorites"></span>
  </span>
  </a>
</li>
<li class="logged-in-link">
  <a class="menuItem" href="<?= $site_url; ?>/cart" title="<?= $lang["menu"]["cart"]; ?>">
  <span class="gigtodo-icon nav-icon gigtodo-icon-relative"><?php include("{$dir}images/svg/basket.svg"); ?></span>
  <?php if($count_cart > 0){ ?>
  <span class="total-user-count count"><?= $count_cart; ?></span>
  <?php } ?>
  </a>
</li>
<li class="logged-in-link">
  <?php require_once("userMenuLinks.php"); ?>
</li>
<li class="logged-in-link mr-lg-0 mr-2 d-none d-sm-block d-md-block d-lg-block">
  <a class="menuItem btn btn-success text-white"><?= showPrice($current_balance); ?></a>
</li>