
<div class="box" align="center">
  <div class="container" style="max-width: 632px;margin: 0 auto;">
    <div class="row bg-green o_sans" style="background-color: <?= $site_color;?>;">
      
      <div class="icon-container">
        <div class="icon bg-white" align="center" style="">
          <img src="<?= img_url("check.png"); ?>" width="48" height="48">
        </div>
      </div>

      <h2 class="o_heading o_mb-xxs">Congratulations!</h2>

      <p class="o_mb-md">You just made a new sale</p>
    </div>
  </div>
</div>


<div class="o_bg-light o_px-xs" align="center">
  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
    <tbody>
      <tr>
        <td class="o_bg-white o_px-md o_py o_sans o_text o_text-secondary" align="center">
          <h4 class="o_heading o_text-dark o_mb-xs"></h4><h4 class="o_heading o_text-dark o_mb-xs selected-element" data-color="Dark" data-size="Heading 4" data-min="10" data-max="26">Hello, <?= $data['user_name']; ?></h4>
          <p class="o_mb-md">You just received an order from <?= $data['buyer_user_name']; ?>. Deliver as soon as possible to release your payment.</p>
          <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
            <tbody>
              <tr>
                <td width="300" class="o_btn o_bg-success o_br o_heading o_text" style="background-color: <?= $site_color; ?>;>" align="center"><a label="Button" class="o_text-white" href='<?= $site_url; ?>/order_details?order_id=<?= $data['order_id']; ?>'>View Order Details</a></td>
              </tr>
            </tbody>
          </table>
          
        </td>
      </tr>
    </tbody>
  </table>
</div>


<div class="o_bg-light o_px-xs" align="center">
  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
    <tbody>
      <tr>
        <td class="o_bg-white o_sans o_text-xs o_text-light o_px-md o_pt-xs" align="center">
          <p><br data-mce-bogus="1"></p><h4 class="o_heading o_text-dark selected-element" data-color="Dark" data-size="Heading 4" data-min="10" data-max="26">Order Summary</h4><p></p>
          <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
            <tbody>
              <tr>
                <td class="o_re o_bb-light" style="font-size: 8px; line-height: 8px; height: 8px;">&nbsp; </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
</div>


<div class="o_bg-light o_px-xs" align="center">
  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
    <tbody>
      <tr>
        <td class="o_re o_bg-white o_px o_pt" align="center">

          <div class="o_col o_col-3 o_col-full" style="margin-top:25px; margin-bottom: 25px;">
            <div class="o_px-xs o_sans o_text o_text-light o_left o_xs-center">
              <h4 class="o_heading o_text-dark"><strong><?= $data['proposal_title']; ?></strong></h4>

              <p class="o_text-xs o_mb-xs">
                Quantity: <?= $data['qty']; ?><br>
                Duration: <?= $data['duration']; ?><br>
                Buyer: <?= $data['buyer_user_name']; ?><br>
                <strong class="selected-element">Amount: </strong><?= showPrice($data['amount']); ?>
                <br>
              </p>

            </div>
          </div>

        </td>
      </tr>
    </tbody>
  </table>
</div>