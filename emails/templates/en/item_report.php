<table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
  <tbody>
    <tr>
      <td class="o_bg-light o_px-xs" align="center" style="background-color: #E8F2E8;padding-left: 8px;padding-right: 8px;">
        <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
          <tbody>
            <tr>
              <td class="o_bg-primary o_px-md o_py-xl o_xs-py-md o_sans o_text-md o_text-white" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 19px;line-height: 28px;background-color: <?=$site_color;?>;;color: #ffffff;padding-left: 24px;padding-right: 24px;padding-top: 64px;padding-bottom: 64px;">
                <table cellspacing="0" cellpadding="0" border="0" role="presentation">
                  <tbody>
                    <tr>
                      <td class="o_sans o_text o_text-secondary o_bg-white o_px o_py o_br-max" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;border-radius: 96px;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;"><img src="<?= img_url("security.png"); ?>" width="48" height="48" alt="" style="max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;"></td>
                    </tr>
                    <tr>
                      <td style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp;</td>
                    </tr>
                  </tbody>
                </table>
                <h2 class="o_heading o_mb-xxs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;font-size: 30px;line-height: 39px;">Reported Item</h2>
                <p class="o_mb-md" style="margin-top: 0px;margin-bottom: 24px;">An item on <?= $site_name; ?> has just been reported.</p></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
	
<table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
  <tbody>
    <tr>
      <td class="o_bg-light o_px-xs" align="center" style="background-color: #E8F2E8;padding-left: 8px;padding-right: 8px;">
        <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
          <tbody>
            <tr>
              <td class="o_bg-white o_px-md o_py-md o_sans o_text o_text-secondary" align="left" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;padding-left: 24px;padding-right: 24px;padding-top: 24px;padding-bottom: 24px;"><h3 class="o_heading o_text-dark" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;color: #242b3d;font-size: 24px;line-height: 31px;">Details of reported item below:</h3>
                <p class="o_text-xxs o_text-light o_mb" style="font-size: 12px;line-height: 19px;color: #82899a;margin-top: 0px;margin-bottom: 16px;">
                  Item reported by: <strong><?= $data['seller_user_name']; ?></strong>
                </p>
                <p class="o_mb" style="margin-top: 0px;margin-bottom: 16px;">
                  <strong>Item Type: </strong><?= $data['item_type']; ?><br>
                  <strong>Item Author:</strong> <?= $data['author']; ?><br>
                </p>
                <p style="margin-top: 0px;margin-bottom: 0px;"><strong>Date Reported:</strong> <?= $data['date']; ?></p>                    
                <p style="margin-top: 0px;margin-bottom: 0px;"> <a class="o_text-secondary" href="<?= $data['item_link']; ?>" style="text-decoration: none;outline: none;color: #424651;"><strong>Item:</strong> <?= $data['item_link']; ?></a></p>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>