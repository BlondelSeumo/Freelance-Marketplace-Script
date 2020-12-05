<div class="mp-box mp-box-white notop d-lg-block d-none">

	<div class="container">

	<div class="box-row">

		<ul class="main-cat-list active">

			<li class="<?=($lang_dir=="right"?'float-right':'')?>">

				<a href="<?= $site_url; ?>/dashboard"><?= $lang["menu"]['dashboard']; ?></a>
				
			</li>

			<li class="<?=($lang_dir=="right"?'float-right':'')?>">

				<a href="#"><?= $lang["menu"]['selling']; ?> <i class="fa fa-fw fa-caret-down"></i></a>

				<div class="menu-cont">

					<ul>

						<?php if($count_active_proposals > 0){ ?>

						<li>
							<a href="<?= $site_url; ?>/selling_orders"><?= $lang["menu"]['orders']; ?></a>
						</li>
						
						<?php } ?>

						<li>
							<a href="<?= $site_url; ?>/proposals/view_proposals"><?= $lang["menu"]['my_proposals']; ?></a>
						</li>

						<li>
							<a href="<?= $site_url; ?>/proposals/create_coupon"><?= $lang["menu"]['create_coupon']; ?></a>
						</li>

						<?php if($count_active_proposals > 0){ ?>

						<li>
							<a href="<?= $site_url; ?>/requests/buyer_requests"><?= $lang["menu"]['buyer_requests']; ?></a>
						</li>

						<?php } ?>

						<li>
							<a href="<?= $site_url; ?>/revenue"><?= $lang["menu"]['revenues']; ?></a>
						</li>

						<?php if($count_active_proposals > 0){ ?>

						<li>
							<a href="<?= $site_url; ?>/withdrawal_requests"><?= $lang["menu"]['withdrawal_requests']; ?></a>
						</li>

						<?php } ?>

					</ul>
					
				</div>

			</li>

			<li class="<?=($lang_dir=="right"?'float-right':'')?>">

				<a href="#">
					
					<?= $lang["menu"]['buying']; ?> <i class="fa fa-fw fa-caret-down"></i>

				</a>

				<div class="menu-cont">

					<ul>

						<li>
							
							<a href="<?= $site_url; ?>/buying_orders"><?= $lang["menu"]['orders']; ?></a>

						</li>
						
						<li>
							
							<a href="<?= $site_url; ?>/purchases"><?= $lang["menu"]['purchases']; ?></a>

						</li>

						<li>
							
							<a href="<?= $site_url; ?>/favorites"><?= $lang["menu"]['favorites']; ?></a>

						</li>

					</ul>

				</div>
				
			</li>



			<li class="<?=($lang_dir=="right"?'float-right':'')?>">

				<a href="#">
					
					<?= $lang["menu"]['requests']; ?> <i class="fa fa-fw fa-caret-down"></i>

				</a>

				<div class="menu-cont">

					<ul>

						<li>
							
							<a href="<?= $site_url; ?>/requests/manage_requests">

								<?= $lang["menu"]['manage_requests']; ?>
								

							</a>


						</li>
						

						<li>
							
							<a href="<?= $site_url; ?>/requests/post_request">

								<?= $lang["menu"]['post_request']; ?>

							</a>


						</li>

					</ul>
					
				</div>
				
			</li>


			<li class="<?=($lang_dir=="right"?'float-right':'')?>">

				<a href="#">
					
					<?= $lang["menu"]['contacts']; ?> <i class="fa fa-fw fa-caret-down"></i>

				</a>

				<div class="menu-cont">

					<ul>

						<li>
							
							<a href="<?= $site_url; ?>/manage_contacts?my_buyers">

								<?= $lang["menu"]['my_buyers']; ?>

							</a>

						</li>
						
						<li>
							
							<a href="<?= $site_url; ?>/manage_contacts?my_sellers">

								<?= $lang["menu"]['my_sellers']; ?>

							</a>

						</li>

					</ul>

				</div>
				
			</li>
            

		<?php if($enable_referrals == "yes"){ ?>

			<li class="<?=($lang_dir=="right"?'float-right':'')?>">

				<a href="#"><?= $lang["menu"]['my_referrals']; ?> <i class="fa fa-fw fa-caret-down"></i></a>

				<div class="menu-cont">

					<ul>

						<li>
							
						<a href="<?= $site_url; ?>/my_referrals"><?= $lang["menu"]['user_referrals']; ?></a>

						</li>
						
						<li>
							
						<a href="<?= $site_url; ?>/proposal_referrals"><?= $lang["menu"]['proposal_referrals']; ?></a>

						</li>

					</ul>

				</div>
				
			</li>

		<?php } ?>


			<li class="<?=($lang_dir=="right"?'float-right':'')?>">

				<a href="<?= $site_url; ?>/conversations/inbox"><?= $lang["menu"]['inbox_messages']; ?></a>
				
			</li>

			<li class="<?=($lang_dir=="right"?'float-right':'')?>">

				<a href="<?= $site_url; ?>/notifications"><?= $lang["menu"]['notifications']; ?></a>
				
			</li>


			<li class="<?=($lang_dir=="right"?'float-right':'')?>">

				<a href="<?= $site_url; ?>/<?= $_SESSION['seller_user_name']; ?>">

								<?= $lang["menu"]['my_profile']; ?>
					
				</a>
				

			</li>


			<li class="<?=($lang_dir=="right"?'float-right':'')?>">

				<a href="<?= $site_url; ?>/settings">

					<?= $lang["menu"]['settings']; ?> <i class="fa fa-fw fa-caret-down"></i>

				</a>

					<div class="menu-cont">

					<ul>

						<li>
							
						<a href="<?= $site_url; ?>/settings?profile_settings"><?= $lang["menu"]['profile_settings']; ?></a>


						</li>
						
						<li>
							
						<a href="<?= $site_url; ?>/settings?account_settings"><?= $lang["menu"]['account_settings']; ?></a>

						</li>

					</ul>

				</div>
				
			</li>

		</ul>

	</div>
	
   </div>

</div>