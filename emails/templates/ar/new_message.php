<!-- subtitle -->
<div class="o_bg-light o_px-xs" align="center" style="background-color: #e8f2e8;padding-left: 8px;padding-right: 8px;">
  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
    <tbody>
      <tr>
        <td class="o_bg-white o_px-md o_pt" align="center" style="background-color: #ffffff;padding-left: 24px;padding-right: 24px;padding-top: 16px;"><h4 class="o_heading o_text-dark" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;color: #242b3d;font-size: 18px;line-height: 23px;">You have a message</h4>
          <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
            <tbody>
              <tr>
                <td width="140" class="o_bb-light" style="font-size: 8px;line-height: 8px;height: 8px;border-bottom: 1px solid #d3dce0;">&nbsp; </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<!-- discount_title -->
	 
<!-- message -->
<div class="o_bg-light o_px-xs" align="left" style="background-color: #e8f2e8;padding-left: 8px;padding-right: 8px;">
  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
    <tbody>
      <tr>
        <td class="o_bg-white o_px-md o_py-md o_sans o_text o_text-secondary" align="left" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;padding-left: 24px;padding-right: 24px;padding-top: 24px;padding-bottom: 24px;">
          <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
            <tbody>
              <tr>
                <td width="48" class="o_bb-light o_text-md o_text-secondary o_sans o_py" align="right" style="vertical-align: top;font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 19px;line-height: 28px;color: #424651;border-bottom: 1px solid #d3dce0;padding-top: 16px;padding-bottom: 16px;">
                  <img class="o_br-max" src="<?= img_url("person-circle.png"); ?>" width="48" height="48"style="max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;border-radius: 96px;">
                </td>
                <td class="o_bb-light o_text o_text-secondary o_sans o_px o_py" align="left" style="vertical-align: top;font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;border-bottom: 1px solid #d3dce0;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;"><p style="margin-top: 0px;margin-bottom: 0px;"><strong class="o_text-dark" style="color: #242b3d;"><?= $data['sender_user_name']; ?></strong> <span class="o_text-default o_text-xs" style="font-size: 14px;line-height: 21px;"> <span class="o_text-light" style="color: #82899a;"> ●</span> Sender</span></p>
                <p class="o_text-xxs o_text-light" style="font-size: 12px;line-height: 19px;color: #82899a;margin-top: 0px;margin-bottom: 0px;"><?= $data['message_date']; ?></p>

              </td>
              </tr>
            </tbody>
          </table>
          <div style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp; </div>
          <p class="o_mb-xs" style="margin-top: 0px;margin-bottom: 8px;"><strong>Message:</strong></p>
          <p class="o_mb-xs" style="margin-top: 0px;margin-bottom: 8px;"><?= $data['message']; ?> </p>
          <?php if(!empty($data['attachment'])){ ?>
          <a class="o_text-primary" href="#" style="color: #126de5;display: block;padding: 7px 8px;font-weight: bold; font-size: 14px;">
              <img src="<?= img_url("attachment.png"); ?>" width="24" height="24" style="max-width: 24px;;vertical-align: middle;"> <?= $data['attachment']; ?>
          </a>
          <?php } ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<!-- message_images -->

<!-- buttons -->
<div class="o_bg-light o_px-xs" align="center" style="background-color: #e8f2e8;padding-left: 8px;padding-right: 8px;">
  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
    <tbody>
      <tr>
        <td class="o_bg-white o_px o_pb-md" align="center" style="background-color: #ffffff;padding-left: 16px;padding-right: 16px;padding-bottom: 24px;">
          <div class="o_col_i" style="display: inline-block;vertical-align: top;">
            <div style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp; </div>
            <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
              <table align="center" cellspacing="0" cellpadding="0" border="0" role="presentation">
                <tbody>
                  <tr>
                    <td class="o_btn o_bg-primary o_br o_heading o_text" align="center" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;mso-padding-alt: 12px 24px;background-color:  <?= $site_color; ?>;border-radius: 4px;">
                      <a class="o_text-white" href='<?= $site_url; ?>/conversations/inbox?single_message_id=<?= $data['message_group_id']; ?>' style="text-decoration: none;outline: none;color: #ffffff;display: block;padding: 12px 24px;mso-text-raise: 3px;">Reply to Message</a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
