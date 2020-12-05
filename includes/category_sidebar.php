<?php

  if(isset($_SESSION['cat_id'])){
    $session_cat_id = $_SESSION['cat_id'];  
  }

  if(isset($_SESSION['cat_child_id'])){
    $session_cat_child_id = $_SESSION['cat_child_id'];  
    $get_child_cats = $db->select("categories_children",array("child_id" => $session_cat_child_id));
    $child_parent_id = $get_child_cats->fetch()->child_parent_id;
  }

  $online_sellers = array();
  $delivery_time = array();
  $seller_level = array();
  $seller_language = array();

  if(isset($_GET['online_sellers'])){
    foreach($_GET['online_sellers'] as $value){
      $online_sellers[$value] = $value;
    }
  }

  if(isset($_REQUEST['instant_delivery'])){
    $instant_delivery = $_REQUEST['instant_delivery'][0];
  }else{
    $instant_delivery = 0;
  }

  if(isset($_REQUEST['order'])){
    $order_by = $_REQUEST['order'][0];
  }else{
    $order_by = "DESC";
  }

  if(isset($_GET['seller_country'])){
    foreach($_GET['seller_country'] as $value){
      $sellerCountry[$value] = $value;
    }
  }
  if(isset($_GET['seller_city'])){
    foreach($_GET['seller_city'] as $value){
      $sellerCity[$value] = $value;
    }
  }
  if(isset($_GET['delivery_time'])){
    foreach($_GET['delivery_time'] as $value){
      $delivery_time[$value] = $value;
    }
  }
  if(isset($_GET['seller_level'])){
    foreach($_GET['seller_level'] as $value){
      $seller_level[$value] = $value;
    }
  }
  if(isset($_GET['seller_language'])){
    foreach($_GET['seller_language'] as $value){
      $seller_language[$value] = $value;
    }
  }

?>


<?php include("$dir/includes/comp/currency_converter.php"); ?>


<div class="card border-success mb-3">
  <div class="card-header bg-success">
    <h3 class="<?=($lang_dir == "right" ? 'float-right':'float-left')?> h5 text-white"><?= $lang['sidebar']['categories']; ?></h3>
  </div>
  <div class="card-body">
    <ul class="nav flex-column" id="proposal_category">
      <?php
        $get_cats = $db->select("categories");
        while($row_cats = $get_cats->fetch()){
        $cat_id = $row_cats->cat_id;
        $cat_featured = $row_cats->cat_featured;
        $cat_url = $row_cats->cat_url;
        $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id, "language_id" => $siteLanguage));
        $row_meta = $get_meta->fetch();
        $cat_title = $row_meta->cat_title;
        $cat_desc = $row_meta->cat_desc;
      ?>
      <li class="nav-item">
        <span class="nav-link 
          <?php
            if($cat_id == @$_SESSION['cat_id']){ echo "active"; }
            if($cat_id == @$child_parent_id){ echo "active"; }
            ?>">     
        <a href="<?= $site_url; ?>/categories/<?= $cat_url; ?>" class="text-success"> <?= $cat_title; ?></a> 
        <a class="h5 text-success float-right" data-toggle="collapse" data-target="#cat_<?= $cat_id; ?>">
        <i class="fa fa-arrow-circle-down"></i>
        </a>
        </span>
        <ul id="cat_<?= $cat_id; ?>" class="collapse">
          <?php
            $get_child_cat = $db->select("categories_children",array("child_parent_id" => $cat_id));
            while($row_child_cat = $get_child_cat->fetch()){
              $child_id = $row_child_cat->child_id;
              $child_url = $row_child_cat->child_url;
              $get_meta = $db->select("child_cats_meta",array("child_id" => $child_id, "language_id" => $siteLanguage));
              $row_meta = $get_meta->fetch();
              $child_title = $row_meta->child_title;
              if(!empty($child_title)){
          ?>
          <li>
            <a class="nav-link text-success <?php if($child_id == @$_SESSION['cat_child_id']){ echo "active"; } ?>" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
            <?= $child_title; ?>
            </a>
          </li>
          <?php }} ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </div>
</div>
<div class="card border-success mb-3">
  <div class="card-body pb-2 pt-2">
    <ul class="nav flex-column">
      <li class="nav-item checkbox checkbox-success">
        <label class="pt-2">
        <input type="checkbox" value="1" class="get_online_sellers"
          <?php if(isset($online_sellers["1"])){ echo "checked"; } ?> >
        <span><?= $lang['sidebar']['online_sellers']; ?></span>
        </label>
      </li>
    </ul>
  </div>
</div>

<div class="card border-success mb-3">
  <div class="card-body pb-2 pt-3 <?=($lang_dir == "right" ? 'text-right':'')?>">
    <ul class="nav flex-column">
      <li class="nav-item checkbox checkbox-success">
        <label>
        <input type="checkbox" value="1" class="get_instant_delivery" 
          <?php if($instant_delivery == 1){ echo "checked"; } ?> >
        <span><?= $lang['sidebar']['instant_delivery']; ?></span>
        </label>
      </li>
    </ul>
  </div>
</div>

<div class="card border-success mb-3">
  <div class="card-header bg-success">
    <h3 class="<?=($lang_dir == "right" ? 'float-right':'float-left')?> text-white h5"><?= $lang['sidebar']['sort_by']['title']; ?></h3>
  </div>
  <div class="card-body">
    <label class="checkcontainer"><?= $lang['sidebar']['sort_by']['new']; ?>
      <input type="radio" <?= ($order_by == "DESC")?"checked":""; ?> value="DESC" class="get_order" name="radio">
      <span class="checkmark"></span>
    </label>

    <label class="checkcontainer"><?= $lang['sidebar']['sort_by']['old']; ?>
      <input type="radio" <?= ($order_by == "ASC")?"checked":""; ?> value="ASC" class="get_order" name="radio">
      <span class="checkmark"></span>
    </label>
  </div>
</div>


<div class="card border-success mb-3">
  <div class="card-header bg-success">
    <h3 class="<?=($lang_dir == "right" ? 'float-right':'float-left')?> text-white h5"><?= $lang["sidebar"]["seller_country"]; ?></h3>
    <button class="btn btn-secondary btn-sm <?=($lang_dir == "right" ? 'float-left':'float-right')?> clear_seller_country clearlink" onclick="clearCountry()">
      <?= $lang['sidebar']['clear_filter']; ?>
    </button>
  </div>
  <div class="card-body">
    <ul class="nav flex-column">
    <?php
    $countries = [];
    if(isset($_SESSION['cat_id'])){
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_cat_id=:cat_id AND proposal_status='active'",array("cat_id"=>$session_cat_id));
    }elseif(isset($_SESSION['cat_child_id'])){
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_child_id=:child_id AND proposal_status='active'",array("child_id"=>$session_cat_child_id));
    }
    while($row_proposals = $get_proposals->fetch()){
    $seller_id = $row_proposals->proposal_seller_id;
    $seller = $db->select("sellers",['seller_id'=>$seller_id])->fetch();
    $seller_country = $seller->seller_country;
    if(!empty($seller_country) AND @$countries[$seller_country] != $seller_country){
    $countries[$seller_country] = $seller_country;
    ?>
    <li class="nav-item checkbox checkbox-success">
      <label>
      <input type="checkbox" value="<?= $seller_country; ?>" class="get_seller_country"
      <?php if(isset($sellerCountry["$seller_country"])){ echo "checked"; } ?>>
      <span><?= $seller_country; ?></span>
      </label>
    </li>
    <?php }} ?>
    </ul>
  </div>
</div>


<div class="card border-success mb-3 seller-cities d-none">
  <div class="card-header bg-success">
    <h3 class="<?=($lang_dir == "right" ? 'float-right':'float-left')?> text-white h5"><?= $lang["sidebar"]["seller_city"]; ?></h3>
    <button class="btn btn-secondary btn-sm <?=($lang_dir == "right" ? 'float-left':'float-right')?> clear_seller_city clearlink" onclick="clearCity()">
      <?= $lang['sidebar']['clear_filter']; ?>
    </button>
  </div>
  <div class="card-body">
    <ul class="nav flex-column">
    <?php
    $cities = [];
    if(isset($_SESSION['cat_id'])){
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_cat_id=:cat_id AND proposal_status='active'",array("cat_id"=>$session_cat_id));
    }elseif(isset($_SESSION['cat_child_id'])){
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_child_id=:child_id AND proposal_status='active'",array("child_id"=>$session_cat_child_id));
    }
    while($row_proposals = $get_proposals->fetch()){
    $seller_id = $row_proposals->proposal_seller_id;
    $seller = $db->select("sellers",['seller_id'=>$seller_id])->fetch();
    $seller_country = $seller->seller_country;
    $seller_city = $seller->seller_city;
    if(!empty($seller_city) AND @$cities[$seller_city] != $seller_city){
    $cities[$seller_city] = $seller_city;
    ?>
    <li class="nav-item checkbox checkbox-success" data-country="<?= $seller_country; ?>">
      <label>
      <input type="checkbox" value="<?= $seller_city; ?>" class="get_seller_city"
      <?php if(isset($sellerCity["$seller_city"])){ echo "checked"; } ?>>
      <span><?= $seller_city; ?></span>
      </label>
    </li>
    <?php }} ?>
    </ul>
  </div>
</div>


<div class="card border-success mb-3">
  <div class="card-header bg-success">
    <h3 class="<?=($lang_dir == "right" ? 'float-right':'float-left')?> text-white h5"><?= $lang['sidebar']['delivery_time']; ?></h3>
    <button class="btn btn-secondary btn-sm <?=($lang_dir == "right" ? 'float-left':'float-right')?> clear_delivery_time clearlink" onclick="clearDelivery()">
    <i class="fa fa-times-circle"></i> Clear Filter
    </button>
  </div>
  <div class="card-body">
    <ul class="nav flex-column">
      <?php
        if(isset($_SESSION['cat_id'])){
          $get_proposals = $db->query("select DISTINCT delivery_id from proposals where proposal_cat_id=:cat_id AND proposal_status='active'",array("cat_id"=>$session_cat_id));
        }elseif(isset($_SESSION['cat_child_id'])){
          $get_proposals = $db->query("select DISTINCT delivery_id from proposals where proposal_child_id=:child_id AND proposal_status='active'",array("child_id"=>$session_cat_child_id));
        }
        while($row_proposals = $get_proposals->fetch()){
        $delivery_id = $row_proposals->delivery_id;
        $select_delivery_time = $db->select("delivery_times",array('delivery_id' => $delivery_id));
        $delivery_title = @$select_delivery_time->fetch()->delivery_title;
        if(!empty($delivery_title)){
      ?>
      <li class="nav-item checkbox checkbox-success">
        <label>
        <input type="checkbox" value="<?= $delivery_id; ?>" class="get_delivery_time"
          <?php if(isset($delivery_time[$delivery_id])){ echo "checked"; } ?> >
        <span> <?= $delivery_title; ?> </span>
        </label>
      </li>
      <?php }} ?>
    </ul>
  </div>
</div>
<div class="card border-success mb-3">
  <div class="card-header bg-success">
    <h3 class="<?=($lang_dir == "right" ? 'float-right':'float-left')?> text-white h5"><?= $lang['sidebar']['seller_level']; ?></h3>
    <button class="btn btn-secondary btn-sm <?=($lang_dir == "right" ? 'float-left':'float-right')?> clear_seller_level clearlink" onclick="clearLevel()">
    <i class="fa fa-times-circle"></i> Clear Filter
    </button>
  </div>
  <div class="card-body">
    <ul class="nav flex-column">
      <?php
        if(isset($_SESSION['cat_id'])){
          $get_proposals = $db->query("select DISTINCT level_id from proposals where proposal_cat_id=:cat_id AND proposal_status='active'",array("cat_id"=>$session_cat_id));
        }elseif(isset($_SESSION['cat_child_id'])){
          $get_proposals = $db->query("select DISTINCT level_id from proposals where proposal_child_id=:child_id AND proposal_status='active'",array("child_id"=>$session_cat_child_id));
        }
        while($row_proposals = $get_proposals->fetch()){
          $level_id = $row_proposals->level_id;
          $select_seller_levels = $db->select("seller_levels",array('level_id' => $level_id));
          $level_title = @$db->select("seller_levels_meta",array("level_id"=>$level_id,"language_id"=>$siteLanguage))->fetch()->title;
          if(!empty($level_title)){
      ?>
      <li class="nav-item checkbox checkbox-primary">
        <label>
        <input type="checkbox" value="<?= $level_id; ?>" class="get_seller_level"
          <?php if(isset($seller_level[$level_id])){ echo "checked"; } ?> >
        <span> <?= $level_title; ?> </span>
        </label>
      </li>
      <?php }} ?>
    </ul>
  </div>
</div>
<div class="card border-success mb-3">
  <div class="card-header bg-success">
    <h2 class="float-left text-white h5"><?= $lang['sidebar']['seller_lang']; ?></h2>
    <button class="btn btn-secondary btn-sm <?=($lang_dir == "right" ? 'float-left':'float-right')?> clear_seller_language clearlink" onclick="clearLanguage()">
      <i class="fa fa-times-circle"></i> Clear Filter
    </button>
  </div>
  <div class="card-body">
    <ul class="nav flex-column">
      <?php
        if(isset($_SESSION['cat_id'])){
          $get_proposals = $db->query("select DISTINCT language_id from proposals where not language_id='0' and proposal_cat_id=:cat_id AND proposal_status='active'",array("cat_id"=>$session_cat_id));
        }elseif(isset($_SESSION['cat_child_id'])){
          $get_proposals = $db->query("select DISTINCT language_id from proposals where not language_id='0' and proposal_child_id=:child_id AND proposal_status='active'",array("child_id"=>$session_cat_child_id));
        }
        while($row_proposals = $get_proposals->fetch()){
          $language_id = $row_proposals->language_id;
          $select_seller_languges = $db->select("seller_languages",array('language_id' => $language_id));
          $language_title = @$select_seller_languges->fetch()->language_title;
          if(!empty($language_title)){
      ?>
      <li class="nav-item checkbox checkbox-primary">
        <label>
        <input type="checkbox" value="<?= $language_id; ?>" class="get_seller_language"
          <?php if(isset($seller_language[$language_id])){ echo "checked"; } ?> >
        <span> <?= $language_title; ?> </span>
        </label>
      </li>
      <?php }} ?>
    </ul>
  </div>
</div>