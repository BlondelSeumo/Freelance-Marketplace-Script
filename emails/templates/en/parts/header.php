<?php
$get_general_settings = $db->select("general_settings");
$row_general_settings = $get_general_settings->fetch();
$site_color = $row_general_settings->site_color;
$site_hover_color = $row_general_settings->site_hover_color;
$site_border_color = $row_general_settings->site_border_color;
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="date=no">
<meta name="format-detection" content="address=no">
<meta name="format-detection" content="email=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="x-apple-disable-message-reformatting">
<meta charset="utf-8">
<style type="text/css">

  body{ 
    margin: 0; width: 100%; height: auto;
    background-color: #e8f2e8;
  }
  a { text-decoration: none; outline: none; }
  @media (max-width: 649px) {
    .o_col-full { max-width: 100% !important; }
    .o_col-half { max-width: 50% !important; }
    .o_hide-lg { display: inline-block !important; font-size: inherit !important; max-height: none !important; line-height: inherit !important; overflow: visible !important; width: auto !important; visibility: visible !important; }
    .o_hide-xs, .o_hide-xs.o_col_i { display: none !important; font-size: 0 !important; max-height: 0 !important; width: 0 !important; line-height: 0 !important; overflow: hidden !important; visibility: hidden !important; height: 0 !important; }
    .o_xs-center { text-align: center !important; }
    .o_xs-left { text-align: left !important; }
    .o_xs-right { text-align: left !important; }
    table.o_xs-left { margin-left: 0 !important; margin-right: auto !important; float: none !important; }
    table.o_xs-right { margin-left: auto !important; margin-right: 0 !important; float: none !important; }
    table.o_xs-center { margin-left: auto !important; margin-right: auto !important; float: none !important; }
    h1.o_heading { font-size: 32px !important; line-height: 41px !important; }
    h2.o_heading { font-size: 26px !important; line-height: 37px !important; }
    h3.o_heading { font-size: 20px !important; line-height: 30px !important; }
    .o_xs-py-md { padding-top: 24px !important; padding-bottom: 24px !important; }
    .o_xs-pt-xs { padding-top: 8px !important; }
    .o_xs-pb-xs { padding-bottom: 8px !important; }
  }
  @media screen {
    @font-face {
      font-family: 'Roboto';
      font-style: normal;
      font-weight: 400;
      src: local("Roboto"), local("Roboto-Regular"), url(https://fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu7GxKOzY.woff2) format("woff2");
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF; }
    @font-face {
      font-family: 'Roboto';
      font-style: normal;
      font-weight: 400;
      src: local("Roboto"), local("Roboto-Regular"), url(https://fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu4mxK.woff2) format("woff2");
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD; }
    @font-face {
      font-family: 'Roboto';
      font-style: normal;
      font-weight: 700;
      src: local("Roboto Bold"), local("Roboto-Bold"), url(https://fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfChc4EsA.woff2) format("woff2");
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF; }
    @font-face {
      font-family: 'Roboto';
      font-style: normal;
      font-weight: 700;
      src: local("Roboto Bold"), local("Roboto-Bold"), url(https://fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfBBc4.woff2) format("woff2");
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD; }
    .o_sans, .o_heading { font-family: "Roboto", sans-serif !important; }
    .o_heading, strong, b { font-weight: 700 !important; }
    a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; }
  }
  .o_heading1 {font-family: "Roboto", sans-serif !important; }


  .box{
    background-color: #E8F2E8;
    /*padding-left: 8px;
    padding-right: 8px;*/
  }

  .box .container:first-child{
    max-width: 632px;margin: 0 auto;
  }

  .box .row{
    font-family: Helvetica, Arial, sans-serif;
    margin-top: 0px;
    margin-bottom: 0px;
    font-size: 19px;
    line-height: 28px;
    padding-left: 24px;
    padding-right: 24px;
    padding-top: 64px;
    padding-bottom: 64px;
  }

  .box .row.bg-white{
    background-color: white;
    color: black;
  }

  .box .row.bg-green{
    background-color: #0ec06e;
    color: #ffffff;
  }

  .box .icon-container{
    max-width: 632px;
    margin: 0 auto;
    display: table;
    margin-bottom: 20px;
  }

  .box .icon{
    font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;color: #424651;border-radius: 96px;padding-left: 16px;padding-right: 16px;padding-top: 16px;padding-bottom: 16px;
  }

  .box .icon.bg-white{
    background-color: #ffffff;
  }

  .box .icon.bg-green{
    background-color: #0EC06E;
  }

  .box .icon img{
    max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;
  }

  .box h2{
    font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 4px;font-size: 30px;line-height: 39px;
  }

  .box p{
    margin-top: 0px;margin-bottom: 24px;
  }

  .box p.text-muted{
    color:#82899a;
  }
  .box p.text-left{
    text-align: left;
  }

  .box .btn{
    font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;mso-padding-alt: 12px 24px;border-radius: 4px; width:300px;
  }

  .box .btn a{
    text-decoration: none;outline: none;display: block;padding: 12px 24px;mso-text-raise: 3px;
  }

  .btn-white{
    background-color: #ffffff;
  }

  .btn-white a{
    color: #0EC06E;
  }

  .btn-green{
    background-color: #0EC06E;
  }

  .btn-green a{
    color: #ffffff;
  }
  

<?php if($data['template'] == "order_email" OR $data['template'] == "order_receipt"){ ?>

  .o_px-xs {
      padding-left: 8px;
      padding-right: 8px;
  }

  .o_bg-light {
      background-color: #e8f2e8;
  }

  .o_block {
      max-width: 632px;
      margin: 0 auto;
  }

  .o_bg-white {
      background-color: #ffffff;
  }
  .o_text {
      font-size: 16px;
      line-height: 24px;
  }
  .o_sans, 
  .o_heading, 
  p, li {
      margin-top: 0px;
      margin-bottom: 0px;
  }

  .o_body .o_mb-xs {
      margin-bottom: 8px;
  }
  h4.o_heading {
      font-size: 18px;
      line-height: 23px;
  }
  .o_heading, strong, b {
      font-weight: 700 !important;
  }
  .o_sans, .o_heading {
      font-family: "Roboto", sans-serif !important;
  }

  .o_px-md {
      padding-left: 24px;
      padding-right: 24px;
  }

  .o_py {
      padding-top: 16px;
      padding-bottom: 16px;
  }

  .o_body .o_mb-md {
      margin-bottom: 24px;
  }

  .o_text-secondary, a.o_text-secondary span, 
  a.o_text-secondary strong, 
  .o_text-secondary.o_link a {
      color: #424651;
  }

  .o_bg-success {
      background-color: #0ec06e;
  }

  .o_br {
      border-radius: 4px;
  }

  .o_btn a {
      display: block;
      padding: 12px 24px;
      mso-text-raise: 3px;
  }
  .o_text-white, a.o_text-white span, a.o_text-white strong, .o_text-white.o_link a {
      color: #ffffff !important;
  }

  .o_bb-light {
      border-bottom: 1px solid #d3dce0;
  }

  .o_text-dark, a.o_text-dark span, a.o_text-dark strong, .o_text-dark.o_link a {
      color: #242b3d;
  }

  .o_text-light, a.o_text-light span, a.o_text-light strong, .o_text-light.o_link a {
      color: #82899a;
  }

  .o_body .o_mb-xs {
      margin-bottom: 8px;
  }
  .o_text-xs {
      font-size: 14px;
      line-height: 21px;
  }

<?php } ?>

</style>
</head>
<body class="o_body o_bg-light">

<!-- header-white-link -->
<div class="o_bg-light o_px-xs o_pt-lg o_xs-pt-xs" align="center" style="background-color: #e8f2e8;padding-left: 8px;padding-right: 8px;padding-top: 32px;">
  <table class="o_block" width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation" style="max-width: 632px;margin: 0 auto;">
    <tbody>
      <tr>
        <td class="o_re o_bg-white o_px o_pb-md o_br-t" align="center" style="font-size: 0;vertical-align: top;background-color: #ffffff;border-radius: 4px 4px 0px 0px;padding-left: 16px;padding-right: 16px;padding-bottom: 24px;">
          <div class="o_col o_col-2" style="display: inline-block;vertical-align: top;width: 100%;max-width: 200px;">
            <div style="font-size: 24px; line-height: 24px; height: 24px;">  </div>
            <div class="o_px-xs o_sans o_text o_left o_xs-center" style="font-family: Helvetica, Arial, sans-serif;margin-top: 0px;margin-bottom: 0px;font-size: 16px;line-height: 24px;text-align: left;padding-left: 8px;padding-right: 8px;">
              <p style="margin-top: 0px;margin-bottom: 0px;">
                <a class="o_text-primary" href="<?= $site_url; ?>" style="text-decoration: none;outline: none;color: #126de5;">
                  <img src="<?= $site_logo; ?>" width="136" height="36" alt="<?= $site_name; ?>" style="max-width: 136px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;">
                </a>
              </p>
            </div>
          </div>
          <div class="o_col o_col-4" style="display: inline-block;vertical-align: top;width: 100%;max-width: 400px;">
            <div style="font-size: 22px; line-height: 22px; height: 22px;">  </div>
            <div class="o_px-xs" style="padding-left: 8px;padding-right: 8px;">
              <table class="o_right o_xs-center" cellspacing="0" cellpadding="0" border="0" role="presentation" style="text-align: right;margin-left: auto;margin-right: 0;">
                <tbody>
                  <tr>
                    <td class="o_btn-b o_heading o_text-xs" align="center" style="font-family: Helvetica, Arial, sans-serif;font-weight: bold;margin-top: 0px;margin-bottom: 0px;font-size: 14px;line-height: 21px;mso-padding-alt: 7px 8px;">
                      <a class="o_text-light" href="#" style="text-decoration: none;outline: none;color: #82899a;display: block;padding: 7px 8px;font-weight: bold;">

                        <span style="mso-text-raise: 6px;display: inline;color: #82899a;">
                        Hello <?= @$data['user_name']; ?>
                        </span> 

                        <img src="<?= img_url("person.png"); ?>" width="24" height="24" style="max-width: 24px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;">

                      </a>
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

