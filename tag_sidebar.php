<?php
  $online_sellers = array();
  $delivery_time = array();
  $seller_level = array();
  $seller_language = array();
  if(isset($_GET['online_sellers'])){
    foreach($_GET['online_sellers'] as $value){
      $online_sellers[$value] = $value;
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
<h1>tag sidebar</h1>
<div class="card border-success mb-3">
  <div class="card-body pb-2 pt-3">
    <ul class="nav flex-column">
      <li class="nav-item checkbox checkbox-success">
        <label>
        <input type="checkbox" value="1" class="get_online_sellers" 
          <?php if(isset($online_sellers["1"])){ echo "checked"; } ?>> 
        <span><?= $lang['sidebar']['online_sellers']; ?></span>
        </label>
      </li>
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
        $get_proposals = $db->query("select DISTINCT delivery_id from proposals where proposal_tags LIKE '%$tag%' AND proposal_status='active'");
        while($row_proposals = $get_proposals->fetch()){
        $delivery_id = $row_proposals->delivery_id;
        $select_delivery_time = $db->select("delivery_times",array('delivery_id' => $delivery_id));
        $delivery_title = $select_delivery_time->fetch()->delivery_title;
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
        $get_proposals = $db->query("select DISTINCT level_id from proposals where proposal_tags LIKE '%$tag%' AND proposal_status='active'");
        while($row_proposals = $get_proposals->fetch()){
        $level_id = $row_proposals->level_id;
        $select_seller_levels = $db->select("seller_levels",array('level_id' => $level_id));
        $level_title = $db->select("seller_levels_meta",array("level_id"=>$level_id,"language_id"=>$siteLanguage))->fetch()->title;
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
    <h3 class="<?=($lang_dir == "right" ? 'float-right':'float-left')?> text-white h5"><?= $lang['sidebar']['seller_lang']; ?></h3>
    <button class="btn btn-secondary btn-sm <?=($lang_dir == "right" ? 'float-left':'float-right')?> clear_seller_language clearlink" onclick="clearLanguage()">
    <i class="fa fa-times-circle"></i> Clear Filter
    </button>
  </div>
  <div class="card-body">
    <ul class="nav flex-column">
      <?php
        $get_proposals = $db->query("select DISTINCT language_id from proposals where not language_id='0' and proposal_tags LIKE '%$tag%' AND proposal_status='active'");
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