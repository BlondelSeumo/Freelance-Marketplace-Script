<?php 
@session_start();

if(isset($_POST['proposal_id'])){

      require_once("../../../includes/db.php");

      $proposal_id = $input->post('proposal_id');

}

$get_faqs = $db->select("proposals_faq",array("proposal_id"=>$proposal_id));

while($row_faq = $get_faqs->fetch()){

      $id = $row_faq->id;

      $title = $row_faq->title;

      $content = $row_faq->content;

      ?>

      <div class="tab rounded border-1"><!-- tab rounded Starts -->

            <div class="tab-header" data-toggle="collapse" href="#faq-<?= $id; ?>">

                  <a class="float-left"> <i class="fa fa-bars mr-2"></i> <?= $title; ?></a>

                  <a class="float-right text-muted"><i class="fa fa-sort-down"></i></a>

                  <div class="clearfix"></div>

            </div>

          	<div class="tab-body p-3 pb-0 collapse" id="faq-<?= $id; ?>" data-parent="#faqTabs">

                  <form action="" method="post" class="edit-faq">

                        <div class="form-group mb-2">

                              <input type="hidden" name="id" value="<?= $id; ?>">

                              <input type="text" name="title" placeholder="Title" class="form-control form-control-sm" value="<?= $title; ?>"required>

                        </div>

                        <div class="form-group mb-2">

                              <textarea name="content" rows="3" class="form-control form-control-sm"><?= $content; ?></textarea>

                        </div>

                        <div class="form-group mb-0">

                              <a href="#" class="btn btn-danger btn-sm delete-faq">Delete</a>

                              <button type="submit" class="btn btn-success btn-sm float-right">Update</button>

                        </div>

                  </form>	

            </div>

      </div><!-- tab rounded Ends -->

<?php } ?>

<div class="tab"><!-- tab rounded Starts -->

      <div class="tab-body rounded border-1 p-3 pb-0 collapse" id="insert-faq" data-parent="#faqTabs">

            <form action="" method="post" class="add-faq">

                  <div class="form-group mb-2">

                        <input type="text" name="title" placeholder="Faq Title" class="form-control form-control-sm" required>

                  </div>

                  <div class="form-group mb-2">

                        <textarea name="content" rows="3" placeholder="Faq Content" class="form-control form-control-sm"></textarea>

                  </div>

                  <div class="form-group mb-0">

                        <button type="submit" class="btn btn-success btn-sm float-right">Insert</button>

                        <div class="clearfix"></div>

                  </div>

            </form>	

      </div>

</div><!-- tab rounded Ends -->

<script>

      $(document).ready(function(){

            function processAddFaq(form_data, status){
            	form_data.append('change_status', status);
                  $.ajax({

                        method: "POST",

                        url: "ajax/insert_faq",

                        data: form_data,

                        async: false,cache: false,contentType: false,processData: false

                  }).done(function(data){

                        $('#wait').removeClass("loader");

                        if(data == "error"){

                              swal({type: 'warning',text: 'You Must Need To Fill Out All Fields Before Updating The Details.'});

                        }else{

                              $(".add-faq").trigger("reset");

                              $("#faqTabs").load("sections/edit/faqs",{proposal_id : <?= $proposal_id; ?>});

                        }

                  });

            }

            function addFaq(form_data, status){
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
            	                processAddFaq(form_data, status);
            	          }
            	    });
            	}

            }


            $(".add-faq").on('submit', function(event){

                  event.preventDefault();

                  var form_data = new FormData(this);

                  form_data.append('proposal_id',<?= $proposal_id; ?>);

                  $('#wait').addClass("loader");

                  $.ajax({
                        method: "POST",
                        url: "ajax/check/insert_faq",
                        data: form_data,
                        dataType: 'json',
                        async: false,cache: false,contentType: false,processData: false

                  }).done(function(data){
                  	if(data === true){
                  	      $('#wait').removeClass("loader");        
                  	      addFaq(form_data, data);        
                  	}else{        
                  	      processAddFaq(form_data, data);
                  	}
                  });

            });


            function processEditFaq(form_data, status){
                  form_data.append('change_status', status);
                  $.ajax({

                        method: "POST",

                        url: "ajax/edit_faq",

                        data: form_data,

                        async: false,cache: false,contentType: false,processData: false

                  }).done(function(data){

                        $('#wait').removeClass("loader");

                        if(data == "error"){

                              swal({type: 'warning',text: 'You Must Need To Fill Out All Fields Before Updating The Details.'});

                        }else{

                              swal({type: 'success',text: 'Changes Saved.'});

                        }

                  });
            }

            function editFaq(form_data, status=false){
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
                            processEditFaq(form_data, status);
                      }
                });
            }
      }

      $(".edit-faq").on('submit', function(event){

            event.preventDefault();
            var form_data = new FormData(this);        
            form_data.append('proposal_id',<?= $proposal_id; ?>);
            $('#wait').addClass("loader");
            $.ajax({              
                  method: "POST",
                  url: "ajax/check/edit_faq",
                  dataType: 'json',
                  data: form_data,
                  async: false,cache: false,contentType: false,processData: false                  
            }).done(function(data){  
            	console.log(data);          
                if(data === true){
                      $('#wait').removeClass("loader");        
                      editFaq(form_data, data);        
                }else{        
                      processEditFaq(form_data, data);
                }  
          });      

      });

      function processDeleteFaq(id, main, status){
            $.ajax({

                  method: "POST",

                  url: "ajax/delete_faq",

                  data: {proposal_id : <?= $proposal_id; ?>, id : id, change_status: status},

                  success : function(data){
                        main.remove();
                  }
                  
            });

      }

      function deleteFaq(id, main, status=false){
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
                      processDeleteFaq(id, main, status);
                }
          });
      }
}



$(".delete-faq").on('click',function(){
      event.preventDefault();

      var id = $(this).parent().parent().find("input[name='id']").val();

      var main = $(this).parent().parent().parent().parent();

      $.ajax({

            method: "POST",

            url: "ajax/check/delete_faq",

            dataType: 'json',

            data: {proposal_id : <?= $proposal_id; ?>, id : id},

            success : function(data){
                  console.log(data);
                  if(data === true){
                      $('#wait').removeClass("loader");        
                      deleteFaq(id, main, data);        
                }else{        
                      processDeleteFaq(id, main, data);
                }
          }

    });

});
});
</script>