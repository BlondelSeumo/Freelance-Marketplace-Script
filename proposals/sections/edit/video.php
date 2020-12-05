<?php
$proposal_videosettings =  $db->select("proposal_videosettings",array('proposal_id'=>$proposal_id))->fetch();
$enable = $proposal_videosettings->enable;
$price_per_minute = $proposal_videosettings->price_per_minute;
$days_within_scheduled = $proposal_videosettings->days_within_scheduled;

$video_schedules = $db->select("video_schedules");

?>
<h4 class="font-weight-normal">Video</h4>
<hr>

<form action="#" method="post" class="video-form"><!--- form Starts -->

  <div class="form-group row"><!--- form-group row Starts --->
    <label class="col-md-4 col-form-label">Enable video calling:</label>
    <div class="col-md-5">
      <input type="checkbox" name="enable" class="mt-3" value="1" <?php if($enable==1){echo "checked";} ?>>
    </div>
  </div>
  
  <div class="form-group row"><!--- form-group row Starts --->
    <label class="col-md-4 col-form-label">Price per minute:</label>
    <div class="col-md-5">
      <input type="text" name="price_per_minute" class="form-control" value="<?= $price_per_minute; ?>">
    </div>
  </div>

  <div class="form-group row"><!--- form-group row Starts --->
    <label class="col-md-4 col-form-label">Days within which a video session can be scheduled:</label>
    <div class="col-md-5">
      <select name="days_within_scheduled" class="form-control">
        <?php foreach($video_schedules as $schedule){ ?>
          <option value="<?= $schedule->id; ?>" <?php if($days_within_scheduled==$schedule->id){echo"selected";} ?>><?= $schedule->title; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
  <hr>
  <div class="form-group mb-0"><!--- form-group Starts --->
    <a href="#" class="btn btn-secondary float-left back-to-overview"><?= $lang['button']['back']; ?></a>
    <input class="btn btn-success float-right" type="submit" value="<?= $lang['button']['save_continue']; ?>">
  </div><!--- form-group Starts --->
</form><!--- form Ends -->

<script>
$(document).ready(function(){

  $('.back-to-overview').click(function(){
    <?php if($d_proposal_status == "draft"){ ?>
    $("input[type='hidden'][name='section']").val("overview");
    $('#video').removeClass('show active');
    $('#overview').addClass('show active');
    $('#tabs a[href="#video"]').removeClass('active');
    <?php }else{ ?>
    $('.nav a[href="#overview"]').tab('show');
    <?php } ?>
  });

  $(".video-form").on('submit', function(event){
    event.preventDefault();
    var form_data = new FormData(this);
    form_data.append('proposal_id',<?= $proposal_id; ?>);
    $('#wait').addClass("loader");
    $.ajax({
      method: "POST",
      url: "../plugins/videoPlugin/save_video",
      data: form_data,
      async: false,cache: false,contentType: false,processData: false
    }).done(function(data){
      $('#wait').removeClass("loader");
      if(data == "error"){
        swal({type:'warning',text:'You Must Need To Fill Out All Fields Before Updating The Details.'});
      }else{
        swal({
          type: 'success',
          text: 'Details Saved.',
          timer: 1000,
          onOpen: function(){
            swal.showLoading();
          }
        }).then(function(){
          $("input[type='hidden'][name='section']").val("description");
          <?php if($d_proposal_status == "draft"){ ?>
          $('#video').removeClass('show active');
          $('#description').addClass('show active');
          $('#tabs a[href="#description"]').addClass('active');
          <?php }else{ ?> 
          $('.nav a[href="#description"]').tab('show'); 
          <?php } ?>
        });
      }
    });
  });

});
</script>