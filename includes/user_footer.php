<?php

$select_buyer_reviews = $db->select("buyer_reviews",array("review_seller_id" => $seller_id),"DESC");
$count_reviews = $select_buyer_reviews->rowCount();

if(!$count_reviews == 0){

?>
 
 <div class="row">

 	<div class="col-md-12">

 		<div class="card user-reviews mt-4 mb-4 rounded-0">

 			<div class="card-header make">

 				<h4>

                    <?= str_replace('{user_name}',$get_seller_user_name,$lang['user_profile']['reviews']); ?>

 					<?php

                        // for($seller_i=0; $seller_i<$average_rating; $seller_i++){

                        //     echo " <img class='rating' src='images/user_rate_full_big.png' > ";

                        // }

                        // for($seller_i=$average_rating; $seller_i<5; $seller_i++){

                        //     echo " <img class='rating' src='images/user_rate_blank_big.png' > ";

                        // }

                    ?>

 					<span class="text-muted"><?php printf("%.1f", $average); ?> (<?= $count_reviews; ?>) </span>

 					<div class="dropdown float-right">

 					<button id="<dropdown-button></dropdown-button>" class="btn btn-success dropdown-toggle" data-toggle="dropdown">

 						<?= $lang['button']['most_recent']; ?>
 						
 					</button>

 					<ul class="dropdown-menu">

 						<li class="dropdown-item active all"><?= $lang['button']['most_recent']; ?></li>
 						<li class="dropdown-item good"><?= $lang['button']['positive_reviews']; ?></li>
 						<li class="dropdown-item bad"><?= $lang['button']['negative_reviews']; ?></li>
 						
 					</ul>

                    </div>

 				</h4>

 			</div>

 			<div class="card-body">

 				<article id="all" class="proposal-reviews">

 					<ul class="reviews-list">
                        
                        <?php

                        while($row_buyer_reviews = $select_buyer_reviews->fetch()){
                        $review_buyer_id = $row_buyer_reviews->review_buyer_id;
                        $buyer_rating = $row_buyer_reviews->buyer_rating;
                        $buyer_review = $row_buyer_reviews->buyer_review;
                        $review_date = $row_buyer_reviews->review_date;

                        $select_buyer = $db->select("sellers",array("seller_id" => $review_buyer_id));
                        $row_buyer = $select_buyer->fetch();
                        $buyer_user_name = $row_buyer->seller_user_name;
                        $buyer_image = getImageUrl2("sellers","seller_image",$row_buyer->seller_image);

                        ?>

 						<li class="star-rating-row">

 							<span class="user-picture">
                                
                        <?php if(!empty($buyer_image)){ ?>
 								   <img src="<?= $buyer_image; ?>" width="60" height="60">
                        <?php }else{ ?>
                           <img src="user_images/empty-image.png" width="60" height="60">
                        <?php } ?>
 								
 							</span>

 							<h4>

 								<a href="#" class="mr-1 text-success"> <?= $buyer_user_name; ?> </a>

 								<?php

                                    for($buyer_i=0; $buyer_i<$buyer_rating; $buyer_i++){

                                    echo " <img class='rating' src='images/user_rate_full.png' > ";

                                    }

                                    for($buyer_i=$buyer_rating; $buyer_i<5; $buyer_i++){

                                    echo " <img class='rating' src='images/user_rate_blank.png' > ";

                                    }

                                ?>	

 							</h4>

 							<div class="msg-body">

 							<?= $buyer_review; ?>

 							</div>

 							<span class="rating-date"><?= $review_date; ?></span>

 						</li>

 						<hr>

                        <?php } ?>

 					</ul>
 					
 				</article>



 				<article id="good" class="proposal-reviews">

 					<ul class="reviews-list">
                        
                        <?php

                            $select_buyer_reviews = $db->query("select * from buyer_reviews where review_seller_id='$seller_id' AND (buyer_rating='5' or buyer_rating='4') order by 1 DESC");

                            $count_reviews = $select_buyer_reviews->rowCount();

                            if($count_reviews == 0){

                                echo "

                                <li>

                                <h3 align='center'> There is currently no positive review for this proposal/service. </h3>

                                </li>

                                ";

                            }

                        while($row_buyer_reviews = $select_buyer_reviews->fetch()){

                        $review_buyer_id = $row_buyer_reviews->review_buyer_id;

                        $buyer_rating = $row_buyer_reviews->buyer_rating;

                        $buyer_review = $row_buyer_reviews->buyer_review;

                        $review_date = $row_buyer_reviews->review_date;


                        $select_buyer = $db->select("sellers",array("seller_id" => $review_buyer_id));

                        $row_buyer = $select_buyer->fetch();

                        $buyer_user_name = $row_buyer->seller_user_name;

                        $buyer_image = getImageUrl2("sellers","seller_image",$row_buyer->seller_image);


                        ?>

 						<li class="star-rating-row">

 							<span class="user-picture">
                                
                                <?php if(!empty($buyer_image)){ ?>

 								   <img src="<?= $buyer_image; ?>" width="60" height="60">
                                
                                <?php }else{ ?>

                                    <img src="user_images/empty-image.png" width="60" height="60">

                                <?php } ?>
 								
 							</span>

 							<h4>

 								<a href="#" class="mr-1 text-success"> <?= $buyer_user_name; ?> </a>

 								<?php

                                    for($buyer_i=0; $buyer_i<$buyer_rating; $buyer_i++){

                                        echo " <img class='rating' src='images/user_rate_full.png' > ";

                                    }

                                    for($buyer_i=$buyer_rating; $buyer_i<5; $buyer_i++){

                                        echo " <img class='rating' src='images/user_rate_blank.png' > ";

                                    }


                                ?>	

 							</h4>


 							<div class="msg-body">

 								<?= $buyer_review; ?>

 							</div>

 							<span class="rating-date"><?= $review_date; ?></span>
 						
 						</li>

 						<hr>
                        
                        <?php } ?>

 					</ul>
 					
 				</article>

 				<article id="bad" class="proposal-reviews">

 					<ul class="reviews-list">
                        
                        <?php

                            $select_buyer_reviews = $db->query("select * from buyer_reviews where review_seller_id='$seller_id' AND (buyer_rating='3' or buyer_rating='2' or buyer_rating='1') order by 1 DESC");

                            $count_reviews = $select_buyer_reviews->rowCount();

                            if($count_reviews == 0){

                                echo "

                                <li>

                                <h3 align='center'> There is currently no negative review for this seller. </h3>

                                </li>

                                ";


                            }

                        while($row_buyer_reviews = $select_buyer_reviews->fetch()){

                        $review_buyer_id = $row_buyer_reviews->review_buyer_id;

                        $buyer_rating = $row_buyer_reviews->buyer_rating;

                        $buyer_review = $row_buyer_reviews->buyer_review;

                        $review_date = $row_buyer_reviews->review_date;


                        $select_buyer = $db->select("sellers",array("seller_id" => $review_buyer_id));

                        $row_buyer = $select_buyer->fetch();

                        $buyer_user_name = $row_buyer->seller_user_name;

                        $buyer_image = getImageUrl2("sellers","seller_image",$row_buyer->seller_image);


                        ?>


 						<li class="star-rating-row">

 							<span class="user-picture">
                                
                        <?php if(!empty($buyer_image)){ ?>
 								   <img src="<?= $buyer_image; ?>" width="60" height="60">
                        <?php }else{ ?>
                           <img src="user_images/empty-image.png" width="60" height="60">
                        <?php } ?>
 								
 							</span>

 							<h4>

 								<a href="#" class="mr-1 text-success"> <?= $buyer_user_name; ?> </a>

 								<?php

                                    for($buyer_i=0; $buyer_i<$buyer_rating; $buyer_i++){

                                        echo " <img class='rating' src='images/user_rate_full.png' > ";

                                    }

                                    for($buyer_i=$buyer_rating; $buyer_i<5; $buyer_i++){

                                        echo " <img class='rating' src='images/user_rate_blank.png' > ";

                                    }

                                ?>	

 							</h4>


 							<div class="msg-body">

 								<?= $buyer_review; ?>

 							</div>

 							<span class="rating-date"><?= $review_date; ?></span> 							

 						</li>
 						
                        <?php } ?>

 					</ul>
 					
 				</article>


 			</div>

 		</div>


 	</div>



 </div>

 <?php } ?>
