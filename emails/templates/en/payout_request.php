<table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
  <tbody>
    <tr>
      <td class="o_bg-light o_px-xs" align="center" style="background-color: #e8f2e8;padding-left: 8px;padding-right: 8px;">
        <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
          <tbody>
            <tr>
              <td class="o_bg-white o_px-md o_py-xl o_xs-py-md o_sans o_text-md o_text-light" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 19px;line-height: 28px;background-color: #ffffff;color: #82899a;padding-left: 24px;padding-right: 24px;padding-top: 64px;padding-bottom: 64px;"><table role="presentation" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                    <tr>
                      <td class="o_bb-primary" height="40" width="32" style="border-bottom: 1px solid <?=$site_border_color;?>;;"> </td>
                      <td rowspan="2" class="o_sans o_text o_text-secondary o_px o_py" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;"><img src="<?= img_url("voice_over.png"); ?>" width="96" height="96" alt="" style="max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;"></td>
                      <td class="o_bb-primary" height="40" width="32" style="border-bottom: 1px solid <?=$site_border_color;?>;;"> </td>
                    </tr>
                    <tr>
                      <td height="40"> </td>
                      <td height="40"> </td>
                    </tr>
                    <tr>
                      <td style="font-size: 8px; line-height: 8px; height: 8px;"> </td>
                      <td style="font-size: 8px; line-height: 8px; height: 8px;"> </td>
                    </tr>
                </tbody>
                </table>
                <h2 class="o_heading o_text-dark o_mb-xxs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;color: #242b3d;font-size: 30px;line-height: 39px;">Payout Request </h2>
                <p class="o_mb-md" style="margin-top: 0px;margin-bottom: 24px;">Hello admin, the customer <?= $data['seller_user_name']; ?> just requested a payment for your services.</p>
                <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
                  <tbody>
                    <tr>
                      <td width="300" class="o_btn o_bg-primary o_br o_heading o_text" align="center" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;mso-padding-alt: 12px 24px;background-color: <?=$site_color;?>;;border-radius: 4px;"><a class="o_text-white" href='<?= $site_url; ?>/admin/index?pending_payouts' style="text-decoration: none;outline: none;color: #ffffff;display: block;padding: 12px 24px;mso-text-raise: 3px;">View Request Details</a></td>
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
</table><!-- hero-white -->