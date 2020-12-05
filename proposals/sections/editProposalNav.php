<nav id="tabs"><!--- tabs Starts --->

	<div class="container">

		<ul class="nav nav-tabs" id="nav-tab" role="tablist">

			<li class="nav-item">
				<a class="nav-link <?php if(!isset($_GET['instant_delivery']) AND !isset($_GET['video'])){ echo "active"; } ?>" id="nav-home-tab" data-toggle="tab" href="#overview">
					<?= $lang['tabs']['overview']; ?>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link <?php if($checkVideo==false){echo"d-none";} ?> <?= (isset($_GET['video']) ? "active" : ""); ?>" id="nav-home-tab" data-toggle="tab" href="#video">
					<?= $lang['tabs']['video_settings']; ?>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link <?php if($checkVideo==true){echo"d-none";} ?> <?= (isset($_GET['instant_delivery']) ? "active" : ""); ?>" id="nav-home-tab" data-toggle="tab" href="#instant-delivery">
					<?= $lang['tabs']['instant_delivery']; ?>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link <?php if(isset($checkVideo) and $checkVideo==true){echo"d-none";} ?>" id="nav-home-tab" data-toggle="tab" href="#pricing">
					<?= $lang['tabs']['pricing']; ?>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link" id="nav-home-tab" data-toggle="tab" href="#description">
					<?= $lang['tabs']['description']; ?>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link <?php if($enable_delivery==1){echo"d-none";} ?>" id="nav-home-tab" data-toggle="tab" href="#requirements">
					<?= $lang['tabs']['requirements']; ?>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link" id="nav-home-tab" data-toggle="tab" href="#gallery">
					<?= $lang['tabs']['gallery']; ?>
				</a>
			</li>

		</ul>

	</div>

</nav><!--- tabs Ends --->