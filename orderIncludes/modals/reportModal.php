<div id="report-modal" class="modal fade"><!-- report-modal modal fade Starts -->
  <div class="modal-dialog"><!-- modal-dialog Starts -->
    <div class="modal-content"><!-- modal-content Starts -->
      <div class="modal-header p-2 pl-3 pr-3"><!-- modal-header Starts -->
        Report This Message
        <button class="close" data-dismiss="modal"><span>&times;</span></button>
      </div><!-- modal-header Ends -->
      <div class="modal-body"><!-- modal-body p-0 Starts -->
        <h6>Let us know why you would like to report this user?.</h6>
        <form action="" method="post">
          <div class="form-group mt-3"><!--- form-group Starts --->
            <select class="form-control float-right" name="reason" required="">
              <option value="">Select Reason</option>
              <?php if($login_seller_id == $buyer_id){ ?>
                <option>The Seller tried to abuse the rating system.</option>
                <option>The Seller was using inappropriate language.</option>
                <option>The Seller delivered something that infringes copyrights</option>
                <option>The Seller delivered something partial or insufficient</option>
              <?php }else{ ?>
                <option>The Buyer tried to abuse the rating system.</option>
                <option>The Buyer was using inappropriate language.</option>
              <?php } ?>
            </select>
          </div><!--- form-group Ends --->
          <br>
          <br>
          <div class="form-group mt-1 mb-3"><!--- form-group Starts --->
            <label> Additional Information </label>
            <textarea name="additional_information" rows="3" class="form-control" required=""></textarea>
          </div><!--- form-group Ends --->
          <button type="submit" name="submit_report" class="float-right btn btn-sm btn-success">
            Submit Report
          </button>
        </form>
        <?php 
          if(isset($_POST['submit_report'])){
            $reason = $input->post('reason');
            $additional_information = $input->post('additional_information');
            $r_date = date("F d, Y");
            $insert = $db->insert("reports",array("reporter_id"=>$login_seller_id,"content_id"=>$order_id,"content_type"=>'order',"reason"=>$reason,"additional_information"=>$additional_information,"date"=>$r_date));

            $insert_notification = $db->insert("admin_notifications",array("seller_id" => $login_seller_id,"content_id" => $order_id,"reason" => "order_report","date" => $r_date,"status" => "unread"));

            if($insert){
              send_report_email("order","No Author",$order_id,$r_date);
              echo "<script>alert('Your Report Has Been Successfully Submited.')</script>";
              echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";
            }
          }
        ?>
      </div><!-- modal-body p-0 Ends -->
    </div><!-- modal-content Ends -->
  </div><!-- modal-dialog Ends -->
</div><!-- report-modal modal fade Ends -->