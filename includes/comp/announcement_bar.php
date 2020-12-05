<?php if($enable_bar == "1"){ ?>
  <div id="announcement_bar" style="background-color:<?= $bg_color; ?>;color:<?= $text_color; ?>;">
    <span class="time d-none"><?= $bar_last_updated; ?></span>
    <?= $bar_text; ?>
    <a href="#" class="float-right close-icon">
      <i class="fa fa-times"></i>
    </a>
  </div>
  <div id="announcement_bar_margin"></div>
<?php } ?>