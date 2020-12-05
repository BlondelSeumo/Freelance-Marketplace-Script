<?php

require_once("../functions/email.php");

$featured_proposal = $row_proposal->proposal_featured;

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$featured_proposal_while_creating = $row_payment_settings->featured_proposal_while_creating;

$get_general_settings = $db->select("general_settings");   
$row_general_settings = $get_general_settings->fetch();
$approve_proposals = $row_general_settings->approve_proposals;
if($approve_proposals == "yes"){
  $text = "Save & Submit For Approval"; 
}else{ 
  $text = "Save & Publish"; 
}

?>

<h1><img style="position:relative; top:-5px;" src="../images/comp/winner.png">  Yay! You are almost done!</h1>

<h6 class="font-weight-normal line-height-normal">
  Congrats! you're almost done submitting this proposal. <br>
  You can go back and check if you entered all the details for this proposal correctly. If all looks good and you agree with 
  <a href="<?= $site_url; ?>/terms_and_conditions" target="_black" class="text-primary">all our policies</a>, please click on the “Save & Submit For Approval” button.<br><br>
  <span class="text-muted">
  If you do not wish to submit this proposal for approval at this time, please exit this page. You can easily retrieve this proposal by clicking on "Selling => My Proposals => Drafts". Cheers!
  </span>
</h6>

<form action="" method="post">
  <?php if($featured_proposal_while_creating == 1){ ?>
  <?php if($featured_proposal != "yes"){ ?>
  <h1 class="h3">Make Proposal Featured (Optional)</h1>
  <h6 class="font-weight-normal line-height-normal">
    Let your proposal appear on several places on <?= $site_name; ?><br>
    Proposal will always be at the top section of search results <br>
    WIth <?= $site_name; ?> feature, your proposal already has a 50% chance of getting ordered by potential buyers
    <p class="ml-4 mt-3">
      <label for="checkid" style="word-wrap:break-word">
        <input type="checkbox" id="checkid" name="proposal_featured" value="1" style="vertical-align:middle;margin-left: -1.25rem;"> Make Proposal Featured
      </label>
    </p>
  </h6>
  <?php }} ?>
  <div class="form-group mb-0 mt-3"><!--- form-group Starts --->
    <a href="#" class="btn btn-secondary back-to-gallery"><?= $lang['button']['back']; ?></a>
    <input class="btn btn-success" type="submit" name="submit_proposal" value="<?= $text; ?>">
    <a href="#" class="btn btn-success d-none" id="featured-button">Make Proposal Featured</a>
  </div><!--- form-group Starts --->
</form>

<script>
$('.back-to-gallery').click(function(){
  $("input[type='hidden'][name='section']").val("gallery");
  $('#publish').removeClass('show active');
  $('#gallery').addClass('show active');
  $('#tabs a[href="#publish"]').removeClass('active');
});

$("input[name='proposal_featured']").change(function(){
  if (this.checked) {
    $("#featured-button").removeClass("d-none");
    $("input[name='submit_proposal'").addClass("d-none");
  }else{
    $("#featured-button").addClass("d-none");
    $("input[name='submit_proposal'").removeClass("d-none");
  }
});

$("#featured-button").click(function(){
  proposal_id = "<?= $proposal_id; ?>";
  $.ajax({
    method: "POST",
    url: "pay_featured_listing",
    data: {proposal_id: proposal_id, createProposal:1}
  }).done(function(data){
    $("#featured-proposal-modal").html(data); 
  });
});
</script>
<?php 
if(isset($_POST['submit_proposal'])){
  if($approve_proposals == "yes"){ 
    $status = "pending";
    $text = "Your Proposal Has Been Successfully Submitted For Approval."; 
  }else{ 
    $status = "active"; 
    $text = "Your Proposal Has Been Successfully Publish. Now Its Live"; 
  }
  $update_proposal = $db->update("proposals",array("proposal_status"=>$status),array("proposal_id"=>$proposal_id));
  if($update_proposal){
    if($row_general_settings->proposal_email == "yes"){
      $site_email_address = $row_general_settings->site_email_address;
      $get_meta = $db->select("cats_meta",array("cat_id" => $d_proposal_cat_id, "language_id" => $siteLanguage));
      $cat_title = $get_meta->fetch()->cat_title;

      // $data = [];
      // $data['template'] = "new_proposal";
      // $data['to'] = $site_email_address;
      // $data['subject'] = "$site_name - $login_seller_user_name Has Just Created A New Proposal.";
      // $data['user_name'] = "";
      // $data['seller_user_name'] = $login_seller_user_name;
      // $data['proposal_title'] = $d_proposal_title;
      // $data['cat_title'] = $cat_title;
      // $data['status'] = ucfirst($status);
      // send_mail($data);

      send_proposal_email($login_seller_user_name,$d_proposal_title,$cat_title,$status);

    }
    echo "<script>
    swal({
      type: 'success',
      text: '$text',
      timer: 2000,
      onOpen: function(){
        swal.showLoading()
      }
    }).then(function(){
      window.open('view_proposals','_self');
    });
    </script>";
  }
}
?>