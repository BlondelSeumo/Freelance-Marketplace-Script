<?php if($count_extras > 0){ ?>
<hr>
<ul class="buyables m-b-25 list-unstyled <?=($lang_dir == "right" ? 'text-right':'')?>">
  <?php
  if($proposal_price == 0){
    $formId = "checkoutForm$packagenum";
  }else{
    $formId = "checkoutForm";
  }
  $i = 0;
  $total = 0;
  $get_extras = $db->select("proposals_extras",array("proposal_id"=>$proposal_id));
  while($row_extras = $get_extras->fetch()){
    $id = $row_extras->id;
    $name = $row_extras->name;
    $price = $row_extras->price;
    $total += $price;
    $i++;
  ?>
  <li class="<?=($lang_dir=="right" ? 'text-right':'')?>">
    <label class="<?=($lang_dir=="right"?'text-right':'')?>">
      <input class="mb-2" style="width:15px;height:15px;" type="checkbox" name="proposal_extras[<?= $i; ?>]" data-packagenum="<?= $packagenum; ?>" value="<?= $id; ?>" form="<?= $formId; ?>">
      <span class="js-express-delivery-text <?=($lang_dir=="right"?'text-right':'')?>">
        <?= $name; ?>
      </span>
      <span class='price <?=($lang_dir=="right"?'text-right':'')?>'>
        <b class='currency'><?= showPrice($price); ?></b>
        <b class="num d-none"><?= $price; ?></b>
      </span>
    </label>
  </li>
  <?php } ?>
</ul> 
<?php } ?>