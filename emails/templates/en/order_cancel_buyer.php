<table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
  <tbody>
    <tr>
      <td class="o_bg-light o_px-xs" align="center" style="background-color: #e8f2e8;padding-left: 8px;padding-right: 8px;">
        <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
          <tbody>
            <tr>
              <td class="o_bg-primary o_px-md o_py-xl o_xs-py-md o_sans o_text-md o_text-white" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 19px;line-height: 28px;background-color: <?= $site_color;?>;color: #ffffff;padding-left: 24px;padding-right: 24px;padding-top: 64px;padding-bottom: 64px;">
                <table cellspacing="0" cellpadding="0" border="0" role="presentation">
                  <tbody>
                    <tr>
                      <td class="o_sans o_text o_text-secondary o_bg-white o_px o_py o_br-max" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;border-radius: 96px;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;"><img src="<?= img_url("cancel.png"); ?>" width="96" height="96" alt="" style="max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;"></td>
                    </tr>
                    <tr>
                      <td style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp;</td>
                    </tr>
                  </tbody>
                </table>
                <h2 class="o_heading o_mb-xxs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;font-size: 30px;line-height: 39px;">Order Canceled</h2>
                <p class="o_mb-md" style="margin-top: 0px;margin-bottom: 24px;">Your Order Has Been Cancelled And All Funds Returned To Your Shopping Balance.</p>
                <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
                  <tbody>
                    <tr>
                      <td width="300" class="o_btn o_bg-white o_br o_heading o_text" align="center" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;mso-padding-alt: 12px 24px;background-color: #ffffff;border-radius: 4px;"><a class="o_text-primary" href='<?= $site_url; ?>/order_details?order_id=<?= $data['order_id']; ?>' style="text-decoration: none;outline: none;color: <?= $site_color;?>;display: block;padding: 12px 24px;mso-text-raise: 3px;">View Order Details</a></td>
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