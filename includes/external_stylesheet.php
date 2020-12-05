<?php 
   $site_color = $row_general_settings->site_color;
   $site_hover_color = $row_general_settings->site_hover_color;
   $site_border_color = $row_general_settings->site_border_color;
   $page = basename($_SERVER['PHP_SELF'], ".php");

?>
<style type="text/css">

  .paypal-button.paypal-button-color-blue {
    background: <?= $site_color; ?> !important;
  }

  .btn_search, 
  .btn_join,
  .gnav-header .search-button-wrapper .btn-primary,
  .input-group-append,
  .badge-success,
  .gnav-header .account-nav .count,
  a.support-que,
  .hero .search-box.focus .search-magnifier,
  .proposal-reviews .active,
  .btn-order.primary,
  .bg-success,
  .nav-pills .nav-link.active, 
  .nav-pills .show > .nav-link{
    background: <?= $site_color; ?> !important;
  }

  .ui-toolkit .btn-primary.btn-primary:active,
  .ui-toolkit .btn-primary.btn-primary:hover,
  .ds-reduced-colors .ui-toolkit .btn-primary.btn-primary:active,
  .ds-reduced-colors .ui-toolkit .btn-primary.btn-primary:hover {
    background: <?= $site_hover_color; ?> !important;
  }

  .breadcrumb a:before{
    background: <?= $site_color; ?>;
  }

  .border-success{
    border-color:<?= $site_color; ?> !important;
  }

  .btn-success{
    border-color:<?= $site_border_color; ?>;
    background: <?= $site_color; ?>;
  } 

  .btn-success:hover,
  .btn-success:focus,
  .btn-success:active{
    border-color:<?= $site_border_color; ?>;
    background: <?= $site_hover_color; ?> !important;
  }

  .btn-success:active{
    border-color:<?= $site_border_color; ?> !important;
    background: <?= $site_color; ?> !important;
    box-shadow: unset !important;
  }
/*  #order-status-bar{
      background: <?= $site_color; ?> !important;
  }*/
  .order-page .order-review-box{
      background: <?= $site_color; ?> !important;
  }

  <?php if($page == "create_proposal" or $page == "edit_proposal"){ ?>

  #tabs .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    border: 0px;
    border-bottom: 3px solid <?= $site_color; ?> !important;
  }

  <?php } ?>

  .view-proposals .btn-toggle.active {
      background-color: <?= $site_color; ?> !important;
  }

  .btn_join:hover{
    background: <?= $site_hover_color; ?> !important;
    border: 0px solid <?= $site_color; ?> !important;
    color:white !important;
  }

  .input-group-addon{
    background-color: #e9ecef;
    border: 1px solid #ced4da;
  }

  .input-group-append{
    background: <?= $site_color; ?> !important;
  }

  .input-group-append:hover{
    background: <?= $site_hover_color; ?> !important;
  }

  .btn-order.primary {
    -webkit-box-shadow: 0 2px 0 <?= $site_border_color; ?>;
    box-shadow: 0 2px 0 <?= $site_border_color; ?>;
  }

  .cat-nav .top-nav-item.active {
    border-bottom: 3px solid <?= $site_border_color; ?>;
  }

  .text-success,
  .card_user h4,
  .timer h5,
  .hero .search-box .search-magnifier,
  .popup-support-wrap .breadcrumbs .fa-home,
  .contacts-sidebar .active,
  .page-link,
  .proposal-unfavorite,
  .dil{
    color:<?= $site_color; ?> !important;
  }

  .order-page .nav-link.active,
  .order-page .seller-buyer-name {
    color: <?= $site_color; ?> !important;
  }

  .gnav-header #search-query:focus, 
  .gnav-header #search-query:active{
    border: 1px solid <?= $site_border_color; ?>;
  }

  .btn-outline-success{
    color:<?= $site_color; ?>;
    border-color:<?= $site_border_color; ?>;
  }

  .btn-outline-success:hover{
    background:<?= $site_color; ?>;
    border-color:<?= $site_border_color; ?>;
  }

  .is-online {
    border: 1px solid <?= $site_color; ?>;
    color: <?= $site_color; ?>;
  }

  .card_user .get_btn {
    border: 1px solid <?= $site_color; ?>;
    color: <?= $site_color; ?>;
  }

  .btn-success:focus, .btn-success.focus{
    box-shadow: none !important;
  }

  .card_user .get_btn:hover{
    border-color: <?= $site_hover_color; ?>;
    background: <?= $site_hover_color; ?>;
  }

  .page-item.active .page-link {
    background-color: <?= $site_color; ?>;
    border-color: <?= $site_border_color; ?>;
  }
  
</style>