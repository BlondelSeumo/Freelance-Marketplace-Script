<?php if($count_tip == 0){ ?>

<div class="modal fade" id="tipModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Do You Want To Give a Tip To Seller?</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <button class="btn btn-success btn-lg mr-2" data-toggle="modal" data-target="#tipModal2" data-dismiss="modal">Yes, Add Tip</button>
        <button class="btn btn-success btn-lg" data-dismiss="modal">No, Thanks</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="tipModal2" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Give a Tip Seller.</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        
         <form action="" class="tip-form" method="post" align="center"><!--- form Starts --->

            <div class="form-group row justify-content-center mt-2">

               <div class="input-group col-md-6">
                  <span class="input-group-addon">
                     <b><i class="fa fa-dollar"></i></b>
                  </span>
                  <input type="number" name="amount" class="form-control" placeholder="Amount" min="1" required=""/>
               </div>

            </div>

            <textarea name="message" class="form-control mb-3" rows="4" placeholder="Leave Your Seller A Message."></textarea>

            <input type="submit" name="submit_tip" class="btn btn-success" value="Submit Tip">

         </form><!--- form Ends --->

      </div>
    </div>
  </div>
</div>

<div class="order-review-box mb-3 p-3 d-none"><!--- order-review-box Starts --->

   <h3 class="text-center text-white mb-3 mt-2"> Give A Tip To Seller </h3>

   <div class="row justify-content-center"><!--- row Starts --->

      <div class="col-md-6">

         <form action="" class="tip-form" method="post" align="center"><!--- form Starts --->

            <div class="form-group row justify-content-center mt-2">

               <div class="input-group col-md-6">
                  <span class="input-group-addon">
                     <b><i class="fa fa-dollar"></i></b>
                  </span>
                  <input type="number" name="amount" class="form-control" placeholder="Amount" min="5" required=""/>
               </div>

            </div>

            <textarea name="message" class="form-control mb-3" rows="4" placeholder="Leave Your Seller A Message."></textarea>

            <input type="submit" name="submit_tip" class="btn btn-success" value="Submit Tip">

         </form><!--- form Ends --->

      </div>
   
   </div><!--- row Ends --->

</div><!--- order-review-box Ends --->

<div class="append-modal"></div>

<script type="text/javascript">
$(document).ready(function(){

  $("#tipModal").modal("show");

  $(".tip-form").submit(function(event){
    event.preventDefault();

    var order_id = "<?= $order_id; ?>";
    var amount = $(this).find("input[name='amount']").val();
    var message = $(this).find("textarea[name='message']").val();

    $.ajax({
       method: "POST",
       url: "orderIncludes/modals/tipModal",
       data: {order_id: order_id, amount: amount, message: message}
    }).done(function(data){
      $("#tipModal2").modal("hide");
      $(".append-modal").html(data);
    });

  });

});
</script>

<?php } ?>