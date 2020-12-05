<?php if(!isset($_SESSION["seller_user_name"])){ ?>

<ul class="list-inline top_btn">

 <li class="list-inline-item"><a href="#" data-toggle="modal" data-target="#register-modal"><?= $lang['become_seller']; ?></a></li><br>
 
 <li class="list-inline-item"><a href="#" data-toggle="modal" data-target="#login-modal"><?= $lang['sign_in']; ?></a></li><br>

  <li class="list-inline-item">
		  
  <button class="btn btn_join" data-toggle="modal" data-target="#register-modal"><?= $lang['join_now']; ?></button>

  </li>		  
		  
</ul>

<?php }else{ ?>

		<?php 

		$count_unread_notifications = $db->count("notifications",array("receiver_id" => $seller_id,"status" => "unread"));

		$count_unread_inbox_messages = $db->query("select * from inbox_messages where message_receiver=:r_id AND message_status='unread'",array("r_id"=>$seller_id))->rowCount();

		$count_favourites = $db->count("favorites",array("seller_id" => $seller_id));

		?>

        <ul class="list-inline top_login_btn">

 		<li class="list-inline-item">
		  
		<a class="" href="<?= $site_url; ?>/dashboard" >

		<img src="<?= $site_url; ?>/images/icon1.png"><span class="d-lg-none"> <?= $lang['dashboard']['title']; ?></span>

		</a>
		  
		 </li><br>

		  <li class="list-inline-item">
		  
  				 	<a href="#" class="d-icon dropdown-toggle mr-lg-2"  data-toggle="dropdown" title="Notifications">

			 			<img src="<?= $site_url; ?>/images/icon2.png">
	                
	                	<span class="d-lg-none">

			 			<?= $lang['notifications']; ?> 
	                    
	                    <?php if($count_unread_notifications > 0){ ?>
	                    
	       				<span class="badge badge-pill badge-danger"><?= $count_unread_notifications; ?> New</span>
	                    
	                    <?php } ?>
					 			
					 	</span>

				 	</a>

				 	<div class="dropdown-menu notifications-dropdown" style="width:110% !important; position:relative;">

					

      			 	</div>
				 	
		  </li><br>

		 <li class="list-inline-item">

       				 	<a href="#" class="d-icon dropdown-toggle mr-lg-2" data-toggle="dropdown" title="Inbox Messages">

				 		<img src="<?= $site_url; ?>/images/icon4.png">

				 		<span class="d-lg-none">

				 		<?= $lang['messages']; ?> 
                            
                        <?php if($count_unread_inbox_messages > 0 ){ ?>
                            
                         <span class="badge badge-pill badge-danger"><?= $count_unread_inbox_messages; ?> New</span>
                            
                        <?php } ?>

				 		</span>

				 	</a>

				 	<div class="dropdown-menu messages-dropdown" style="width:135% !important; position:relative;">

					

				 	</div>
				 
          </li><br>

         	<li class="list-inline-item">

			<a class=" mr-lg-2" href="<?= $site_url; ?>/favorites" title="Favorites">

				 		<img src="<?= $site_url; ?>/images/icon5.png">
                        
                          <span class="d-lg-none">

				 			<?= $lang['favorites']; ?>  
                            
                            <?php if($count_favourites > 0){ ?>
                            
                             <span class="badge badge-pill badge-success"> <?= $count_favourites; ?> </span> 
                            
                            <?php } ?>
				 			
				 		</span>
				 		
				 	</a>

            </li><br>
			

   			<li class="list-inline-item">

			<a class=" mr-lg-2" href="<?= $site_url; ?>/cart" title="Cart">

				 		<img src="<?= $site_url; ?>/images/icon6.png">
                        
				 		<span class="d-lg-none">

				 			<?= $lang['cart']; ?> 
                            
                            <?php if($count_cart > 0){ ?>
                            
                                <span class="badge badge-pill badge-success"> <?= $count_cart; ?> </span> 
                            
                            <?php } ?>
				 			
				 		</span>
				 		
				 	</a>

            </li><br>
			
		  
		  <li class="list-inline-item">
		  
		  				 	<div class="dropdown">

				 		<a href="#" style="color:black;" class="dropdown-toggle" data-toggle="dropdown">
                            
                            <?php if(!empty($seller_image)){ ?>

				 			<img src="<?= $site_url; ?>/user_images/<?= $seller_image; ?>" width="36" height="35" class="rounded-circle">
                                                        
							<?php }else{ ?>

                            <img src="<?= $site_url; ?>/user_images/empty-image.png" width="27" height="27" class="rounded-circle">

                            <?php } ?>
                            
                            <?= $_SESSION['seller_user_name']; ?>     
                            
                            <?php if($current_balance > 0){ ?>

				 			<span class="badge badge-success">

				 			<?= showPrice($current_balance); ?>
				 				
				 			</span>
                            
                            <?php } ?>

				 		</a>

				 		<div class="dropdown-menu dropdown-menu-2" style="width:200px !important;">

						<a class="dropdown-item" href="<?= $site_url; ?>/dashboard">

						<?= $lang['dashboard']['title']; ?>

						</a>

						<a class="dropdown-item dropdown-toggle" href="#" data-toggle="collapse" data-target="#selling-2">

						<?= $lang['selling']; ?>

						</a>

						<div id="selling-2" class="dropdown-submenu collapse">

						<a class="dropdown-item" href="<?= $site_url; ?>/selling_orders">

						<?= $lang['orders']; ?>

						</a>

						<a class="dropdown-item" href="<?= $site_url; ?>/proposals/view_proposals">

						<?= $lang['my_proposals']; ?>

						</a>

						<a class="dropdown-item" href="<?= $site_url; ?>/requests/buyer_requests">

						<?= $lang['buyer_requests']; ?>

						</a>

						<a class="dropdown-item" href="<?= $site_url; ?>/revenue">

						<?= $lang['revenues']; ?>

						</a>

						</div>

						<a class="dropdown-item dropdown-toggle" href="#" data-toggle="collapse" data-target="#buying-2">

						<?= $lang['buying']; ?>

						</a>

						<div id="buying-2" class="dropdown-submenu collapse">

						<a class="dropdown-item" href="<?= $site_url; ?>/buying_orders">

						<?= $lang['orders']; ?>

						</a>

						<a class="dropdown-item" href="<?= $site_url; ?>/purchases">

						<?= $lang['purchases']; ?>

						</a>

						<a class="dropdown-item" href="<?= $site_url; ?>/favorites">

						<?= $lang['favorites']; ?>

						</a>

						</div>


						<a class="dropdown-item dropdown-toggle" href="#" data-toggle="collapse" data-target="#requests-2">

						<?= $lang['requests']; ?>

						</a>

						<div id="requests-2" class="dropdown-submenu collapse">

						<a class="dropdown-item" href="<?= $site_url; ?>/requests/post_request">

						<?= $lang['post_request']; ?>

						</a>

						<a class="dropdown-item" href="<?= $site_url; ?>/requests/manage_requests">

						<?= $lang['manage_requests']; ?>

						</a>

						</div>


						<a class="dropdown-item dropdown-toggle" href="#" data-toggle="collapse" data-target="#contacts-2">

						<?= $lang['contacts']; ?>

						</a>

						<div id="contacts-2" class="dropdown-submenu collapse">

						<a class="dropdown-item" href="<?= $site_url; ?>/manage_contacts?my_buyers">

						<?= $lang['my_buyers']; ?>

						</a>

						<a class="dropdown-item" href="<?= $site_url; ?>/manage_contacts?my_sellers">

						<?= $lang['my_sellers']; ?>

						</a>


						</div>

						<a class="dropdown-item dropdown-toggle" href="#" data-toggle="collapse" data-target="#referrals-2">

						<?= $lang['my_referrals']['title']; ?>

						</a>

						<div id="referrals-2" class="dropdown-submenu collapse">

						<?php if($enable_referrals == "yes"){ ?>

						<a class="dropdown-item" href="<?= $site_url; ?>/my_referrals">

						<?= $lang['user_referrals']; ?>

						</a>

						<?php } ?>

						<a class="dropdown-item" href="<?= $site_url; ?>/proposal_referrals">

						<?= $lang['proposal_referrals']; ?>

						</a>

						</div>


						<a class="dropdown-item" href="<?= $site_url; ?>/conversations/inbox">

						<?= $lang['inbox_messages']; ?>

						</a>


						<a class="dropdown-item" href="<?= $site_url; ?>/<?= $_SESSION['seller_user_name']; ?>">

						<?= $lang['my_profile']; ?>

						</a>


						<a class="dropdown-item dropdown-toggle" href="#" data-toggle="collapse" data-target="#settings-2">

						<?= $lang['settings']; ?>

						</a>

						<div id="settings-2" class="dropdown-submenu collapse">

						<a class="dropdown-item" href="<?= $site_url; ?>/settings?profile_settings">

						<?= $lang['profile_settings']; ?>

						</a>

						<a class="dropdown-item" href="<?= $site_url; ?>/settings?account_settings">

						<?= $lang['account_settings']; ?>

						</a>

						</div>

						<div class="dropdown-divider"></div>

						<a class="dropdown-item" href="<?= $site_url; ?>/logout">

						<?= $lang['logout']; ?>

						</a>

					</div>

					</div>
				  
				  </li>
				  
				  </ul>
				  
				 
				  <script>
				  
				  $(".dropdown-menu .dropdown-item.dropdown-toggle").click(function(){
			
					$('.collapse.dropdown-submenu').collapse('hide');
					
				  });


				  $(".dropdown-menu .dropdown-item-2.dropdown-toggle").click(function(){
						
						$('.collapse.dropdown-submenu-2').collapse('hide');
						
				  });
				  
				  </script>
		  
		<?php } ?>