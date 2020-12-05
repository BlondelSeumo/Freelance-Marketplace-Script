<form action="#" method="post" class="proposal-form"><!--- form Starts -->

<h5 class="font-weight-normal">

<span class="float-left mr-2"><i class="fa fa-file text-info fa-1x"></i> </span>

<span class="float-left">

Tell your buyer what you need to get started (Optional) <br>

<small class="text-muted">Structure your Buyer Instructions as free text.</small>

</span>

<div class="clearfix"></div>

</h5>

<hr>

<div class="form-group requirements">
<p class="mb-1">Requirements</p>
<textarea name="buyer_instruction" placeholder="If you need to obtain information, files or other items from the buyer prior to starting your work, please add your instructions here. For example: Please send me your company name or Please send me the photo you need me to edit." rows="4" class="form-control"><?= $d_buyer_instruction; ?></textarea>

</div>


<div class="form-group mb-0"><!--- form-group Starts --->

<a href="#" class="btn btn-secondary float-left back-to-desc"><?= $lang['button']['back']; ?></a>

<input class="btn btn-success float-right" type="submit" value="<?= $lang['button']['save_continue']; ?>">

</div><!--- form-group Starts --->

</form><!--- form Ends -->

<script>
  
$(document).ready(function(){

   $('.back-to-desc').click(function(){

   <?php if($d_proposal_status == "draft"){ ?>

   $("input[type='hidden'][name='section']").val("description");

   $('#requirements').removeClass('show active');
   $('#description').addClass('show active');
   $('#tabs a[href="#requirements"]').removeClass('active');

   <?php }else{ ?>

   $('.nav a[href="#description"]').tab('show');

   <?php } ?>

   });

});

</script>