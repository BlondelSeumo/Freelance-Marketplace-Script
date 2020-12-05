<?php
  @$count_unread_notifications = $db->count("notifications",array("receiver_id" => $seller_id,"status" => "unread"));
  @$count_favourites = $db->count("favorites",array("seller_id" => $seller_id));
  @$count_unread_inbox_messages = $db->query("select * from inbox_messages where message_receiver=:r_id AND message_status='unread'",array("r_id"=>$seller_id))->rowCount();
  $next_icon = "
  <div class='flag-img flag-img-right'>
  <span class='gigtodo-icon float-right'>
  <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' aria-hidden='true' focusable='false'>
  <path d='M10,17a1,1,0,0,1-.707-1.707L12.586,12,9.293,8.707a1,1,0,0,1,1.414-1.414L15.414,12l-4.707,4.707A1,1,0,0,1,10,17Z'></path>
  </svg>
  </span>
  </div>
  ";
?>
<div class="cat-mobile" id="gigtodo-modal-container" style="display:none;"><!--- cat-mobile Starts --->
  <div data-overlay-mask="" class="overlay-mask mobile-catnav-overlay-mask" data-aria-hidden="true"></div>
  <div data-overlay-content-wrapper="" class="overlay-mask overlay-content-wrapper mobile-catnav-overlay-mask">
    <div class="mobile-catnav-wrapper overlay-region position-relative p-xs-0" id="mobile-catnav-overlay" aria-hidden="false" data-overlay-has-trigger="true" style="top: 0px;">
      <div data-ui="mobile-cat-nav" class="mobile-cat-nav bg-gray-lighter pb-xs-4 width-full position-fixed animated" style="height: 100vh;">

        <div class="bg-white display-flex-md show-md pt-md-3 pl-md-2 pb-md-3">
          <div class="flex-md-5 pl-md-0">
            <a role="button" href="<?= $site_url; ?>">
            <img src="<?= $site_logo_image; ?>" width="158">
            </a>
          </div>
          <div class="flex-md-1 pr-md-2">
            <button class="btn-link text-right overlay-close flex-xs-1 justify-self-flex-end border-0 p-md-0" data-overlay-close="">
              <span class="screen-reader-only">Close Menu</span>
              <span class="gigtodo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                  <path d="M13.414,12l6.293-6.293a1,1,0,0,0-1.414-1.414L12,10.586,5.707,4.293A1,1,0,0,0,4.293,5.707L10.586,12,4.293,18.293a1,1,0,1,0,1.414,1.414L12,13.414l6.293,6.293a1,1,0,0,0,1.414-1.414Z"></path>
                </svg>
              </span>
            </button>
          </div>
        </div>

        <div data-ui="mobile-catnav-header" class="mobile-catnav-header bb-xs-1 align-items-center bg-white display-flex-xs flex-nowrap position-relative height-50px">
          <button class="mobile-catnav-back-btn btn-link overlay-back p-xs-2 text-left display-none flex-xs-1 align-self-flex-start z-index-1 position-absolute" data-subnav-id="0" data-ternav-id="0" data-topnav-name="" data-subnav-name="">
            <span class="screen-reader-only">Go Back</span>
            <span class="gigtodo-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M16,21a0.994,0.994,0,0,1-.664-0.253L5.5,12l9.841-8.747a1,1,0,0,1,1.328,1.494L8.5,12l8.159,7.253A1,1,0,0,1,16,21Z"></path>
              </svg>
            </span>
          </button>
          <div class="flex-xs-4 width-full pt-md-4 pb-md-4 pl-xs-2">
            <h6 id="mobile-catnav-header-title" class="text-left position-absolute vertical-center"><!--  <a href="<?= $site_url; ?>"><img src="images/<?= $site_favicon; ?>" class="rounded" title='Home' alt='Home'/></a> --> Browse Categories</h6>
            <h6 id="mobile-sub-catnav-header-title" class="text-center position-absolute position-left position-right vertical-center pl-md-8 pr-md-8"></h6>
            <h6 id="mobile-tertiary-catnav-header-title" class="text-center position-absolute position-left position-right vertical-center pl-md-8 pr-md-8 display-none"></h6>
          </div>
          <div class="flex-xs-1 width-full">
            <button class="show-xs show-sm btn-link p-xs-2 overlay-close border-0 float-right" data-overlay-close="">
              <span class="screen-reader-only">Close Menu</span>
              <span class="gigtodo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M13.414,12l6.293-6.293a1,1,0,0,0-1.414-1.414L12,10.586,5.707,4.293A1,1,0,0,0,4.293,5.707L10.586,12,4.293,18.293a1,1,0,1,0,1.414,1.414L12,13.414l6.293,6.293a1,1,0,0,0,1.414-1.414Z"></path>
                </svg>
              </span>
            </button>
          </div>
        </div>

        <div data-ui="mobile-catnav-scroll-wrapper" class="height-full overflow-y-scroll">

          <div class="mobile-topnav bg-white animated">
            <ul data-ui="mobile-top-catnav-container" class="mobile-top-catnav-container list-unstyled mobile-catnav-margin">
              <?php

                $get_categories = $db->query("select * from categories where cat_featured='yes'".($lang_dir=="right"?'order by 1 DESC':'')." LIMIT 0,10");
                while($row_categories = $get_categories->fetch()){
                $cat_id = $row_categories->cat_id;
                
                $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id,"language_id" => $siteLanguage));
                $row_meta = $get_meta->fetch();
                @$cat_title = $row_meta->cat_title;

                $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id'");
                $count = $get_child_cat->rowCount();
              
              ?>
              <li class="p-xs-2 bb-xs-1 text-body-larger top-nav-item" data-uid="1<?= $cat_id; ?>" data-name="<?= $cat_title; ?>">
                <?php if($count == 0){ echo"<a href='$site_url/category?cat_id=$cat_id'>"; } ?>
                <div class="flag">
                  <div class="flag-body"><?= $cat_title; ?></div>
                  <?php if($count > 0){ ?>
                  <?= $next_icon; ?>
                  <?php } ?>
                </div>
                <?php if($count == 0){ echo"</a>"; } ?>
              </li>
              <?php } ?>
            </ul>
          </div>

          <?php

            $get_categories = $db->query("select * from categories where cat_featured='yes'".($lang_dir=="right"?'order by 1 DESC':'')." LIMIT 0,10");
            while($row_categories = $get_categories->fetch()){
            $cat_id = $row_categories->cat_id;
            $cat_url = $row_categories->cat_url;

            $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id,"language_id" => $siteLanguage));
            $row_meta = $get_meta->fetch();
            @$cat_title = $row_meta->cat_title;

            ?>
          <div class="mobile-subnav bg-white animated" id="mobile-sub-catnav-content-1<?= $cat_id; ?>">
            <ul class="mobile-sub-catnav-container list-unstyled mobile-catnav-margin display-none">
              <li class="p-xs-1 bb-xs-1 text-body-larger strong subnav-item a11y-focus-only">
                <a class="p-xs-1 text-gray display-inline-block width-full text-underline" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                View All <?= $cat_title; ?>
                </a>
              </li>
              <?php 
              
                $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id'");
                while($row_child_cat = $get_child_cat->fetch()){
                $child_id = $row_child_cat->child_id;
                $child_url = $row_child_cat->child_url;

                $get_meta = $db->select("child_cats_meta",array("child_id" => $child_id, "language_id" => $siteLanguage));
                $row_meta = $get_meta->fetch();
                $child_title = @$row_meta->child_title;

                ?>
              <li class="p-xs-2 bb-xs-1 text-body-larger subnav-item a11y-focus-only">
                <a href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                  <div class="flag">
                    <div class="flag-body"><?= $child_title; ?></div>
                  </div>
                </a>
              </li>
              <?php } ?>
            </ul>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<!--- cat-mobile Ends --->
<?php if(isset($_SESSION['seller_user_name'])){ ?>
<div class="user-mobile" id="gigtodo-modal-container" style="display: none;">
  <!--- user-mobile Starts --->
  <div data-overlay-mask="" class="overlay-mask mobile-catnav-overlay-mask" data-aria-hidden="true"></div>
  <div data-overlay-content-wrapper="" class="overlay-mask overlay-content-wrapper mobile-catnav-overlay-mask">
    <div class="mobile-catnav-wrapper overlay-region position-relative p-xs-0" id="mobile-catnav-overlay" aria-hidden="false" data-overlay-has-trigger="true" style="top: 0px;">
      <div data-ui="mobile-cat-nav" class="mobile-cat-nav bg-gray-lighter pb-xs-4 width-full position-fixed animated" style="height: 100vh;">
        <div class="bg-white display-flex-md show-md pt-md-3 pl-md-2 pb-md-3">
          <div class="flex-md-5 pl-md-0">
            <a role="button" href="/?ref=catnav-logo">
            <img src="<?= $site_logo_image; ?>" width="158">
            </a>
          </div>
          <div class="flex-md-1 pr-md-2">
            <button class="btn-link text-right overlay-close flex-xs-1 justify-self-flex-end border-0 p-md-0" data-overlay-close="">
              <span class="screen-reader-only">Close Menu</span>
              <span class="gigtodo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                  <path d="M13.414,12l6.293-6.293a1,1,0,0,0-1.414-1.414L12,10.586,5.707,4.293A1,1,0,0,0,4.293,5.707L10.586,12,4.293,18.293a1,1,0,1,0,1.414,1.414L12,13.414l6.293,6.293a1,1,0,0,0,1.414-1.414Z"></path>
                </svg>
              </span>
            </button>
          </div>
        </div>
        <div data-ui="mobile-catnav-header" class="mobile-catnav-header bb-xs-1 align-items-center bg-white display-flex-xs flex-nowrap position-relative height-50px">
          <button class="mobile-catnav-back-btn btn-link overlay-back p-xs-2 text-left display-none flex-xs-1 align-self-flex-start z-index-1 position-absolute" data-subnav-id="0" data-ternav-id="0" data-topnav-name="" data-subnav-name="">
            <span class="gigtodo-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" focusable="false">
                <path d="M16,21a0.994,0.994,0,0,1-.664-0.253L5.5,12l9.841-8.747a1,1,0,0,1,1.328,1.494L8.5,12l8.159,7.253A1,1,0,0,1,16,21Z"></path>
              </svg>
            </span>
          </button>
          <div class="flex-xs-4 width-full pt-md-4 pb-md-4 pl-xs-2">
            <h6 id="mobile-catnav-header-title" class="text-left position-absolute vertical-center">User Menu</h6>
            <h6 id="mobile-sub-catnav-header-title" class="text-center position-absolute position-left position-right vertical-center pl-md-8 pr-md-8"></h6>
            <h6 id="mobile-tertiary-catnav-header-title" class="text-center position-absolute position-left position-right vertical-center pl-md-8 pr-md-8 display-none"></h6>
          </div>
          <div class="flex-xs-1 width-full">
            <button class="show-xs show-sm btn-link p-xs-2 overlay-close border-0 float-right" data-overlay-close="">
              <span class="screen-reader-only">Close Menu</span>
              <span class="gigtodo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M13.414,12l6.293-6.293a1,1,0,0,0-1.414-1.414L12,10.586,5.707,4.293A1,1,0,0,0,4.293,5.707L10.586,12,4.293,18.293a1,1,0,1,0,1.414,1.414L12,13.414l6.293,6.293a1,1,0,0,0,1.414-1.414Z"></path>
                </svg>
              </span>
            </button>
          </div>
        </div>
        <div data-ui="mobile-catnav-scroll-wrapper" class="height-full overflow-y-scroll">
          <div class="mobile-topnav bg-white animated">
            <ul class="mobile-top-catnav-container list-unstyled mobile-catnav-margin">
              <li class="p-xs-2 bb-xs-1 text-body-larger top-nav-item a11y-focus-only" data-uid="d-1" data-name="<?= $lang["menu"]['dashboard']; ?>">
                <a href="#">
                  <div class="flag">
                    <span class="gigtodo-icon icon-smaller mr-2">
                    <?php include($dir . "images/svg/dashboard.svg"); ?>
                    </span>
                    <div class="flag-body">
                      <?= $lang["menu"]['dashboard']; ?>
                    </div>
                    <?= $next_icon; ?>
                  </div>
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger top-nav-item a11y-focus-only">
                <a href="<?= $site_url; ?>/notifications">
                  <div class="flag">
                    <span class="gigtodo-icon icon-smaller mr-2">
                    <?php include($dir . "images/svg/notification.svg"); ?>
                    </span>
                    <div class="flag-body">
                      Notifications
                      <?php if($count_unread_notifications > 0){ ?>
                      <span class="badge badge-pill badge-danger"> <?= $count_unread_notifications; ?> New</span>
                      <?php } ?>
                    </div>
                  </div>
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger top-nav-item a11y-focus-only">
                <a href="<?= $site_url; ?>/conversations/inbox">
                  <div class="flag">
                    <span class="gigtodo-icon icon-smaller mr-2">
                    <?php include($dir . "images/svg/email.svg"); ?>
                    </span>
                    <div class="flag-body">
                      Messages
                      <?php if($count_unread_inbox_messages > 0){ ?>
                      <span class="badge badge-pill badge-danger"> <?= $count_unread_inbox_messages; ?> New</span>
                      <?php } ?>
                    </div>
                  </div>
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger top-nav-item a11y-focus-only">
                <a href="<?= $site_url; ?>/favorites">
                  <div class="flag">
                    <span class="gigtodo-icon icon-smaller mr-2">
                    <?php include($dir . "images/svg/heart.svg"); ?>
                    </span>
                    <div class="flag-body">
                      Favorites
                      <?php if($count_favourites > 0){ ?>
                      <span class="badge badge-pill badge-danger"> <?= $count_favourites; ?></span>
                      <?php } ?>
                    </div>
                  </div>
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger top-nav-item a11y-focus-only">
                <a href="<?= $site_url; ?>/cart">
                  <div class="flag">
                    <span class="gigtodo-icon icon-smaller mr-2">
                    <?php include($dir . "images/svg/basket.svg"); ?>
                    </span>
                    <div class="flag-body">
                      Cart
                      <?php if($count_cart > 0){ ?>
                        <span class="badge badge-pill badge-danger"> <?= $count_cart; ?></span>
                      <?php } ?>
                    </div>
                  </div>
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger top-nav-item a11y-focus-only">
                <a href="<?= $site_url; ?>/logout">
                  <div class="flag">
                    <span class="gigtodo-icon icon-smaller mr-2">
                    <?php include($dir . "images/svg/logout.svg"); ?>
                    </span>
                    <div class="flag-body">
                      <?= $lang["menu"]['logout']; ?>
                    </div>
                  </div>
                </a>
              </li>
            </ul>
          </div>
          <div class="mobile-subnav bg-white animated" id="mobile-sub-catnav-content-d-1">
            <!--- mobile-subnav bg-white animated Starts --->
            <ul class="mobile-sub-catnav-container list-unstyled mobile-catnav-margin">
              <li class="p-xs-1 bb-xs-1 text-body-larger strong subnav-item a11y-focus-only">
                <a class="p-xs-1 display-inline-block text-underline" href="<?= $site_url; ?>/dashboard">
                View Dashboard Home
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger subnav-item" data-uid="s-1" data-name="<?= $lang["menu"]['selling']; ?>">
                <a href="#">
                  <div class="flag">
                    <div class="flag-body"><?= $lang["menu"]['selling']; ?></div>
                    <?= $next_icon; ?>
                  </div>
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger subnav-item" data-uid="b" data-name="<?= $lang['menu']['buying']; ?>">
                <a href="#">
                  <div class="flag">
                    <div class="flag-body"><?= $lang['menu']['buying']; ?></div>
                    <?= $next_icon; ?>
                  </div>
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger subnav-item" data-uid="r" data-name="<?= $lang["menu"]['requests']; ?>">
                <a href="#">
                  <div class="flag">
                    <div class="flag-body"><?= $lang["menu"]['requests']; ?></div>
                    <?= $next_icon; ?>
                  </div>
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger subnav-item" data-uid="contacts" data-name="<?= $lang['menu']['contacts']; ?>">
                <a href="#">
                  <div class="flag">
                    <div class="flag-body"><?= $lang['menu']['contacts']; ?></div>
                    <?= $next_icon; ?>
                  </div>
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger subnav-item" data-uid="referrals" data-name="<?= $lang['menu']['my_referrals']; ?>">
                <a href="#">
                  <div class="flag">
                    <div class="flag-body"><?= $lang['menu']['my_referrals']; ?></div>
                    <?= $next_icon; ?>
                  </div>
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger subnav-item">
                <a href="<?= $site_url; ?>/<?= $_SESSION['seller_user_name']; ?>">
                  <div class="flag">
                    <div class="flag-body"><?= $lang['menu']['my_profile']; ?></div>
                  </div>
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger subnav-item" data-uid="settings" data-name="<?= $lang['menu']['settings']; ?>">
                <a href="#">
                  <div class="flag">
                    <div class="flag-body"><?= $lang['menu']['settings']; ?></div>
                    <?= $next_icon; ?>
                  </div>
                </a>
              </li>
              <li class="p-xs-2 bb-xs-1 text-body-larger subnav-item">
                <a href="<?= $site_url; ?>/customer_support">
                  <div class="flag">
                    <div class="flag-body">Contact Support</div>
                  </div>
                </a>
              </li>
            </ul>
          </div>
          <!--- mobile-subnav bg-white animated Ends --->
          <div class="mobile-tertiarynav bg-white animated display-none" id="mobile-tertiary-nav-s-1">
            <!--- mobile-tertiarynav Starts --->
            <ul class="list-unstyled mobile-catnav-margin">
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url."/selling_orders"; ?>">
                <?= $lang['menu']['orders']; ?>
                </a>
              </li>
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url."/proposals/view_proposals"; ?>">
                <?= $lang['menu']['my_proposals']; ?>
                </a>
              </li>
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url."/requests/buyer_requests"; ?>">
                <?= $lang['menu']['buyer_requests']; ?>
                </a>
              </li>
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url."/revenue"; ?>">
                <?= $lang['menu']['revenues']; ?>
                </a>
              </li>
              <li class="p-xs-1 bb-xs-1 text-body-larger strong">
                <a class="p-xs-1 display-inline-block text-underline" href="<?= $site_url; ?>/proposals/create_proposal">
                Create A New Proposal
                </a>
              </li>
            </ul>
          </div>
          <!--- mobile-tertiarynav Ends --->
          <div class="mobile-tertiarynav bg-white animated display-none" id="mobile-tertiary-nav-b">
            <!--- mobile-tertiarynav Starts --->
            <ul class="list-unstyled mobile-catnav-margin">
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url."/buying_orders"; ?>">
                <?= $lang['menu']['orders']; ?>
                </a>
              </li>
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url."/purchases"; ?>">
                <?= $lang['menu']['purchases']; ?>
                </a>
              </li>
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url."/favorites"; ?>">
                <?= $lang['menu']['favorites']; ?>
                </a>
              </li>
            </ul>
          </div>
          <!--- mobile-tertiarynav Ends --->
          <div class="mobile-tertiarynav bg-white animated display-none" id="mobile-tertiary-nav-r">
            <!--- mobile-tertiarynav Starts --->
            <ul class="list-unstyled mobile-catnav-margin">
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url."/requests/post_request"; ?>">
                <?= $lang['menu']['post_request']; ?>
                </a>
              </li>
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url."/requests/manage_requests"; ?>">
                <?= $lang['menu']['manage_requests']; ?>
                </a>
              </li>
            </ul>
          </div>
          <!--- mobile-tertiarynav Ends --->
          <div class="mobile-tertiarynav bg-white animated display-none" id="mobile-tertiary-nav-contacts">
            <!--- mobile-tertiarynav Starts --->
            <ul class="list-unstyled mobile-catnav-margin">
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url; ?>/manage_contacts?my_buyers">
                <?= $lang['menu']['my_buyers']; ?>
                </a>
              </li>
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url; ?>/manage_contacts?my_sellers">
                <?= $lang['menu']['my_sellers']; ?>
                </a>
              </li>
            </ul>
          </div>
          <!--- mobile-tertiarynav Ends --->
          <div class="mobile-tertiarynav bg-white animated display-none" id="mobile-tertiary-nav-referrals">
            <!--- mobile-tertiarynav Starts --->
            <ul class="list-unstyled mobile-catnav-margin">
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url; ?>/my_referrals">
                <?= $lang['menu']['user_referrals']; ?>
                </a>
              </li>
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url; ?>/proposal_referrals">
                <?= $lang['menu']['proposal_referrals']; ?>
                </a>
              </li>
            </ul>
          </div>
          <!--- mobile-tertiarynav Ends --->
          <div class="mobile-tertiarynav bg-white animated display-none" id="mobile-tertiary-nav-settings">
            <!--- mobile-tertiarynav Starts --->
            <ul class="list-unstyled mobile-catnav-margin">
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url; ?>/settings?profile_settings">
                <?= $lang['menu']['profile_settings']; ?>
                </a>
              </li>
              <li class="p-xs-1 bb-xs-1 text-body-larger">
                <a class="p-xs-1 display-inline-block" href="<?= $site_url; ?>/settings?account_settings">
                <?= $lang['menu']['account_settings']; ?>
                </a>
              </li>
            </ul>
          </div>
          <!--- mobile-tertiarynav Ends --->
        </div>
      </div>
    </div>
  </div>
</div>
<!--- user-mobile Ends --->
<?php } ?>