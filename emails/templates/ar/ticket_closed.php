
<div class="box" align="center">
  <div class="container" style="max-width: 632px;margin: 0 auto;">
    <div class="row bg-white o_sans">

      <div class="icon-container">
        <div class="icon bg-green" align="center" style="background-color: <?= $site_color;?>;">
          <img src="<?= img_url("check-white.png"); ?>" width="48" height="48">
        </div>
      </div>

      <h2 class="o_heading">Dear <?= $data['user_name']; ?></h2>

      <p class="text-left text-muted" style="margin-bottom: 5px; margin-top: 15px;">
        We have just closed your ticket. If your issue still hasn't been resolved, please contact us by either replying to this email, or using the customer support channel on our website.
      </p>

      <p class="text-left text-muted" style="margin-bottom: 5px;">Best Regards,</p>

      <p class="text-left text-muted" style="margin-bottom: 0px;"><?= $site_name; ?> Team.</p>

    </div>
  </div>
</div>