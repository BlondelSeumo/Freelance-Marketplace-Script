<div class="proposal-card-base slide list-view-card"><!--- proposal Starts --->
<div class="flex-row">
<div class="col-media">
<a href="<?= $site_url; ?>/proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_self">
<div class="media-container">
<div class="media-slider">
<div class="thumbnail-wrapper">
<img src="<?= $proposal_img1; ?>" class="proposal-thumbnail">
</div>
</div>
</div>
</a>
</div>
<div class="col-proposal-info w-100">
<a href="<?= $site_url; ?>/proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" class="proposal-link-main w-100"><h3><?= $proposal_title; ?></h3></a>
<div class="rating-badges-container">
  <span class="proposal-rating">
  <svg class="fit-svg-icon full_star" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
  	<path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"></path>
  </svg>
  <span>
  <strong><?php if($proposal_rating == "0"){ echo "0.0"; }else{ printf("%.1f", $average_rating); } ?></strong>
  (<?= $count_reviews; ?>)
  </span>
  </span>
</div>
</div>
</div>
<div class="flex-row space-between mobile">
<div class="proposal-seller-info">
<span class="fit-avatar s24">
<img src="<?= $seller_image; ?>" width="32" height="32">
</span>
<div class="seller-info-wrapper">
<a href="<?= $site_url; ?>/<?= $seller_user_name; ?>" rel="nofollow" class="seller-name">by <?= $seller_user_name; ?></a>
<div class="proposal-seller-tooltip badge-hint js-badge-hint hint--bottom-right" >
<span class="seller-level"><?= $seller_level; ?></span>
</div>
</div>
</div>
<div class="proposal-price">
<a class="js-proposal-card-imp-data" href="#">
<small>Starting At</small><?= showPrice($proposal_price); ?>
</a>
</div>
</div>
</div><!--- proposal Ends --->
