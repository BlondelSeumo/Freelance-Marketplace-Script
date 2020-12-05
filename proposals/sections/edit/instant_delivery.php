<h5 class="font-weight-normal float-left"><?= $lang['edit_proposal']['instant_delivery']['title']; ?></h5>

<div class="float-right switch-box">
   <span class="text"><?= $lang['edit_proposal']['instant_delivery']['enable']; ?></span>
   <label class="switch">
      <?php if($enable_delivery == 0){ ?>
         <input type="checkbox" name="enable" id="enable_button" form="delivery-form" value="0" />
      <?php }else if($enable_delivery == 1){ ?>
         <input type="checkbox" name="enable" id="enable_button" form="delivery-form" value="1" checked="">
      <?php } ?>
      <span class="slider instant-slider"></span>
   </label>
</div>

<div class="clearfix"></div>

<hr class="mt-0">

<div class="alert alert-warning d-none">
  ! Instant Delivery Will Only Work When Some One Buy This Proposal Directly Or Buy Cart.
</div>

<div class="alert alert-info">
  <?= $lang['edit_proposal']['instant_delivery']['alert1']; ?>
</div>

<form action="#" enctype="multipart/form-data" method="post" id="delivery-form"><!--- form Starts -->

  <div class="form-group">
    <p class="mb-2">Message</p>
    <textarea name="message" id="instant_delivery_message" placeholder="Message" rows="4" class="form-control"><?= $delivery_message; ?></textarea>
  </div>

  <div class="alert alert-info">
    <?= $lang['edit_proposal']['instant_delivery']['alert2']; ?>
  </div>

  <div class="form-group float-left">
    <input type="file" id="deliveryFile" name="file" class="mb-3"/>
    <div id="downloadFile">
    <?php if(!empty($delivery_file)){ ?>
      <a href="download?proposal_id=<?= $proposal_id; ?>" target="_blank" class="instant_file_loaded">
        <i class="fa fa-download"></i> <?= $delivery_file; ?>
      </a>
    <?php } ?>
    </div>
  </div>

  <?php if(@$enable_watermark == 1){ ?>
  <div class="form-group float-right">
    <label for="">Enable Watermark : </label>
    <input type="checkbox" <?= ($delivery_watermark ==1)?"checked":""; ?> name="enable_watermark" value="1" style="position: relative; top: 2px;">
  </div>
  <?php } ?>

  <div class="clearfix"></div>

  <hr class="mt-0">

  <div class="form-group mb-0"><!--- form-group Starts --->
    <a href="#" class="btn btn-secondary float-left back-to-req"><?= $lang['button']['back']; ?></a>
    <input class="btn btn-success float-right" type="submit" value="<?= $lang['button']['save_continue']; ?>">
  </div><!--- form-group Starts --->

</form><!--- form Ends -->

<script>
$(document).ready(function(){

  $('.instant-slider').click(function(){
      var value=$('#enable_button').val();
      if (value== 1 ||value ==1){
          $('#enable_button').val(0);
      }else{
          $('#enable_button').val(1);
      }
  });

  $('.back-to-req').click(function(){
    <?php if($d_proposal_status == "draft"){ ?>
      $("input[type='hidden'][name='section']").val("overview");
      $('#overview').addClass('show active');
      $('#instant-delivery').removeClass('show active');
      $('#tabs a[href="#instant-delivery"]').removeClass('active');
    <?php }else{ ?>
      $('.nav a[href="#overview"]').tab('show');
    <?php } ?>
  });

  $('#deliveryFile').bind('change', function() {

    size = this.files[0].size/1024;
    // alert(size);
    if(size > 100000){
      alert("You exceeded our max upload size limit.");
      $(this).val("");
    }

  });


  function check_delivery(form_data){
    $('#wait').addClass("loader");    
    $.ajax({
      method: "POST",
      url: "ajax/check/delivery",
      data: form_data,
      dataType: 'json',
      async: false,cache: false,contentType: false,processData: false
    }).done(function(data){
        if(data === true){
        $('#wait').removeClass("loader");        
        deliveryRequest(form_data, data);        
      }else{
        processDeliveryRequest(form_data, data);
      }
    });
  }

  function processDeliveryRequest(form_data, status){
    form_data.append('change_status', status);
    var file_input = $("#delivery-form input[type='file']")[0].files;
    if(file_input.length != 0){
        swal({
         type: 'success',
         text: 'File Is Uploading.',
         onOpen: function(){
           swal.showLoading();
         }
        });
        timeout = 1000;
      }else{
        timeout = 100;
      }

     setTimeout(function(){
        
      },timeout ); 

    $('#wait').addClass("loader");
    $.ajax({
      method: "POST",
      url: "ajax/save_delivery",
      data: form_data,
      async: false,cache: false,contentType: false,processData: false
    }).done(function(data){        
      data = $.parseJSON(data);
      //console.log(data);
      //return false;

      if(file_input.length != 0){
        swal.close();
        $("#delivery-form input[type='file']").val('');
      }

       $('#wait').removeClass("loader");
       if(data.status == "error_file"){
          alert("<?= $lang['alert']['extension_not_supported']; ?>");
       }else{
          // alert(data.file);
          if(data.file){
            $("#downloadFile").html("<a href='download?proposal_id=<?= $proposal_id; ?>' target='_blank'> <i class='fa fa-download'></i> "+data.file+" </a>");
          }

          if($("input[name='enable']:checked").length > 0){
            enable_delivery = 1;
          }else{
            enable_delivery = 0;
          }

          if(enable_delivery == 1){
            $('#tabs a[href="#requirements"]').addClass('d-none');
            $('#pricing .float-right.switch-box').hide();
            $('.packages').hide();
            $('.add-attribute').hide();
            $('.proposal-price').show();
            $('.proposal-price input[name="proposal_price"]').attr('min',<?= $min_proposal_price; ?>);
          }else{
            $('#pricing .float-right.switch-box').show();
            $('#tabs a[href="#requirements"]').removeClass('d-none');
          }

          swal({
           type: 'success',
           text: 'Details Saved.',
           timer: 1000,
           onOpen: function(){
             swal.showLoading();
           }
          }).then(function(){
              <?php if($d_proposal_status == "draft"){ ?>
                $('#instant-delivery').removeClass('show active');
                $('#pricing').addClass('show active');
                $('#tabs a[href="#pricing"]').addClass('active');
              <?php }else{ ?> 
                $('.nav a[href="#pricing"]').tab('show'); 
              <?php } ?>
              $("input[type='hidden'][name='section']").val("pricing");
          });
      }
    });

  }

  function deliveryRequest(form_data, status=false){
     if(status == true){
       swal({
           title: "Are you sure?",
           text: "You have made some changes, your porposal would be set as pending state!",
           type: "warning",
           showCancelButton: true,
           confirmButtonColor: "#DD6B55",
           confirmButtonText: "Yes, I understand continue.",
           closeOnConfirm: false
         }).then(function (isConfirm) {
             if(isConfirm.dismiss == 'cancel'){            
               return;
             }else if (isConfirm.value == true){
               processDeliveryRequest(form_data, status);
             }
         });
    }
  }


   $("#delivery-form").submit(function(event){
       event.preventDefault();
       var instant_delivery_message =$('#instant_delivery_message').val();
       var instant_delivery_file=$('#deliveryFile').val();
       var enable_button=$('#enable_button').val();
       var loaded_file=parseInt($('.instant_file_loaded').length);
       if (enable_button==1 || enable_button == "1") {
           if (instant_delivery_message == "" || instant_delivery_file == null) {
               alert("Please Enter Delivery Message.");
           } else if (loaded_file < 1) {
               if (instant_delivery_file == "" || instant_delivery_file == null) {
                   alert("Please Select Instant Delivery File.");
               }else{
                   var form_data = new FormData(this);
                   form_data.append('proposal_id',<?= $proposal_id; ?>);
                   check_delivery(form_data);
               }
           } else {
               var form_data = new FormData(this);
               form_data.append('proposal_id',<?= $proposal_id; ?>);
               check_delivery(form_data);
           }
       }else{
           var form_data = new FormData(this);
           form_data.append('proposal_id',<?= $proposal_id; ?>);
           check_delivery(form_data);
       }
   });

});
</script>