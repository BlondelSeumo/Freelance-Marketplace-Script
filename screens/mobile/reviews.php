<div class="reviews-package mb-3"><!--- reviews-package Starts --->
<header><h2> Reviews<small>
<span class="star-rating-s15">
  <svg class="fit-svg-icon full_star" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
  	<path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"></path>
  </svg>
</span>
<span class="total-rating-out-five"><?php if($proposal_rating == "0"){echo "0.0";	}else{printf("%.1f", $average_rating);}	?></span>
<span class="total-rating">(<?= $count_reviews; ?>)</span>
</small>
</h2>
<span class="ficon ficon-chevron-down"></span> 
<div class="filter-dd rf">
<select>
<option class="js-gtm-event-auto" value="all">Most Recent</option>
<option class="js-gtm-event-auto" value="good">Positive Reviews</option>
<option class="js-gtm-event-auto" value="bad">Negative Reviews</option>
</select>
</div>
</header>
<div class="reviews-wrap"><!--- reviews-wrap Starts --->
<?php include("mobile_proposal_reviews.php"); ?>
</div><!--- reviews-wrap Ends --->
</div><!--- reviews-package Ends --->