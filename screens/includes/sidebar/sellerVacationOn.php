<?php if($proposal_seller_user_name == @$_SESSION['seller_user_name']){ ?>
<h4 style="line-height: 25px;">
  Vacation mode is <span class="badge badge-success">ON</span>. Proposal is unavailable. New buyers cannot contact you.<br><br> Activate this proposal? <a class="text-success" href="<?= $site_url; ?>/proposals/view_proposals.php">Click here</a>
</h4>
<?php }else{ ?>
<h4 style="line-height: 25px;"> This seller is away. Vacation mode is <span class="badge badge-success">ON</span> </h4>
<?php } ?>