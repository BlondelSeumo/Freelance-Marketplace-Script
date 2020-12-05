<div class="card mb-3"><!--- card Starts -->

	<div class="card-body"><!--- card-body Starts -->

		<form action="index" method="get">
		
			<div class="input-group">
				<?php if($lang_dir == "right"){ ?>
					<div class="input-group-prepend">
						<button class="btn btn-success rounded-0 rounded-right" type="submit">
							<i class="fa fa-search"></i>
						</button>
					</div>
					<input type="text" class="form-control <?= $textRight; ?>" placeholder="<?= $lang['placeholder']['search']; ?>" name="search" value="<?= @$input->get("search"); ?>" required />
				<?php }else{ ?>
					<input type="text" class="form-control" placeholder="<?= $lang['placeholder']['search']; ?>" name="search" value="<?= @$input->get("search"); ?>" required />
					<div class="input-group-prepend">
						<button class="btn btn-success rounded-0 rounded-right" type="submit">
							<i class="fa fa-search"></i>
						</button>
					</div>			
				<?php } ?>
			</div>

		</form>

	</div><!--- card-body Ends -->

</div><!--- card Ends -->

<div class="card card-primary">
	<div class="card-header <?= $textRight; ?>">Categories</div>
	<div class="card-body">
		<ul class="mb-0 list-unstyled ml-3 mr-3 <?= $textRight; ?>">
			<?php
			$categories = $db->select("post_categories");
			while($cat = $categories->fetch()){
				$image = $cat->cat_image;
				$cat_meta = $db->select("post_categories_meta", 
					array(
						'cat_id' => $cat->id,
						'language_id' => $_SESSION['siteLanguage']
				))->fetch();				
				$cat_name = !empty($cat_meta->cat_name) ? $cat_meta->cat_name:'';
				//echo $cat_name.'<br />';
			?>
				<li>
					<a href="index?cat_id=<?= $cat->id; ?>">
						<?php if(!empty($image)){ ?>
							<img src="../blog_cat_images/<?= $image; ?>" width="18" class='mr-1'>
						<?php }else{ ?>
							<span style="margin-left: 26px;"></span>
						<?php } ?>
						<?= $cat_name; ?>
					</a>
				</li>
			<?php } ?>
		</ul>
	</div>
</div>