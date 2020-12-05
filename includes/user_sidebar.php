<div class="card user-sidebar rounded-0 mb-4"><!--- card user-sidebar rounded-0 Starts -->

	<div class="card-body"><!-- card-body Starts -->

		<h3><?= $lang['user_profile']['description']; ?></h3>
		<p><?= $seller_about; ?></p>

		<hr class="card-hr">
		<h3 class="float-left"><?= $lang['user_profile']['languages']; ?></h3>

		<?php if(isset($_SESSION['seller_user_name'])){ ?>

			<?php if($login_seller_user_name == $seller_user_name){ ?>

			<ul class="list-unstyled"><!-- list-unstyled Starts -->

			<li class="mb-4 clearfix">

			<button data-toggle="collapse" data-target="#language" class="btn btn-success float-right">
				<i class="fa fa-plus-circle" aria-hidden="true"></i> <?= $lang['button']['add_new']; ?>
			</button>

			</li>

			<div id="language" class="collapse form-style mb-2"><!-- language collapse form-style mb-2 Starts -->

			<form method="post"><!-- form Starts -->

			<div class="form-group"><!-- form-group Starts -->

			<select class="form-control" name="language_id" required="">

			<option value=""><?= $lang['label']['select_language']; ?></option>

			<?php 

			$s_langs = array();

			$get = $db->select("languages_relation",array("seller_id"=>$login_seller_id));

			while($row = $get->fetch()){

			array_push($s_langs,$row->language_id);

			}

			$s_langs = implode(",", $s_langs);

			if(!empty($s_langs)){ $query_where  = "where not language_id IN ($s_langs)"; }else{ $query_where = ""; }

			$get_languages = $db->query("select * from seller_languages $query_where");

			while($row_languages = $get_languages->fetch()){

			$language_id = $row_languages->language_id;
			$language_title = $row_languages->language_title;

			?>
			<option value="<?= $language_id; ?>"> <?= $language_title; ?> </option>
			<?php } ?>

			<option value="custom">Custom Language</option>

			</select>

			</div><!-- form-group Ends -->

			<div class="form-group language-title d-none"><!-- form-group Starts -->

			<input type="text" placeholder="Language Name" class="form-control" name="language_title">

			</div><!-- form-group Ends -->

			<div class="form-group"><!-- form-group Starts -->

			<select class="form-control" name="language_level" required="">

				<option class="hidden"><?= $lang['label']['select_level']; ?></option>
				<option value="basic"> Basic </option>
				<option value="Fluent"> Fluent </option>
				<option value="Conversational"> Conversational </option>
				<option value="Native or Bilingual"> Native or Bilingual </option>

			</select>

			</div><!-- form-group Ends -->

			<div class="text-center"><!-- text-center Starts -->

			<button type="button" data-toggle="collapse" data-target="#language" class="btn btn-secondary">
			<?= $lang['button']['cancel']; ?>
			</button>

			<button type="submit" name="insert_language" class="btn btn-success">
			<?= $lang['button']['add']; ?>
			</button>

			</div><!-- text-center Ends -->

			</form><!-- form Ends -->

			<?php 

			if(isset($_POST['insert_language'])){
				
				$language_id = $input->post('language_id');
				$language_level = $input->post('language_level');

				if($language_id == "custom"){

					$language_title = $input->post('language_title');

					$count = $db->count("seller_languages",["language_title"=>$language_title]);

					if($count == 1){

						echo "<script>alert('{$lang['alert']['language_already_added']}');</script>";
						echo "<script>window.open('$seller_user_name','_self');</script>";

						exit();

					}else{

						$insert_language = $db->insert("seller_languages",["language_title"=>$language_title]);
						$language_id = $db->lastInsertId();

					}

				}

				$insert_language = $db->insert("languages_relation",array("seller_id" => $seller_id,"language_id" => $language_id,"language_level" => $language_level));
				echo "<script>window.open('$seller_user_name','_self');</script>";
				
			}

			?>

			</div><!-- language collapse form-style mb-2 Ends -->

			</ul><!-- list-unstyled Ends -->

			<?php } ?>

		<?php } ?>
		    
		<div class="clearfix"></div>

		<ul class="list-unstyled mt-3"><!-- list-unstyled mt-3 Starts -->

		<?php

		$select_languages_relation = $db->select("languages_relation",array("seller_id" => $seller_id));

		while($row_languages_relation = $select_languages_relation->fetch()){
			
			$relation_id = $row_languages_relation->relation_id;
			$language_id = $row_languages_relation->language_id;
			$language_level = $row_languages_relation->language_level;


			$get_language = $db->select("seller_languages",array("language_id" => $language_id));
			$row_language = $get_language->fetch();
			$language_title = $row_language->language_title;

		?>

		<li class="card-li mb-1"><!--- card-li mb-1 Starts -->

		<?= $language_title; ?> - <span class="text-muted"> <?= $language_level; ?> </span>

		<?php if(isset($_SESSION['seller_user_name'])){ ?>

		<?php if($login_seller_user_name == $seller_user_name){ ?>

		<a href="user.php?delete_language=<?= $relation_id; ?>">
		 <i class="fa fa-trash-o"></i>
		</a>

		<?php } ?>

		<?php } ?>

		</li><!--- card-li mb-1 Ends -->

		<?php } ?>

		</ul><!-- list-unstyled mt-3 Ends -->

		<hr class="card-hr">

		<h3 class="float-left"><?= $lang['user_profile']['skills']; ?></h3>

		<?php if(isset($_SESSION['seller_user_name'])){ ?>

		<?php if($login_seller_user_name == $seller_user_name){ ?>

		<ul class="list-unstyled"><!-- list-unstyled Starts -->

		<li class="mb-4 clearfix">

		<button data-toggle="collapse" data-target="#add_skill" class="btn btn-success float-right">
			<i class="fa fa-plus-circle" aria-hidden="true"></i> <?= $lang['button']['add_new']; ?>
		</button>

		</li>

		<div id="add_skill" class="collapse form-style mb-2"><!-- add_skill collapse form-style mb-2 Starts -->

		<form method="post"><!-- form Starts -->

		<div class="form-group"><!-- form-group Starts -->

		<select class="form-control" name="skill_id" required="">

		<option value=""><?= $lang['label']['select_skill']; ?></option>

		<?php 

		$s_skills = array();

		$get = $db->select("skills_relation",array("seller_id"=>$login_seller_id));

		while($row = $get->fetch()){

		array_push($s_skills,$row->skill_id);

		}

		$s_skills = implode(",", $s_skills);

		if(!empty($s_skills)){ $query_where  = "where not skill_id IN ($s_skills)"; }else{ $query_where = ""; }

		$get_seller_skills = $db->query("select * from seller_skills $query_where");
		while($row_seller_skills = $get_seller_skills->fetch()){

		$skill_id = $row_seller_skills->skill_id;
		$skill_title = $row_seller_skills->skill_title;

		?>

		<option value="<?= $skill_id; ?>"> <?= $skill_title; ?> </option>

		<?php } ?>

		<option value="custom">Custom Skill</option>

		</select>

		</div><!-- form-group Ends -->

		<div class="form-group skill-name d-none"><!-- form-group Starts -->

		<input type="text" placeholder="Skill Name" class="form-control" name="skill_name">

		</div><!-- form-group Ends -->

		<div class="form-group"><!-- form-group Starts -->

		<select class="form-control" name="skill_level" required="">

		<option value="" class="hidden"><?= $lang['label']['select_level']; ?></option>
		<option> Beginner </option>
		<option> Intermediate </option>
		<option> Expert </option>

		</select>

		</div><!-- form-group Ends -->

		<div class="text-center"><!-- text-center Starts -->

		<button type="button" data-toggle="collapse" data-target="#add_skill" class="btn btn-secondary">
		<?= $lang['button']['cancel']; ?>
		</button>

		<button type="submit" name="insert_skill" class="btn btn-success">
		<?= $lang['button']['add']; ?>
		</button>

		</div><!-- text-center Ends -->

		</form><!-- form Ends -->

		<?php

		if(isset($_POST['insert_skill'])){
			
		$skill_id = $input->post('skill_id');
		$skill_level = $input->post('skill_level');

		if($skill_id == "custom"){

			$skill_name = $input->post('skill_name');
			$count = $db->count("seller_skills",["skill_title"=>$skill_name]);

			if($count == 1){
				echo "<script>alert('{$lang['alert']['skill_already_added']}');</script>";
				echo "<script>window.open('$seller_user_name','_self');</script>";
				exit();
			}else{
				$insert_skill = $db->insert("seller_skills",array("skill_title"=>$skill_name));
				$skill_id = $db->lastInsertId();
			}

		}
			
		$insert_skill = $db->insert("skills_relation",array("seller_id" => $seller_id,"skill_id" => $skill_id,"skill_level" => $skill_level));
			
		echo "<script>window.open('$seller_user_name','_self');</script>";
			
		}

		?>

		</div><!-- language collapse form-style mb-2 Ends -->

		</ul><!-- list-unstyled Ends -->

		<?php } ?>

		<?php } ?>
		    
		<div class="clearfix"></div>

		<ul class="list-unstyled mt-3"><!-- list-unstyled mt-3 Starts -->

		<?php

		$select_skills_relation = $db->select("skills_relation",array("seller_id" => $seller_id));
		while($row_skills_relation = $select_skills_relation->fetch()){
			
			$relation_id = $row_skills_relation->relation_id;
			$skill_id = $row_skills_relation->skill_id;
			$skill_level = $row_skills_relation->skill_level;
			
			$get_skill = $db->select("seller_skills",array("skill_id" => $skill_id));
			$row_skill = $get_skill->fetch();
			$skill_title = $row_skill->skill_title;

		?>

		<li class="card-li mb-1"><!--- card-li mb-1 Starts -->

		<?= $skill_title; ?> - <span class="text-muted"> <?= $skill_level; ?> </span>

		<?php if(isset($_SESSION['seller_user_name'])){ ?>

		<?php if($login_seller_user_name == $seller_user_name){ ?>

		<a href="user.php?delete_skill=<?= $relation_id; ?>">
		 <i class="fa fa-trash-o"></i>
		</a>

		<?php } ?>

		<?php } ?>

		</li><!--- card-li mb-1 Ends -->

		<?php } ?>

		</ul><!-- list-unstyled mt-3 Ends -->

	</div><!-- card-body Ends -->

</div><!--- card user-sidebar rounded-0 Ends -->