<?php 

if (!isset($_GET['id'])) {
	$title = $input->post("title");
	$content = "";
} else {
	
	$idea = $db->select("ideas",['id'=>$_GET['id']])->fetch();
	
	$title = $idea->title;
	$content = $idea->content;
	
}

?>
<div class="card"><!--- card Starts -->
	<div class="card-body <?= $textRight; ?>"><!--- card-body Starts -->
	
		<?php if(!isset($_SESSION["seller_user_name"])){ ?>

		<div class="alert alert-warning rounded-0">
		    
		<p class="lead mt-1 mb-1 text-center">
			<strong>Sorry!</strong> You can't submit an idea/feedback without logging in first. If you have a general question, please email us at <?= $site_email_address ?>.
		</p>

		</div>

		<?php }else{ ?>

		<form action="" method="post">

			<div class="form-group"><!--- form-group Starts --->

			<h5> I suggest you ...</h5>

			<input type="text" name="title" class="form-control <?= $textRight; ?>" value="<?= $title; ?>" placeholder="Enter your idea" required>

			</div><!--- form-group Ends --->

			<div class="form-group"><!--- form-group Starts --->

			<textarea name="content" class="form-control <?= $textRight; ?>" placeholder="Describe your idea..." rows="5" required=""><?= $content; ?></textarea>

			</div><!--- form-group Ends --->

			<div class="form-group"><!--- form-group Starts --->

			<button class="btn btn-success mr-2" name="submit" type="submit" value="submit">
				<i class="fa fa-plus-circle"></i> <?php if(empty($idea)){ ?> Post idea <?php }else{ ?> Update Idea <?php } ?>
			</button>

			<a href="index" class="btn btn-danger"><i class="fa fa-times-circle"></i> Cancel</a>

			</div><!--- form-group Ends --->

		</form>

		<?php } ?>

	</div><!--- card-body Ends -->
</div><!--- card Ends -->
<?php 
	
	if(isset($_POST['submit'])){

		$data = array(
	      "seller_id" => $login_seller_id,
	      "title" => $input->post('title'),
	      "content" => $input->post('content'),
	      "date" => date("d F Y, h:i A")
      ); 

		if(!isset($_GET['id'])) {
        if($db->insert("ideas", $data)){
          redirect("index");
        }
      }else{
        if($db->update("ideas",$data,['id'=>$idea->id])){
          redirect("my-feedback");
        }
      }

	}

?>