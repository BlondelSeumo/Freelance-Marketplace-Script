<?php if($enable_converter == 1){ ?>
<div class="card border-success mb-3">
  <div class="card-header bg-success">
    <h3 class="<?=($lang_dir == "right" ? 'float-right':'float-left')?> h5 text-white"><?= $lang['sidebar']['change_currency']; ?></h3>
  </div>
  <div class="card-body">

    <select id="currencySelect" class="form-control">
      <option data-url="<?= "$site_url/change_currency?id=0"; ?>">
        <?= "$s_currency_name ($s_currency)"; ?>
      </option>
      <?php
      $get_currencies = $db->select("site_currencies");
      while($row = $get_currencies->fetch()){
      $id = $row->id;
      $currency_id = $row->currency_id;
      $position = $row->position;

      $get_currency = $db->select("currencies",array("id" =>$currency_id));
      $row_currency = $get_currency->fetch();
      $name = $row_currency->name;
      $symbol = $row_currency->symbol;
      ?>
      <option data-url="<?= "$site_url/change_currency?id=$id"; ?>" <?php if($id == @$_SESSION["siteCurrency"]){ echo "selected"; } ?>>
        <?= $name; ?> (<?= $symbol ?>)
      </option>
      <?php } ?>
    </select>

  </div>
</div>
<?php } ?>