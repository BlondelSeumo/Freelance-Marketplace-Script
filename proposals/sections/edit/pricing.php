<?php


@session_start();

if(isset($_POST['proposal_id'])){

  require_once("../../../includes/db.php");
  $proposal_id = $input->post('proposal_id');

  $edit_proposal = $db->select("proposals",["proposal_id"=>$proposal_id]);
  $row_proposal = $edit_proposal->fetch();
  $d_proposal_price = $row_proposal->proposal_price;
  $d_proposal_revisions = $row_proposal->proposal_revisions;
  $d_proposal_status = $row_proposal->proposal_status;

  $get_payment_settings = $db->select("payment_settings");
  $row_payment_settings = $get_payment_settings->fetch();
  $min_proposal_price = $row_payment_settings->min_proposal_price;

}

$enable_unlimited_revisions = $row_general_settings->enable_unlimited_revisions;

$revisions = array(0,1,2,3,4,5,6,7,8,9,10);
if($enable_unlimited_revisions == 1){
  $revisions['unlimited'] = "Unlimited Revisions";
}

$d_delivery_id = $row_proposal->delivery_id;

// $get_delivery_time =  $db->select("delivery_times",array('delivery_id' => $d_delivery_id));
// $row_delivery_time = $get_delivery_time->fetch();
// $delivery_proposal_title = $row_delivery_time->delivery_proposal_title;

?>
<h5 class="font-weight-normal float-left">Pricing</h5>
<div class="float-right switch-box">
  <span class="text">Fixed Price :</span>
  <label class="switch">
    <?php if($d_proposal_price == "0" or isset($_POST["fixedPriceOff"])){ ?>
      <input type="checkbox" class="pricing">
    <?php }else if($d_proposal_price != "0" and !isset($_POST["fixedPriceOff"])){ ?>
      <input type="checkbox" class="pricing" checked="">
    <?php } ?>
    <span class="slider"></span>
  </label>
</div>

<div class="clearfix"></div>

<hr class="mt-0">

<div class="form-group row proposal-price justify-content-center">
  <div class="col-md-7">
    <label><?= $lang['label']['proposal_price']; ?></label>
    <div class="input-group">
    <span class="input-group-addon font-weight-bold">
    <?= $s_currency; ?>
    </span>
    <input type="number" class="form-control" form="pricing-form" name="proposal_price" value="<?= $d_proposal_price; ?>">
    </div>
    <small><?= $lang['edit_proposal']['pricing']['warning1']; ?></small>
  </div>
</div>


<div class="form-group row proposal-price justify-content-center mb-4"><!--- form-group row Starts --->
  <div class="col-md-7">
    <label><?= $lang['label']['proposal_revisions']; ?></label>
    <select name="proposal_revisions" form="pricing-form" class="form-control" required="">
    <?php 
      foreach ($revisions as $key => $rev) {
        echo "<option value='$key' ".($key == $d_proposal_revisions?"selected":"").">$rev</option>";
      }
    ?>
    </select>
    <small><?= $lang['edit_proposal']['pricing']['warning2']; ?></small>
  </div>
  <small class="form-text text-danger"><?= ucfirst(@$form_errors['proposal_revisions']); ?></small>
</div><!--- form-group row Ends --->


<div class="form-group row proposal-price justify-content-center mb-4"><!--- form-group row Starts --->
  <div class="col-md-7">
    <label><?= $lang['label']['delivery_time']; ?></label>
    <select name="delivery_id" form="pricing-form" class="form-control" required="">
    <option value="">Select Delivery Time</option>
    <?php 
      $get_delivery_times = $db->query("select * from delivery_times");
      while($row_delivery_times = $get_delivery_times->fetch()){
        $delivery_id = $row_delivery_times->delivery_id;
        $delivery_proposal_title = $row_delivery_times->delivery_proposal_title;
        $selected = ($delivery_id == $d_delivery_id)?"selected":"";
        echo "<option value='$delivery_id' $selected>$delivery_proposal_title</option>";
      }
    ?>
    </select>
    <small><?= $lang['edit_proposal']['pricing']['warning3']; ?></small>
  </div>
  <small class="form-text text-danger"><?= ucfirst(@$form_errors['delivery_id']); ?></small>
</div><!--- form-group row Ends --->


<div class="packages"><?php include("packages.php"); ?></div>

<div class="form-group row add-attribute justify-content-center">
  <div class="col-md-7">
    <div class="input-group">
      <input class="form-control form-control-sm attribute-name" placeholder="Add New Attribute" name="">
      <button class="btn btn btn-success input-group-addon insert-attribute" >
        <i class="fa fa-cloud-upload"></i> &nbsp;Insert 
      </button>
    </div>
  </div>
</div>

<div class="card rounded-0">
  <div class="card-body bg-light pt-3 pb-0">
  <h6 class="font-weight-normal">My Proposal Extras</h6>
  <a data-toggle="collapse" href="#insert-extra" class="small text-success">+ Add Extra</a>
   <div class="tabs accordion mt-2" id="allTabs"><!--- All Tabs Starts --->
      <?php include("extras.php"); ?>
    </div><!--- All Tabs Ends --->
  </div>
</div>

<div class="form-group mt-4 mb-0"><!--- form-group Starts --->
<a href="#" class="btn btn-secondary float-left back-to-instant"><?= $lang['button']['back']; ?></a>
<input class="btn btn-success float-right" type="submit" form="pricing-form" value="<?= $lang['button']['save_continue']; ?>">
</div><!--- form-group Starts --->

<script>
$(document).ready(function(){

if(enable_delivery == 0){

  <?php if($d_proposal_price == "0" or isset($_POST["fixedPriceOff"])){ ?>
    $('.proposal-price').hide();
    $('.proposal-price input[name="proposal_price"]').attr('min',0);
  <?php }else if($d_proposal_price != "0" and !isset($_POST["fixedPriceOff"])){ ?>
    
    $('.packages input[name="proposal_packages[1][price]"]').attr('min',0);
    $('.packages input[name="proposal_packages[2][price]"]').attr('min',0);
    $('.packages input[name="proposal_packages[3][price]"]').attr('min',0);
    
    $('.packages').hide();
    $('.add-attribute').hide();

  <?php } ?>

}else{

  $('#pricing .float-right.switch-box').hide();
  $('.packages').hide();
  $('.add-attribute').hide();

}

$('.back-to-instant').click(function(){
  <?php if($d_proposal_status == "draft"){ ?>
    $("input[type='hidden'][name='section']").val("instant-delivery");
    $('#instant-delivery').addClass('show active');
    $('#pricing').removeClass('show active');
    $('#tabs a[href="#pricing"]').removeClass('active');
  <?php }else{ ?>
    $('.nav a[href="#instant-delivery"]').tab('show');
  <?php } ?>
});

function processDeleteAttribute(attribute_name, tr, status){
  var proposal_id = <?= $proposal_id; ?>;
  $(tr).remove();
  //$(this).parent().parent().remove();
  $.ajax({
    method: "POST",
    url: "ajax/delete_attribute",
    data: { proposal_id : proposal_id, attribute_name: attribute_name, change_status: status },
    success:function(data){
      $('#wait').removeClass("loader");
    }
  });
}

function deleteAttribute(attribute_name, tr, status){  
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
             processDeleteAttribute(attribute_name, tr, status);
           }
       });
    }
}


$("table").on('click','.delete-attribute',function(event){  
  $('#wait').addClass("loader");
  event.preventDefault();
  var attribute_name = $(this).data("attribute");
  var proposal_id = <?= $proposal_id; ?>;
  var tr = $(this).closest('tr');
  $.ajax({
    method: "POST",
    url: "ajax/check/delete_attribute",
    dataType: 'json',
    data: { proposal_id : proposal_id },
    success:function(data){      
      if(data === true){
        $('#wait').removeClass("loader");        
        deleteAttribute(attribute_name, tr, data);        
      }else{
        processDeleteAttribute(attribute_name, tr , data);
      }      
    }
  });
});


$(document).on('click','.edit-attribute',function(event){
  
  $('#wait').addClass("loader");
  event.preventDefault();
  var attribute_name = $(this).data("attribute");
  var proposal_id = <?= $proposal_id; ?>;

  $('#wait').removeClass("loader");
  $("#edit-modal").modal("show");
  $("#edit-modal input[name='name']").val(attribute_name);
  $("#edit-modal input[name='new_name']").val(attribute_name);

});

function updateAttribute(name, new_name, status=false){
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
             processUpdateAttribute(name, new_name, status);
           }
       });
  }
  
}


function processUpdateAttribute(name, new_name, status){
  var proposal_id = <?= $proposal_id; ?>;
  $.ajax({
    method: "POST",
    url: "ajax/edit_attribute",
    data: { proposal_id: proposal_id,name: name,new_name: new_name, change_status:status },
    success:function(data){
      
      $('#wait').removeClass("loader");
      $("#edit-modal").modal("hide");
      $('a[data-attribute="'+name+'"]').parent().find("span").text(new_name);
      $('[data-attribute="'+name+'"]').data('attribute',new_name);
      $('[data-attribute="'+name+'"]').attr('data-attribute',new_name);

      // // this code makes problem
      // $.ajax({
      //   method: "POST",
      //   url: "sections/edit/pricing",
      //   data: { proposal_id: <?= $proposal_id; ?>,fixedPriceOff:1 },
      //   success:function(show_data){
      //     $("#pricing").html(show_data);
      //   }
      // });
    }
  });

}

$(".update-attribute").submit(function(event){
  event.preventDefault();
  var proposal_id = <?= $proposal_id; ?>;
  var name = $("#edit-modal input[name='name']").val();
  var new_name = $("#edit-modal input[name='new_name']").val();
  if(name == new_name) return false;
  $('#wait').addClass("loader");
  $.ajax({
    method: "POST",
    url: "ajax/check/update_attribute",
    dataType: 'json',
    data: { proposal_id: proposal_id },
    success:function(data){
      if(data === true){          
        $('#wait').removeClass("loader");        
        updateAttribute(name, new_name, data);        
      }else{
        processUpdateAttribute(name, new_name, data);
      }

      // // this code makes problem
      // $.ajax({
      //   method: "POST",
      //   url: "sections/edit/pricing",
      //   data: { proposal_id: <?= $proposal_id; ?>,fixedPriceOff:1 },
      //   success:function(show_data){
      //     $("#pricing").html(show_data);
      //   }
      // });

    }
  });

});

function processAttributeRequest(attribute_name, status){
  $.ajax({
  method: "POST",
  url: "ajax/insert_attribute",
  data: { attribute_name : attribute_name, proposal_id: <?= $proposal_id; ?>, change_status: status },
  success:function(data){
    if(data == "error"){
      $('#wait').removeClass("loader");
      swal({type: 'warning',text: 'You Must Need To Give A Name To Attribute Before Inserting It.'});
    }else{
      $('#wait').removeClass("loader");
      $('.attribute-name').val("");
      result = $.parseJSON(data);
      var form_data = new FormData(document.querySelector(".pricing-form"));
      form_data.append('proposal_id',<?= $proposal_id; ?>);
      $.ajax({
        method: "POST",
        url: "ajax/save_pricing",
        data: form_data,
        async: false,cache: false,contentType: false,processData: false
      }).done(function(data){
        // this code makes problem
        $.ajax({
          method: "POST",
          url: "sections/edit/pricing",
          data: { proposal_id: <?= $proposal_id; ?>,fixedPriceOff:1 },
          success:function(show_data){
            $("#pricing").html(show_data);
          }
        });
      });
    }
  }
  });

}

function attributeRequest(attribute_name, status=false){
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
             processAttributeRequest(attribute_name, status);
           }
       });
  }

}

$(".insert-attribute").on('click', function(event){  
  $('#wait').addClass("loader");
  event.preventDefault();
  var attribute_name = $('.attribute-name').val();

  $.ajax({
    method: "POST",
    url: "ajax/check/attribute",
    dataType: 'json',
    data: { attribute_name : attribute_name, proposal_id: <?= $proposal_id; ?> },
    success:function(data){      
      if(data === true){
        $('#wait').removeClass("loader");        
        attributeRequest(attribute_name, data);        
      }else{
        processAttributeRequest(attribute_name, data);
      }
    }
  });
});


function processPricingForm(form_data, status){
	form_data.append('change_status', status);
	$.ajax({
	method: "POST",
	url: "ajax/save_pricing",
	data: form_data,
	async: false,cache: false,contentType: false,processData: false
	}).done(function(data){    
	  $('#wait').removeClass("loader");
	  console.log(data);
	  if(data == "error"){
	    swal({type: 'warning',text: 'You Must Need To Fill Out All Fields Before Updating The Details.'});
	  }else{
	    swal({
	      type: 'success',
	      text: 'Details Saved.',
	      timer: 1000,
	      onOpen: function(){
	          swal.showLoading()
	      }
	    }).then(function(){
	      $("input[type='hidden'][name='section']").val("description");
	      <?php if($d_proposal_status == "draft"){ ?>
	        $('#pricing').removeClass('show active');
	        $('#description').addClass('show active');
	        $('#tabs a[href="#description"]').addClass('active');
	      <?php }else{ ?> $('.nav a[href="#description"]').tab('show'); <?php } ?>
	    });
	  }
	});
}

function pricingForm(form_data, status=false){
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
	           processPricingForm(form_data, status);
	         }
	     });
	}
}

$(".pricing-form").submit(function(event){
  event.preventDefault();
  var form_data = new FormData(this);  
  form_data.append('proposal_id',<?= $proposal_id; ?>);
  $('#wait').addClass("loader");
  $.ajax({
  method: "POST",
  url: "ajax/check/pricing",
  data: form_data,
  dataType: 'json',
  async: false,cache: false,contentType: false,processData: false
  }).done(function(data){    
      console.log(data);
      if(data === true){
        $('#wait').removeClass("loader");        
        pricingForm(form_data, data);        
      }else{
        processPricingForm(form_data, data);
      }
  });  
  
});


});
</script>