<hr>
<?php if($proposal_price != 0){ ?>
<li class="basket-item mb-4">
<?php }else{ ?>
<li class="basket-item mb-0 <?=($lang_dir == "right" ? 'text-right':'')?>">
<?php } ?>
  <span class="item <?=($lang_dir == "right" ? 'text-right':'')?>"><span class="name"><span>Quantity:</span></span></span>
  <div class="quantity-control <?=($lang_dir == "right" ? 'text-right':'')?>">
    <div class="increase <?=($lang_dir == "right" ? 'text-right':'')?>">
    <?php include("../images/svg/plus.svg"); ?>
    </div>
    <span class="quantity <?=($lang_dir == "right" ? 'text-right':'')?>">1</span>
    <div class="decrease <?=($lang_dir == "right" ? 'text-right':'')?>">
    <?php include("../images/svg/minus.svg"); ?>
    </div>
  </div>
  <?php if($proposal_price != 0){ ?>
  <!-- <?= $s_currency; ?><span class="total-price"><?= $proposal_price; ?></span>.00 -->
  <?php } ?>
</li>