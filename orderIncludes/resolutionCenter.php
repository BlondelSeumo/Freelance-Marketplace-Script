<div class="row">
<div class="col-md-10 offset-md-1">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-8 offset-md-2">
          <div class="card">
            <div class="card-body">
              <h3 class="text-center"> Order Cancellation Request</h3>
            </div>
          </div>
          <form method="post">
            <div class="form-group">
              <textarea name="cancellation_message" placeholder="Please be as detailed as possible..." rows="10" class="form-control" required></textarea>
            </div>
            <div class="form-group">
              <label class="font-weight-bold"> Cancellation Request Reason </label>
              <select name="cancellation_reason" class="form-control">
                <option class="hidden"> Select Cancellation Reason </option>
                <?php if($seller_id == $login_seller_id){ ?>
                <option> Buyer is not responding. </option>
                <option> Buyer is extremely rude. </option>
                <option> Buyer requested that I cancel this order.</option>
                <option> Buyer expects more than what this proposal can offer.</option>
                <?php }elseif($buyer_id == $login_seller_id){ ?>
                <option> Seller is not responding. </option>
                <option> Seller is extremely rude. </option>
                <option> Order does meet requirements. </option>
                <option> Seller asked me to cancel. </option>
                <option> Seller cannot do required task. </option>
                <?php }  ?>
              </select>
            </div>
            <input type="submit" name="submit_cancellation_request" value="Submit Cancellation Request" class="btn btn-success float-right">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<?php 
  if(isset($_POST['submit_cancellation_request'])){
    $cancellation_message = $input->post('cancellation_message');
    $cancellation_reason = $input->post('cancellation_reason');
    $last_update_date = date("h:i: M d, Y");
    if($seller_id == $login_seller_id){
      $receiver_id = $buyer_id;
    }else{
      $receiver_id = $seller_id;
    }

    if(send_cancellation_request($order_id,$order_number,$login_seller_id,$row_orders->proposal_id,$row_orders->seller_id,$row_orders->buyer_id,$last_update_date)){
      $insert_order_conversation = $db->insert("order_conversations",array("order_id" => $order_id,"sender_id" => $login_seller_id,"message" => $cancellation_message,"date" => $last_update_date,"reason" => $cancellation_reason,"status" => "cancellation_request"));
  
      if($insert_order_conversation){
        $insert_notification = $db->insert("notifications",array("receiver_id" => $receiver_id,"sender_id" => $login_seller_id,"order_id" => $order_id,"reason" => "cancellation_request","date" => $n_date,"status" => "unread"));

        /// sendPushMessage Starts
        $notification_id = $db->lastInsertId();
        sendPushMessage($notification_id);
        /// sendPushMessage Ends

        $update_order = $db->update("orders",array("order_status" => "cancellation requested"),array("order_id" => $order_id));
        echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";
      }
    }
  }
?>