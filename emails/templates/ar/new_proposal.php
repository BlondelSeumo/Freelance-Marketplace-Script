
<!-- message -->
<table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
  <tbody>
    <tr>
      <td class="o_bg-light o_px-xs" align="center" style="background-color: #e8f2e8;padding-left: 8px;padding-right: 8px;">
        <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
          <tbody>
            <tr>
              <td class="o_bg-white o_px-md o_py-xl o_xs-py-md o_sans o_text-md o_text-light" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 19px;line-height: 28px;background-color: #ffffff;color: #82899a;padding-left: 24px;padding-right: 24px;padding-top: 64px;padding-bottom: 64px;"><table cellspacing="0" cellpadding="0" border="0" role="presentation">
                <tbody>
                  <tr>
                    <td class="o_sans o_text o_text-secondary o_b-primary o_px o_py o_br-max" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;border: 2px solid <?=$site_border_color;?>;border-radius: 96px;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;"><img src="<?= img_url("bell.png"); ?>" width="96" height="96" alt="" style="max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;"></td>
                    </tr>
                  <tr>
                    <td style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp;</td>
                    </tr>
                  </tbody>
                </table>
                <h2 class="o_heading o_text-dark o_mb-xxs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 30px;line-height: 39px;">New Proposal Created</h2>
                <p style="margin-top: 0px;margin-bottom: 0px;"><?= $data['seller_user_name']; ?> has just created a new proposal.</p></td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
</table>

<!-- service-primary -->
   <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
  <tbody>
    <tr>
      <td class="o_bg-light o_px-xs" align="center" style="background-color: #e8f2e8;padding-left: 8px;padding-right: 8px;">
        <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
          <tbody>
            <tr>
              <td class="o_bg-white o_px-md o_py-md o_sans o_text o_text-secondary" align="left" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;padding-left: 24px;padding-right: 24px;padding-top: 24px;padding-bottom: 24px;"><h3 class="o_heading o_text-dark" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;color: #242b3d;font-size: 24px;line-height: 31px;">Title: <?= $data['proposal_title']; ?></h3>
                <p class="o_text-xxs o_text-light o_mb" style="font-size: 12px;line-height: 19px;color: #82899a;margin-top: 0px;margin-bottom: 16px;">Details of the proposal created</p>
                <p class="o_mb" style="margin-top: 0px;margin-bottom: 16px;"><strong>Proposal Category:</strong> <?= $data['cat_title']; ?> <br>
                </p>
                <p style="margin-top: 0px;margin-bottom: 0px;"><strong>Proposal Seller:</strong> <?= $data['seller_user_name']; ?><br>
                
                <a class="o_text-secondary" href="#" style="text-decoration: none;outline: none;color: #424651;">
                  <strong>Proposal Status:</strong> <?= $data['proposal_status']; ?>
                </a>

                </p>

              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
<!-- service-light -->

<!-- button-success -->
<table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
  <tbody>
    <tr>
      <td class="o_bg-light o_px-xs" align="center" style="background-color: #e8f2e8;padding-left: 8px;padding-right: 8px;">
        <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
          <tbody>
            <tr>
              <td class="o_bg-white o_px-md o_py-xs" align="center" style="background-color: #ffffff;padding-left: 24px;padding-right: 24px;padding-top: 8px;padding-bottom: 8px;">
                <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
                  <tbody>
                    <tr>
                      <td width="300" class="o_btn o_bg-success o_br o_heading o_text" align="center" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;mso-padding-alt: 12px 24px;background-color: #0ec06e;border-radius: 4px;"><a class="o_text-white" href="<?= $site_url; ?>/admin/index?view_proposals" style="text-decoration: none;outline: none;background-color:<?=$site_color;?>;color: #ffffff;display: block;padding: 12px 24px;mso-text-raise: 3px;">View All Proposals</a></td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
<!-- button-dark -->