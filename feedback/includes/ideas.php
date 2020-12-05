<?php 

$per_page = 4;
if(isset($_GET['page'])){
	$page = $input->get('page');
	if($page == 0){ $page = 1; }
}else{
	$page = 1;
}

/// Page will start from 0 and multiply by per page
$start_from = ($page-1) * $per_page;

if(isset($_GET['search'])){
	$search = $_GET['search'];
	$ideas = $db->query("select * from ideas where title like :title order by 1 DESC LIMIT :limit OFFSET :offset",["title"=>"%$search%"],array("limit"=>$per_page,"offset"=>$start_from));
}else{
	$search = "";
	$ideas = $db->query("select * from ideas order by 1 DESC LIMIT :limit OFFSET :offset","",array("limit"=>$per_page,"offset"=>$start_from));
}

?>
<div class="card"><!--- card Starts -->
	<div class="card-body"><!--- card-body Starts -->

		<form action="post-idea" method="post">

			<div class="form-group"><!--- form-group Starts --->

			<h5 class="<?= $textRight; ?>"> I suggest you ...</h5>
			<div class="input-group mb-3">
				<?php if($lang_dir == "right"){ ?>
					<div class="input-group-addon bg-success">
						<button class="btn bg-transparent text-white rounded-right" name="post_idea" type="submit">Post idea</button>
					</div>
					<input type="text" name="title" class="form-control form-control-lg  text-right" placeholder="Enter your idea" required/>
				<?php }else{ ?>
					<input type="text" name="title" class="form-control form-control-lg" placeholder="Enter your idea" required/>
					<div class="input-group-addon bg-success">
						<button class="btn bg-transparent text-white rounded-right" name="post_idea" type="submit">Post idea</button>
					</div>
				<?php } ?>
			</div>

			</div><!--- form-group Ends --->

		</form>

		<?php if(empty($ideas->rowCount())){ ?>

		<h3 class="text-center">
			<?php if(isset($_GET['search'])){ ?>
				We couldn't find any results for your search.
			<?php }else{ ?>
				There are currently no ideas in feedback.
			<?php } ?>
		</h3>

		<?php } ?>

		<?php if(!empty($ideas->rowCount()) and isset($_GET['search'])){ ?>
			<h4>Search results</h4>
		<?php } ?>

		<div class="ideas-list mt-4 mb-0"><!-- ideas-list Starts -->

		<?php
			
			while($idea = $ideas->fetch()){
			$count_comments = $db->count("comments",array("idea_id" => $idea->id));

		?>

		<div class="card mb-3 <?= $textRight; ?>">
			<div class="card-body">
				  <h5><a href="idea?id=<?= $idea->id; ?>"><?= $idea->title; ?></a></h5>
				  <p class="mb-2"><?= substr($idea->content, 0,176); ?></p>
				  <span class="text-muted"><?= $count_comments; ?> comments</span>
			</div>
		</div>

		<?php } ?>

		<nav class="nav justify-content-center mb-0"> 

			<ul class="pagination"><!--- pagination Starts --->
	        <?php
	         
	         /// Now Select All From Order Table

				if(isset($_GET['search'])){
					$query = $db->query("select * from ideas where title like :title",["title"=>"%$search%"]);
				}else{
					$query = $db->query("select * from ideas order by 1 DESC");
				}

				/// Count The Total Records
				$total_records = $query->rowCount();
				/// Using ceil function to divide the total records on per page
				$total_pages = ceil($total_records / $per_page);

				echo "<li class='page-item'><a href='index?search=$search&page=1' class='page-link'>{$lang['pagination']['first_page']}</a></li>";

				echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?search=$search&page=1'>1</a></li>";

				$i = max(2, $page - 5);

				if($i > 2){
				echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
				}

				for (; $i < min($page + 6, $total_pages); $i++) {
				echo "<li class='page-item"; 
				if($i == $page){ echo " active "; } 
				echo "'><a href='index?search=$search&page=".$i."' class='page-link'>".$i."</a></li>";
				}

				if ($i != $total_pages and $total_pages > 1){
					echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
				}

				if($total_pages > 1){
				echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?search=$search&page=$total_pages'>$total_pages</a></li>";
				}

				echo "<li class='page-item'><a href='index?search=$search&page=$total_pages' class='page-link'>{$lang['pagination']['last_page']}</a></li>";

	        ?>
	      </ul><!--- pagination Ends --->

		</nav>

		</div><!-- ideas-list Ends -->

	</div><!--- card-body Ends -->
</div><!--- card Ends -->