<?php 

$approve_proposals = $row_general_settings->approve_proposals;

if($approve_proposals == "yes"){ 
	$text = $lang['tabs']['submit_for_approval']; 
}else{ 
	$text = $lang['tabs']['publish']; 
}

?>
<nav id="tabs">

	<div class="container">

		<div class="breadcrumb flat mb-0 nav" role="tablist">
			
			<a class="nav-link active" href="#overview">
				<?= $lang['tabs']['overview']; ?>
			</a>

			<a class="nav-link <?php if($checkVideo==false){echo"d-none";} ?> <?= (isset($_GET['video']) or isset($_GET['publish']))?"active":""; ?>" href="#video">
				<?= $lang['tabs']['video_settings']; ?>
			</a>

			<a class="nav-link <?php if($checkVideo==true){echo"d-none";} ?> <?= (isset($_GET['instant_delivery']) or isset($_GET['publish']))?"active":""; ?>" href="#instant-delivery">
				<?= $lang['tabs']['instant_delivery']; ?>
			</a>

			<a class="nav-link <?php if(isset($_GET['pricing']) or isset($_GET['publish'])){ echo "active"; } ?> <?php if(isset($checkVideo) and $checkVideo==true){echo"d-none";} ?>" href="#pricing">
				<?= $lang['tabs']['pricing']; ?>
			</a>

			<a class="nav-link <?= (isset($_GET['publish']) ? "active" : ""); ?>" href="#description">
				<?= $lang['tabs']['description']; ?>
			</a>
			<a class="nav-link <?php if($enable_delivery==1){echo"d-none";} ?> <?= (isset($_GET['publish']) ? "active" : ""); ?>" href="#requirements">
				<?= $lang['tabs']['requirements']; ?>
			</a>
			<a class="nav-link <?= (isset($_GET['publish']) ? "active" : ""); ?>" href="#gallery">
				<?= $lang['tabs']['gallery']; ?>
			</a>
			<a class="nav-link <?= (isset($_GET['publish']) ? "active" : ""); ?>" href="#publish"><?= $text; ?></a>
		</div>

	</div>

</nav>