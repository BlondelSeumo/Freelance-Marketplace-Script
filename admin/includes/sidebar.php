<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{
?>

<ul class="nav navbar-nav">

  <li class="pt-5">
    <a href="index?dashboard"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
  </li>

<!-- <?php if($a_admins == 1){ ?>

  <li class="pt-5">
    <a href="index?dashboard"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
  </li>

<?php }else{ echo "<li class='pt-0'></li>"; } ?> -->

<?php if($a_settings == 1){ ?>

  <li class="menu-item-has-children dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Settings"> <i class="menu-icon fa fa-cog"></i> Settings</a>
    <ul class="sub-menu children dropdown-menu">
      <li><i class="fa  fa-arrow-circle-right"></i><a href="index?general_settings">General Settings</a></li>
      <li><i class="fa  fa-arrow-circle-right"></i><a href="index?layout_settings">Layout Settings</a></li>
      <li><i class="fa  fa-arrow-circle-right"></i><a href="index?payment_settings">Payment Settings</a></li>
      <li><i class="fa  fa-arrow-circle-right"></i><a href="index?mail_settings">Mail Server Settings</a></li>
      <li><i class="fa  fa-arrow-circle-right"></i><a href="index?email_templates">Email Templates</a></li>
      <li><i class="fa  fa-arrow-circle-right"></i><a href="index?api_settings">Api Settings</a></li>
      <li><i class="fa  fa-arrow-circle-right"></i><a href="index?app_license">Application License</a></li>
      <li><i class="fa  fa-arrow-circle-right"></i><a href="index?app_update">Application Update</a></li>
    </ul>
  </li>

<?php } ?>

<?php if($a_plugins == 1){ ?>

  <li>
    <a href="index?plugins"> <i class="menu-icon fa fa-plug" aria-hidden="true"></i> Plugins</a>
  </li>

<?php } ?>

<?php if($a_pages == 1){ ?>

  <li class="menu-item-has-children dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="menu-icon fa fa-file"></i> Pages
    </a>
    <ul class="sub-menu children dropdown-menu">
      <li><i class="fa fa-arrow-circle-right"></i><a href="index?pages"> View Pages</a></li>
      <li><i class="fa fa-arrow-circle-right"></i><a href="index?insert_page"> Insert Page</a></li>
    </ul>
  </li>

<?php } ?>

<?php if($a_blog == 1){ ?>

  <li class="menu-item-has-children dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="menu-icon fa fa fa-rss"></i> Blog
    </a>
    <ul class="sub-menu children dropdown-menu">
      <li><i class="fa fa-arrow-circle-right"></i><a href="index?post_categories"> Categories </a></li>
      <li><i class="fa fa-arrow-circle-right"></i><a href="index?posts"> Posts </a></li>
      <li><i class="fa fa-arrow-circle-right"></i><a href="index?post_comments"> Comments </a></li>
    </ul>
  </li><!-- li Ends --->

<?php } ?>

<?php if($a_blog == 1){ ?>

  <li class="menu-item-has-children dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="menu-icon fa fa-comments"></i> Feedback
    </a>
    <ul class="sub-menu children dropdown-menu">
      <li><i class="fa fa-arrow-circle-right"></i><a href="index?ideas"> Ideas </a></li>
      <li><i class="fa fa-arrow-circle-right"></i><a href="index?comments"> Comments </a></li>
    </ul>
  </li><!-- li Ends --->

<?php } ?>

  <?php 
    if($a_video_schedules AND $videoPlugin == 1){
      include("../plugins/videoPlugin/admin/includes/sidebar.php");
    }
  ?>

<?php if($a_proposals == 1){ ?>

  <li>
    <a href="index?view_proposals"> <i class="menu-icon fa fa-table"></i>Proposals/Services
    <?php if(!$count_proposals == 0){ ?>
      <span class="badge badge-success"><?= $count_proposals ?></span>
    <?php } ?>
    </a>
  </li>

<?php } ?>

<?php if($a_accounting == 1){ ?>

  <li class="menu-item-has-children dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-cube"></i>Accounting</a>
  <ul class="sub-menu children dropdown-menu">
    <li><i class="fa  fa-arrow-circle-right"></i><a href="index?sales"> Sales</a></li>
    <li><i class="fa  fa-arrow-circle-right"></i><a href="index?expenses"> Expenses</a></li>
  </ul>
  </li>

<?php } ?>

<?php if($a_payouts == 1){ ?>

  <li class="menu-item-has-children dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="payouts"> 
      <i class="menu-icon fa fa-money"></i> Payouts
    </a>
    <ul class="sub-menu children dropdown-menu">
      <li><i class="fa  fa-arrow-circle-right"></i><a href="index?payouts&status=pending">Pending</a></li>
      <li><i class="fa  fa-arrow-circle-right"></i><a href="index?payouts&status=declined">Declined</a></li>
      <li><i class="fa  fa-arrow-circle-right"></i><a href="index?payouts&status=completed">Completed</a></li>
    </ul>
  </li>

<?php } ?>

<?php if($a_reports == 1){ ?>

  <li class="menu-item-has-children dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Reports"> 
    <i class="menu-icon fa fa-flag" ></i>Reports / Abuses
  </a>
  <ul class="sub-menu children dropdown-menu">
    <li><i class="fa  fa-arrow-circle-right"></i><a href="index?order_reports">Order Reports</a></li>
    <li><i class="fa  fa-arrow-circle-right"></i><a href="index?message_reports">Message Reports</a></li>
    <li><i class="fa  fa-arrow-circle-right"></i><a href="index?proposal_reports">Proposal Reports</a></li>
  </ul>
  </li>

<?php } ?>

<?php if($a_inbox == 1){ ?>

  <li>
    <a href="index?inbox_conversations"> <i class="menu-icon fa fa-comments"></i>Inbox Messages</a>
  </li>

<?php } ?>

<?php if($a_reviews == 1){ ?>

  <li class="menu-item-has-children dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-star"></i>Reviews</a>
      <ul class="sub-menu children dropdown-menu">
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?insert_review">Insert Review</a></li>
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?view_buyer_reviews">View Reviews</a></li>
      </ul>
  </li>

<?php } ?>

<?php if($a_buyer_requests == 1){ ?>

  <li>
     <a href="index?buyer_requests"> <i class="menu-icon fa fa-table"></i>Buyer Requests
      <?php if(!$count_requests == 0){ ?>
      <span class="badge badge-success"><?= $count_requests; ?></span>
      <?php } ?>
     </a>
  </li>

<?php } ?>

<?php if($a_restricted_words == 1){ ?>

  <li class="menu-item-has-children dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="menu-icon fa fa-fax"></i>Restricted Words</a>
      <ul class="sub-menu children dropdown-menu">
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?insert_word">Insert Word</a></li>
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?view_words">View Words</a></li>
      </ul>
  </li>

<?php } ?>


<?php if($a_alerts == 1){ ?>

  <li>
      <a href="index?view_notifications"> <i class="menu-icon fa fa-bell"></i>Alerts
      <?php if(!$count_notifications == 0){ ?>
      <span class="badge badge-success"><?= $count_notifications; ?></span>
      <?php } ?>
     </a>
  </li>

<?php } ?>

<?php if($a_cats == 1){ ?>

  <li class="menu-item-has-children dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-cubes"></i>Categories</a>
      <ul class="sub-menu children dropdown-menu">
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?insert_cat"> Insert Category</a></li>
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?view_cats"> View Categories</a></li>
      </ul>
  </li>

  <li class="menu-item-has-children dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-stack-exchange"></i>Sub Categories</a>
      <ul class="sub-menu children dropdown-menu">
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?insert_child_cat">Insert Sub Category</a></li>
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?view_child_cats">View Sub Categories</a></li>
      </ul>
  </li>

  <?php } ?>

  <?php if($a_delivery_times == 1){ ?>

  <li class="menu-item-has-children dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-calendar"></i>Delivery Times</a>
      <ul class="sub-menu children dropdown-menu">
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?insert_delivery_time">Insert Delivery Time</a></li>
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?view_delivery_times">View Delivery Time</a></li>
      </ul>
  </li>

  <?php } ?>

<?php if($a_seller_languages == 1){ ?>

  <li class="menu-item-has-children dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-language"></i> Seller Languages</a>
      <ul class="sub-menu children dropdown-menu">
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?insert_seller_language"> Insert Seller Language</a></li>
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?view_seller_languages"> View Seller Languages</a></li>
      </ul>
  </li>

  <?php } ?>

  <?php if($a_seller_skills == 1){ ?>

  <li class="menu-item-has-children dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Seller Skills"> <i class="menu-icon fa fa-flash"></i> Seller Skills</a>
      <ul class="sub-menu children dropdown-menu">
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?insert_seller_skill"> Insert Seller Skill</a></li>
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?view_seller_skills"> View Seller Skills</a></li>
      </ul>
  </li>

  <?php } ?>

  <?php if($a_seller_levels == 1){ ?>

  <li>
      <a href="index?view_seller_levels"> <i class="menu-icon fa fa-bell"></i>Seller Levels
     </a>
  </li>

  <?php } ?>

  <?php if($a_customer_support == 1){ ?>

  <li class="menu-item-has-children dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Customer Support"> <i class="menu-icon fa fa-phone-square"></i> Customer Support
        <?php if(!$count_support_tickets == 0){ ?>
          <span class="badge badge-success mr-3"><?= $count_support_tickets; ?></span>
        <?php } ?>
      </a>
      <ul class="sub-menu children dropdown-menu">
          <li>
            <i class="fa  fa-arrow-circle-right"></i>
            <a href="index?customer_support_settings" title="Customer Support Settings">
              Support Settings
            </a>
          </li>
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?view_support_requests" title="Customer Support Requests">
              Support Requests
              <?php if(!$count_support_tickets == 0){ ?>
                <span class="badge badge-success"><?= $count_support_tickets; ?></span>
              <?php } ?>
              </a>
          </li>
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?insert_enquiry_type"> Insert Enquiry Type</a>
          </li>
          <li><i class="fa  fa-arrow-circle-right"></i><a href="index?view_enquiry_types"> View Enquiry Types</a>
          </li>
      </ul>
  </li>

  <?php } ?>

  <?php if($a_coupons == 1){ ?>

  <li class="menu-item-has-children dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Seller Skills"> <i class="menu-icon fa fa-gift"></i> Coupons</a>
      <ul class="sub-menu children dropdown-menu">
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?insert_coupon"> Insert Coupon</a></li>
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?view_coupons"> View Coupons</a></li>
      </ul>
  </li>

  <?php } ?>

  <?php if($a_slides == 1){ ?>

  <li class="menu-item-has-children dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Seller Skills"> <i class="menu-icon fa  fa-picture-o"></i> Slides</a>
      <ul class="sub-menu children dropdown-menu">
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?insert_slide"> Insert Slide</a></li>
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?view_slides"> View Slides</a></li>
      </ul>
  </li>
  
  <?php } ?>

  <?php if($a_terms == 1){ ?>

  <li class="menu-item-has-children dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Seller Skills"> <i class="menu-icon fa fa-table"></i> Terms & Conditions</a>
      <ul class="sub-menu children dropdown-menu">
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?insert_term"> Insert Term</a></li>
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?view_terms"> View Terms</a></li>
      </ul>
  </li>

  <?php } ?>

  <?php if($a_sellers == 1){ ?>

  <li>
      <a href="index?view_sellers"> <i class="menu-icon fa fa-users"></i> All Users </a>
  </li>

  <?php } ?>

  <?php if($a_orders == 1){ ?>

  <li>
      <a href="index?view_orders"> <i class="menu-icon fa fa-eye"></i> View Orders </a>
  </li>
  
  <?php } ?>

  <?php if($a_referrals == 1){ ?>

  <li>
      <a href="index?view_referrals"> <i class="menu-icon fa fa-universal-access"></i> View Referrals
       <?php
              if(!$count_referrals == 0){
          ?>
      <span class="badge badge-success"><?= $count_referrals;?></span>
      <?php } ?>
      </a>
  </li>

  <li>
    <a href="index?view_proposal_referrals"> 
      <i class="menu-icon fa fa-universal-access"></i>View Proposal Referrals
      <?php if(!$count_proposals_referrals == 0){ ?>
        <span class="badge badge-success"><?= $count_proposals_referrals;?></span>
      <?php } ?>
    </a>
  </li>

  <?php } ?>

  <?php if($a_files == 1){ ?>

   <li class="menu-item-has-children dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Seller Skills"> <i class="menu-icon fa fa-file"></i> Files</a>
      <ul class="sub-menu children dropdown-menu">
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?view_proposals_files"> Proposals Files</a></li>
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?view_inbox_files"> Messages Files</a></li>
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?view_order_files"> Orders Files</a></li>
      </ul>
  </li>

  <?php } ?>

  <?php if($a_knowledge_bank == 1){ ?>

  <li class="menu-item-has-children dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Seller Skills"> 
  <i class="menu-icon fa fa-book"></i> Knowledge Bank
  </a>
          <ul class="sub-menu children dropdown-menu">
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?insert_article_cat"> Insert Article Category</a></li>
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?view_article_cats"> View Article Categories</a></li>
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?insert_article"> Insert Article</a></li>
          <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?view_articles"> View Articles</a></li>
          </ul>
   </li>

  <?php } ?>

  <?php if($a_currencies == 1){ ?>

  <li class="menu-item-has-children dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Seller Skills"> 
      <i class="menu-icon fa fa-money"></i> Site Currencies
    </a>
    <ul class="sub-menu children dropdown-menu">
      <li>
        <i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?insert_currency"> Insert Currency</a>
      </li>
      <li>
        <i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?view_currencies"> View Currencies</a>
      </li>
    </ul>
   </li>

  <?php } ?>

  <?php if($a_languages == 1){ ?>

  <li class="menu-item-has-children dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Languages"> 
      <i class="menu-icon fa fa-language"></i> Languages
    </a>
    <ul class="sub-menu children dropdown-menu">
      <li>
        <i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?insert_language">Insert Language</a>
      </li>
      <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?view_languages"> View Languages</a></li>
    </ul>
  </li>
  
  <?php } ?>

  <?php if($a_admins == 1){ ?>

  <li class="menu-item-has-children dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Seller Skills"> <i class="menu-icon fa fa-users"></i> Admins</a>
    <ul class="sub-menu children dropdown-menu">
      <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?admin_logs"> Admin Logs</a></li>
      <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?insert_user"> Insert Admin</a></li>
      <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?view_users"> View Admins</a></li>
      <li><i class="menu-icon fa fa-arrow-circle-right"></i><a href="index?user_profile=<?= $admin_id; ?>"> Edit My Profile</a></li>
    </ul>
  </li>

  <?php } ?>

  <li>
    <a href="logout"> 
      <i class="menu-icon fa fa-power-off"></i> Logout 
    </a>
  </li>

</ul>
<?php } ?>
