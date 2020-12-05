<!-- Feedback Sidebar -->

<div class="card mb-3"><!--- card Starts -->

	<div class="card-body"><!--- card-body Starts -->

		<form action="" method="get">

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

<div class="card"><!--- card Starts -->
	<div class="card-body <?= $textRight; ?>"><!--- card-body Starts -->

		<h4><a class="text-dark" href="#">General</a></h4>

		<a class="btn btn-success mb-4" href="post-idea">
			<i class="fa fa-plus-circle"></i> Post a new idea
		</a>

		<h5>My Ideas/Comments</h5>

		<a class="text-success" href="my-feedback"> 
			<i class="fa fa-comments-o" aria-hidden="true"></i> My feedback
		</a>

	</div><!--- card-body Ends -->
</div><!--- card Ends -->