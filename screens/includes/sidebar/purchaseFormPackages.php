<form method="post" action="../../checkout" id="checkoutForm<?= $packagenum; ?>" class="<?=($lang_dir == "right" ? 'text-right':'')?>">
  <input type="hidden" name="proposal_id" value="<?= $proposal_id; ?>">
  <input type="hidden" name="package_id" value="<?= $package_id; ?>">
  <input type="hidden" name="proposal_qty" value="1">
  <h3>
      <?= $package_name; ?>
      <span class="<?=($lang_dir == "left" ? 'float-right':'')?> font-weight-normal">
        <!-- <?= $s_currency; ?><span class="<?= $priceClass; ?>"><?= $price; ?> -->
        <?= showPrice($price,$priceClass); ?>
      </span>
      <span class="<?= "$priceClass-num"; ?> d-none"><?= $price; ?></span>
    </span>
  </h3>
  <p><?= $row->description; ?></p>
  <h6 class="mb-3">
    <i class="fa fa-clock-o"></i> <?= $delivery_time; ?> Days Delivery &nbsp; &nbsp; <i class="fa fa-refresh"></i>
    
    <?php
      if($row->revisions != "unlimited"){
        echo $row->revisions.' Revisions';
      }else{
        echo "Unlimited Revisions";
      }
    ?>

  </h6>
  <?php include('buttons.php'); ?>
</form>