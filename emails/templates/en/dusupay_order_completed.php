
<table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
  <tbody>
    <tr>
      <td class="o_bg-light o_px-xs" align="center" style="background-color: #E8F2E8;padding-left: 8px;padding-right: 8px;">
        <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
          <tbody>
            <tr>
              <td class="o_bg-white o_px-md o_py o_sans o_text o_text-secondary" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;padding-left: 24px;padding-right: 24px;padding-top: 16px;padding-bottom: 16px;"><table cellspacing="0" cellpadding="0" border="0" role="presentation">
                <tbody>
                  <tr>
                    <td class="o_sans o_text o_text-secondary o_b-primary o_px o_py o_br-max" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;border: 2px solid <?= $site_border_color; ?>;border-radius: 96px;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;"><img src="<?= img_url("check.png"); ?>" width="48" height="48" alt="" style="max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;"></td>
                  </tr>
                  <tr>
                    <td style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp;</td>
                  </tr>
                </tbody>
              </table>
                <h4 class="o_heading o_text-dark o_mb-xs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 8px;color: #242b3d;font-size: 18px;line-height: 23px;">Hello, <?= $data['user_name']; ?></h4>
                
                <p class="o_mb-md text-left" style="margin-top: 0px;margin-bottom: 24px;">
                  
                  <?= $data['message']; ?>

                </p>

                <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
                  <tbody>
                    <tr>
                      <td width="300" class="o_btn o_bg-success o_br o_heading o_text" align="center" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;mso-padding-alt: 12px 24px;background-color: <?= $site_color; ?>;border-radius: 4px;"><a class="o_text-white" href="<?= $data['link_url']; ?>" style="text-decoration: none;outline: none;color: #ffffff;display: block;padding: 12px 24px;mso-text-raise: 3px;">Complete Order</a></td>
                    </tr>
                  </tbody>
                </table>
                
                <p class="o_mb" style="margin-top: 16px;margin-bottom: 16px;">
                  If the button doesn't work, copy that link
                </p>

                <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                  <tbody>
                    <tr>
                      <td width="284" class="o_bg-ultra_light o_br o_text-xs o_sans o_px-xs o_py" align="center" style="font-family: Helvetica, Arial, sans-serif; margin-top: 0px; margin-bottom: 0px; font-size: 14px; line-height: 21px; background-color: #e8f2e8; border-radius: 4px; padding-left: 8px; padding-right: 8px; padding-top: 16px; padding-bottom: 16px;">

                        <p class="o_text-dark" style="color: #242b3d;margin-top: 0px;margin-bottom: 0px;">
                          <?= $data['link_url']; ?>
                        </p>

                      </td>
                    </tr>
                  </tbody>
                </table>


            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table><!-- hero-white -->

