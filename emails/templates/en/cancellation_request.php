
<table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
  <tbody>
    <tr>
      <td class="o_bg-light o_px-xs" align="center" style="background-color: #e8f2e8;padding-left: 8px;padding-right: 8px;">
        <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
          <tbody>
            <tr>
              <td class="o_bg-primary o_px-md o_py-xl o_xs-py-md o_sans o_text-md o_text-white" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 19px;line-height: 28px;background-color: <?=$site_color;?>;;color: #ffffff;padding-left: 24px;padding-right: 24px;padding-top: 64px;padding-bottom: 64px;">
                <table cellspacing="0" cellpadding="0" border="0" role="presentation">
                  <tbody>
                    <tr>
                      <td class="o_sans o_text o_text-secondary o_bg-white o_px o_py o_br-max" align="center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;border-radius: 96px;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;"><img src="<?= img_url("cancel.png"); ?>" width="48" height="48" alt="" style="max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;"></td>
                    </tr>
                    <tr>
                      <td style="font-size: 24px; line-height: 24px; height: 24px;">&nbsp;</td>
                    </tr>
                  </tbody>
                </table>
                <h2 class="o_heading o_mb-xxs" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;font-size: 30px;line-height: 39px;">Cancellation Request</h2>
                <p class="o_mb-md" style="margin-top: 0px;margin-bottom: 24px;"><?= $data['sender_user_name']; ?> has requested to cancel this order. </p></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
	
<!-- invoice-details -->
<table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
 <tbody>
   <tr>
     <td class="o_bg-light o_px-xs" align="center" style="background-color: #e8f2e8;padding-left: 8px;padding-right: 8px;">
       <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
         <tbody>
           <tr>
             <td class="o_bg-white o_px-md o_py-md o_sans o_text o_text-secondary" align="left" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;background-color: #ffffff;color: #424651;padding-left: 24px;padding-right: 24px;padding-top: 24px;padding-bottom: 24px;"><h3 class="o_heading o_text-dark" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;color: #242b3d;font-size: 24px;line-height: 31px;"><strong>Order Number</strong> #<?= $data['order_number']; ?></h3>
               <p class="o_text-xxs o_text-light o_mb" style="font-size: 12px;line-height: 19px;color: #82899a;margin-top: 0px;margin-bottom: 16px;">Order details for cancellation</p>
               <p class="o_mb" style="margin-top: 0px;margin-bottom: 16px;"><strong><?= $data['proposal_title']; ?></strong><br>
                 <strong>Order Seller:</strong> <?= $data['seller_user_name']; ?><br>
                 <strong>Order Buyer:</strong> <?= $data['buyer_user_name']; ?> </p>
               <p style="margin-top: 0px;margin-bottom: 0px;">
                  <strong>Date of Cancellation Request:</strong>
                  <?= $data['date']; ?> 
               </p>
             </td>
           </tr>
           </tbody>
         </table>
       </td>
     </tr>
   </tbody>
</table>
