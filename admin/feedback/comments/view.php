
<div class="breadcrumbs">
	<div class="col-sm-4">
		<div class="page-header float-left">
		  <div class="page-title">
		    <h1><i class="menu-icon fa fa-rss"></i> Feedback</h1>
		  </div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="page-header float-right">
		  <div class="page-title">
				<ol class="breadcrumb text-right">
				  <li class="active">Comments</li>
				</ol>
		  </div>
		</div>
	</div>
</div>

<div class="container"><!--- container Starts --->

<div class="row"><!-- row Starts -->

    <div class="col-lg-12"><!-- col-lg-12 Starts -->

        <div class="card card-default"><!-- card card-default Starts -->

            <div class="card-header"><!-- card-header Starts -->

                <i class="fa fa-money fa-fw"></i> View All Comments

            </div><!-- card-header Ends -->

            <div class="card-body"><!-- card-body Starts -->

                <div class="table-responsive"><!--- table-responsive Starts -->

                    <table class="table table-bordered table-hover table-striped">
                        <!--- table table-bordered table-hover table-striped Starts -->

                        <thead>
                            <tr>

                              <th>No</th>
                              <th>Idea</th>
                              <th>User</th>
                              <th>Comment</th>
                              <th>Date:</th>
                              <th>Delete:</th>

                            </tr>
                        </thead>

                        <tbody>
                           <?php

									$i = 0;
									$comments = $db->select("comments","","DESC");
									while($comment = $comments->fetch()){

									$idea = $db->select("ideas",["id"=>$comment->idea_id])->fetch()->title;
									$user = $db->select("sellers",["seller_id"=>$comment->seller_id])->fetch()->seller_user_name;

									$i++;

									?>

                          <tr>

                              <td>
                                 <?= $i; ?>
                              </td>

                              <td>
                                 <?= $idea; ?>
                              </td>

                              <td>
                                 <?= $user; ?>
                              </td>

                              <td width="400">
                                  <?= $comment->comment; ?>
                              </td>

                              <td>
                                  <?= $comment->date; ?>
                              </td>

                              <td>

                                 <a class="btn btn-danger text-white" href="index?delete_comment=<?= $comment->id; ?>" onclick="if(!confirm('Are you sure you want to delete selected item.')){ return false; }">

                                    <i class="fa fa-trash"></i> Delete

                                 </a>

                              </td>

                          </tr>

                          <?php } ?>

                        </tbody>

                    </table><!--- table table-bordered table-hover table-striped Starts -->

                </div><!--- table-responsive Ends -->

            </div><!-- card-body Ends -->

        </div><!-- card card-default Ends -->

	</div><!-- col-lg-12 Ends -->

	</div><!-- row Ends -->

</div><!--- container Ends --->